<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Prova: {{ $prova->titulo }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Aluno/realizandoProva.css') }}" rel="stylesheet"> 
</head>
<body>

    <header class="exam-header">
        <div class="exam-info">
            <h1>{{ $prova->titulo }}</h1>
            <p>Questões: {{ $prova->questoes->count() }} • Duração: {{ $prova->duracao_minutos }} min</p>
        </div>
        
        <div id="timer" class="timer-box">
            <i class='bx bx-time-five'></i> <span id="timer-text">--:--</span>
        </div>
    </header>

    <div class="container">
        
        @if (session('error')) 
            <div class="alert alert-danger" style="background:#fee2e2; color:#b91c1c; padding:1rem; border-radius:8px; margin-bottom:2rem;">
                {{ session('error') }}
            </div> 
        @endif

        <form id="prova-form" action="{{ route('aluno.provas.submeter', $tentativa) }}" method="POST">
            @csrf
            
            @foreach($prova->questoes as $index => $questao)
                <div class="card-questao">
                    <div class="questao-header">
                        <h4>Questão {{ $index + 1 }}</h4>
                        <span class="badge-pontos">{{ $questao->pontuacao }} pontos</span>
                    </div>
                    
                    <div class="enunciado">
                        {!! nl2br(e($questao->enunciado)) !!}
                    </div>
                    
                    @if($questao->imagem_apoio)
                        <img src="{{ asset('storage/' . $questao->imagem_apoio) }}" class="img-apoio" alt="Imagem de Apoio">
                    @endif
                    
                    @if($questao->tipo_questao == 'multipla_escolha')
                        <div class="alternativas-list">
                            @foreach($questao->alternativas as $alternativa)
                                <div class="opcao-radio">
                                    <input type="radio" 
                                           name="respostas[{{ $questao->id }}]" 
                                           value="{{ $alternativa->id }}" 
                                           id="alt-{{ $alternativa->id }}"
                                           {{ (isset($respostasSalvas[$questao->id]) && $respostasSalvas[$questao->id] == $alternativa->id) ? 'checked' : '' }}>
                                    <label for="alt-{{ $alternativa->id }}">
                                        {{ $alternativa->texto_alternativa }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @elseif($questao->tipo_questao == 'texto')
                        <textarea name="respostas[{{ $questao->id }}]" 
                                  rows="6" 
                                  class="form-control" 
                                  placeholder="Digite sua resposta aqui...">{{ $respostasTextoSalvas[$questao->id] ?? '' }}</textarea>
                    @endif
                </div>
            @endforeach
            
            <div class="footer-actions">
                <button type="button" class="btn-submit" onclick="confirmarEntrega()">
                    Finalizar e Entregar <i class='bx bx-check-circle'></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Confirmação com estilo Azul
        function confirmarEntrega() {
            Swal.fire({
                title: 'Finalizar Prova?',
                text: "Você revisou todas as suas respostas?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e', // Verde para confirmar (sucesso)
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, entregar!',
                cancelButtonText: 'Revisar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('prova-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const horaLimite = new Date('{{ $horaLimiteISO }}');
            const timerElement = document.getElementById('timer');
            const timerText = document.getElementById('timer-text');
            const formElement = document.getElementById('prova-form');
            
            const duracaoTotal = {{ $prova->duracao_minutos }} * 60;
            const warningThreshold = duracaoTotal * 0.10; // 10% restante

            function updateTimer() {
                const agora = new Date();
                const diff = horaLimite - agora;

                if (diff <= 0) {
                    clearInterval(intervalo);
                    timerText.innerText = "00:00:00";
                    timerElement.classList.add('danger');
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tempo Esgotado!',
                        text: 'Sua prova será enviada automaticamente.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => formElement.submit());
                    return;
                }

                const totalSeg = Math.floor(diff / 1000);
                const h = Math.floor(totalSeg / 3600);
                const m = Math.floor((totalSeg % 3600) / 60);
                const s = totalSeg % 60;

                timerText.innerText = 
                    String(h).padStart(2, '0') + ':' + 
                    String(m).padStart(2, '0') + ':' + 
                    String(s).padStart(2, '0');

                if (totalSeg <= warningThreshold) {
                    timerElement.classList.add('danger');
                }
            }

            const intervalo = setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>

</body>
</html>