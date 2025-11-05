<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - {{ $prova->titulo }}</title>
    
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
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .card-header {
            background-color: #007bff; /* Cor primária do Bootstrap */
            color: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .card-text {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid rgba(0,0,0,.1);
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
        }
        .btn-success { background-color: #28a745; }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
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

        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="mb-0">{{ $prova->titulo }}</h2>

                @if (session('error'))
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Turma:</strong> {{ $prova->turma->nome }}</p>
                <p class="card-text"><strong>Período para Iniciar:</strong> {{ $prova->data_abertura->format('d/m/Y H:i') }} até {{ $prova->data_fechamento->format('d/m/Y H:i') }}</p>
                <p class="card-text"><strong>Duração:</strong> {{ $prova->duracao_minutos }} minutos a partir do início.</p>
                
                @if($prova->instrucoes)
                    <hr>
                    <h5>Instruções:</h5>
                    <p class="card-text">{!! nl2br(e($prova->instrucoes)) !!}</p>
                @endif

                <hr>
                <p class="card-text">Ao clicar em "Iniciar Prova", seu tempo começará a correr. Certifique-se de que está pronto!</p>
                
                <form action="{{ route('aluno.provas.iniciar', $prova) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">Iniciar Prova</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>