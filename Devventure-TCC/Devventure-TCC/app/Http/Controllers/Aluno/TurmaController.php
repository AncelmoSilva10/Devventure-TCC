<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercicio; 
use App\Models\Aula;    
use App\Models\Aviso;
use App\Models\Prova;
use Illuminate\Support\Facades\DB;


class TurmaController extends Controller
{
    public function minhasTurmas()
    {
        $aluno = Auth::guard('aluno')->user();
        $turmas = $aluno->turmas()->get();
        return view('Aluno/turma', ['turmas' => $turmas]);
    }

public function mostrarTurmaEspecifica(Turma $turma, Request $request)
{
    $alunoLogado = Auth::guard('aluno')->user();


    $avisosDaTurma = Aviso::where(function($query) use ($turma, $alunoLogado) {
       
        $query->whereHas('turmas', function($q) use ($turma) {
            $q->where('turma_id', $turma->id);
        })
        
        ->orWhere(function($subQuery) use ($alunoLogado, $turma) {
            $subQuery->whereHas('alunos', function($q) use ($alunoLogado) {
                $q->where('aluno_id', $alunoLogado->id);
            })
            ->where('professor_id', $turma->professor_id);
        });
    })
    ->with('professor')
    ->orderBy('created_at', 'desc')
    ->paginate(5, ['*'], 'avisosPage');

    $alunosDaTurma = $turma->alunos()
        ->orderBy('nome')
        ->paginate(10, ['*'], 'colegasPage');

    $aulasDaTurma = $turma->aulas()
        ->orderBy('created_at', 'desc')
        ->paginate(6, ['*'], 'aulasPage');

    $exerciciosDaTurma = Exercicio::where('turma_id', $turma->id)
        ->where('data_publicacao', '<=', now()) 
        ->with(['respostas' => function ($query) use ($alunoLogado) {
            $query->where('aluno_id', $alunoLogado->id);
        }])
        ->orderBy('data_fechamento', 'asc') 
        ->paginate(6, ['*'], 'exerciciosPage');

    $provasDaTurma = $turma->provas()
        ->where('data_abertura', '<=', now()) 
        ->with(['tentativas' => function($query) use ($alunoLogado) { 
            $query->where('aluno_id', $alunoLogado->id);
        }])
        ->orderBy('data_fechamento', 'desc')
        ->paginate(6, ['*'], 'provasPage');

    return view('Aluno/turmaEspecifica', [
        'turma' => $turma,
        'alunos' => $alunosDaTurma,
        'exercicios' => $exerciciosDaTurma, 
        'aulas' => $aulasDaTurma,
        'avisos' => $avisosDaTurma, // Agora contém os dois tipos
        'provas' => $provasDaTurma,
    ]);
}

public function mostrarRanking(Turma $turma)
{
    if (!Auth::guard('aluno')->user()->turmas->contains($turma->id)) {
        abort(403, 'Acesso não autorizado a este ranking.');
    }

    $totalExercicios = $turma->exercicios()->count();
    $totalAulas = $turma->aulas()->count();

    $alunos = $turma->alunos()
                    ->orderBy('total_pontos', 'desc')
                    ->orderBy('updated_at', 'asc')
                    ->get();

    $alunosRanking = $alunos->map(function ($aluno) use ($totalExercicios, $totalAulas, $turma) {
        
        $exerciciosConcluidos = $aluno->respostas()
            ->whereHas('exercicio', function($q) use ($turma) {
                $q->where('turma_id', $turma->id);
            })
            ->count();

        $aulasConcluidas = $aluno->aulas()
            ->where('turma_id', $turma->id)
            ->wherePivot('status', 'concluido')
            ->count();

        $frequenciaPorc = 0;
        if ($totalAulas > 0) {
            $frequenciaPorc = ($aulasConcluidas / $totalAulas) * 100;
        } elseif ($totalAulas == 0) {
            $frequenciaPorc = 100;
        }

        $aluno->exercicios_concluidos = $exerciciosConcluidos;
        $aluno->total_exercicios_turma = $totalExercicios;
        $aluno->frequencia_formatada = round($frequenciaPorc) . '%';

        return $aluno;
    });

    return view('Aluno.ranking', [
        'turma' => $turma,
        'alunosRanking' => $alunosRanking,
        'backRoute' => route('turmas.especifica', $turma->id) 
    ]);
}
}