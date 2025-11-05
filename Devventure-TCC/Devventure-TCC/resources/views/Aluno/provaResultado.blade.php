<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Resultado da Prova</title>
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/aluno/alunoGeral.css') }}" rel="stylesheet"> {{-- Ajuste o caminho CSS para aluno --}}
    {{-- Se você usa Bootstrap ou outro framework CSS, adicione aqui --}}
    {{-- Exemplo: <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .card-resultado {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
        .card-resultado h2 {
            color: #007bff; /* Cor primária */
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        .card-resultado h3 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card-resultado p {
            margin-bottom: 10px;
            font-size: 1.1em;
            line-height: 1.6;
        }
        .card-resultado p strong {
            color: #555;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
        .alert-warning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            color: white;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-primary { background-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; }
        .text-success { color: #28a745; font-weight: bold; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-info { color: #17a2b8; font-weight: bold; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <div class="card-resultado">
            <h2>Resultado da Prova: {{ $tentativa->prova->titulo }}</h2>
            
            <p><strong>Aluno:</strong> {{ $tentativa->aluno->nome }}</p>
            <p><strong>Turma:</strong> {{ $tentativa->prova->turma->nome_turma }}</p>
            <p><strong>Duração Máxima:</strong> {{ $tentativa->prova->duracao_minutos }} minutos</p>
            <p><strong>Tempo Realizado:</strong> {{ $tempoDecorrido ?? 'N/A' }}</p>
            
            <hr>

            <h3>Seu Desempenho</h3>
            <p><strong>Pontuação Final:</strong> <span class="text-primary">{{ $tentativa->pontuacao_final ?? 'Aguardando correção' }}</span></p>
            <p><strong>Total de Questões:</strong> {{ $totalQuestoes }}</p>
            <p><strong>Acertos:</strong> <span class="text-success">{{ $acertos }}</span></p>
            <p><strong>Erros:</strong> <span class="text-danger">{{ $erros }}</span></p>
            @if($pendentes > 0)
                <p><strong>Questões de Texto:</strong> <span class="text-info">{{ $pendentes }}</span> (Aguardando correção do professor)</p>
            @endif

            <hr>

            {{-- Opcional: Mostrar detalhes de cada questão (certo/errado) --}}
            <h3 class="mt-4">Detalhes das Respostas</h3>
            @foreach($tentativa->prova->questoes as $index => $questao)
                <div class="card-questao-detalhe mb-3 p-3 border rounded">
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
                                    <span class="text-success">(Correta)</span>
                                @else
                                    <span class="text-danger">(Incorreta)</span>
                                @endif
                            @else
                                <span class="text-warning">Não Respondida</span>
                            @endif
                        </p>
                        <p><strong>Resposta Correta:</strong> {{ $altCorreta->texto_alternativa ?? 'N/A' }}</p>
                    @elseif($questao->tipo_questao == 'texto')
                        <p><strong>Sua Resposta:</strong></p>
                        <div class="border p-2 bg-light rounded">{{ nl2br(e($respostaAluno->resposta_texto ?? '')) }}</div>
                        <p class="mt-2 text-info">Aguardando correção manual do professor.</p>
                    @endif
                </div>
            @endforeach

            <div class="text-center">
                <a href="{{ url('/alunoDashboard') }}" class="btn btn-primary">Voltar para o Dashboard</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>