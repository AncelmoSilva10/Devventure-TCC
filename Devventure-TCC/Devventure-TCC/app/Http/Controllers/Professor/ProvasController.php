<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Prova;
use App\Models\ProvaQuestao;
use App\Models\ProvaAlternativa;
use App\Models\Turma; // Certifique-se de ter o Model Turma
use App\Models\AlunoProvaTentativa;
use App\Models\AlunoRespostaProva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProvasController extends Controller
{
   
    /**
     * Mostra o formulário de criação de prova.
     * Agora carrega as turmas para o professor selecionar.
     */
    public function create()
    {
        // Certifique-se que o usuário logado é um professor e obtenha suas turmas
        $professor = Auth::user();
        $turmas = Turma::where('professor_id', $professor->id)->get(); // Ajuste conforme sua relação Turma-Professor
        
        // A view `provas.blade.php` parece ser a que você usa
        // para a interface do professor.
        return view('Professor.provas', compact('turmas'));
    }

    /**
     * Salva a nova prova no banco.
     */
    public function store(Request $request)
    {
        // Validação básica
        $request->validate([
            'turma_id' => 'required|exists:turmas,id', // Adicionado validação para turma_id
            'titulo' => 'required|string|max:255',
            'instrucoes' => 'nullable|string',
            'data_abertura' => 'required|date',
            'data_fechamento' => 'required|date|after:data_abertura',
            'duracao_minutos' => 'required|integer|min:1',
            'questoes' => 'required|array|min:1',
        ]);

        // 1. Cria a Prova
        $prova = Prova::create([
            'turma_id' => $request->turma_id,
            'titulo' => $request->titulo,
            'instrucoes' => $request->instrucoes,
            'data_abertura' => $request->data_abertura,
            'data_fechamento' => $request->data_fechamento,
            'duracao_minutos' => $request->duracao_minutos,
        ]);

        // 2. Loop para criar as Questões
        foreach ($request->questoes as $questaoData) {
            $questao = $prova->questoes()->create([
                'enunciado' => $questaoData['enunciado'],
                'tipo_questao' => $questaoData['tipo_questao'],
                'pontuacao' => $questaoData['pontuacao'] ?? 1.0,
            ]);

            // 3. Se for múltipla escolha, cria as Alternativas
            if ($questaoData['tipo_questao'] == 'multipla_escolha' && isset($questaoData['alternativas'])) {
                foreach ($questaoData['alternativas'] as $index => $alternativaData) {
                    $questao->alternativas()->create([
                        'texto_alternativa' => $alternativaData['texto'],
                        'correta' => (isset($questaoData['alternativa_correta']) && $index == $questaoData['alternativa_correta']), // Adicionado isset
                    ]);
                }
            }
        }

        // Redireciona para algum lugar, talvez o dashboard do professor ou uma lista de provas
        return redirect()->route('professorDashboard') // ou 'professor.provas.index' se criar uma
                         ->with('success', 'Prova criada com sucesso!');
    }
    
    /**
     * (Para o Professor) Ver resultados de todos os alunos para uma prova específica.
     */
    public function resultados(Turma $turma, Prova $prova) // Parâmetro {turma} e {prova}
    {
        // Garante que a prova pertence à turma
        if ($prova->turma_id !== $turma->id) {
            abort(404, 'Prova não encontrada nesta turma.');
        }

        $prova->load('tentativas.aluno', 'turma');
        return view('Professor.relatorios.provaResultado', compact('prova', 'turma'));
    }

}