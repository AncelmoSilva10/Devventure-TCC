<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Prova;
use App\Models\ProvaQuestao;
use App\Models\ProvaAlternativa;
use App\Models\Turma; 
use App\Models\AlunoProvaTentativa;
use App\Models\AlunoRespostaProva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProvasController extends Controller
{
   
    // Mostra o formulário de criação de prova.
    public function create()
    {

        $professor = Auth::user();
        $turmas = Turma::where('professor_id', $professor->id)->get(); 
        
        return view('Professor.provas', compact('turmas'));
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
        
        // Validação específica para cada imagem dentro do array de questões
        'questoes.*.imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Máx 5MB
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

    return redirect()->route('professorDashboard')
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
        
        $turmaId = $prova->turma_id;

        $prova->delete();

        return redirect()->route('turmas.especificaID', $turmaId)
                         ->with('sweet_success', 'Prova excluída com sucesso!');
    }

}