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
    
    <link href="{{ asset('css/Aluno/resultadoProva.css') }}" rel="stylesheet"> 
</head>
<body>

    <div class="container">
        
        <div class="card-header-custom">
            <h2>Resultado da Prova: {{ $tentativa->prova->titulo }}</h2>
        </div>

        <div class="content-grid">

            <div class="report-main-content">
                <div class="card">
                    <h3><i class='bx bxs-detail'></i> Detalhes das Respostas</h3>
                    @foreach($tentativa->prova->questoes as $index => $questao)
                        <div class="card-questao-detalhe">
                            <h5>Questão {{ $index + 1 }}:</h5>
                            <p>{!! nl2br(e($questao->enunciado)) !!}</p>
                            
                            @php
                                $respostaAluno = $tentativa->respostas->where('prova_questao_id', $questao->id)->first();
                            @endphp

                            @if($questao->tipo_questao == 'multipla_escolha')
                                <p><strong>Sua Resposta:</strong>
                                    @php
                                        $altRespondida = $questao->alternativas->where('id', $respostaAluno->prova_alternativa_id ?? null)->first();
                                        $altCorreta = $questao->alternativas->where('correta', true)->first();
                                    @endphp
                                    @if($altRespondida)
                                        {{ $altRespondida->texto_alternativa }} 
                                        @if($respostaAluno->correta)
                                            <span class="text-success-custom">(Correta)</span>
                                        @else
                                            <span class="text-danger-custom">(Incorreta)</span>
                                        @endif
                                    @else
                                        <span class="text-warning-custom">Não Respondida</span>
                                    @endif
                                </p>
                                <p><strong>Resposta Correta:</strong> {{ $altCorreta->texto_alternativa ?? 'N/A' }}</p>
                            @elseif($questao->tipo_questao == 'texto')
                                <p><strong>Sua Resposta:</strong></p>
                                <div class="border p-2 bg-light rounded">{{ nl2br(e($respostaAluno->resposta_texto ?? '')) }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="sidebar">
                <div class="card">
                    <h3><i class='bx bxs-bar-chart-alt-2'></i> Seu Desempenho</h3>
                    <p><strong>Pontuação Final:</strong> <span class="text-primary-custom">{{ $tentativa->pontuacao_final ?? 'N/A' }}</span></p>
                    <p><strong>Total de Questões:</strong> {{ $totalQuestoes }}</p>
                    <p><strong>Acertos:</strong> <span class="text-success-custom">{{ $acertos }}</span></p>
                    <p><strong>Erros:</strong> <span class="text-danger-custom">{{ $erros }}</span></p>
                    @if(isset($pendentes) && $pendentes > 0)
                        <p><strong>Questões Pendentes:</strong> <span class="text-info-custom">{{ $pendentes }}</span></p>
                    @endif
                </div>

                <div class="card">
                    <h3><i class='bx bx-user'></i> Informações</h3>
                    <p><strong>Aluno:</strong> {{ $tentativa->aluno->nome }}</p>
                    <p><strong>Turma:</strong> {{ $tentativa->prova->turma->nome_turma }}</p>
                    <p><strong>Tempo Realizado:</strong> {{ $tempoDecorrido ?? 'N/A' }}</p>
                </div>

                <div class="card">
                    <h3><i class='bx bx-navigation'></i> Ações</h3>
                    <a href="{{ route('aluno.dashboard') }}" class="btn-custom btn-primary-custom">
                        <i class='bx bx-arrow-back'></i> Voltar para o Dashboard
                    </a>
                </div>
            </aside>

        </div>
    </div>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            
            @if (session('success'))
                Swal.fire({
                    title: 'Excelente!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Ver Resultados',
                    confirmButtonColor: '#28a745' // Cor verde
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Ops!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    title: 'Atenção!',
                    text: "{{ session('warning') }}",
                    icon: 'warning',
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#ffc107'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    title: 'Informação',
                    text: "{{ session('info') }}",
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#17a2b8'
                });
            @endif
        });
    </script>

</body>
</html>