<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link href="<?php echo e(asset('css/Adm/admDashboard.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="<?php echo e(asset('images/logoDevventure.png')); ?>" alt="Admin Logo" class="admin-logo">
                <span class="logo-text">Admin Dashboard</span>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#overview" class="active"><i class="fas fa-chart-line"></i><span>Visão Geral</span></a></li>
                    <li><a href="#alunos"><i class="fas fa-user-graduate"></i><span>Alunos</span></a></li>
                    <li><a href="#professores"><i class="fas fa-chalkboard-teacher"></i><span>Professores</span></a></li>
                    <li><a href="#depoimentos"><i class="fas fa-comment-dots"></i><span>Depoimentos</span></a></li>
                    <li><a href="#charts-section"><i class="fas fa-chart-pie"></i><span>Análises</span></a></li>
                    <li><a href="<?php echo e(route('admin.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i><span>Sair</span>
                    </a></li>
                </ul>
            </nav>
            <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </aside>

        <div class="main-content">
            <header class="navbar">
                <div class="nav-left">
                    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                    <span class="navbar-title">Dashboard Administrativo</span>
                </div>
                <div class="nav-right">
                    <span class="admin-name">Olá, Admin!</span>
                    <div class="admin-avatar">
                        <img src="<?php echo e(asset('images/user.png')); ?>" alt="Admin Avatar">
                    </div>
                </div>
            </header>

            <div class="dashboard-body">
                <section id="overview" class="dashboard-section active">
                    <h2>Visão Geral</h2>
                    <div class="info-cards-grid">
                        <div class="info-card primary">
                            <i class="fas fa-user-graduate icon"></i>
                            <div class="card-content">
                                <span class="card-number" id="studentsCount"><?php echo e($alunosCount ?? '0'); ?></span>
                                <p class="card-text">Alunos Cadastrados</p>
                            </div>
                        </div>
                        <div class="info-card secondary">
                            <i class="fas fa-chalkboard-teacher icon"></i>
                            <div class="card-content">
                                <span class="card-number" id="teachersCount"><?php echo e($professoresCount ?? '0'); ?></span>
                                <p class="card-text">Professores Cadastrados</p>
                            </div>
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3>Alunos vs Professores (Proporção)</h3>
                            <div id="alunosProfessoresChart" style="height: 300px;"></div>
                        </div>
                        <div class="chart-card">
                            <h3>Alunos vs Professores (Contagem)</h3>
                            <div id="overviewBarChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </section>

                
<section id="alunos" class="dashboard-section">
                    <h2>Alunos</h2>
                    <div class="card data-table-card">
                        <header class="card-header">
                            <h4>Lista de Alunos</h4>
                            <div class="card-actions">
                                <form class="search-box" id="searchAlunosForm" 
      data-search-url="<?php echo e(route('admin.alunos.search')); ?>">
                                    <input type="text" id="searchAlunosInput" placeholder="Pesquisar aluno...">
                                    <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
                                </form>
                              <a href="/download-csvAuno"> <button class="btn btn-outline" id="exportStudentsBtn"><i class="fas fa-download"></i> Exportar</button>
                              </a>
                            </div>
                        </header>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>RA</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                               <tbody id="alunosTableBody"
       data-block-url="/admin/alunos"
       data-unblock-url="/admin/alunos"
       data-csrf-token="<?php echo e(csrf_token()); ?>">
                                    <?php $__empty_1 = true; $__currentLoopData = $alunosData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($aluno->nome); ?></td>
                                        <td><?php echo e($aluno->email); ?></td>
                                        <td><?php echo e($aluno->ra); ?></td>
                                        <td>
                                            <?php if($aluno->status === 'ativo'): ?>
                                                <span class="badge badge-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Bloqueado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-icon" title="Ver Detalhes do Aluno"><i class="fas fa-eye"></i></a>
                                            <?php if($aluno->status === 'ativo'): ?>
                                                <form action="<?php echo e(route('admin.alunos.block', $aluno->id)); ?>" method="POST" style="display: inline;" 
                                                      class="form-confirm" data-action-text="bloquear" data-user-name="<?php echo e($aluno->nome); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn-icon" title="Bloquear Aluno"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('admin.alunos.unblock', $aluno->id)); ?>" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="desbloquear" data-user-name="<?php echo e($aluno->nome); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn-icon" title="Desbloquear Aluno"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="5" class="text-center">Nenhum aluno cadastrado.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination" id="alunosPagination">
                           </div>
                    </div>
                </section>

                <section id="professores" class="dashboard-section">
                    <h2>Professores</h2>
                    <div class="card data-table-card">
                        <header class="card-header">
                            <h4>Lista de Professores</h4>
                            <div class="card-actions">
                                <form class="search-box" id="searchProfessoresForm" 
      data-search-url="<?php echo e(route('admin.professores.search')); ?>">
                                    <input type="text" id="searchProfessoresInput" placeholder="Pesquisar professor...">
                                    <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
                                </form>
                               <a href="/download-csvProf"> <button class="btn btn-outline" id="exportTeachersBtn"><i class="fas fa-download"></i> Exportar</button></a>
                            </div>
                        </header>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="professoresTableBody"
       data-block-url="/admin/professores"
       data-unblock-url="/admin/professores"
       data-csrf-token="<?php echo e(csrf_token()); ?>">
                                    <?php $__empty_1 = true; $__currentLoopData = $professoresData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($professor->nome); ?></td>
                                        <td><?php echo e($professor->email); ?></td>
                                        <td><?php echo e($professor->cpf); ?></td>
                                        <td>
                                            <?php if($professor->status === 'ativo'): ?>
                                                <span class="badge badge-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Bloqueado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-icon" title="Ver Detalhes do Professor"><i class="fas fa-eye"></i></a>
                                            <?php if($professor->status === 'ativo'): ?>
                                                <form action="<?php echo e(route('admin.professores.block', $professor->id)); ?>" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="bloquear" data-user-name="<?php echo e($professor->nome); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn-icon" title="Bloquear Professor"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('admin.professores.unblock', $professor->id)); ?>" method="POST" style="display: inline;"
                                                      class="form-confirm" data-action-text="desbloquear" data-user-name="<?php echo e($professor->nome); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn-icon" title="Desbloquear Professor"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="5" class="text-center">Nenhum professor cadastrado.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination" id="professoresPagination">
                           </div>
                    </div>
                </section>

        </section> <section id="depoimentos" class="dashboard-section">
    <h2>Gerenciar Depoimentos</h2>
    <div class="card data-table-card">

        <header class="card-header">
            <h4>Lista de Depoimentos</h4>
            <div class="card-actions">
                <form class="search-box" id="searchDepoimentosForm" 
      data-search-url="<?php echo e(route('admin.depoimentos.search')); ?>">
                    <input type="text" id="searchDepoimentosInput" placeholder="Pesquisar por autor ou texto...">
                    <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </header>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Autor</th>
                        <th>Depoimento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody id="depoimentosTableBody" 
       data-block-url="/admin/depoimentos"
       data-unblock-url="/admin/depoimentos"
       data-csrf-token="<?php echo e(csrf_token()); ?>">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $depoimentosData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depoimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($depoimento->autor); ?></td>
                        <td>
                            <span title="<?php echo e($depoimento->texto); ?>">
                                <?php echo e(\Illuminate\Support\Str::limit($depoimento->texto, 80, '...')); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($depoimento->aprovado): ?> 
                                <span class="badge badge-success">Aprovado</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Bloqueado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($depoimento->aprovado): ?>
                                <form action="<?php echo e(route('admin.depoimentos.block', $depoimento->id)); ?>" method="POST" style="display: inline;" 
                                      class="form-confirm" data-action-text="bloquear" data-user-name="o depoimento de <?php echo e($depoimento->autor); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-icon" title="Bloquear Depoimento"><i class="fas fa-ban" style="color: #e53e3e;"></i></button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo e(route('admin.depoimentos.unblock', $depoimento->id)); ?>" method="POST" style="display: inline;"
                                      class="form-confirm" data-action-text="aprovar" data-user-name="o depoimento de <?php echo e($depoimento->autor); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-icon" title="Aprovar Depoimento"><i class="fas fa-check-circle" style="color: #48bb78;"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center">Nenhum depoimento encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="pagination" id="depoimentosPagination">
             <?php echo e($depoimentosData->fragment('depoimentos')->links()); ?>

        </div>
    </div>
</section>

<section id="charts-section" class="dashboard-section">

                <section id="charts-section" class="dashboard-section">
    <h2>Análises e Gráficos</h2>
    
    <div class="charts-grid-full" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        <div class="chart-card">
            <h3>Distribuição de Usuários (Pizza)</h3>
            <div id="userDistributionPieChart" style="height: 350px;"></div>
        </div>
        <div class="chart-card">
            <h3>Distribuição de Usuários (Barras)</h3>
            <div id="userDistributionBarChart" style="height: 350px;"></div>
        </div>

        <div class="chart-card">
            <h3>Conteúdo: Turmas vs Exercícios (Pizza)</h3>
            <div id="contentDistributionPieChart" style="height: 350px;"></div>
        </div>
        <div class="chart-card">
            <h3>Panorama Geral da Plataforma (Barras)</h3>
            <div id="contentDistributionBarChart" style="height: 350px;"></div>
        </div>

    </div>
</section>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
        // Passando dados do PHP para o JavaScript
        window.dashboardData = {
            alunosCount: <?php echo e($alunosCount ?? 0); ?>,
            professoresCount: <?php echo e($professoresCount ?? 0); ?>,
            turmasCount: <?php echo e($turmasCount ?? 0); ?>,         // Novo
            exerciciosCount: <?php echo e($exerciciosCount ?? 0); ?>   // Novo
        };
    </script>
    <script src="<?php echo e(asset('js/Adm/admDashboard.js')); ?>"></script>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Adm/dashboard.blade.php ENDPATH**/ ?>