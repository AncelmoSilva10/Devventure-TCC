<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Fazendo Prova</title>
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/aluno/alunoGeral.css') }}" rel="stylesheet"> {{-- Ajuste o caminho CSS para aluno --}}
    {{-- Se você usa Bootstrap ou outro framework CSS, adicione aqui --}}
    {{-- Exemplo: <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px 0;
        }
        .navbar-top {
            background-color: #343a40; /* Cor da sua navbar, se for diferente da incluída */
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .timer-display {
            font-size: 1.8em;
            font-weight: bold;
            color: #dc3545; /* Vermelho para o timer */
            background-color: #f8f9fa;
            padding: 5px 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,.1);
            position: fixed; /* Fixa o timer na tela */
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
        }
        .card-questao {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .card-questao h4 {
            margin-top: 0;
            color: #007bff; /* Cor primária */
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .card-questao p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .card-questao label {
            display: block;
            margin-bottom: 8px;
            cursor: pointer;
            padding: 8px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }
        .card-questao label:hover {
            background-color: #f0f0f0;
        }
        .card-questao input[type="radio"] {
            margin-right: 10px;
            vertical-align: middle;
        }
        .card-questao textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            min-height: 100px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #28a745; /* Verde para submeter */
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            box-shadow: 0 2px 5px rgba(0,0,0,.2);
            transition: background-color 0.2s ease;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-warning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="main-content">
        <div class="container">
            <div id="timer" class="timer-display">
                --:--
            </div>

            <h1 class="mb-4">{{ $prova->titulo }}</h1>
            <p class="mb-3">Tempo restante: <span id="timer-text"></span></p>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
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
                                    <label for="alt-{{ $alternativa->id }}-{{ $tentativa->id }}">
                                        <input type="radio" 
                                               name="respostas[{{ $questao->id }}]" 
                                               value="{{ $alternativa->id }}" 
                                               id="alt-{{ $alternativa->id }}-{{ $tentativa->id }}"
                                               {{ (isset($respostasSalvas[$questao->id]) && $respostasSalvas[$questao->id] == $alternativa->id) ? 'checked' : '' }}
                                               >
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
            const timerTextElement = document.getElementById('timer-text'); // Novo elemento para texto completo
            const formElement = document.getElementById('prova-form');

            function updateTimer() {
                const agora = new Date();
                const diffMilissegundos = horaLimite - agora;

                if (diffMilissegundos <= 0) {
                    // Tempo acabou!
                    clearInterval(intervalo);
                    timerElement.innerHTML = "00:00";
                    timerTextElement.innerHTML = "Tempo Esgotado!";
                    Swal.fire({
                        icon: 'error',
                        title: 'Tempo Esgotado!',
                        text: 'Sua prova será submetida automaticamente.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 3000 // Da um tempo para o SweetAlert antes de submeter
                    }).then(() => {
                        formElement.submit(); // Submete o formulário automaticamente
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
                timerTextElement.innerHTML = formattedTime; // Atualiza também o texto completo

                // Alerta o aluno quando faltar pouco tempo (ex: 5 minutos)
                if (totalSegundos === 300) { // 5 minutos = 300 segundos
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Faltam apenas 5 minutos para o término da prova!',
                        timer: 5000,
                        timerProgressBar: true
                    });
                }
            }

            const intervalo = setInterval(updateTimer, 1000);
            updateTimer(); // Chama imediatamente para evitar delay no primeiro segundo
        });
    </script>
    {{-- Se você usa Bootstrap ou outro framework JS, adicione aqui --}}
    {{-- Exemplo: <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>
</html>