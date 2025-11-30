<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Resultado da Prova</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Fontes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Aluno/showProva.css') }}" rel="stylesheet"> 
</head>
<body>

    <div class="page-wrapper">
        
        <header class="page-header-blue">
            <div class="header-container max-width-container">
                
                <div class="header-info">
                    <h1>Resultado da Prova</h1>
                    <p>{{ $tentativa->prova->titulo }}</p>
                </div>
            </div>
        </header>

        <div class="content-grid max-width-container">
            
            <div class="report-main-content">
                <div class="card">
                    <h2><i class='bx bxs-detail'></i> Detalhes das Respostas</h2>
                    @foreach($tentativa->prova->questoes as $index => $questao)
                        @php
                            // Busca a resposta do aluno específica para esta questão
                            $respostaAluno = $tentativa->respostas->where('prova_questao_id', $questao->id)->first();
                            // Define status e classes visuais
                            $acertou = $respostaAluno ? $respostaAluno->correta : false;
                            $statusClass = $acertou ? 'status-correct' : 'status-wrong';
                            $statusIcon = $acertou ? 'bx-check-circle' : 'bx-x-circle';
                            $statusText = $acertou ? 'Correta' : 'Incorreta';
                        @endphp
                        
                        <div class="card-questao-detalhe {{ $statusClass }}">
                            <div class="questao-header">
                                <h5>Questão {{ $index + 1 }}</h5>
                                <span class="status-text">
                                    <i class='bx {{ $statusIcon }}'></i> {{ $statusText }}
                                </span>
                            </div>
                            
                            <p class="questao-enunciado">{!! nl2br(e($questao->enunciado)) !!}</p>
                            
                            @if($questao->tipo_questao == 'multipla_escolha')
                                @php
                                    $altRespondida = $questao->alternativas->where('id', $respostaAluno->prova_alternativa_id ?? null)->first();
                                    $altCorreta = $questao->alternativas->where('correta', true)->first();
                                @endphp
                                <div class="feedback-box">
                                    <p><strong>Sua Resposta:</strong> {{ $altRespondida->texto_alternativa ?? 'Não respondeu' }}</p>
                                    @if(!$acertou)
                                        <p class="gabarito-text">Gabarito: {{ $altCorreta->texto_alternativa ?? 'N/A' }}</p>
                                    @endif
                                </div>
                            @elseif($questao->tipo_questao == 'texto')
                                <div class="feedback-box">
                                    <p><strong>Sua Resposta:</strong></p>
                                    <div class="resposta-texto">{{ nl2br(e($respostaAluno->resposta_texto ?? 'Em branco')) }}</div>
                                </div>
                            @endif
                            
                            @if(isset($respostaAluno->feedback_professor) || isset($respostaAluno->nota_manual))
                                <div class="feedback-manual">
                                    <strong>Nota/Feedback do Professor:</strong> {{ $respostaAluno->nota_manual ?? 'Aguardando' }}
                                    @if(isset($respostaAluno->feedback_professor))
                                        <p>{{ $respostaAluno->feedback_professor }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="sidebar">
                <div class="card">
                    <h2><i class='bx bxs-award'></i> Seu Desempenho</h2>
                    <div class="score-display">
                        <div class="score-number">{{ $tentativa->pontuacao_final ?? '0' }}</div>
                        <div class="score-label">Pontos Finais</div>
                    </div>
                    
                    <div class="info-row">
                        <span>Total de Questões</span>
                        <strong>{{ $totalQuestoes }}</strong>
                    </div>
                    <div class="info-row success-row">
                        <span>Acertos</span>
                        <strong>{{ $acertos }}</strong>
                    </div>
                    <div class="info-row danger-row">
                        <span>Erros</span>
                        <strong>{{ $erros }}</strong>
                    </div>
                    @if(isset($pendentes) && $pendentes > 0)
                        <div class="info-row warning-row" style="border:none;">
                            <span>Pendentes (Correção Manual)</span>
                            <strong>{{ $pendentes }}</strong>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <h2><i class='bx bx-user'></i> Informações</h2>
                    <div class="info-row">
                        <span>Aluno</span>
                        <strong>{{ $tentativa->aluno->nome }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Turma</span>
                        <strong>{{ $tentativa->prova->turma->nome_turma }}</strong>
                    </div>
                    <div class="info-row" style="border:none;">
                        <span>Tempo Realizado</span>
                        <strong>{{ $tempoDecorrido ?? 'N/A' }}</strong>
                    </div>
                </div>

                <a href="{{ route('turmas.especifica', $tentativa->prova->turma_id) }}" class="btn-custom btn-primary-custom">
                    <i class='bx bx-arrow-back'></i> Voltar para a Turma
                </a>
            </aside>

        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // Lógica do SweetAlert (Mantida, mas sem emojis)
            @if (session('success'))
                Swal.fire({
                    title: 'Excelente!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Ver Resultados',
                    confirmButtonColor: '#22c55e' 
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Ops!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    title: 'Informação',
                    text: "{{ session('info') }}",
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>

</body>
</html>