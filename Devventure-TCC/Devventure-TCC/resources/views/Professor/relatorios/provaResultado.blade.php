<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Resultados da Prova - {{ $prova->titulo }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/respostasProva.css') }}" rel="stylesheet">
</head>
<body>

    <div class="container">
        
        <div class="card">
            <div class="card-header">
                <h2><i class='bx bxs-bar-chart-alt-2'></i> Resultados da Prova</h2>
                
                {{-- !! BOTÃO DE EXCLUIR (DENTRO DE UM FORMULÁRIO) !! --}}
                <form id="delete-prova-form" action="{{ route('professor.provas.destroy', $prova) }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-custom btn-sm">
                        <i class='bx bxs-trash'></i> Excluir Prova
                    </button>
                </form>

                <a href="{{ route('turmas.especificaID', $turma) }}" class="btn btn-secondary-custom btn-sm">
                    <i class='bx bx-arrow-back'></i> Voltar para Turma
                </a>
            </div>
            
            <div style="padding: 0 10px 10px 10px;">
                <h3 style="font-size: 1.2rem; font-weight: 500; margin: 0;">Prova: <strong>{{ $prova->titulo }}</strong></h3>
                <p style="margin: 0; color: #555;">Turma: <strong>{{ $turma->nome_turma }}</strong></p>
            </div>

            @if($prova->tentativas->isEmpty())
                <div class="alert alert-info" style="margin-top: 20px;">
                    <i class='bx bx-info-circle'></i> Nenhum aluno realizou esta prova ainda.
                </div>
            @else
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Hora de Início</th>
                                <th>Hora de Término</th>
                                <th>Duração</th>
                                <th>Pontuação</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prova->tentativas as $tentativa)
                                <tr>
                                    <td>{{ $tentativa->aluno->nome }}</td>
                                    <td>{{ $tentativa->hora_inicio->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($tentativa->hora_fim)
                                            {{ $tentativa->hora_fim->format('d/m/Y H:i') }}
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
                                    <td><strong>{{ $tentativa->pontuacao_final ?? 'N/A' }}</strong></td>
                                    <td>
                                        @if($tentativa->hora_fim)
                                            <span class="badge bg-success">Finalizada</span>
                                        @else
                                            <span class="badge bg-primary">Iniciada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $tentativa->aluno]) }}" class="btn btn-info-custom btn-sm">
                                            <i class='bx bxs-user-detail'></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.getElementById('delete-prova-form');
            
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Esta ação não pode ser revertida! Todas as tentativas e respostas dos alunos para esta prova serão excluídas permanentemente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545', 
                        cancelButtonColor: '#6c757d', 
                        confirmButtonText: 'Sim, excluir!',
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