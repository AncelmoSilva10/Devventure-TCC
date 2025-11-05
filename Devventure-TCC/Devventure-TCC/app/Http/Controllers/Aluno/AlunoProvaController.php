<?php

namespace App\Http\Controllers\Aluno;

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

class AlunoProvaController extends Controller
{
    
    public function show(Prova $prova)
    {
        $aluno = Auth::user();
        $agora = Carbon::now();

        // Verifica se a prova está no período de abertura/fechamento
        if ($agora->isBefore($prova->data_abertura)) {
            return back()->with('error', 'Esta prova ainda não abriu.');
        }
        if ($agora->isAfter($prova->data_fechamento)) {
            return back()->with('error', 'O prazo para iniciar esta prova já expirou.');
        }
        
        // Verifica se o aluno já tem uma tentativa finalizada
        $tentativaAnterior = $prova->tentativas()
                                   ->where('aluno_id', $aluno->id)
                                   ->whereNotNull('hora_fim') // Apenas tentativas finalizadas
                                   ->first();
        if ($tentativaAnterior) {
             return redirect()->route('aluno.provas.resultado', $tentativaAnterior->id)->with('info', 'Você já completou esta prova.');
        }

        return view('Aluno.provaShow', compact('prova')); // View com instruções
    }

    /**
     * INICIA A PROVA (Cria a tentativa e o timer)
     */
    public function iniciar(Prova $prova)
    {
        $aluno = Auth::user();
        $agora = Carbon::now();

        // Reforça validações de data
        if ($agora->isBefore($prova->data_abertura) || $agora->isAfter($prova->data_fechamento)) {
            return back()->with('error', 'Esta prova não pode ser iniciada no momento.');
        }

        // Verifica se já existe uma tentativa EM ANDAMENTO (sem hora_fim)
        $tentativaEmAndamento = $prova->tentativas()
                                     ->where('aluno_id', $aluno->id)
                                     ->whereNull('hora_fim')
                                     ->first();

        if ($tentativaEmAndamento) {
            // Se já tem uma tentativa em andamento, redireciona para continuar
            return redirect()->route('aluno.provas.fazer', $tentativaEmAndamento->id);
        }

        // Cria a nova tentativa
        $tentativa = AlunoProvaTentativa::create([
            'prova_id' => $prova->id,
            'aluno_id' => $aluno->id,
            'hora_inicio' => $agora,
        ]);

        // Redireciona para a página de fazer a prova
        return redirect()->route('aluno.provas.fazer', $tentativa->id);
    }

    /**
     * Página onde o aluno FAZ a prova (com timer).
     */
    public function fazer(AlunoProvaTentativa $tentativa)
    {
        // Garante que o aluno logado é o dono da tentativa
        if ($tentativa->aluno_id != Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        // Se a prova já foi finalizada, redireciona para o resultado
        if ($tentativa->hora_fim != null) {
            return redirect()->route('aluno.provas.resultado', $tentativa->id);
        }

        // Carrega a prova e suas questões
        $prova = $tentativa->prova->load('questoes.alternativas');

        // Calcula a hora exata de término
        $horaLimite = $tentativa->hora_inicio->addMinutes($prova->duracao_minutos);

        // Verifica se o tempo já não expirou no servidor
        if (Carbon::now()->isAfter($horaLimite)) {
            // (Opcional: Submeter automaticamente se o tempo acabou)
            // Se o tempo acabou, finalize a tentativa e redirecione
            if ($tentativa->hora_fim === null) {
                $tentativa->update(['hora_fim' => Carbon::now()]);
                // Aqui você pode chamar uma correção para questões de múltipla escolha se quiser
                $this->corrigirTentativaAutomatica($tentativa);
            }
            return redirect()->route('aluno.provas.resultado', $tentativa->id)->with('error', 'O tempo para esta prova expirou.');
        }
        
        // Passa a hora limite (em formato ISO) para o JavaScript
        $horaLimiteISO = $horaLimite->toIso8601String();

        // Carregar respostas prévias (se o aluno recarregou a página e não submeteu)
        $respostasSalvas = $tentativa->respostas->pluck('prova_alternativa_id', 'prova_questao_id')->toArray();
        $respostasTextoSalvas = $tentativa->respostas->pluck('resposta_texto', 'prova_questao_id')->toArray();

        return view('Aluno.provaFazer', compact('tentativa', 'prova', 'horaLimiteISO', 'respostasSalvas', 'respostasTextoSalvas'));
    }

    /**
     * Recebe as respostas do aluno.
     */
    public function submeter(Request $request, AlunoProvaTentativa $tentativa)
    {
        $aluno = Auth::guard('aluno')->user(); // Pega o aluno logado

        if ($tentativa->aluno_id != $aluno->id) { 
            abort(403, 'Acesso negado.');
        }
        
        // 1. Verifica se a prova já foi submetida
        if ($tentativa->hora_fim !== null) {
            return redirect()->route('aluno.provas.resultado', $tentativa->id)->with('info', 'Esta prova já foi submetida.');
        }

        $prova = $tentativa->prova;
        $horaLimite = $tentativa->hora_inicio->addMinutes($prova->duracao_minutos);

        // =======================================================
        // !! BLOCO DE VALIDAÇÃO DE RESPOSTAS VAZIAS ADICIONADO !!
        // =======================================================
        $respostasEnviadas = $request->input('respostas', []);
        
        // Converte o array de respostas em uma Coleção e remove todos os valores vazios (null, '', 0)
        $respostasPreenchidas = collect($respostasEnviadas)->filter();

        // Se, após filtrar, a coleção estiver vazia, significa que o aluno não respondeu nada.
        if ($respostasPreenchidas->isEmpty()) {
            // Redireciona de volta para a página de fazer a prova com uma mensagem de erro.
            // Note que NÃO atualizamos a 'hora_fim' ainda.
            return redirect()->back()
                             ->with('error', 'Você não pode entregar uma prova em branco. Responda pelo menos uma questão.')
                             ->withInput(); // Mantém as respostas (vazias) no formulário
        }
        // =======================================================
        // FIM DO BLOCO DE VALIDAÇÃO
        // =======================================================

        // Se a validação passou, AGORA SIM travamos a hora do fim
        $tentativa->update(['hora_fim' => Carbon::now()]);

        // Verifica se enviou após o tempo limite
        if ($tentativa->hora_fim->isAfter($horaLimite)) {
             // Opcional: registrar que foi submetido após o tempo ou aplicar penalidade
        }

        $pontuacaoTotal = 0;

        // Apaga respostas anteriores para evitar duplicação em caso de reenvio inesperado
        $tentativa->respostasQuestoes()->delete(); // Verifique se o nome do relacionamento é 'respostasQuestoes'

        // Loop para salvar e corrigir as respostas
        foreach ($prova->questoes as $questao) {
            $respostaAluno = $respostasEnviadas[$questao->id] ?? null;
            $correta = false;
            
            if ($questao->tipo_questao == 'multipla_escolha') {
                
                $alternativaCorreta = $questao->alternativas->where('correta', true)->first();
                
                if ($alternativaCorreta && $respostaAluno == $alternativaCorreta->id) {
                    $correta = true;
                    $pontuacaoTotal += $questao->pontuacao;
                }

                AlunoRespostaProva::create([
                    'aluno_prova_tentativa_id' => $tentativa->id,
                    'prova_questao_id' => $questao->id,
                    'prova_alternativa_id' => $respostaAluno,
                    'correta' => $correta,
                ]);

            } elseif ($questao->tipo_questao == 'texto') {
                
                if (!empty($respostaAluno)) {
                    $pontuacaoTotal += $questao->pontuacao;
                }

                AlunoRespostaProva::create([
                    'aluno_prova_tentativa_id' => $tentativa->id,
                    'prova_questao_id' => $questao->id,
                    'resposta_texto' => $respostaAluno,
                    'correta' => true, 
                ]);
            }
        }

        // Atualiza a pontuação final (agora incluindo questões de texto) na tabela da tentativa
        $tentativa->update(['pontuacao_final' => $pontuacaoTotal]);

        // Incrementa o total de pontos do aluno
        if ($pontuacaoTotal > 0) {
            $aluno->increment('total_pontos', $pontuacaoTotal);
        }

        return redirect()->route('aluno.provas.resultado', $tentativa->id)->with('success', 'Prova submetida com sucesso!');
    }

    /**
     * Mostra o resultado para o aluno.
     */
    public function resultado(AlunoProvaTentativa $tentativa)
    {
        // Garante que o aluno logado é o dono
        if ($tentativa->aluno_id != Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        // Carrega tudo
        $tentativa->load('prova.questoes', 'respostas.questao.alternativas');

        $totalQuestoes = $tentativa->prova->questoes->count();
        $acertos = $tentativa->respostas->where('correta', true)->count();
        $erros = $tentativa->respostas->where('correta', false)->count();
        $pendentes = $tentativa->respostas->where('correta', null)->count(); // Questões de texto

        // Calcula o tempo que o aluno levou
        $tempoDecorrido = null;
        if ($tentativa->hora_inicio && $tentativa->hora_fim) {
            $diff = $tentativa->hora_inicio->diff($tentativa->hora_fim);
            $tempoDecorrido = $diff->format('%Hh %Im %Ss');
        }

        return view('Aluno.provaResultado', compact('tentativa', 'totalQuestoes', 'acertos', 'erros', 'pendentes', 'tempoDecorrido'));
    }

    /**
     * Método auxiliar para corrigir automaticamente as questões de múltipla escolha
     * caso o tempo expire e a prova seja submetida automaticamente.
     */
    protected function corrigirTentativaAutomatica(AlunoProvaTentativa $tentativa)
    {
        $prova = $tentativa->prova->load('questoes.alternativas');
        $pontuacaoTotal = 0;

        foreach ($prova->questoes as $questao) {
            if ($questao->tipo_questao == 'multipla_escolha') {
                $respostaAluno = $tentativa->respostas()->where('prova_questao_id', $questao->id)->first();
                $correta = false;
                
                if ($respostaAluno && $alternativaCorreta = $questao->alternativas->where('correta', true)->first()) {
                    if ($respostaAluno->prova_alternativa_id == $alternativaCorreta->id) {
                        $correta = true;
                        $pontuacaoTotal += $questao->pontuacao;
                    }
                }
                // Atualiza a resposta (se já existe) ou cria (se não existe e o tempo acabou)
                AlunoRespostaProva::updateOrCreate(
                    ['aluno_prova_tentativa_id' => $tentativa->id, 'prova_questao_id' => $questao->id],
                    ['correta' => $correta]
                );
            }
            // Questões de texto são ignoradas para correção automática
        }
        $tentativa->update(['pontuacao_final' => $pontuacaoTotal]);

    }
}