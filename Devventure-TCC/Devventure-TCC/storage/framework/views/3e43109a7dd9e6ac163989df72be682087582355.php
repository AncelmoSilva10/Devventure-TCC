<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Aluno/alunoDashboard.css')); ?>" rel="stylesheet">
</head>
<body>

<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main class="page-aluno-dashboard">
    <div class="container">

        <div class="page-header">
            <div class="header-text">
                <h1>Painel do Aluno</h1>
                <p>Olá, <?php echo e(Auth::guard('aluno')->user()->nome); ?>! Continue seus estudos.</p>
            </div>
            <a href="<?php echo e(route('aluno.turma')); ?>" class="btn-primary">
                <i class='bx bxs-group'></i> Acessar Minhas Turmas
            </a>
        </div>

        <?php if($convites->isNotEmpty()): ?>
        <div class="card card-convites-destaque">
            <h3>Você tem novos convites!</h3>
            <div class="convites-container">
                <?php $__currentLoopData = $convites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $convite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="convite-item">
                        <div class="convite-info">
                            <p>O professor(a) <strong><?php echo e($convite->turma->professor->nome); ?></strong> convidou você para a turma:</p>
                            <span><i class='bx bxs-chalkboard'></i> <?php echo e($convite->turma->nome_turma); ?></span>
                        </div>
                        <div class="convite-actions">
                            <form action="<?php echo e(route('convites.aceitar', $convite)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-aceitar"><i class='bx bx-check'></i> Aceitar</button>
                            </form>
                            <form action="<?php echo e(route('convites.recusar', $convite)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-recusar"><i class='bx bx-x'></i> Recusar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            
            <div class="coluna-principal">
                <div class="card">
                    <h3><i class='bx bx-bar-chart-alt-2'></i> Seu Progresso nas Aulas</h3>
                    <p class="modulo-title">Progresso total de vídeos assistidos</p>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: <?php echo e($progressoPercentual); ?>%;"><?php echo e($progressoPercentual); ?>%</div>
                    </div>
                    <p class="progresso-detalhes">Continue assistindo às aulas para avançar.</p>
                </div>

                <div class="card">
                    <h3><i class='bx bx-alarm-exclamation'></i> Histórico de Entregas</h3>
                    <div class="deadlines-list">
                        <?php $__empty_1 = true; $__currentLoopData = $todasEntregas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($item->type === 'exercicio'): ?>
                                <?php
                                    $entregue = $item->respostas->isNotEmpty();
                                    $statusClass = 'status-pending';
                                    $statusText = 'Pendente';

                                    if ($entregue) {
                                        $statusClass = 'status-delivered';
                                        $statusText = 'Realizado';
                                    } elseif (now()->isAfter($item->data_fechamento)) {
                                        $statusClass = 'status-late';
                                        $statusText = 'Fechado';
                                    }
                                ?>
                                <a href="<?php echo e(route('aluno.exercicios.mostrar', $item->id)); ?>" class="deadline-item <?php echo e($statusClass); ?>">
                                    <div class="status-icon">
                                        <i class='bx bxs-spreadsheet'></i>
                                    </div>
                                    <div class="deadline-info">
                                        <strong>Exercício: <?php echo e($item->nome); ?></strong>
                                        <small>Turma: <?php echo e($item->turma->nome_turma); ?></small>
                                    </div>
                                    <div class="deadline-date">
                                        <span><?php echo e($statusText); ?> (Até <?php echo e($item->data_fechamento->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i')); ?>)</span>
                                    </div>
                                </a>
                            <?php elseif($item->type === 'prova'): ?>
                                <?php
                                    $link = route('aluno.provas.show', $item->id);
                                    
                                    $statusClass = 'status-pending';
                                    $statusText = 'Pendente';

                                    if ($item->statusTentativa === 'finalizada') {
                                        $statusClass = 'status-delivered';
                                        $statusText = 'Realizado';
                                        $link = route('aluno.provas.resultado', $item->tentativaExistente->id);
                                    } elseif ($item->statusTentativa === 'iniciada') {
                                        $statusClass = 'status-iniciada';
                                        $statusText = 'Em Andamento';
                                        $link = route('aluno.provas.fazer', $item->tentativaExistente->id);
                                    } elseif ($item->statusTentativa === 'atrasada') {
                                        $statusClass = 'status-late';
                                        $statusText = 'Fechado';
                                        $link = route('aluno.provas.show', $item->id);
                                    } elseif ($item->statusTentativa === 'pendente') {
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pendente';
                                    }
                                ?>
                                <a href="<?php echo e($link); ?>" class="deadline-item <?php echo e($statusClass); ?>">
                                    <div class="status-icon">
                                        <i class='bx bxs-file-doc'></i>
                                    </div>
                                    <div class="deadline-info">
                                        <strong>Prova: <?php echo e($item->titulo); ?></strong>
                                        <small>Turma: <?php echo e($item->turma->nome_turma); ?></small>
                                    </div>
                                    <div class="deadline-date">
                                        <span><?php echo e($statusText); ?> (Até <?php echo e($item->data_fechamento->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i')); ?>)</span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-state">
                                <i class='bx bx-check-double'></i>
                                <p>Nenhuma entrega com prazo futuro. Bom trabalho!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

           <div class="coluna-lateral">

                <div class="card">
                    <h3><i class='bx bxs-star'></i> Meus Pontos</h3>
                    <div class="my-points-display">
                        <span><?php echo e(Auth::guard('aluno')->user()->total_pontos ?? 0); ?></span>
                        <small>pontos totais</small>
                    </div>
                    <p class="my-points-info">Continue completando aulas e exercícios para subir no ranking!</p>
                </div>

                <div class="card card-minhas-turmas">
                    <h3><i class='bx bxs-chalkboard'></i> Minhas Turmas</h3>
                    <div class="lista-turmas-dashboard">
                        <?php $__empty_1 = true; $__currentLoopData = $minhasTurmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="turma-item-wrapper">
                                <a href="<?php echo e(route('turmas.especifica', $turma)); ?>" class="turma-item-dashboard">
                                    <div class="turma-info">
                                        <strong><?php echo e($turma->nome_turma); ?></strong>
                                        <small>Professor(a): <?php echo e($turma->professor->nome); ?></small>
                                    </div>
                                    <i class='bx bx-chevron-right'></i>
                                </a>
                                <a href="<?php echo e(route('aluno.turma.ranking', $turma)); ?>" class="turma-ranking-link">
                                    <i class='bx bx-bar-chart-alt-2'></i> Ver Ranking
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="empty-message">Você ainda não está matriculado em nenhuma turma.</p>
                        <?php endif; ?>
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
            title: 'Sucesso!',
            text: "<?php echo e(session('sweet_success')); ?>",
            icon: 'success',
            confirmButtonColor: '#0d3c70',
            confirmButtonText: 'Ok'
        });
    </script>
<?php endif; ?>

</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Aluno/dashboard.blade.php ENDPATH**/ ?>