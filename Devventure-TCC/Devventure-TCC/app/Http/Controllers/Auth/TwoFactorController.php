<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Professor;
use App\Models\Aluno;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class TwoFactorController extends Controller
{
    /**
     * Mostra o formulário para inserir o código de 6 dígitos.
     */
    public function showVerifyForm()
    {

        if (!session('user_to_verify')) {
            return redirect('/'); 
        }
        return view('auth.2fa_verify');
    }

    /**
     * Valida o código de 6 dígitos para finalizar o cadastro.
     */
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $verificationData = $request->session()->get('user_to_verify');

        if (!$verificationData) {
            return redirect('/')->withErrors(['msg' => 'Sua sessão de verificação expirou. Por favor, tente novamente.']);
        }
        

        $model = $verificationData['guard'] === 'professor' ? Professor::class : Aluno::class;
        $user = $model::find($verificationData['id']);

        if (!$user || $user->two_factor_code !== $request->code || now()->gt($user->two_factor_expires_at)) {
            return back()->withErrors(['msg' => 'Código inválido ou expirado.']);
        }

        $user->status = 'ativo'; 
        $user->email_verified_at = now();

        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();
        
        $request->session()->forget('user_to_verify');

        $routeName = $verificationData['guard'] === 'professor' ? 'login.professor' : 'login.aluno';
        return redirect()->route($routeName)->with('status', 'E-mail verificado com sucesso! Você já pode fazer o login.');
    }

    /**
     * Reenvia o código de verificação para Professores ou Alunos pendentes.
     */
    public function resend(Request $request)
    {
        $email = $request->session()->get('email_for_verification');

        if (!$email) {
            return back()->withErrors(['msg' => 'Sessão expirada. Tente fazer login novamente.']);
        }

        $user = null;
        $guard = null;

        $professor = Professor::where('email', $email)->where('status', 'pendente')->first();
        if ($professor) {
            $user = $professor;
            $guard = 'professor';
        } else {
            $aluno = Aluno::where('email', $email)->where('status', 'pendente')->first();
            if ($aluno) {
                $user = $aluno;
                $guard = 'aluno';
            }
        }
        
        if (!$user) {
            return back()->withErrors(['msg' => 'Não foi possível encontrar uma conta pendente com este e-mail.']);
        }

        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(15);
        $user->save();

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Não foi possível enviar o e-mail. Tente novamente mais tarde.']);
        }

        $request->session()->put('user_to_verify', [
            'id' => $user->id,
            'guard' => $guard
        ]);
        
        $request->session()->forget('email_for_verification');

        return redirect()->route('2fa.verify.form')
                       ->with('status', 'Um novo código de verificação foi enviado para o seu e-mail.');
    }
}