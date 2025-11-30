<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Turmas</title>
    
    <link href="{{ asset('css/Professor/turmaProfessor.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

  <div class="turma-wrapper">
      
      <header class="turma-header">
          
          <div class="header-content">
              
              <div class="header-left">
                  <a href="/professorDashboard" class="back-link">
                      <i class="fas fa-arrow-left"></i> Voltar para Dashboard
                  </a>

                  <div class="header-info">
                        @if(request('contexto') == 'relatorios')
                            <h1>Selecionar Turma</h1>
                            <p>Selecione uma turma para ver o <strong>rendimento</strong>.</p>
                        @else
                            <h1>Gerenciar Turmas</h1>
                            <p>Selecione uma turma para <strong>gerenciar</strong> o dia a dia.</p>
                        @endif
                  </div>
              </div>

              <div class="header-actions">
                  
                  <form action="{{ url('/professorGerenciarEspecifica') }}" method="GET" class="search-form">
                      @if(request('contexto'))
                          <input type="hidden" name="contexto" value="{{ request('contexto') }}">
                      @endif
                      <input name="search" type="text" placeholder="Pesquisar..." value="{{ request('search') }}">
                      <button type="submit"><i class='bx bx-search'></i></button>
                  </form>

                  <button class="btn-header-white" id="btnAdicionarHeader">
                      <i class='bx bx-plus'></i> Nova Turma
                  </button>
              </div>

          </div>
      </header>

      <div class="main-content">
          <div class="card-container">
              
              <div class="class-list">
                  
                  @forelse ($turmas as $turma)
                      @php
                          if(request('contexto') == 'relatorios') {
                              $rotaDestino = route('professor.relatorios.index', $turma->id);
                              $textoBotao = "Ver Relatório";
                              $iconeBotao = "bx-line-chart";
                          } else {
                              $rotaDestino = route('turmas.especificaID', $turma->id);
                              $textoBotao = "Gerenciar";
                              $iconeBotao = "bx-chevron-right";
                          }
                      @endphp

                      <a href="{{ $rotaDestino }}" class="class-item {{ $turma->status_class ?? '' }}">
                          
                          <div class="class-info-group">
                              <div class="class-details">
                                  <h3>{{ $turma->nome_turma }}</h3>
                                  <span>{{ $turma->ano_turma ?? 'Ano Atual' }} - {{ ucfirst($turma->turno) }}</span>
                              </div>
                          </div>

                          <div class="class-stats-group">
                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-users"></i> Alunos</div>
                                  <div class="stat-value">{{ $turma->alunos_count ?? 0 }} Matriculados</div>
                              </div>
                              
                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-book"></i> Atividades</div>
                                  <div class="stat-value">{{ $turma->exercicios_count ?? 0 }} Criadas</div>
                              </div>

                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-chart-line"></i> Desempenho</div>
                                  <div class="stat-value">Média {{ $turma->media_formatada ?? 'N/A' }}</div>
                              </div>
                          </div>

                          <div class="btn-enter">
                              {{ $textoBotao }} <i class='bx {{ $iconeBotao }}'></i>
                          </div>
                      </a>

                  @empty
                      <div class="empty-state">
                          <i class='bx bx-folder-open' style="font-size: 3rem; margin-bottom: 10px;"></i>
                          <p>Nenhuma turma encontrada.</p>
                          <button class="empty-btn" id="btnAdicionarEmpty">Criar turma</button>
                      </div>
                  @endforelse

              </div>
          </div>
      </div>
  </div>


  <div class="modal-overlay" id="modal">
    <div class="modal-content">
      <form action="{{ url('/cadastrar-turma') }}" method="POST">
        @csrf
         <h2>Criar Turma</h2>
  
        <label for="nome_turma">Nome da turma</label>
        <input type="text" id="nome_turma" name="nome_turma" placeholder="Ex: 3º DS" required />
  
        <label for="turno">Turno</label>
        <select id="turno" name="turno" required>
          <option value="" disabled selected>Selecione...</option>
          <option value="manha">Manhã</option>
          <option value="tarde">Tarde</option>
          <option value="noite">Noite</option>
        </select>
  
        <label for="disciplina">Ano da Turma</label>
        <input type="text" id="disciplina" name="ano_turma" placeholder="Ex: 2025" required />
  
        <label for="data_inicio">Data de início</label>
        <input type="date" id="data_inicio" name="data_inicio" required />
  
        <label for="data_fim">Data de término</label>
        <input type="date" id="data_fim" name="data_fim" required />
  
        <div class="modal-buttons">
          <button type="button" id="cancelar">Cancelar</button>
          <button type="submit" class="criar">Criar turma</button>
        </div>
      </form> 
    </div>
  </div>

  @include('layouts.footer')

  <script src="{{ asset('js/Professor/turmaProfessor.js') }}"></script>

  @if (session('sweet_success'))
    <script>
        Swal.fire({
            title: "Sucesso!",
            text: "{{ session('sweet_success') }}",
            icon: "success",
            confirmButtonText: "Ok"
        });
    </script>
  @endif

</body>
</html>