<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Turmas</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="<?php echo e(asset('css/Aluno/alunoTurma.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="turma-wrapper">
        
        <!-- HEADER AZUL -->
        <header class="turma-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="<?php echo e(route('aluno.dashboard')); ?>" class="back-link">
                        <i class='bx bx-arrow-back'></i> Voltar para o Painel
                    </a>
                    
                    <div class="header-info">
                        <h1>Minhas Turmas</h1>
                        <p>Selecione uma turma para ver atividades e conteúdos.</p>
                    </div>
                </div>

                <div class="header-actions">
                    <div class="header-badge">
                        <i class='bx bxs-school'></i> <?php echo e($turmas->count()); ?> Turmas
                    </div>
                </div>
            </div>
        </header>

        <div class="main-content">
            <div class="card-container">
                
                <div class="section-header">
                    <div class="section-title">
                        <h2>Turmas Matriculadas</h2>
                        <p>Acesse o ambiente de aprendizado de cada disciplina.</p>
                    </div>
                </div>

                <div class="class-list">
                    <?php $__empty_1 = true; $__currentLoopData = $turmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('turmas.especifica', $turma)); ?>" class="class-item">
                            
                            <!-- Informações da Turma -->
                            <div class="class-info-group">
                                <div class="class-details">
                                    <h3><?php echo e($turma->nome_turma); ?></h3>
                                    <span><?php echo e($turma->ano_turma ?? 'Ano Atual'); ?> • <?php echo e(ucfirst($turma->turno)); ?></span>
                                    <small>Prof. <?php echo e($turma->professor->nome); ?></small>
                                </div>
                            </div>

                            <!-- Informações Extras (Opcional) -->
                            <div class="class-stats-group">
                                <div class="stat-col">
                                    <span class="stat-label">Situação</span>
                                    <span class="stat-value" style="color: var(--success-green);">Cursando</span>
                                </div>
                            </div>

                            <!-- Botão de Ação -->
                            <div class="btn-enter">
                                Acessar <i class='bx bx-chevron-right'></i>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <i class='bx bx-folder-open'></i>
                            <p>Você ainda não está matriculado em nenhuma turma.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
    
    <script src="<?php echo e(asset('js/Aluno/turmaAluno.js')); ?>"></script>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/turma.blade.php ENDPATH**/ ?>