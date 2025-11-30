<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - <?php echo e($turma->nome_turma); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Professor/relatorios.css')); ?>" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header class="reports-header">
        <div class="header-container">
            <div class="header-left">
                <a href="<?php echo e(route('professor.turmas')); ?>" class="back-link">
                    <i class='bx bx-chevron-left'></i> Voltar para Minhas Turmas
                </a>
                
                <div class="header-title">
                    <h1><i class='bx bxs-bar-chart-alt-2'></i> Relatórios de Desempenho</h1>
                    <p><?php echo e($turma->nome_turma); ?></p>
                </div>
            </div>

            <div class="header-actions">
                <span class="export-label">Exportar:</span>
                <a href="<?php echo e(route('professor.relatorios.exportar', ['turma' => $turma->id, 'formato' => 'pdf'])); ?>" class="btn-export" target="_blank">
                    <i class='bx bxs-file-pdf'></i> PDF
                </a>
                <a href="<?php echo e(route('professor.relatorios.exportar', ['turma' => $turma->id, 'formato' => 'csv'])); ?>" class="btn-export">
                    <i class='bx bxs-spreadsheet'></i> Excel
                </a>
            </div>
        </div>
    </header>

    <div class="reports-wrapper">
        <div class="reports-grid">
           
            <div class="card card-media">
                <div class="card-header">
                    <h3>Média Geral</h3>
                    <i class='bx bx-line-chart card-icon'></i>
                </div>
                <div class="card-body">
                    <span class="main-metric"><?php echo e(round($mediaGeral, 1)); ?></span>
                    <small>/ 100 pontos</small>
                </div>
            </div>

            <div class="card card-engajamento">
                <div class="card-header">
                    <h3>Engajamento</h3>
                    <i class='bx bx-task card-icon'></i>
                </div>
                <div class="card-body">
                    <span class="main-metric"><?php echo e($taxaEngajamento); ?><small>%</small></span>
                    <?php if($ultimoExercicio): ?>
                        <small>na última atividade</small>
                    <?php else: ?>
                        <small>Sem atividades</small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card card-destaques">
                <div class="card-header">
                    <h3><i class='bx bxs-trophy'></i> Destaques</h3>
                </div>
                <ul class="user-list">
                    <?php $__empty_1 = true; $__currentLoopData = $alunosDestaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno])); ?>">
                        <img src="<?php echo e($aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id); ?>" alt="Avatar" class="avatar">
                        <span class="user-name"><?php echo e($aluno->nome); ?></span>
                        <span class="user-points"><?php echo e($aluno->total_pontos); ?> pts</span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="empty-message">Sem dados.</p>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="card card-atencao">
                <div class="card-header">
                    <h3><i class='bx bxs-error-circle'></i> Atenção</h3>
                </div>
                 <ul class="user-list">
                    <?php $__empty_1 = true; $__currentLoopData = $alunosAtencao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno])); ?>">
                        <img src="<?php echo e($aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id); ?>" alt="Avatar" class="avatar">
                        <span class="user-name"><?php echo e($aluno->nome); ?></span>
                        <span class="user-points low-score">Pendente</span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="empty-message">Tudo em dia!</p>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card card-grafico">
                <div class="card-header">
                    <h3>Evolução da Turma</h3>
                </div>
                <div class="chart-container">
                    <?php if($desempenhoPorExercicio->count() > 1): ?>
                        <canvas id="desempenhoChart"></canvas>
                    <?php else: ?>
                        <div class="empty-message">
                            <p>Dados insuficientes para gerar o gráfico de evolução.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        <?php if($desempenhoPorExercicio->count() > 1): ?>
            const ctx = document.getElementById('desempenhoChart').getContext('2d');
            
            // Gradiente Verde
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(0, 121, 107, 0.5)'); // Verde forte topo
            gradient.addColorStop(1, 'rgba(0, 121, 107, 0.0)'); // Transparente base

            const desempenhoData = {
                labels: [
                    <?php $__currentLoopData = $desempenhoPorExercicio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desempenho): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e(Str::limit($desempenho->nome, 15)); ?>',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                datasets: [{
                    label: 'Média da Turma',
                    data: [
                        <?php $__currentLoopData = $desempenhoPorExercicio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desempenho): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($desempenho->respostas_avg_nota); ?>,
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#00796b', // Linha Verde Sólida
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#00796b',
                    pointRadius: 4,
                    tension: 0.4 
                }]
            };

            const config = {
                type: 'line', 
                data: desempenhoData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            max: 100,
                            grid: { color: '#f0f0f0' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#004d40',
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            };
            new Chart(ctx, config);
        <?php endif; ?>
    </script>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/relatorios/index.blade.php ENDPATH**/ ?>