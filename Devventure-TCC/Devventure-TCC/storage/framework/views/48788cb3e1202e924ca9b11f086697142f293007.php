<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($aula->titulo); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Aluno/verAulaAluno.css')); ?>" rel="stylesheet">
</head>
<body>

    <header class="page-header-blue">
        <div class="header-container">
            <a href="<?php echo e(route('turmas.especifica', $aula->turma)); ?>" class="back-link">
                <i class='bx bx-arrow-back'></i> Voltar para a Turma
            </a>
            
            <div class="header-info">
                <h1><?php echo e($aula->titulo); ?></h1>
                <p>
                    <span><i class='bx bxs-chalkboard'></i> <?php echo e($aula->turma->nome_turma); ?></span>
                    <span style="margin: 0 10px;">|</span>
                    <span><i class='bx bxs-user-badge'></i> Prof. <?php echo e($aula->turma->professor->nome); ?></span>
                </p>
            </div>
        </div>
    </header>

    <main class="page-content">
        
        <div class="video-wrapper-card" 
             id="video-wrapper" 
             data-video-id="<?php echo e($videoId); ?>" 
             data-aula-id="<?php echo e($aula->id); ?>" 
             data-progress-url="<?php echo e(route('aulas.progresso')); ?>">

            <div class="video-container">
                <?php if($videoId): ?>
                    <div id="player-iframe-id"></div>
                <?php else: ?>
                    <div class="video-error-state">
                        <i class='bx bxs-error-circle'></i>
                        <p>Link de vídeo inválido ou não encontrado.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="status-video" id="status-video">
            <p><i class='bx bx-info-circle'></i> Assista ao vídeo até o final para liberar o questionário.</p>
        </div>

        <div id="quiz-container" class="quiz-container" style="display: none;">
            
            <?php if($aula->formulario && $aula->formulario->perguntas->isNotEmpty()): ?>

                <div class="card-formulario">
                    <h3><i class='bx bx-task'></i> Valide sua Aula</h3>
                    <p class="desc">Responda às perguntas abaixo para registrar sua presença.</p>

                    <?php
                        $jaRespondeu = \App\Models\Resposta::where('aluno_id', auth('aluno')->id())
                                            ->whereIn('pergunta_id', $aula->formulario->perguntas->pluck('id'))
                                            ->exists();
                    ?>

                    <?php if($jaRespondeu): ?>
                        <div class="alert-success">
                            <i class='bx bxs-check-circle'></i>
                            <span>Você já respondeu a este formulário. Aula validada!</span>
                        </div>
                    <?php else: ?>
                        <form action="<?php echo e(route('aluno.formulario.responder', $aula)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <?php $__currentLoopData = $aula->formulario->perguntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pergunta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group-quiz">
                                    <p class="pergunta-texto"><?php echo e($loop->iteration); ?>. <?php echo e($pergunta->texto_pergunta); ?></p>
                                    
                                    <div class="opcoes-container">
                                        <?php $__currentLoopData = $pergunta->opcoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opcao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="opcao-radio">
                                                <input 
                                                    type="radio" 
                                                    name="respostas[<?php echo e($pergunta->id); ?>]" 
                                                    id="opcao-<?php echo e($opcao->id); ?>" 
                                                    value="<?php echo e($opcao->id); ?>"
                                                    required>
                                                <label for="opcao-<?php echo e($opcao->id); ?>"><?php echo e($opcao->texto_opcao); ?></label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <button type="submit" class="btn-enviar-respostas">
                                <i class='bx bx-send'></i> Enviar Respostas
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
            <?php endif; ?>
        </div>  
    </main>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="<?php echo e(asset('js/Aluno/verAulas.js')); ?>"></script>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/verAulas.blade.php ENDPATH**/ ?>