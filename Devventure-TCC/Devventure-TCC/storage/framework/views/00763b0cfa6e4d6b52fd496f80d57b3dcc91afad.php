<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - <?php echo e($turma->nome_turma); ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('css/Aluno/alunoRanking.css')); ?>">

    <?php if(request()->is('professor*')): ?>
    <style>
        :root {
            --primary-blue: #00796b !important;
            --primary-hover: #004d40 !important;
            --primary-light: #e0f2f1 !important;
            --score-blue: #00796b !important;
        }
        .turma-header {
            background: linear-gradient(135deg, #00796b, #004d40) !important;
            box-shadow: 0 6px 20px rgba(0, 121, 107, 0.2) !important;
        }
        .back-link:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }
        .header-badge {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        /* Cor da nota */
        .student-score-box .score-val {
            color: #00796b !important;
        }
        /* Ícones da lista */
        .student-meta i {
            color: #00796b !important;
        }
        /* Seleção do item */
        .rank-item.current-user {
            background-color: #e0f2f1 !important;
            border-left-color: #00796b !important;
        }
        .rank-item:hover {
            border-color: #b2dfdb !important;
        }
    </style>
    <?php endif; ?>
</head>

<body>


    <div class="ranking-wrapper">

        <header class="turma-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="<?php echo e($backRoute); ?>" class="back-link">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    
                    <div class="header-info">
                        <h1>Ranking da Turma</h1>
                        <p><?php echo e($turma->nome_turma); ?> - Professor(a): <?php echo e($turma->professor->nome ?? 'Docente'); ?></p>
                    </div>
                </div>

                <div class="header-actions">
                    <div class="header-badge">
                        <i class="fas fa-trophy"></i> <?php echo e(count($alunosRanking)); ?> Alunos
                    </div>
                </div>
            </div>
        </header>

        <main class="ranking-container">
            <div class="ranking-card">
                
                <div class="card-header">
                    <div>
                        <h2>Classificação Geral</h2>
                        <p>Baseado na pontuação total acumulada</p>
                    </div>
                </div>

                <div class="ranking-list">
                    
                    <?php $__currentLoopData = $alunosRanking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $pos = $index + 1;
                            
                            // Verifica se é Aluno Logado (para destacar "VOCÊ")
                            $isCurrentUser = false;
                            if(Auth::guard('aluno')->check()) {
                                $isCurrentUser = Auth::guard('aluno')->id() == $aluno->id;
                            }
                            
                            $rowClass = 'pos-other';
                            if($pos == 1) $rowClass = 'pos-1';
                            if($pos == 2) $rowClass = 'pos-2';
                            if($pos == 3) $rowClass = 'pos-3';
                            
                            if($isCurrentUser) $rowClass .= ' current-user';

                            $avatarUrl = $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/150?u='.$aluno->id;
                            
                            // Detecção pela URL também no PHP
                            $isProfessorRoute = request()->is('professor*');
                            
                            $scoreColor = $aluno->total_pontos > 0 ? 'text-green' : 'text-blue'; 
                        ?>

                        <div class="rank-item <?php echo e($rowClass); ?>">
                            <div class="rank-pos">
                                <?php if($pos <= 3): ?> 
                                    <i class="fas fa-medal medal-icon"></i> 
                                <?php endif; ?>
                                <?php echo e($pos); ?>º
                            </div>

                            <img src="<?php echo e($avatarUrl); ?>" alt="<?php echo e($aluno->nome); ?>" class="student-avatar">

                            <div class="student-info">
                                <span class="student-name">
                                    <?php echo e($aluno->nome); ?>

                                    <?php if($isCurrentUser): ?>
                                        <span class="badge-you">VOCÊ</span>
                                    <?php endif; ?>
                                </span>
                                
                                <div class="student-meta">
                                    <?php if($isProfessorRoute && isset($aluno->frequencia_formatada)): ?>
                                        <span title="Frequência"><i class="far fa-calendar-check"></i> <?php echo e($aluno->frequencia_formatada); ?> Freq.</span>
                                        <span class="dot" style="margin:0 5px">•</span>
                                        <span title="Exercícios"><i class="far fa-check-circle"></i> <?php echo e($aluno->exercicios_concluidos); ?>/<?php echo e($aluno->total_exercicios_turma); ?> Ativ.</span>
                                    <?php else: ?>
                                        <span>Aluno matriculado</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="student-score-box">
                                <div class="score-val <?php echo e($scoreColor); ?>">
                                    <?php echo e($aluno->total_pontos); ?>

                                </div>
                                <div class="score-label">PONTOS</div>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </main>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/ranking.blade.php ENDPATH**/ ?>