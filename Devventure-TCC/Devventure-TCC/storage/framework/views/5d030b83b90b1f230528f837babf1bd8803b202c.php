<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel do Professor</title>
    <link href="<?php echo e(asset('css/Professor/professorDashboard.css')); ?>" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main class="page-professor-dashboard">
    <div class="container">
        
        <header class="page-header">
            <div class="header-content">
                <h1>Painel do Professor</h1>
                <p>Olá, <?php echo e(Auth::guard('professor')->user()->nome); ?>! Gerencie suas turmas e aulas.</p>
            </div>
        </header>

        
        <section class="acoes-rapidas">
            <a href="<?php echo e(route('professor.turmas')); ?>" class="card-acao">
                <i class='bx bxs-group'></i>
                <h3>Gerenciar Turmas</h3>
                <p>Crie e edite suas turmas.</p>
            </a>
            <a href="<?php echo e(route('professor.exercicios.index')); ?>" class="card-acao">
                <i class='bx bxs-spreadsheet'></i>
                <h3>Exercícios</h3>
                <p>Elabore e atribua novos exercícios.</p>
            </a>

            <a href="<?php echo e(route('professor.provas.create')); ?>" class="card-acao">
                <i class='bx bxs-file'></i>
                <h3>Provas</h3>
                <p>Acompanhe o rendimento da jornada dos alunos.</p>
            </a>
          
            <a href="<?php echo e(route('professor.turmas', ['contexto' => 'relatorios'])); ?>" class="card-acao">
                <i class='bx bxs-bar-chart-square'></i>
                <h3>Desempenho</h3>
                <p>Acompanhe o progresso de suas turmas.</p>
            </a>
        </section>


        <div class="content-grid">
            <div class="coluna-principal">
                <div class="card">
                    <h2><i class='bx bx-list-ul'></i> Suas Turmas</h2>
                    <div class="lista-turmas">
                        <?php $__empty_1 = true; $__currentLoopData = $turmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="item-turma">
                                <div class="info-turma">
                                    <strong><?php echo e($turma->nome_turma); ?></strong>
                                    <small><?php echo e($turma->alunos_count); ?> <?php echo e($turma->alunos_count == 1 ? 'aluno' : 'alunos'); ?></small>
                                </div>
                                
                                <div class="turma-actions">
                                    <a href="<?php echo e(route('professor.relatorios.index', $turma)); ?>" class="btn-acao btn-relatorio">
                                        <i class='bx bx-line-chart'></i> Relatórios
                                    </a>
                                    <a href="<?php echo e(route('turmas.especificaID', $turma)); ?>" class="btn-acao btn-gerenciar">
                                        <i class='bx bx-cog'></i> Gerenciar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="empty-message">Você ainda não criou nenhuma turma. <a href="<?php echo e(route('professor.turmas')); ?>">Criar agora</a></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('professor.turmas')); ?>" class="link-ver-todas">Ver todas as turmas <i class='bx bx-right-arrow-alt'></i></a>
                </div>
                
                <div class="card">
                    <h2><i class='bx bxs-videos'></i> Últimas Aulas Adicionadas</h2>
                    <div class="lista-aulas">
                        <?php $__empty_1 = true; $__currentLoopData = $aulasRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="item-aula">
                                <i class='bx bx-play-circle'></i>
                                <div class="info-aula">
                                    <strong><?php echo e($aula->titulo); ?></strong>
                                    <small>Turma: <?php echo e($aula->turma->nome_turma); ?></small>
                                </div>
                                <span class="data-aula"><?php echo e($aula->created_at->format('d/m/Y')); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="empty-message">Nenhuma aula foi criada recentemente.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="coluna-lateral">
                <div class="card card-estatisticas">
                    <h2><i class='bx bx-bar-chart-square'></i> Estatísticas Gerais</h2>
                    <div class="item-estatistica">
                        <span class="numero"><?php echo e($totalAlunos); ?></span>
                        <span class="descricao">Total de Alunos</span>
                    </div>
                    <div class="item-estatistica">
                        <span class="numero"><?php echo e($totalAulas); ?></span>
                        <span class="descricao">Aulas Criadas</span>
                    </div>
                    <div class="item-estatistica">
                        <span class="numero"><?php echo e($totalTurmas); ?></span>
                        <span class="descricao">Turmas Criadas</span>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
</main>

<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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

<script src="<?php echo e(asset('js/Professor/professorDashboard.js')); ?>"></script>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Professor/dashboard.blade.php ENDPATH**/ ?>