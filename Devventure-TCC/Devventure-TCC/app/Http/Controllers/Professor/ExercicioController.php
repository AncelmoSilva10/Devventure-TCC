<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercicio;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Storage;
use App\Models\RespostaExercicio;   

class ExercicioController extends Controller
{
    public function exercicios(Request $request) 
    { 
        $professorId = Auth::guard('professor')->id();
        $status = $request->input('status', 'disponiveis');
        $searchTerm = $request->input('search');
        $agora = Carbon::now();

        $query = Exercicio::with(['turma', 'arquivosApoio', 'imagensApoio'])
                          ->where('professor_id', $professorId);

        if ($status == 'disponiveis') {
            $query->where('data_fechamento', '>', $agora);
        } else {
            $query->where('data_fechamento', '<=', $agora);
        }

        if ($searchTerm) {
            $query->where('nome', 'like', '%' . $searchTerm . '%');
        }

        $exercicios = $query->get();
        
        $turmas = Turma::where('professor_id', $professorId)->get();
        
        return view('Professor/Exercicio', [
            'exercicios' => $exercicios,
            'status' => $status,
            'turmas' => $turmas 
        ]);
    }

    public function CriarExercicios(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'turma_id' => 'required|exists:turmas,id',
            'pontos' => 'required|integer|min:0', 
            'data_publicacao' => 'required|date',
            'data_fechamento' => 'required|date|after_or_equal:data_publicacao',
            'arquivos_apoio' => 'nullable|array',
            'arquivos_apoio.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,zip,txt,java|max:5120',
            'imagens_apoio' => 'nullable|array',
            'imagens_apoio.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        
        if ($validator->fails()) {
            dd($validator->errors());
        }

        
        try {
            DB::transaction(function () use ($request) {
                $exercicio = Exercicio::create([
                    'nome' => $request->nome,
                    'descricao' => $request->descricao,
                    'pontos' => $request->pontos, 
                    'data_publicacao' => $request->data_publicacao,
                    'data_fechamento' => $request->data_fechamento,
                    'turma_id' => $request->turma_id,
                    'professor_id' => Auth::guard('professor')->id()
                ]);

                if ($request->hasFile('arquivos_apoio')) {
                    foreach ($request->file('arquivos_apoio') as $arquivo) {
                        $path = $arquivo->store('exercicios/arquivos_apoio', 'public');
                        $nomeOriginal = $arquivo->getClientOriginalName();
                        $exercicio->arquivosApoio()->create([
                            'arquivo_path' => $path,
                            'nome_original' => $nomeOriginal 
                        ]);
                    }
                }

                if ($request->hasFile('imagens_apoio')) {
                    foreach ($request->file('imagens_apoio') as $imagem) {
                        $path = $imagem->store('exercicios/imagens_apoio', 'public');
                        $exercicio->imagensApoio()->create(['imagem_path' => $path]);
                    }
                }
            });

        } catch (\Exception $e) {
            Log::error('Erro ao criar exercício: ' . $e->getMessage());
           
        }

        return redirect()->back()->with('sweet_success', 'Exercício criado com sucesso!');
    }

    public function mostrarRespostas(Exercicio $exercicio)
{
    // Segurança: Garante que o professor só veja exercícios que ele mesmo criou.
    if ($exercicio->professor_id !== Auth::guard('professor')->id()) {
        abort(403);
    }

    
    $exercicio->load(['respostas.aluno', 'respostas.arquivos']);

    return view('Professor.respostasExercicio', compact('exercicio'));
}

public function avaliarResposta(Request $request, RespostaExercicio $resposta)
{
    
    if ($resposta->exercicio->professor_id !== Auth::guard('professor')->id()) {
        abort(403);
    }

    $request->validate([
        'conceito' => 'required|in:MB,B,R,I',
        'nota' => 'required|integer|min:0|max:100',
        'feedback' => 'nullable|string',
    ]);


    DB::transaction(function () use ($request, $resposta) {
        
        
        $aluno = $resposta->aluno;

        
        $notaAntiga = $resposta->nota ?? 0;

        
        $notaNova = (int) $request->nota;

        
        $diferencaDePontos = $notaNova - $notaAntiga;

        
        $aluno->increment('total_pontos', $diferencaDePontos);

     
        $resposta->update([
            'conceito' => $request->conceito,
            'nota' => $notaNova,
            'feedback' => $request->feedback,
        ]);
    });

    return redirect()->back()->with('sweet_success', 'Avaliação guardada com sucesso e pontuação do aluno atualizada!');
}

    public function destroy(Exercicio $exercicio)
    {
        // 1. Segurança
        if ($exercicio->turma->professor_id !== Auth::guard('professor')->id()) {
            abort(403, 'Ação não autorizada.');
        }

        // 2. Apagar arquivos de Resposta dos Alunos
        foreach ($exercicio->respostas as $resposta) {
            foreach ($resposta->arquivos as $arqAluno) {
                // Ajuste 'arquivo_path' conforme o nome da sua coluna no banco
                if (Storage::exists('public/' . $arqAluno->arquivo_path)) {
                    Storage::delete('public/' . $arqAluno->arquivo_path);
                }
            }
        }

        // 3. Apagar Imagens de Apoio (Do Professor)
        // Certifique-se que o relacionamento 'imagensApoio' existe no Model Exercicio
        foreach ($exercicio->imagensApoio as $img) {
            // Ajuste 'path' conforme o nome da coluna na tabela de imagens
            if (Storage::exists('public/' . $img->path)) {
                Storage::delete('public/' . $img->path);
            }
        }

        // 4. Apagar Arquivos de Apoio (Do Professor)
        // Certifique-se que o relacionamento 'arquivosApoio' existe no Model Exercicio
        foreach ($exercicio->arquivosApoio as $arq) {
            // Ajuste 'path' conforme o nome da coluna na tabela de arquivos
            if (Storage::exists('public/' . $arq->path)) {
                Storage::delete('public/' . $arq->path);
            }
        }

        // 5. Apagar o registro do banco
        // O banco apagará as linhas das tabelas filhas via Cascade (se configurado nas migrations)
        $exercicio->delete();

        return redirect()->route('professor.exercicios.index')
                         ->with('sweet_success', 'Exercício e arquivos excluídos com sucesso!');
    }
}

