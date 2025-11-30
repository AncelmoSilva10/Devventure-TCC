<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Turma: {{ $turma->nome_turma }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/detalheTurma.css') }}" rel="stylesheet">
</head>
<body>
    
    <div class="turma-wrapper">
        
        <header class="turma-header">
            <div style="width: 100%; max-width: 1300px; margin: 0 auto;">
                <a href="{{ route('professor.turmas') }}" class="back-link">
                    <i class='bx bx-chevron-left'></i> Voltar para Minhas Turmas
                </a>
                <div class="header-content">
                    <div class="header-info">
                        <h1>{{ $turma->nome_turma }}</h1>
                        <p>Turno: {{ ucfirst($turma->turno) }} | {{ $turma->ano_turma ?? date('Y') }}</p>
                    </div>
                    <div class="header-actions">
                        <button class="btn-header btn-glass" id="btnAbrirModalAula"><i class='bx bx-video-plus'></i> Nova Aula</button>
                        <button class="btn-header btn-glass" id="btnAbrirModalAluno"><i class='bx bx-user-plus'></i> Convidar</button>
                        <button class="btn-header btn-white" id="btnAbrirModalAviso"><i class='bx bx-paper-plane'></i> Enviar Aviso</button>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-body">
            
            <div class="main-content">
                <div class="card" style="height: 100%;">
                    <div class="card-header">
                        <h2><i class='bx bxs-group'></i> Alunos ({{ $alunos->total() }})</h2>
                        <a href="{{ route('professor.turma.ranking', $turma) }}" class="btn-ranking-mini">
                            <i class='bx bxs-bar-chart-alt-2'></i> Ver Ranking
                        </a>
                    </div>
                    
                    <ul class="student-list">
                        @forelse($alunos as $aluno)
                            <a href="{{ route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno]) }}" class="student-item">
                                <div class="student-info">
                                    <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id }}" alt="Avatar" class="avatar">
                                    <span>{{ $aluno->nome }}</span>
                                </div>
                                <div class="student-progress">
                                    <small>{{ $aluno->progresso_percentual ?? 0 }}%</small>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: {{ $aluno->progresso_percentual ?? 0 }}%;"></div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <li class="empty-message">Nenhum aluno matriculado.</li>
                        @endforelse
                    </ul>

                    <div class="pagination">
                        {{ $alunos->appends(request()->except('alunosPage'))->links() }}
                    </div>
                </div>
            </div>

            <div class="sidebar-column">
                
                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-spreadsheet'></i> Exercícios</h2></div>
                    <ul class="content-list">
                        @forelse ($exercicios as $exercicio)
                            <a href="{{ route('professor.exercicios.respostas', $exercicio) }}" class="content-item">
                                <div class="content-item-flex" style="width: 100%;">
                                    <span>{{ Str::limit($exercicio->nome, 20) }}</span>
                                    <small>{{ \Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m') }}</small>
                                </div>
                            </a>
                        @empty
                            <li class="empty-message">Vazio.</li>
                        @endforelse
                    </ul>
                    <div class="pagination">{{ $exercicios->appends(request()->except('exerciciosPage'))->links() }}</div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-file-blank'></i> Provas</h2></div>
                    <ul class="content-list">
                        @forelse ($provas as $prova)
                            <a href="{{ route('Professor.relatorios.provaResultado', ['turma' => $turma->id, 'prova' => $prova->id]) }}" class="content-item">
                                <div class="content-item-flex" style="width: 100%;">
                                    <span>{{ Str::limit($prova->titulo, 20) }}</span>
                                    <small>{{ \Carbon\Carbon::parse($prova->data_fechamento)->format('d/m') }}</small>
                                </div>
                            </a>
                        @empty
                            <li class="empty-message">Vazio.</li>
                        @endforelse
                    </ul>
                    <div class="pagination">{{ $provas->appends(request()->except('provasPage'))->links() }}</div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-bell'></i> Mural de Avisos</h2></div>
                    <ul class="avisos-list">
                        @forelse ($avisos as $aviso)
                            <li class="aviso-item">
                                <div class="aviso-title">{{ $aviso->titulo }}</div>
                                <span class="aviso-date">{{ $aviso->created_at->diffForHumans() }}</span>
                                <div class="aviso-content">{!! nl2br(e(Str::limit($aviso->conteudo, 100))) !!}</div>
                            </li>
                        @empty
                            <li class="empty-message">Nenhum aviso.</li>
                        @endforelse
                    </ul>
                    <div class="pagination">{{ $avisos->appends(request()->except('avisosPage'))->links() }}</div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-time-five'></i> Histórico</h2></div>
                    <ul class="timeline">
                        @forelse ($historico as $item)
                            <li class="timeline-item">
                                <div class="timeline-icon"><i class='bx {{ $item['tipo'] == 'aula' ? 'bx-video' : 'bx-file' }}'></i></div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ \Carbon\Carbon::parse($item['data'])->format('d/m H:i') }}</span>
                                    <h3>{{ Str::limit($item['titulo'], 25) }}</h3>
                                    </div>
                            </li>
                        @empty
                            <li class="empty-message">Vazio.</li>
                        @endforelse
                    </ul>
                    <div class="pagination">{{ $historico->appends(request()->except('historicoPage'))->links() }}</div>
                </aside>

            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalAdicionarAula">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Nova Aula</h2>
            <form action="{{ route('turmas.aulas.formsAula', $turma) }}" method="POST">
                @csrf
                <div class="form-group"><label>Título</label><input type="text" name="titulo" required></div>
                <div class="form-group"><label>Link YouTube</label><input type="url" name="video_url" required></div>
                <div class="form-group"><label>Duração (Min,Seg)</label><input type="text" name="duracao_texto" placeholder="ex: 5,30" required></div>
                <div class="form-group"><label>Pontos</label><input type="number" name="pontos" value="5" required></div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Salvar</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalConvidarAluno">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Convidar Aluno</h2>
            <form action="{{ route('turmas.convidar', $turma) }}" method="POST">
                @csrf
                <div class="form-group"><label>RA do Aluno</label><input type="text" name="ra" required></div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Enviar</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalEnviarAviso">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Novo Aviso</h2>
            <form action="{{ route('professor.avisos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                <div class="form-group"><label>Título</label><input type="text" name="titulo" required></div>
                <div class="form-group"><label>Mensagem</label><textarea name="conteudo" rows="4" required></textarea></div>
                <div class="form-group">
                    <label>Destinatários:</label>
                    <div class="recipient-toggle">
                        <label class="toggle-option active" id="tab-all">
                            <input type="radio" name="alcance" value="todos" checked onchange="toggleRecipient('all')"> Toda a Turma
                        </label>
                        <label class="toggle-option" id="tab-select">
                            <input type="radio" name="alcance" value="selecionados" onchange="toggleRecipient('select')"> Selecionar Alunos
                        </label>
                    </div>
                    <div id="student-list-container" class="student-selection-container">
                        <div class="student-checkbox-list">
                            <div class="checkbox-item" style="background:#f0f0f0;">
                                <input type="checkbox" id="selectAllStudents"> <label for="selectAllStudents"><strong>Todos</strong></label>
                            </div>
                            @foreach($alunos as $aluno)
                                <div class="checkbox-item">
                                    <input type="checkbox" name="alunos[]" value="{{ $aluno->id }}" class="student-checkbox" id="aluno_{{$aluno->id}}">
                                    <label for="aluno_{{$aluno->id}}">{{ $aluno->nome }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Enviar</button></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <script>
        window.flashMessages = {
            sweetSuccessConvite: "{{ session('sweet_success_convite') }}",
            sweetErrorConvite: "{{ session('sweet_error_convite') }}",
            sweetErrorAula: "{{ session('sweet_error_aula') }}"
        };
        @if (session('sweet_success'))
            Swal.fire({ title: 'Sucesso!', text: "{{ session('sweet_success') }}", icon: 'success', confirmButtonColor: '#1a62ff' });
        @endif
    </script>
    <script src="{{ asset('js/Professor/detalheTurmaProfessor.js') }}"></script>
</body>
</html>