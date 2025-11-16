<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - {{ $prova->titulo }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="{{ asset('css/Aluno/iniciandoProva.css') }}" rel="stylesheet"> 
</head>
<body>

    <div class="container">
    
        @if (session('success'))
            <div class="alert alert-success"><i class='bx bx-check-circle'></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger"><i class='bx bx-error-alt'></i> {{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info"><i class='bx bx-info-circle'></i> {{ session('info') }}</div>
        @endif

        <div class="content-grid">
            <div>
                <div class="card">
                    <div class="card-header-custom">
                        <h2>{{ $prova->titulo }}</h2>
                        <div class="deadline-info">
                            <i class='bx bx-time-five'></i> Prazo de entrega: {{ $prova->data_fechamento->format('d/m/Y \à\s H:i') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Turma:</strong> {{ $prova->turma->nome_turma }}</p>
                        <p class="card-text"><strong>Período para Iniciar:</strong> {{ $prova->data_abertura->format('d/m/Y H:i') }} até {{ $prova->data_fechamento->format('d/m/Y H:i') }}</p>
                        <p class="card-text"><strong>Duração:</strong> {{ $prova->duracao_minutos }} minutos a partir do início.</p>
                        
                        @if($prova->instrucoes)
                            <h3>Instruções do Professor</h3>
                            <p class="card-text">{!! nl2br(e($prova->instrucoes)) !!}</p>
                        @else
                            <h3>Instruções do Professor</h3>
                            <p class="card-text">Nenhuma instrução específica fornecida.</p>
                        @endif


                        <div class="button-group">
                            <button type="button" class="btn btn-secondary-custom" onclick="window.history.back()">
                                <i class='bx bx-arrow-back'></i> Voltar
                            </button>
                            
                            <form action="{{ route('aluno.provas.iniciar', $prova) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class='bx bx-play-circle'></i> Iniciar Prova
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card status-card">
                    <h3>Status da Prova</h3>
                    @php
                        $agora = \Carbon\Carbon::now();
                        $statusBadgeClass = 'pending'; // Default
                        $statusBadgeText = 'Pendente';
                        $statusMessage = 'Você ainda não iniciou esta prova.';

                        $tentativa = Auth::user()->tentativasProvas()->where('prova_id', $prova->id)->first();

                        if ($tentativa) {
                            if ($tentativa->hora_fim !== null) {
                                $statusBadgeClass = 'realizada';
                                $statusBadgeText = 'Realizada';
                                $statusMessage = 'Você já completou esta prova.';
                            } elseif ($tentativa->hora_inicio !== null && $tentativa->hora_fim === null) {
                                $statusBadgeClass = 'iniciada';
                                $statusBadgeText = 'Em Andamento';
                                $statusMessage = 'Você já iniciou esta prova. Clique em "Iniciar Prova" para continuar.';
                            }
                        } elseif ($agora->isAfter($prova->data_fechamento)) {
                             $statusBadgeClass = 'fechada';
                             $statusBadgeText = 'Fechada';
                             $statusMessage = 'O prazo para esta prova já expirou.';
                        }
                    @endphp
                    <div class="status-badge {{ $statusBadgeClass }}">
                        <i class='bx bx-info-circle'></i> {{ $statusBadgeText }}
                    </div>
                    <p>{{ $statusMessage }}</p>
                </div>

                <div class="card">
                    <h3>Sua Pontuação</h3>
                    @if ($tentativa && $tentativa->hora_fim !== null)
                        <p><strong>Pontuação Final:</strong> {{ $tentativa->pontuacao_final ?? 'Aguardando correção' }}</p>
                        <p><strong>Status:</strong> <span class="status-badge realizada">Realizada</span></p>
                        <p>Você pode ver o resultado completo <a href="{{ route('aluno.provas.resultado', $tentativa->id) }}">aqui</a>.</p>
                    @elseif ($tentativa && $tentativa->hora_inicio !== null && $tentativa->hora_fim === null)
                        <p>Você já iniciou esta prova.</p>
                        <p>Pontuação: 0 pontos (Até o momento)</p>
                    @else
                        <p>Você ainda não iniciou</p>
                        <p>0 pontos</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>