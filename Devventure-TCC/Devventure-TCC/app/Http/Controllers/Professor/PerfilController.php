<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Professor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class PerfilController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nome' => ['required', 'string', 'max:255'],
        'cpf' => ['required', 'string', 'unique:professor,cpf'],
        'area' => ['required', 'string'],
        'formacao' => ['required', 'string'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:professor,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    ]);

   
    $caminhoAvatar = null;
    if ($request->hasFile('avatar')) {
        $caminhoAvatar = $request->file('avatar')->store('avatars/professores', 'public');
    }

  
    $professor = Professor::create([
        'nome' => $request->nome,
        'cpf' => $request->cpf,
        'areaEnsino' => $request->area,
        'formacao' => $request->formacao,
        'telefone' => $request->telefone,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'avatar' => $caminhoAvatar, 
    ]);

        $code = rand(100000, 999999);
        $professor->two_factor_code = $code;
        $professor->two_factor_expires_at = now()->addMinutes(15);
        $professor->save();

        try {
            Mail::to($professor->email)->send(new TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Não foi possível enviar o e-mail de verificação. Tente novamente.']);
        }

        $request->session()->put('user_to_verify', [
            'id' => $professor->id,
            'guard' => 'professor'
        ]);

        return redirect()->route('2fa.verify.form');
    }

  public function edit()
    {
        
        $professor = Auth::guard('professor')->user();
        return view('Professor/Perfil', compact('professor'));
    }


    public function update(Request $request)
    {
        $professor = Auth::guard('professor')->user();

        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('professor')->ignore($professor->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('professor')->ignore($professor->id)], 
            'areaEnsino' => ['required', 'string', 'max:255'],
            'formacao' => ['required', 'string'],
            'telefone' => ['nullable', 'string', 'max:15'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $professor->nome = $request->input('nome');
        $professor->email = $request->input('email');
        $professor->cpf = $request->input('cpf'); 
        $professor->areaEnsino = $request->input('areaEnsino');
        $professor->formacao = $request->input('formacao'); 
        $professor->telefone = $request->input('telefone');

        
        if ($request->hasFile('avatar')) {
            
            if ($professor->avatar && Storage::disk('public')->exists($professor->avatar)) {
                Storage::disk('public')->delete($professor->avatar);
            }
            $path = $request->file('avatar')->store('avatars/professores', 'public');
            $professor->avatar = $path;
        }

        
        if ($request->filled('password')) {
            $professor->password = Hash::make($request->input('password'));
        }

        $professor->save();

        
        return redirect()->route('professor.perfil.edit')->with('sweet_success', 'Seu perfil foi atualizado com sucesso!');
    }


}
