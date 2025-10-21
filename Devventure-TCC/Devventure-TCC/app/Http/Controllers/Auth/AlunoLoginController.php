<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail; 

class AlunoLoginController extends Controller
{
    
    public function verifyUser(Request $request)
    {
        
        if (Auth::guard('professor')->check()) {
            Auth::guard('professor')->logout();
        }

        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        
       
        if (!Auth::guard('aluno')->attempt($request->only('email', 'password'))) {
            return back()->withErrors(['msg' => 'E-mail ou senha inválidos']);
        }

        
        $user = Auth::guard('aluno')->user();

        if ($user->status === 'bloqueado') {
        
        Auth::guard('aluno')->logout();
        
        return back()->withErrors(['msg' => 'Sua conta está bloqueada. Entre em contato com o suporte.']);
        }


        if ($user->status === 'pendente') {
            
                Auth::guard('professor')->logout();

                $request->session()->put('email_for_verification', $user->email);
        
                return redirect()->route('login-professor') 
                    ->with('needs_verification', 'Seu e-mail precisa ser verificado para login.')
                    ->withInput($request->only('email'));
            }


        $request->session()->regenerate();
        return redirect()->intended('/alunoDashboard');

    }

    
    public function logoutUser(Request $request)
    {
        Auth::guard('aluno')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/loginAluno');
    }
}
