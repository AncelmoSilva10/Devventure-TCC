<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Prova;
use App\Models\Turma; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProvasController extends Controller
{
    public function create(Request $request)
    {
        $professorId = Auth::guard('professor')->id();

        $turmas = Turma::where('professor_id', $professorId)->get();

        $query = Prova::whereHas('turma', function($q) use ($professorId) {
            $q->where('professor_id', $professorId);
        })->with(['turma', 'questoes']);

        $status = $request->get('status', 'todos');
        
        if ($status == 'disponiveis') {
            $query->where('data_fechamento', '>', now());
        } elseif ($status == 'concluidas') {
            $query->where('data_fechamento', '<=', now());
        }

        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        $provas = $query->orderBy('data_abertura', 'desc')->get();

        return view('Professor.provas', compact('turmas', 'provas', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id', 
            'titulo' => 'required|string|max:255',
            'instrucoes' => 'nullable|string',
            'data_abertura' => 'required|date',
            'data_fechamento' => 'required|date|after:data_abertura',
            'duracao_minutos' => 'required|integer|min:1',
            'questoes' => 'required|array|min:1',
            'questoes.*.imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $prova = Prova::create([
            'turma_id' => $request->turma_id,
            'titulo' => $request->titulo,
            'instrucoes' => $request->instrucoes,
            'data_abertura' => $request->data_abertura,
            'data_fechamento' => $request->data_fechamento,
            'duracao_minutos' => $request->duracao_minutos,
        ]);

        foreach ($request->questoes as $index => $questaoData) {
            $caminhoImagem = null;
            if ($request->hasFile("questoes.$index.imagem")) {
                $caminhoImagem = $request->file("questoes.$index.imagem")->store('questoes_apoio', 'public');
            }

            $questao = $prova->questoes()->create([
                'enunciado' => $questaoData['enunciado'],
                'imagem_apoio' => $caminhoImagem, 
                'tipo_questao' => $questaoData['tipo_questao'],
                'pontuacao' => $questaoData['pontuacao'] ?? 1.0,
            ]);

            if ($questaoData['tipo_questao'] == 'multipla_escolha' && isset($questaoData['alternativas'])) {
                foreach ($questaoData['alternativas'] as $idxAlt => $alternativaData) {
                    $questao->alternativas()->create([
                        'texto_alternativa' => $alternativaData['texto'],
                        'correta' => (isset($questaoData['alternativa_correta']) && $idxAlt == $questaoData['alternativa_correta']), 
                    ]);
                }
            }
        }

        // Redireciona de volta para a lista de provas em vez do dashboard geral
        return redirect()->action([ProvasController::class, 'create'])
                         ->with('sweet_success', 'Prova criada com sucesso!');
    }

    public function resultados(Turma $turma, Prova $prova) 
    {
        if ($prova->turma_id !== $turma->id) {
            abort(404, 'Prova não encontrada nesta turma.');
        }

        $prova->load('tentativas.aluno', 'turma');
        return view('Professor.relatorios.provaResultado', compact('prova', 'turma'));
    }

    public function destroy(Prova $prova)
    {
        $professorId = Auth::guard('professor')->id();
        $turma = Turma::findOrFail($prova->turma_id);

        if ($turma->professor_id !== $professorId) {
            abort(403, 'Acesso não autorizado para excluir esta prova.');
        }
        
        $prova->delete();

        // Redireciona de volta para a lista de provas
        return redirect()->action([ProvasController::class, 'create'])
                         ->with('sweet_success', 'Prova excluída com sucesso!');
    }
}