<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aluno;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class PerfilController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ra' => ['required', 'string', 'max:255', 'unique:aluno,ra'],
            'semestre' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:aluno,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $caminhoAvatar = null;
        if ($request->hasFile('avatar')) {
            $caminhoAvatar = $request->file('avatar')->store('avatars', 'public');
        }

        $aluno = new Aluno();
        $aluno->nome = $request->nome;
        $aluno->ra = $request->ra;
        $aluno->semestre = $request->semestre;
        $aluno->email = $request->email;
        $aluno->telefone = $request->telefone;
        $aluno->password = Hash::make($request->password);
        $aluno->avatar = $caminhoAvatar;
        $aluno->save();

        $code = rand(100000, 999999);
        $aluno->two_factor_code = $code;
        $aluno->two_factor_expires_at = now()->addMinutes(15);
        $aluno->save();

        try {
            Mail::to($aluno->email)->send(new TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Não foi possível enviar o e-mail de verificação. Tente novamente.']);
        }

        $request->session()->put('user_to_verify', [
            'id' => $aluno->id,
            'guard' => 'aluno'
        ]);

        return redirect()->route('2fa.verify.form');
    }

    public function edit()
    {
        $aluno = Auth::guard('aluno')->user();
        return view('Aluno/perfil', ['aluno' => $aluno]);
    }

    public function update(Request $request)
    {
        $aluno = Auth::guard('aluno')->user();
        
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('aluno')->ignore($aluno->id),
            ],
            'telefone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('avatar')) {
            if ($aluno->avatar) {
                Storage::disk('public')->delete($aluno->avatar);
            }
            $path = $request->file('avatar')->store('avatars/alunos', 'public');
            $validatedData['avatar'] = $path;
        }

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $aluno->update($validatedData);

        return redirect()->route('aluno.perfil.edit')->with('sweet_success', 'Suas alterações foram salvas com sucesso!');
    }
}