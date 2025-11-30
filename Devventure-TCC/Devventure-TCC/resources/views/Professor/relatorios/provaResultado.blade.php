<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Resultados: {{ $prova->titulo }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/respostasProva.css') }}" rel="stylesheet">
</head>
<body>

    <div class="main-wrapper">
        
        <header class="page-header">
            <div class="header-container">
                <div class="header-left">
                    <a href="{{ route('turmas.especificaID', $turma) }}" class="back-link">
                        <i class='bx bx-arrow-back'></i> Voltar para Turma
                    </a>
                    
                    <div class="header-info">
                        <h1>{{ $prova->titulo }}</h1>
                        <p>Resultados da Turma: {{ $turma->nome_turma }}</p>
                    </div>
                </div>

                <div class="header-actions">
                    <form id="delete-prova-form" action="{{ route('professor.provas.destroy', $prova) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-header">
                            <i class='bx bxs-trash'></i> Excluir Prova
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content-body">
            
            <div class="card-table">
                
                <div class="card-header-internal">
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    <h2>Tentativas dos Alunos</h2>
                </div>

                @if($prova->tentativas->isEmpty())
                    <div class="empty-state">
                        <i class='bx bx-info-circle'></i>
                        <p>Nenhum aluno realizou esta prova ainda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Início</th>
                                    <th>Término</th>
                                    <th>Duração</th>
                                    <th>Pontuação</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prova->tentativas as $tentativa)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;">{{ $tentativa->aluno->nome }}</div>
                                            <small style="color:#64748b;">RA: {{ $tentativa->aluno->ra ?? '-' }}</small>
                                        </td>
                                        <td>{{ $tentativa->hora_inicio->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($tentativa->hora_fim)
                                                {{ $tentativa->hora_fim->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-warning">--</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tentativa->hora_inicio && $tentativa->hora_fim)
                                                {{ $tentativa->hora_inicio->diff($tentativa->hora_fim)->format('%Hh %Im') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><strong>{{ $tentativa->pontuacao_final ?? '0' }}</strong></td>
                                        <td>
                                            @if($tentativa->hora_fim)
                                                <span class="badge bg-success">Finalizada</span>
                                            @else
                                                <span class="badge bg-primary">Em Andamento</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $tentativa->aluno]) }}" class="btn-action">
                                                <i class='bx bx-show'></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </main>
    </div>

    @include('layouts.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.getElementById('delete-prova-form');
            
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    Swal.fire({
                        title: 'Excluir Prova?',
                        text: "Isso apagará todas as notas e tentativas dos alunos. Não há como desfazer!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d32f2f', // Vermelho
                        cancelButtonColor: '#64748b',  // Cinza
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>