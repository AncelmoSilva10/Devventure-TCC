<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Resultados</title>
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/Professor/exercicioProfessor.css') }}" rel="stylesheet">
    {{-- Se você usa Bootstrap ou outro framework CSS, adicione aqui --}}
    {{-- Exemplo: <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        .badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
        .bg-success { background-color: #198754; }
        .bg-primary { background-color: #0d6efd; }
        .text-warning { color: #ffc107; }
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            color: white;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-sm { padding: .25rem .5rem; font-size: .875rem; }
        .btn-outline-secondary {
            background-color: transparent;
            color: #6c757d;
            border: 1px solid #6c757d;
        }
        .btn-info { background-color: #0dcaf0; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="container">
       <a href="{{ route('turmas.especificaID', $turma) }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bx bx-arrow-back"></i> Voltar para o gerenciamento da turma
        </a>
        <h2 class="mb-4">Resultados da Prova: "{{ $prova->titulo }}" da Turma "{{ $turma->nome_turma }}"</h2>

        @if($prova->tentativas->isEmpty())
            <div class="alert alert-info">Nenhum aluno realizou esta prova ainda.</div>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Hora de Início</th>
                        <th>Hora de Término</th>
                        <th>Duração Realizada</th>
                        <th>Pontuação (Auto-corrigida)</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prova->tentativas as $tentativa)
                        <tr>
                            <td>{{ $tentativa->aluno->nome }}</td>
                            <td>{{ $tentativa->hora_inicio->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if($tentativa->hora_fim)
                                    {{ $tentativa->hora_fim->format('d/m/Y H:i:s') }}
                                @else
                                    <span class="text-warning">Em andamento</span>
                                @endif
                            </td>
                            <td>
                                @if($tentativa->hora_inicio && $tentativa->hora_fim)
                                    @php
                                        $diff = $tentativa->hora_inicio->diff($tentativa->hora_fim);
                                        echo $diff->format('%Hh %Im %Ss');
                                    @endphp
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $tentativa->pontuacao_final ?? 'Aguardando correção' }}</td>
                            <td>
                                @if($tentativa->hora_fim)
                                    <span class="badge bg-success">Finalizada</span>
                                @else
                                    <span class="badge bg-primary">Iniciada</span>
                                @endif
                            </td>
                            <td>
                                {{-- A rota abaixo ainda não existe no seu routes/web.php.
                                    Você precisará criá-la e um método no ProvasController para exibir os detalhes da tentativa.
                                    Ex: Route::get('/professor/provas/tentativa/{tentativa}/detalhes', [ProvasController::class, 'detalhesTentativa'])->name('professor.provas.resultados.detalhes');
                                --}}
                                 <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $tentativa->aluno]) }}" class="btn btn-sm btn-info" disabled>
                                    Detalhes
                                 </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>