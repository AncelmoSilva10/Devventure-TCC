<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aviso;
use App\Models\Turma; // Importe o model Turma

class AvisoController extends Controller
{
    
    public function create()
    {
        
        $turmas = Auth::user()->turmas;
        return view('professor.avisosCriar', compact('turmas'));
    }

   
    public function store(Request $request)
{
    // 1. Validação Básica
    $rules = [
        'titulo' => 'required|string|max:255',
        'conteudo' => 'required|string',
        'turma_id' => 'required|exists:turmas,id', // Confirme se sua tabela é 'turmas' ou 'turma'
        'alcance' => 'required|in:todos,selecionados'
    ];

    // 2. Validação Condicional (Só exige alunos se a aba for 'selecionados')
    if ($request->alcance === 'selecionados') {
        $rules['alunos'] = 'required|array|min:1';
        $rules['alunos.*'] = 'exists:aluno,id'; // Confirme se sua tabela é 'aluno' ou 'alunos'
    }

    $request->validate($rules, [
        'alunos.required' => 'Selecione pelo menos um aluno ou mude para "Toda a Turma".'
    ]);

    // 3. Criar o Aviso
    $aviso = Aviso::create([
        'professor_id' => Auth::guard('professor')->id(),
        'titulo' => $request->titulo,
        'conteudo' => $request->conteudo,
 
    ]);
    if ($request->alcance === 'todos') {
        $aviso->turmas()->attach($request->turma_id);
    } else {
        $aviso->alunos()->attach($request->alunos);
    }

    return redirect()->route('turmas.especificaID', $request->turma_id)
                     ->with('sweet_success', 'Aviso enviado com sucesso!');
}
} 