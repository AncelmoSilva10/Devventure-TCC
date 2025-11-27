<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $turma->nome_turma }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/Aluno/alunoTurmaEspecifica.css') }}" rel="stylesheet">
</head>
<body>
    <div class="turma-wrapper">
        <header class="turma-header">
            <div class="header-overlay"></div>
            <div class="header-content">
                <a href="{{ route('aluno.turma') }}" class="back-link"><i class='bx bx-chevron-left'></i> Voltar</a>
                <div class="header-info">
                    <h1>{{ $turma->nome_turma }}</h1>
                    <p>Professor(a): {{ $turma->professor->nome }}</p>
                </div>
                <div class="header-stats">
                    <div class="stat-item"><i class='bx bxs-group'></i><span>{{ $alunos->total() }} Alunos</span></div>
                    <div class="stat-item"><i class='bx bxs-book-content'></i><span>{{ $exercicios->total() }} Exercícios</span></div>
                    <div class="stat-item"><i class='bx bxs-videos'></i><span>{{ $aulas->total() }} Aulas</span></div>
                    <div class="stat-item"><i class='bx bxs-file-blank'></i><span>{{ $provas->total() }} Provas</span></div>
                </div>
            </div>
        </header>

        <main class="page-body">
            <div class="main-content">
                <div class="tabs-navigation">
    {{-- Botão Exercícios --}}
    <button class="tab-link {{ request('tab', 'exercicios') == 'exercicios' ? 'active' : '' }}" data-tab="exercicios">
        <i class='bx bxs-pencil'></i> Exercícios
    </button>

    {{-- Botão Aulas --}}
    <button class="tab-link {{ request('tab') == 'aulas' ? 'active' : '' }}" data-tab="aulas">
        <i class='bx bxs-videos'></i> Aulas
    </button>

    {{-- Lógica PHP para verificar aviso recente --}}
@php
    $temAvisoNovo = false;
    $ultimoId = 0;
    
    if(isset($avisos) && $avisos->count() > 0) {
        $ultimoAviso = $avisos->first();
        $ultimoId = $ultimoAviso->id; // Guarda o ID do aviso para o JS usar
        
        // Se foi criado há menos de 3 dias
        if($ultimoAviso->created_at > now()->subDays(3)) {
            $temAvisoNovo = true;
        }
    }
@endphp

{{-- Botão com ID e evento onclick --}}
<button class="tab-link {{ request('tab') == 'avisos' ? 'active' : '' }}" 
        data-tab="avisos" 
        id="btn-avisos"
        data-latest-id="{{ $ultimoId }}"
        onclick="marcarComoLido()">
    
    {{-- A bolinha vermelha --}}
    @if($temAvisoNovo)
        <span class="notification-badge" id="badge-aviso"></span>
    @endif
    
    {{-- Ícone (adicionei um ID para remover a cor vermelha via JS também) --}}
    <i class='bx bxs-bell {{ $temAvisoNovo ? 'icon-alert' : '' }}' id="icon-aviso"></i> 
    Mural de Avisos
</button>

    {{-- Botão Provas --}}
    <button class="tab-link {{ request('tab') == 'provas' ? 'active' : '' }}" data-tab="provas">
        <i class='bx bxs-file-blank'></i> Provas
    </button>
</div>

                <div class="tabs-content">
                    <div class="tab-pane {{ request('tab', 'exercicios') == 'exercicios' ? 'active' : '' }}" id="exercicios">
                        <div class="content-grid">
                            @forelse($exercicios as $exercicio)
                                @php
                                    $statusClass = 'status-pending';
                                    $statusText = 'Pendente';
                                    if ($exercicio->respostas->isNotEmpty()) {
                                        $statusClass = 'status-delivered';
                                        $statusText = 'Concluído';
                                    } elseif (now()->isAfter($exercicio->data_fechamento)) {
                                        $statusClass = 'status-late';
                                        $statusText = 'Prazo Encerrado';
                                    }
                                @endphp
                                <a href="{{ route('aluno.exercicios.mostrar', $exercicio->id) }}" class="exercise-card {{ $statusClass }}">
                                    <div class="card-content">
                                        <div class="card-header">
                                            <h3>{{ $exercicio->nome }}</h3>
                                            <span class="status-tag">{{ $statusText }}</span>
                                        </div>
                                        <p class="card-description">{{ Str::limit($exercicio->descricao, 100) }}</p>
                                        <div class="card-footer">
                                            <div class="deadline-info">
                                                <i class='bx bxs-time-five'></i>
                                                <span>Entregar até: {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y') }}</span>
                                            </div>
                                            <i class='bx bx-right-arrow-alt card-arrow'></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhum exercício postado nesta turma ainda.</p>
                                </div>
                                @endforelse
                            </div>
                            <div class="pagination">
                                {{ $exercicios->appends(['tab' => 'exercicios'])->appends(request()->except('exerciciosPage'))->links() }}
                            </div>
                        </div>
                        <div class="tab-pane {{ request('tab') == 'provas' ? 'active' : '' }}" id="provas">
                            <div class="content-grid">
                                @forelse($provas as $provaItem)
                                    @php
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pendente';
                                        $linkRoute = route('aluno.provas.show', $provaItem->id); // Rota padrão para ver detalhes da prova
    
                                        // Verifica se o aluno já tem uma tentativa para esta prova
                                        $tentativaAluno = $provaItem->tentativas->first();
    
                                        if ($tentativaAluno) {
                                            if ($tentativaAluno->hora_fim !== null) {
                                                $statusClass = 'status-delivered'; // Concluída
                                                $statusText = 'Concluída';
                                                $linkRoute = route('aluno.provas.resultado', $tentativaAluno->id);
                                            } else {
                                                $statusClass = 'status-in-progress'; // Em Andamento
                                                $statusText = 'Em Andamento';
                                                $linkRoute = route('aluno.provas.fazer', $tentativaAluno->id);
                                            }
                                        } elseif (now()->isBefore($provaItem->data_abertura)) {
                                            $statusClass = 'status-upcoming'; // Em Breve
                                            $statusText = 'Em Breve';
                                            $linkRoute = '#'; // Não clicável ou leva para info
                                        } elseif (now()->isAfter($provaItem->data_fechamento)) {
                                            $statusClass = 'status-late'; // Prazo Encerrado
                                            $statusText = 'Prazo Encerrado';
                                            $linkRoute = '#'; // Não clicável ou leva para info
                                        }
                                    @endphp
                                    <a href="{{ $linkRoute }}" class="exercise-card {{ $statusClass }}">
                                        <div class="card-content">
                                            <div class="card-header">
                                                <h3>{{ $provaItem->titulo }}</h3>
                                                <span class="status-tag">{{ $statusText }}</span>
                                            </div>
                                            <p class="card-description">{{ Str::limit($provaItem->instrucoes, 100) }}</p>
                                            <div class="card-footer">
                                                <div class="deadline-info">
                                                    <i class='bx bxs-hourglass-bottom'></i>
                                                    <span>Duração: {{ $provaItem->duracao_minutos }} min</span>
                                                </div>
                                                <div class="deadline-info">
                                                    <i class='bx bxs-time-five'></i>
                                                    <span>Fecha em: {{ \Carbon\Carbon::parse($provaItem->data_fechamento)->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <i class='bx bx-right-arrow-alt card-arrow'></i>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="empty-state">
                                        <i class='bx bx-info-circle'></i>
                                        <p>Nenhuma prova postada nesta turma ainda.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="pagination">
                                {{ $provas->appends(['tab' => 'provas'])->appends(request()->except('provasPage'))->links() }}
                            </div>
                        </div>

                    <div class="tab-pane {{ request('tab') == 'aulas' ? 'active' : '' }}" id="aulas">
                        <div class="content-grid">
                            @forelse($aulas as $aula)
                                <a href="{{ route('aulas.view', $aula) }}" class="lesson-card">
                                    <div class="card-content">
                                        <div class="lesson-icon">
                                            <i class='bx bxs-movie-play'></i>
                                        </div>
                                        <div class="lesson-info">
                                            <h3>{{ $aula->titulo }}</h3>
                                            <p>Clique para assistir à aula</p>
                                        </div>
                                        <i class='bx bx-right-arrow-alt card-arrow'></i>
                                    </div>
                                </a>
                            @empty
                                 <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhuma aula disponível.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pagination">
                             {{ $aulas->appends(['tab' => 'aulas'])->appends(request()->except('aulasPage'))->links() }}
                        </div>
                    </div>

                    <div class="tab-pane {{ request('tab') == 'avisos' ? 'active' : '' }}" id="avisos">
                        <div class="avisos-list">
                             @forelse($avisos as $aviso)
                                <div class="card-aviso">
                                    <div class="card-aviso-header">
                                        <h3>{{ $aviso->titulo }}</h3>
                                        <span class="data-aviso">
                                            {{ $aviso->created_at->diffForHumans() }} </span>
                                    </div>
                                    <div class="card-aviso-body">
                                        <p>{!! nl2br(e($aviso->conteudo)) !!}</p>
                                    </div>
                                    <div class="card-aviso-footer">
                                        <span>Enviado por: {{ $aviso->professor->nome }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhum aviso postado nesta turma ainda.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pagination">
                             {{ $avisos->appends(['tab' => 'avisos'])->appends(request()->except('avisosPage'))->links() }}
                        </div>
                    </div>


                </div>
            </div>

            <aside class="sidebar">
                <div class="card ranking-card">
                    <a href="{{ route('aluno.turma.ranking', $turma) }}" class="btn-ranking">
                        <i class='bx bxs-bar-chart-alt-2'></i>
                        <span>Ver Ranking da Turma</span>
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-section">
                        <h2><i class='bx bxs-group'></i> Colegas de Turma</h2>
                        <ul class="classmates-list">
                            @forelse($alunos as $alunoItem) <li>
                                    <img src="{{ $alunoItem->avatar ? asset('storage/' . $alunoItem->avatar) : 'https://i.pravatar.cc/40?u='.$alunoItem->id }}" alt="Avatar" class="avatar">
                                    <span>{{ $alunoItem->nome }}</span>
                                </li>
                            @empty
                                <li class="empty-message">Nenhum outro aluno na turma.</li>
                            @endforelse
                        </ul>
                         <div class="pagination">
                             {{ $alunos->appends(request()->except('colegasPage'))->links() }}
                        </div>
                    </div>
                </div>
            </aside>
        </main>
    </div>
    
   <script>
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                const tab = link.getAttribute('data-tab');

                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(item => item.classList.remove('active'));

                link.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnAvisos = document.getElementById('btn-avisos');
            // Verificação de segurança caso o botão não exista
            if(!btnAvisos) return; 

            const badge = document.getElementById('badge-aviso');
            const icon = document.getElementById('icon-aviso');
            
            // 1. Pega o ID do aviso mais recente vindo do PHP
            const latestId = btnAvisos.getAttribute('data-latest-id');
            
            // 2. Verifica no navegador qual foi o último aviso que o aluno clicou
            const lidoId = localStorage.getItem('ultimo_aviso_lido_turma_{{ $turma->id }}');

            // 3. Se o aluno já viu este aviso específico, esconde o alerta IMEDIATAMENTE
            if (latestId && lidoId == latestId) {
                if(badge) badge.style.display = 'none';
                if(icon) icon.classList.remove('icon-alert');
            }
        });

        // Função chamada quando clica na aba
        function marcarComoLido() {
            const btnAvisos = document.getElementById('btn-avisos');
            if(!btnAvisos) return;

            const badge = document.getElementById('badge-aviso');
            const icon = document.getElementById('icon-aviso');
            const latestId = btnAvisos.getAttribute('data-latest-id');

            // 1. Esconde visualmente na hora
            if(badge) badge.style.display = 'none';
            if(icon) icon.classList.remove('icon-alert');

            // 2. Salva no navegador que este aviso (ID X) já foi visto nesta turma
            if (latestId) {
                localStorage.setItem('ultimo_aviso_lido_turma_{{ $turma->id }}', latestId);
            }
        }
    </script>

    <!-- 2. Script do SweetAlert (SÓ RODA SE TIVER SUCESSO) -->
    @if (session('sweet_success'))
        <script>
            Swal.fire({
                title: 'Parabéns!',
                text: "{{ session('sweet_success') }}",
                icon: 'success',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Ótimo!'
            });
        </script>
    @endif
</body>
</html>