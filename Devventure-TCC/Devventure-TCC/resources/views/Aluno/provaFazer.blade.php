<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Fazendo Prova</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/aluno/realizandoProva.css') }}" rel="stylesheet"> 

</head>
<body>

    <div class="main-content">
        <div class="container">
            <div id="timer" class="timer-display">
                --:--
            </div>

            <h1>{{ $prova->titulo }}</h1>
            <p class="mb-3">Tempo restante: <span id="timer-text">--:--</span></p>

            @if (session('error'))
                <div class="alert alert-danger"><i class='bx bx-error-alt'></i> {{ session('error') }}</div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning"><i class='bx bx-error'></i> {{ session('warning') }}</div>
            @endif

            <form id="prova-form" action="{{ route('aluno.provas.submeter', $tentativa) }}" method="POST">
                @csrf
                
                @foreach($prova->questoes as $index => $questao)
                    <div class="card-questao">
                        <h4>Questão {{ $index + 1 }}: ({{ $questao->pontuacao }} pts)</h4>
                        <p>{!! nl2br(e($questao->enunciado)) !!}</p>
                        
                        @if($questao->tipo_questao == 'multipla_escolha')
                            
                            @foreach($questao->alternativas as $alternativa)
                                <div>
                                    <input type="radio" 
                                           name="respostas[{{ $questao->id }}]" 
                                           value="{{ $alternativa->id }}" 
                                           id="alt-{{ $alternativa->id }}-{{ $tentativa->id }}"
                                           {{ (isset($respostasSalvas[$questao->id]) && $respostasSalvas[$questao->id] == $alternativa->id) ? 'checked' : '' }}
                                           >

                                    <label for="alt-{{ $alternativa->id }}-{{ $tentativa->id }}">
                                        {{ $alternativa->texto_alternativa }}
                                    </label>
                                </div>
                            @endforeach

                        @elseif($questao->tipo_questao == 'texto')
                            
                            <textarea name="respostas[{{ $questao->id }}]" 
                                      rows="5" 
                                      placeholder="Digite sua resposta aqui..."
                                      >{{ $respostasTextoSalvas[$questao->id] ?? '' }}</textarea>
                        
                        @endif
                    </div>
                @endforeach
                
                <button type="submit" class="btn-submit">Entregar Prova</button>
            </form>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const horaLimite = new Date('{{ $horaLimiteISO }}');
            const timerElement = document.getElementById('timer');
            const timerTextElement = document.getElementById('timer-text');
            const formElement = document.getElementById('prova-form');

            const duracaoTotalSegundos = {{ $prova->duracao_minutos }} * 60;
            
            const warningThresholdSegundos = duracaoTotalSegundos * 0.10;

            function updateTimer() {
                const agora = new Date();
                const diffMilissegundos = horaLimite - agora;

                if (diffMilissegundos <= 0) {
                    clearInterval(intervalo);
                    timerElement.innerHTML = "00:00:00";
                    timerTextElement.innerHTML = "Tempo Esgotado!";
                    timerElement.classList.add('timer-danger'); // Fica vermelho ao esgotar
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Tempo Esgotado!',
                        text: 'Sua prova será submetida automaticamente.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        formElement.submit();
                    });
                    return;
                }

                const totalSegundos = Math.floor(diffMilissegundos / 1000);
                const horas = Math.floor(totalSegundos / 3600);
                const minutos = Math.floor((totalSegundos % 3600) / 60);
                const segundos = totalSegundos % 60;

                const formattedTime = 
                    String(horas).padStart(2, '0') + ':' + 
                    String(minutos).padStart(2, '0') + ':' + 
                    String(segundos).padStart(2, '0');
                
                timerElement.innerHTML = formattedTime;
                timerTextElement.innerHTML = formattedTime;

                if (totalSegundos <= warningThresholdSegundos) {
                    timerElement.classList.add('timer-danger');
                }

                // Alerta de 1 minuto
                if (totalSegundos === 300) { 
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Faltam apenas 1 minutos para o término da prova!',
                        timer: 1000,
                        timerProgressBar: true
                    });
                }
            }

            const intervalo = setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>

</body>
</html>