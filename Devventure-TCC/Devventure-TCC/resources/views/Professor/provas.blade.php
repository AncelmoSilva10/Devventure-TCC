<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/criarProva.css') }}" rel="stylesheet">
</head>
<body>

    <div class="main-wrapper">
        
        <header class="page-header">
            <div class="header-container">
                <div class="header-left">
                    <a href="/professorDashboard" class="back-link">
                        <i class='bx bx-arrow-back'></i> Voltar ao Painel
                    </a>
                    
                    <div class="header-info">
                        <h1>Provas</h1>
                        <p>Crie e gerencie provas com total facilidade.</p>
                    </div>
                </div>

                <div class="header-actions">
                    <button class="btn-novo-header" id="btnAbrirModalProva">
                        <i class='bx bx-plus-circle'></i> Criar Prova
                    </button>
                </div>
            </div>
        </header>

        <main class="content-body">
            
            <section class="stats-bar">
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Total Listado</span>
                        <strong>{{ $provas->count() }}</strong>
                    </div>
                    <i class='bx bx-list-ul stat-icon'></i>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Filtro Atual</span>
                        <strong>{{ ucfirst($status ?? 'Geral') }}</strong>
                    </div>
                    <i class='bx bx-filter-alt stat-icon'></i>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Data de Hoje</span>
                        <strong style="font-size: 1.2rem;">{{ date('d/m/Y') }}</strong>
                    </div>
                    <i class='bx bx-calendar stat-icon'></i>
                </div>
            </section>

            <section class="toolbar">
                <div class="toggle-group">
                    <a href="{{ url('/professorProvas') }}?status=disponiveis" 
                       class="toggle-btn {{ request('status') == 'disponiveis' ? 'active' : '' }}">
                        Em Aberto
                    </a>

                    <a href="{{ url('/professorProvas') }}?status=concluidas" 
                       class="toggle-btn {{ request('status') == 'concluidas' ? 'active' : '' }}">
                        Concluídas
                    </a>

                    <a href="{{ url('/professorProvas') }}?status=todos" 
                       class="toggle-btn {{ !request('status') || request('status') == 'todos' ? 'active' : '' }}">
                        Todas
                    </a>
                </div>

                <form action="{{ url('/professorProvas') }}" method="GET" class="search-box">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" placeholder="Pesquisar por título ou turma..." value="{{ request('search') }}">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </section>

            <section class="grid-cards">
                @forelse ($provas as $prova)
                    @php
                        $agora = \Carbon\Carbon::now();
                        $fechamento = \Carbon\Carbon::parse($prova->data_fechamento);
                        $aberto = $agora->lt($fechamento);
                        $statusClass = $aberto ? 'status-aberto' : 'status-fechado';
                        $corTexto = $aberto ? '#10b981' : '#ef4444';
                    @endphp

                    <div class="card-exercicio {{ $statusClass }}" 
                         onclick="window.location='{{ route('Professor.relatorios.provaResultado', ['turma' => $prova->turma->id, 'prova' => $prova->id]) }}'">

                        <div class="card-status-bar"></div>

                        <div class="card-header">
                            <span class="turma-tag">
                                {{ $prova->turma->nome_turma }} • {{ ucfirst($prova->turma->turno) }}
                            </span>
                            <div class="pontos-badge">
                                <i class='bx bxs-list-check'></i> {{ $prova->questoes->count() ?? 0 }} questões
                            </div>
                        </div>

                        <div class="card-body">
                            <h3 class="card-title">{{ $prova->titulo }}</h3>

                            <div class="dates-grid">
                                <div class="date-item">
                                    <span class="date-label">Publicação</span>
                                    <span class="date-value">
                                        <i class='bx bx-calendar-check'></i> 
                                        {{ \Carbon\Carbon::parse($prova->data_abertura)->format('d/m H:i') }}
                                    </span>
                                </div>
                                <div class="date-item">
                                    <span class="date-label">Fechamento</span>
                                    <span class="date-value" style="color: {{ $corTexto }}">
                                        <i class='bx bx-time-five'></i> 
                                        {{ $fechamento->format('d/m H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="anexos-count">
                                <span>
                                    @php
                                        $comImagem = $prova->questoes->filter(function($q) { return $q->imagem_apoio; })->count();
                                    @endphp
                                    {{ $comImagem > 0 ? "$comImagem imagem(ns)" : 'Sem anexos' }}
                                </span>
                            </div>
                            <span class="btn-detalhes">
                                Detalhes <i class='bx bx-chevron-right'></i>
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: white; border-radius: 16px; border: 2px dashed #cbd5e1;">
                        <i class='bx bx-ghost' style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3 style="color: #64748b; margin-bottom: 0.5rem;">Nenhuma prova encontrada</h3>
                        <p style="color: #94a3b8;">Tente mudar os filtros ou crie uma nova prova.</p>
                    </div>
                @endforelse
            </section>
        </main>
    </div>

    <div id="modalCriarProva" class="modal-prova" style="display: none;">
        <div class="modal-content-prova">
            <span class="close-modal" style="cursor:pointer;">&times;</span>

            <form action="{{ route('professor.provas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="container p-0">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                                    <h4 class="text-success fw-bold"><i class='bx bxs-notepad'></i> Nova Prova</h4>
                                </div>
                                <div class="card-body px-4 pb-4">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Turma</label>
                                        <select name="turma_id" class="form-select" required>
                                            <option value="">Selecione...</option>
                                            @foreach ($turmas as $turma)
                                                <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Título</label>
                                        <input type="text" name="titulo" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Instruções</label>
                                        <textarea name="instrucoes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                                    <h5 class="fw-bold"><i class='bx bxs-help-circle'></i> Questões</h5>
                                </div>
                                <div class="card-body px-4 pb-4">
                                    <div id="questoes-container"></div>
                                    <button type="button" id="add-questao-btn" class="btn btn-outline-secondary w-100 py-2 border-dashed">
                                        <i class='bx bx-plus-circle'></i> Adicionar Questão
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3"><i class='bx bxs-cog'></i> Configurações</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Abertura</label>
                                        <input type="datetime-local" name="data_abertura" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Fechamento</label>
                                        <input type="datetime-local" name="data_fechamento" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Duração (min)</label>
                                        <input type="number" name="duracao_minutos" class="form-control" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="fw-bold"><i class='bx bxs-save'></i> Publicar</h5>
                                    <p class="small opacity-75 mb-3">Revise antes de salvar.</p>
                                    <button type="submit" class="btn btn-light w-100 fw-bold text-success">Salvar Prova</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questaoIndex = 0;
            const container = document.getElementById('questoes-container');
            const addBtn = document.getElementById('add-questao-btn');

            window.removerQuestao = btn => btn.closest('.questao-item').remove();

            window.toggleAlternativas = function(select) {
                const body = select.closest('.card-body');
                const alts = body.querySelector('.alternativas-container');
                alts.style.display = select.value === 'multipla_escolha' ? 'block' : 'none';
                
                const inputs = alts.querySelectorAll('input[type="text"]');
                inputs.forEach(i => i.required = select.value === 'multipla_escolha');
            };

            function addQuestao() {
                const i = questaoIndex++;
                const div = document.createElement('div');
                div.className = 'card mb-3 border questao-item';
                
                let altsHtml = '';
                ['A','B','C','D','E'].forEach((L, j) => {
                    altsHtml += `
                        <div class="input-group mb-2">
                            <div class="input-group-text bg-light"><input type="radio" name="questoes[${i}][alternativa_correta]" value="${j}" class="form-check-input mt-0"> <strong class="ms-2">${L}</strong></div>
                            <input type="text" class="form-control" name="questoes[${i}][alternativas][${j}][texto]" placeholder="Opção ${L}">
                        </div>`;
                });

                div.innerHTML = `
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                        <small class="fw-bold text-secondary">Questão #${i+1}</small>
                        <button type="button" class="btn-close" onclick="removerQuestao(this)"></button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2 mb-2">
                            <div class="col-8">
                                <select name="questoes[${i}][tipo_questao]" class="form-select form-select-sm" onchange="toggleAlternativas(this)">
                                    <option value="texto">Dissertativa</option>
                                    <option value="multipla_escolha">Múltipla Escolha</option>
                                </select>
                            </div>
                            <div class="col-4"><input type="number" name="questoes[${i}][pontuacao]" class="form-control form-select-sm" placeholder="Pts" value="1" step="0.1" required></div>
                        </div>
                        <textarea name="questoes[${i}][enunciado]" class="form-control mb-2" rows="2" placeholder="Pergunta..." required></textarea>
                        <input type="file" name="questoes[${i}][imagem]" class="form-control form-control-sm mb-2" accept="image/*">
                        <div class="alternativas-container p-2 bg-light rounded" style="display:none;">${altsHtml}</div>
                    </div>`;
                container.appendChild(div);
            }

            if(addBtn) addBtn.onclick = addQuestao;
            addQuestao(); // Adiciona a primeira

            // Modal Logic
            const modal = document.getElementById("modalCriarProva");
            const btnAbrir = document.getElementById("btnAbrirModalProva");
            const closeBtn = document.querySelector(".close-modal");

            if(modal && btnAbrir) {
                btnAbrir.onclick = () => modal.style.display = "flex";
                closeBtn.onclick = () => modal.style.display = "none";
                window.onclick = (e) => { if(e.target == modal) modal.style.display = "none"; }
            }
        });

        @if (session('sweet_success'))
            Swal.fire({ title: "Sucesso!", text: "{{ session('sweet_success') }}", icon: "success", confirmButtonColor: "#00796B" });
        @endif
    </script>
</body>
</html>