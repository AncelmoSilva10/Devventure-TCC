<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Professor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Depoimento;
class DashboardController extends Controller
{
    public function admDashboard()
    {
        $alunosCount = Aluno::count();
    $professoresCount = Professor::count();

    // Paginação com 5 registros por página
    $alunosData = Aluno::paginate(5);
    $professoresData = Professor::paginate(5);

    $depoimentosData = Depoimento::latest()->paginate(5);

    return view('Adm/dashboard', compact('alunosCount', 'professoresCount', 'alunosData', 'professoresData', 'depoimentosData'));
    }

   

    public function countUsers()
    {
        $professores = Professor::where('role', 'professor')->count();
        $alunos = Aluno::where('role', 'aluno')->count();

        return response()->json([
            'professores' => $professores,
            'alunos' => $alunos,
        ]);
    }


    public function searchAlunos(Request $request)
{
    $query = $request->input('query');

    
    
    $alunos = Aluno::where('nome', 'LIKE', "%{$query}%")
                   ->orWhere('email', 'LIKE', "%{$query}%")
                   ->orWhere('ra', 'LIKE', "%{$query}%")
                   ->get(); 

    
    return response()->json($alunos);
}


public function searchProfessores(Request $request)
{
    $query = $request->input('query');

    $professores = Professor::where('nome', 'LIKE', "%{$query}%")
                            ->orWhere('email', 'LIKE', "%{$query}%")
                            ->orWhere('cpf', 'LIKE', "%{$query}%")
                            ->get();
    
    return response()->json($professores);
}

public function blockAluno(Aluno $aluno)
{
    $aluno->update(['status' => 'bloqueado']);

    
    return redirect(url()->previous() . '#alunos')->with('success', 'Aluno bloqueado com sucesso!');
}

public function unblockAluno(Aluno $aluno)
{
    $aluno->update(['status' => 'ativo']);

    
    return redirect(url()->previous() . '#alunos')->with('success', 'Aluno desbloqueado com sucesso!');
}

public function blockProfessor(Professor $professor)
{
    $professor->update(['status' => 'bloqueado']);

    
    return redirect(url()->previous() . '#professores')->with('success', 'Professor bloqueado com sucesso!');
}

public function unblockProfessor(Professor $professor)
{
    $professor->update(['status' => 'ativo']);

    
    return redirect(url()->previous() . '#professores')->with('success', 'Professor desbloqueado com sucesso!');
}

public function downloadCsvAlunos() 
    {
       
        $sql = 'select Nome, Email, RA from aluno';
        $alunos = DB::select($sql);

        
        $filename = 'lista_de_alunos.csv';

        
        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        
        $callback = function () use ($alunos) {
            $file = fopen('php://output', 'w');

            
            $header_columns = [
                'Nome',
                'Email',
                'RA'
            ];
            
            fputcsv($file, $header_columns, ';');

            
            foreach ($alunos as $aluno) {
                
                $row = [
                    'nome'  => mb_convert_encoding($aluno->Nome, 'ISO-8859-1', 'UTF-8'),
                    'email' => $aluno->Email,
                    'ra'    => $aluno->RA
                ];

             
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

      
        return Response::stream($callback, 200, $headers);
    }

public function downloadCsvProfessores()
    {
        
        $sql = 'select Nome, Email, CPF from professor';
        $professores = DB::select($sql);

       
        $filename = 'lista_de_professores.csv';

      
        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

      
        $callback = function () use ($professores) {
            $file = fopen('php://output', 'w');

            
            $header_columns = [
                'Nome Completo',
                'E-mail',
                'CPF'
            ];
            fputcsv($file, $header_columns, ';');

            
            foreach ($professores as $professor) {
              
                $row = [
                    mb_convert_encoding($professor->Nome, 'ISO-8859-1', 'UTF-8'),
                    $professor->Email,
                    $professor->CPF
                ];

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        
        return Response::stream($callback, 200, $headers);
    }

    public function blockDepoimento(Depoimento $depoimento)
{
    $depoimento->update(['aprovado' => false]);

    
    return redirect(url()->previous() . '#depoimentos')->with('success', 'Depoimento bloqueado com sucesso!');
}


public function unblockDepoimento(Depoimento $depoimento)
{
    $depoimento->update(['aprovado' => true]);

    
    return redirect(url()->previous() . '#depoimentos')->with('success', 'Depoimento aprovado com sucesso!');
}

public function searchDepoimentos(Request $request)
{
    $query = $request->input('query');

    
    $depoimentos = Depoimento::where('autor', 'LIKE', "%{$query}%")
                             ->orWhere('texto', 'LIKE', "%{$query}%")
                             ->get();
    
    
    return response()->json($depoimentos);
}

}
