<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Turmas</title>
    
    <link href="<?php echo e(asset('css/Professor/turmaProfessor.css')); ?>" rel="stylesheet">
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
                        <?php if(request('contexto') == 'relatorios'): ?>
                            <h1>Selecionar Turma</h1>
                            <p>Selecione uma turma para ver o <strong>rendimento</strong>.</p>
                        <?php else: ?>
                            <h1>Gerenciar Turmas</h1>
                            <p>Selecione uma turma para <strong>gerenciar</strong> o dia a dia.</p>
                        <?php endif; ?>
                  </div>
              </div>

              <div class="header-actions">
                  
                  <form action="<?php echo e(url('/professorGerenciarEspecifica')); ?>" method="GET" class="search-form">
                      <?php if(request('contexto')): ?>
                          <input type="hidden" name="contexto" value="<?php echo e(request('contexto')); ?>">
                      <?php endif; ?>
                      <input name="search" type="text" placeholder="Pesquisar..." value="<?php echo e(request('search')); ?>">
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
                  
                  <?php $__empty_1 = true; $__currentLoopData = $turmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <?php
                          if(request('contexto') == 'relatorios') {
                              $rotaDestino = route('professor.relatorios.index', $turma->id);
                              $textoBotao = "Ver Relatório";
                              $iconeBotao = "bx-line-chart";
                          } else {
                              $rotaDestino = route('turmas.especificaID', $turma->id);
                              $textoBotao = "Gerenciar";
                              $iconeBotao = "bx-chevron-right";
                          }
                      ?>

                      <a href="<?php echo e($rotaDestino); ?>" class="class-item <?php echo e($turma->status_class ?? ''); ?>">
                          
                          <div class="class-info-group">
                              <div class="class-details">
                                  <h3><?php echo e($turma->nome_turma); ?></h3>
                                  <span><?php echo e($turma->ano_turma ?? 'Ano Atual'); ?> - <?php echo e(ucfirst($turma->turno)); ?></span>
                              </div>
                          </div>

                          <div class="class-stats-group">
                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-users"></i> Alunos</div>
                                  <div class="stat-value"><?php echo e($turma->alunos_count ?? 0); ?> Matriculados</div>
                              </div>
                              
                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-book"></i> Atividades</div>
                                  <div class="stat-value"><?php echo e($turma->exercicios_count ?? 0); ?> Criadas</div>
                              </div>

                              <div class="stat-col">
                                  <div class="stat-label"><i class="fas fa-chart-line"></i> Desempenho</div>
                                  <div class="stat-value">Média <?php echo e($turma->media_formatada ?? 'N/A'); ?></div>
                              </div>
                          </div>

                          <div class="btn-enter">
                              <?php echo e($textoBotao); ?> <i class='bx <?php echo e($iconeBotao); ?>'></i>
                          </div>
                      </a>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <div class="empty-state">
                          <i class='bx bx-folder-open' style="font-size: 3rem; margin-bottom: 10px;"></i>
                          <p>Nenhuma turma encontrada.</p>
                          <button class="empty-btn" id="btnAdicionarEmpty">Criar turma</button>
                      </div>
                  <?php endif; ?>

              </div>
          </div>
      </div>
  </div>


  <div class="modal-overlay" id="modal">
    <div class="modal-content">
      <form action="<?php echo e(url('/cadastrar-turma')); ?>" method="POST">
        <?php echo csrf_field(); ?>
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
        <input type="text" id="disciplina" name="ano_turma" placeholder="Ex: terceiro ano" required />
  
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

  <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <script src="<?php echo e(asset('js/Professor/turmaProfessor.js')); ?>"></script>

  <?php if(session('sweet_success')): ?>
    <script>
        Swal.fire({
            title: "Sucesso!",
            text: "<?php echo e(session('sweet_success')); ?>",
            icon: "success",
            confirmButtonText: "Ok"
        });
    </script>
  <?php endif; ?>

</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Professor/turma.blade.php ENDPATH**/ ?>