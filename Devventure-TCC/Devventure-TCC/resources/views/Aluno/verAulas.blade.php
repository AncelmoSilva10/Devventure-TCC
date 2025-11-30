<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $aula->titulo }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Aluno/verAulaAluno.css') }}" rel="stylesheet">
</head>
<body>

    <header class="page-header-blue">
        <div class="header-container">
            <a href="{{ route('turmas.especifica', $aula->turma) }}" class="back-link">
                <i class='bx bx-arrow-back'></i> Voltar para a Turma
            </a>
            
            <div class="header-info">
                <h1>{{ $aula->titulo }}</h1>
                <p>
                    <span><i class='bx bxs-chalkboard'></i> {{ $aula->turma->nome_turma }}</span>
                    <span style="margin: 0 10px;">|</span>
                    <span><i class='bx bxs-user-badge'></i> Prof. {{ $aula->turma->professor->nome }}</span>
                </p>
            </div>
        </div>
    </header>

    <main class="page-content">
        
        <div class="video-wrapper-card" 
             id="video-wrapper" 
             data-video-id="{{ $videoId }}" 
             data-aula-id="{{ $aula->id }}" 
             data-progress-url="{{ route('aulas.progresso') }}">

            <div class="video-container">
                @if($videoId)
                    <div id="player-iframe-id"></div>
                @else
                    <div class="video-error-state">
                        <i class='bx bxs-error-circle'></i>
                        <p>Link de vídeo inválido ou não encontrado.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="status-video" id="status-video">
            <p><i class='bx bx-info-circle'></i> Assista ao vídeo até o final para liberar o questionário.</p>
        </div>

        <div id="quiz-container" class="quiz-container" style="display: none;">
            
            @if ($aula->formulario && $aula->formulario->perguntas->isNotEmpty())

                <div class="card-formulario">
                    <h3><i class='bx bx-task'></i> Valide sua Aula</h3>
                    <p class="desc">Responda às perguntas abaixo para registrar sua presença.</p>

                    @php
                        $jaRespondeu = \App\Models\Resposta::where('aluno_id', auth('aluno')->id())
                                            ->whereIn('pergunta_id', $aula->formulario->perguntas->pluck('id'))
                                            ->exists();
                    @endphp

                    @if ($jaRespondeu)
                        <div class="alert-success">
                            <i class='bx bxs-check-circle'></i>
                            <span>Você já respondeu a este formulário. Aula validada!</span>
                        </div>
                    @else
                        <form action="{{ route('aluno.formulario.responder', $aula) }}" method="POST">
                            @csrf
                            
                            @foreach ($aula->formulario->perguntas as $pergunta)
                                <div class="form-group-quiz">
                                    <p class="pergunta-texto">{{ $loop->iteration }}. {{ $pergunta->texto_pergunta }}</p>
                                    
                                    <div class="opcoes-container">
                                        @foreach ($pergunta->opcoes as $opcao)
                                            <div class="opcao-radio">
                                                <input 
                                                    type="radio" 
                                                    name="respostas[{{ $pergunta->id }}]" 
                                                    id="opcao-{{ $opcao->id }}" 
                                                    value="{{ $opcao->id }}"
                                                    required>
                                                <label for="opcao-{{ $opcao->id }}">{{ $opcao->texto_opcao }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <button type="submit" class="btn-enviar-respostas">
                                <i class='bx bx-send'></i> Enviar Respostas
                            </button>
                        </form>
                    @endif
                </div>
                
            @endif
        </div>  
    </main>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="{{ asset('js/Aluno/verAulas.js') }}"></script>

</body>
</html>