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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
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

    public function exportar(Request $request, Turma $turma)
{
    if ($turma->professor_id !== Auth::guard('professor')->id()) {
        abort(403);
    }

    $formato = $request->query('formato');

    
    $exercicios = $turma->exercicios()->orderBy('created_at')->get();

    
    $alunos = $turma->alunos()
        ->with(['respostasExercicios'])
        ->orderBy('nome')
        ->get();

    $fileName = 'Relatorio_Geral_' . str_replace(' ', '_', $turma->nome_turma) . '_' . date('d-m-Y');

    // --- LÓGICA PARA CSV (EXCEL) ---
    if ($formato === 'csv') {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

       
        $columns = ['Nome do Aluno', 'Pontuação Geral'];
        foreach ($exercicios as $ex) {
            $columns[] = $ex->nome; 
        }

        $callback = function() use($alunos, $exercicios, $columns) {
            $file = fopen('php://output', 'w');
            
            
            fputs($file, "\xEF\xBB\xBF"); 

            
            fputcsv($file, $columns, ';'); 

            foreach ($alunos as $aluno) {
                $row = [
                    $aluno->nome,
                    $aluno->total_pontos
                ];

                foreach ($exercicios as $ex) {
                   
                    $resposta = $aluno->respostasExercicios->where('exercicio_id', $ex->id)->first();
                    
                    if ($resposta) {
                        
                        $row[] = number_format($resposta->nota, 1, ',', ''); 
                    } else {
                        $row[] = 'PENDENTE';
                    }
                }

               
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // --- LÓGICA PARA PDF ---
    if ($formato === 'pdf') {
        
        $orientation = $exercicios->count() > 5 ? 'landscape' : 'portrait';

        $pdf = Pdf::loadView('Professor.relatorios.pdf_export', [
            'turma' => $turma,
            'alunos' => $alunos,
            'exercicios' => $exercicios
        ])->setPaper('a4', $orientation);

        return $pdf->download($fileName . '.pdf');
    }

    return back();
}

public function exportarIndividual(Request $request, Turma $turma, Aluno $aluno)
{
    if ($turma->professor_id !== Auth::guard('professor')->id()) {
        abort(403);
    }

    $formato = $request->query('formato'); // 'pdf' ou 'csv'

    // 1. Pega TODOS os exercícios da turma (ordenados por data)
    $exercicios = $turma->exercicios()->orderBy('created_at')->get();

    // 2. Carrega as respostas desse aluno
    $aluno->load('respostasExercicios');

    // 3. Monta o array de dados combinados (Exercício + Status)
    $dadosRelatorio = $exercicios->map(function($exercicio) use ($aluno) {
        // Tenta encontrar a resposta do aluno para este exercício
        $resposta = $aluno->respostasExercicios->where('exercicio_id', $exercicio->id)->first();

        return [
            'titulo' => $exercicio->nome, // Ou $exercicio->titulo
            'data_envio' => $resposta ? $resposta->created_at->format('d/m/Y H:i') : '-',
            'nota' => $resposta ? number_format($resposta->nota, 1, ',', '') : '-',
            'status' => $resposta ? 'Entregue' : 'PENDENTE',
            'conceito' => $resposta && $resposta->conceito ? $resposta->conceito : '-'
        ];
    });

    $fileName = 'Relatorio_' . str_replace(' ', '_', $aluno->nome) . '_' . date('d-m-Y');

    // --- LÓGICA EXCEL (CSV) ---
    if ($formato === 'csv') {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName.csv",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        $columns = ['Exercício', 'Status', 'Data de Envio', 'Nota', 'Conceito'];

        $callback = function() use($dadosRelatorio, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM para acentos
            fputcsv($file, $columns, ';'); // Cabeçalho

            foreach ($dadosRelatorio as $row) {
                fputcsv($file, [
                    $row['titulo'],
                    $row['status'],
                    $row['data_envio'],
                    $row['nota'],
                    $row['conceito']
                ], ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // --- LÓGICA PDF ---
    if ($formato === 'pdf') {
        $pdf = Pdf::loadView('Professor.relatorios.pdf_aluno_individual', [
            'turma' => $turma,
            'aluno' => $aluno,
            'dados' => $dadosRelatorio
        ]);

        return $pdf->download($fileName . '.pdf');
    }

    return back();
}
}