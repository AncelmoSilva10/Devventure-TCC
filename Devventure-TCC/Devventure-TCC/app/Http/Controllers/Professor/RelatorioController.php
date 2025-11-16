<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\RespostaExercicio;
use App\Models\Turma;
use App\Models\Prova;
use App\Models\AlunoProvaTentativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    /**
     * Mostra o dashboard de relatórios geral da turma.
     */
    public function index(Turma $turma)
    {
        if ($turma->professor_id !== Auth::guard('professor')->id()) {
            abort(403);
        }

        // --- Estatísticas de Exercícios ---
        $mediaGeralExercicios = RespostaExercicio::whereHas('exercicio', function ($query) use ($turma) {
            $query->where('turma_id', $turma->id);
        })->avg('nota');

        $alunosDestaque = $turma->alunos()->orderBy('total_pontos', 'desc')->take(3)->get();

        $ultimoExercicio = $turma->exercicios()->latest('data_publicacao')->first(); // Cuidado: 'data_publicacao' ou 'created_at'?
        $alunosAtencao = collect();
        $taxaEngajamento = 0;

        if ($ultimoExercicio) {
            $totalAlunos = $turma->alunos()->count();
            $respostasUltimoExercicio = $ultimoExercicio->respostas()->count();
            if ($totalAlunos > 0) {
                $taxaEngajamento = round(($respostasUltimoExercicio / $totalAlunos) * 100);
            }
            $alunosQueEntregaramIds = $ultimoExercicio->respostas()->pluck('aluno_id');
            $alunosAtencao = $turma->alunos()->whereNotIn('id', $alunosQueEntregaramIds)->take(5)->get();
        }

        $desempenhoPorExercicio = Exercicio::where('turma_id', $turma->id)
            ->whereHas('respostas')
            ->withAvg('respostas', 'nota')
            ->get();
            
        // --- Estatísticas de Provas ---
        $mediaGeralProvas = AlunoProvaTentativa::whereHas('prova', function ($query) use ($turma) {
            $query->where('turma_id', $turma->id);
        })->whereNotNull('hora_fim')->avg('pontuacao_final'); // Média das provas finalizadas


        // --- ADICIONADO: LÓGICA PARA CRIAR A $mediaGeral QUE A VIEW ESPERA ---
        // Vamos calcular a média das duas médias (Exercícios e Provas)
        $medias = [];
        if (!is_null($mediaGeralExercicios)) {
            $medias[] = $mediaGeralExercicios;
        }
        if (!is_null($mediaGeralProvas)) {
            // Se a pontuação da prova for até 100, ok. Se for (ex: 10), normalize para 100.
            // Assumindo que a pontuacao_final já é base 100, como os exercícios.
            $medias[] = $mediaGeralProvas;
        }

        if (count($medias) > 0) {
            // Calcula a média das médias disponíveis
            $mediaGeral = array_sum($medias) / count($medias);
        } else {
            // Caso não haja nenhuma nota de exercício ou prova
            $mediaGeral = 0;
        }
        // --- FIM DA ADIÇÃO ---


        return view('Professor.relatorios.index', compact(
            'turma', 
            'mediaGeral', // <--- ADICIONADO: A variável que a view realmente espera
            'mediaGeralExercicios', 
            'mediaGeralProvas',
            'alunosDestaque', 'alunosAtencao',
            'desempenhoPorExercicio', 'taxaEngajamento', 'ultimoExercicio'
        ));
    }

    /**
     * Mostra o relatório individual de um aluno específico.
     */
    public function relatorioAluno(Turma $turma, Aluno $aluno)
    {
        if ($turma->professor_id !== Auth::guard('professor')->id()) {
            abort(403);
        }

        $aluno->load([
            'respostasExercicios.exercicio', 
            'aulas' => function ($query) {
                $query->where('aula_aluno.status', 'concluido');
            },
            // --- CARREGAMENTO DE PROVAS AGORA MAIS PROFUNDO ---
            'tentativasProvas' => function($query) {
                $query->whereNotNull('hora_fim') // Apenas tentativas finalizadas
                        ->with([
                            'prova', // Carrega a prova (para o título)
                            'respostasQuestoes' => function($q) {
                                $q->with('questao'); // Carrega as respostas individuais e suas questões
                            }
                        ]);
            }
        ]);

        return view('Professor.relatorios.aluno', compact('turma', 'aluno'));
    }
}