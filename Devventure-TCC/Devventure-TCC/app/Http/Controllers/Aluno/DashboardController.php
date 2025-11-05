<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Convite; 
use App\Models\Exercicio;
use App\Models\Prova; 
use App\Models\AlunoProvaTentativa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    
    public function __invoke(Request $request)
    {
        $aluno = Auth::guard('aluno')->user();
        
        // Convites
        $convites = Convite::where('aluno_id', $aluno->id)
                               ->where('status', 'pendente')
                               ->with('turma.professor')
                               ->get();

        $turmasIds = $aluno->turmas()->pluck('id');

        // Próximos Exercícios
        $proximosExercicios = Exercicio::whereIn('turma_id', $turmasIds)
            ->where('data_fechamento', '>=', now()) 
            ->orderBy('data_fechamento', 'asc')
            ->with(['turma', 'respostas' => function ($query) use ($aluno) {
                $query->where('aluno_id', $aluno->id);
            }])
            ->get();

       $proximasProvas = Prova::whereIn('turma_id', $turmasIds)
                               ->where('data_abertura', '<=', now()) // A prova já está aberta ou abrirá
                               // ->where('data_fechamento', '>=', now()) // <-- REMOVA ESTA LINHA para incluir provas atrasadas
                               ->orderBy('data_fechamento', 'asc') // Ordena pelas mais próximas a fechar/mais atrasadas
                               ->with('turma') 
                               ->whereDoesntHave('tentativas', function ($query) use ($aluno) {
                                   $query->where('aluno_id', $aluno->id)
                                         ->whereNotNull('hora_fim'); // APENAS EXCLUI AS PROVAS JÁ FINALIZADAS PELO ALUNO
                               })
                               ->get();
                               
        // Para cada prova, verificar o status (se já iniciou, finalizou, ou se está atrasada)
        foreach ($proximasProvas as $prova) {
            $prova->tentativaExistente = AlunoProvaTentativa::where('aluno_id', $aluno->id)
                                                            ->where('prova_id', $prova->id)
                                                            ->first();
            $prova->statusTentativa = 'pendente'; // Assume pendente por padrão

            // Se o aluno já iniciou a tentativa
            if ($prova->tentativaExistente) {
                if ($prova->tentativaExistente->hora_fim) {
                    $prova->statusTentativa = 'finalizada'; // Não deveria chegar aqui por conta do whereDoesntHave
                } else {
                    $prova->statusTentativa = 'iniciada'; // Aluno já iniciou, mas não finalizou
                }
            } 
            
            // Verifica se a prova está atrasada (data de fechamento já passou)
            // Mesmo se o aluno já iniciou, se a data de fechamento passou, ela está "atrasada" para finalização.
            if ($prova->data_fechamento && $prova->data_fechamento->isPast() && $prova->statusTentativa != 'finalizada') {
                $prova->statusTentativa = 'atrasada'; 
            }
            
            // Verifica se a prova ainda não abriu (embora o 'data_abertura <= now()' já filtre isso)
            if ($prova->data_abertura && $prova->data_abertura->isFuture()) {
                 $prova->statusTentativa = 'nao_aberta'; // Pouco provável de acontecer com a consulta atual, mas bom ter
            }
        }

        // Unificar Próximos Exercícios e Próximas Provas em uma única coleção
        $todasEntregas = collect();
        $todasEntregas = $todasEntregas->merge($proximosExercicios->map(function($item) {
            $item->type = 'exercicio';
            return $item;
        }));
        $todasEntregas = $todasEntregas->merge($proximasProvas->map(function($item) {
            $item->type = 'prova';
            return $item;
        }));
        
        // Ordena a coleção unificada por data_fechamento e depois limita
        $todasEntregas = $todasEntregas->sortBy(function($item) {
            return $item->data_fechamento;
        })->take(5);


        // Progresso nas Aulas
        $totalSegundosAulas = Aula::whereIn('turma_id', $turmasIds)->sum('duracao_segundos');
        $segundosAssistidosPeloAluno = $aluno->aulas()
            ->whereIn('turma_id', $turmasIds)
            ->sum('segundos_assistidos');

        $progressoPercentual = 0;
        if ($totalSegundosAulas > 0) {
            $progressoPercentual = round(($segundosAssistidosPeloAluno / $totalSegundosAulas) * 100);
        }
        if ($progressoPercentual > 100) {
            $progressoPercentual = 100;
        }

        // Minhas Turmas
        $minhasTurmas = $aluno->turmas()->with('professor')->latest()->get();

        return view('Aluno.dashboard', [
            'convites' => $convites,
            'todasEntregas' => $todasEntregas,
            'progressoPercentual' => $progressoPercentual,
            'minhasTurmas' => $minhasTurmas,
        ]);
    }
}