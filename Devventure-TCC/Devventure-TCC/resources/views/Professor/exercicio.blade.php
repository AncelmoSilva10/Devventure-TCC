<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Exercícios</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/Professor/exercicioProfessor.css') }}" rel="stylesheet">
</head>
<body>

    @include('layouts.navbar')

    <main>
        <section class="intro">
            <div>
                <a href="/professorDashboard" class="btn-voltar">
                    <i class='bx bx-arrow-back'></i> Voltar ao Painel
                </a>
                <h1>Meus Exercícios</h1>
                <p>Gerencie as atividades curriculares das suas turmas.</p>
            </div>
            <button class="btn-novo" onclick="document.getElementById('modal').style.display='flex'">
                <i class='bx bx-plus-circle'></i> Criar Exercício
            </button>
        </section>

        <section class="stats-bar">
            <div class="stat-card">
                <div class="stat-info">
                    <span>Total Listado</span>
                    <strong>{{ $exercicios->count() }}</strong>
                </div>
                <i class='bx bx-list-ul stat-icon'></i>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span>Status Visualização</span>
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
        <a href="{{ url('/professorExercicios') }}?status=disponiveis" 
           class="toggle-btn {{ request('status') == 'disponiveis' ? 'active' : '' }}">
            Em Aberto
        </a>

        <a href="{{ url('/professorExercicios') }}?status=concluidas" 
           class="toggle-btn {{ request('status') == 'concluidas' ? 'active' : '' }}">
            Concluídas
        </a>

        <a href="{{ url('/professorExercicios') }}?status=todos" 
           class="toggle-btn {{ !request('status') || request('status') == 'todos' ? 'active' : '' }}">
            Todas
        </a>
    </div>

    <form action="{{ url('/professorExercicios') }}" method="GET" class="search-box">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" placeholder="Pesquisar por título ou turma..." value="{{ request('search') }}">
        <button type="submit"><i class='bx bx-search'></i></button>
    </form>
</section>

        <section class="grid-cards">
            @forelse ($exercicios as $exercicio)
                @php
                    // Lógica visual simples no Blade para determinar status
                    $agora = \Carbon\Carbon::now();
                    $fechamento = \Carbon\Carbon::parse($exercicio->data_fechamento);
                    $aberto = $agora->lt($fechamento);
                    $statusClass = $aberto ? 'status-aberto' : 'status-fechado';
                    $statusTexto = $aberto ? 'Aberto' : 'Encerrado';
                    $corTexto = $aberto ? '#10b981' : '#ef4444';
                @endphp

                <div class="card-exercicio {{ $statusClass }}" onclick="window.location='{{ route('professor.exercicios.respostas', $exercicio) }}'">
                    <div class="card-status-bar"></div>

                    <div class="card-header">
                        <span class="turma-tag">{{ $exercicio->turma->nome_turma }} • {{ ucfirst($exercicio->turma->turno) }}</span>
                        <div class="pontos-badge">
                            <i class='bx bxs-star'></i> {{ $exercicio->pontos ?? 0 }} pts
                        </div>
                    </div>

                    <div class="card-body">
                        <i class='bx bx-file-blank card-icon-bg'></i>

                        <h3 class="card-title">{{ $exercicio->nome }}</h3>

                        <div class="dates-grid">
                            <div class="date-item">
                                <span class="date-label">Publicação</span>
                                <span class="date-value">
                                    <i class='bx bx-calendar-check'></i> 
                                    {{ \Carbon\Carbon::parse($exercicio->data_publicacao)->format('d/m H:i') }}
                                </span>
                            </div>
                            <div class="date-item">
                                <span class="date-label">Entrega</span>
                                <span class="date-value" style="color: {{ $corTexto }}">
                                    <i class='bx bx-time-five'></i> 
                                    {{ $fechamento->format('d/m H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="anexos-count">
                            @if($exercicio->imagensApoio->count() > 0 || $exercicio->arquivosApoio->count() > 0)
                                <i class='bx bx-paperclip'></i> 
                                {{ $exercicio->imagensApoio->count() + $exercicio->arquivosApoio->count() }} Anexos
                            @else
                                <span style="opacity: 0.5;">Sem anexos</span>
                            @endif
                        </div>
                        <span class="btn-detalhes">
                            Gerenciar <i class='bx bx-chevron-right'></i>
                        </span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: white; border-radius: 16px; border: 2px dashed #cbd5e1;">
                    <i class='bx bx-ghost' style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <h3 style="color: #64748b; margin-bottom: 0.5rem;">Nenhum exercício encontrado</h3>
                    <p style="color: #94a3b8;">Tente mudar os filtros ou crie uma nova atividade.</p>
                </div>
            @endforelse
        </section>
    </main>

    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            @if ($errors->any())
                <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <strong>Erro ao criar:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('professor.exercicios.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h2>Novo Exercício</h2>

                <div class="form-group">
                    <label>Título da Atividade</label>
                    <input name="nome" type="text" class="form-control" placeholder="Ex: Lista de Exercícios 01 - Lógica" required />
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Turma</label>
                        <select name="turma_id" class="form-control" required>
                            <option value="" disabled selected>Selecione a turma...</option>
                            @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pontos</label>
                        <input name="pontos" type="number" class="form-control" value="10" min="0" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" placeholder="Instruções para o aluno..."></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Data de Abertura</label>
                        <input name="data_publicacao" type="datetime-local" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Data de Entrega</label>
                        <input name="data_fechamento" type="datetime-local" class="form-control" required />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <label class="upload-box" for="arquivos_apoio">
                        <i class='bx bx-file'></i>
                        <p id="txt-arquivos">Adicionar Arquivos</p>
                        <input type="file" name="arquivos_apoio[]" id="arquivos_apoio" multiple style="display:none" onchange="updateLabel(this, 'txt-arquivos', 'arquivos')">
                    </label>

                    <label class="upload-box" for="imagens_apoio">
                        <i class='bx bx-image'></i>
                        <p id="txt-imagens">Adicionar Imagens</p>
                        <input type="file" name="imagens_apoio[]" id="imagens_apoio" accept="image/*" multiple style="display:none" onchange="updateLabel(this, 'txt-imagens', 'imagens')">
                    </label>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modal').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn-confirm">Criar Atividade</button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    <script src="{{ asset('js/Professor/exercicioProfessor.js') }}"></script>
    <script>
        function updateLabel(input, labelId, type) {
            const label = document.getElementById(labelId);
            if(input.files.length > 0) {
                label.innerText = input.files.length + (type === 'imagens' ? " imagem(ns)" : " arquivo(s)");
                label.style.fontWeight = "bold";
                label.style.color = "#00796B";
            } else {
                label.innerText = type === 'imagens' ? "Adicionar Imagens" : "Adicionar Arquivos";
            }
        }

        @if ($errors->any())
            document.getElementById('modal').style.display = 'flex';
        @endif

        @if (session('sweet_success'))
            Swal.fire({
                title: "Sucesso!",
                text: "{{ session('sweet_success') }}",
                icon: "success",
                confirmButtonColor: "#00796B"
            });
        @endif
    </script>
</body>
</html>