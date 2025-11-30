<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Resultado da Prova</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Aluno/showProva.css')); ?>" rel="stylesheet"> 
</head>
<body>

    <div class="page-wrapper">
        
        <header class="page-header-blue">
            <div class="header-container max-width-container">
                
                <div class="header-info">
                    <h1>Resultado da Prova</h1>
                    <p><?php echo e($tentativa->prova->titulo); ?></p>
                </div>
            </div>
        </header>

        <div class="content-grid max-width-container">
            
            <div class="report-main-content">
                <div class="card">
                    <h2><i class='bx bxs-detail'></i> Detalhes das Respostas</h2>
                    <?php $__currentLoopData = $tentativa->prova->questoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $questao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Busca a resposta do aluno específica para esta questão
                            $respostaAluno = $tentativa->respostas->where('prova_questao_id', $questao->id)->first();
                            // Define status e classes visuais
                            $acertou = $respostaAluno ? $respostaAluno->correta : false;
                            $statusClass = $acertou ? 'status-correct' : 'status-wrong';
                            $statusIcon = $acertou ? 'bx-check-circle' : 'bx-x-circle';
                            $statusText = $acertou ? 'Correta' : 'Incorreta';
                        ?>
                        
                        <div class="card-questao-detalhe <?php echo e($statusClass); ?>">
                            <div class="questao-header">
                                <h5>Questão <?php echo e($index + 1); ?></h5>
                                <span class="status-text">
                                    <i class='bx <?php echo e($statusIcon); ?>'></i> <?php echo e($statusText); ?>

                                </span>
                            </div>
                            
                            <p class="questao-enunciado"><?php echo nl2br(e($questao->enunciado)); ?></p>
                            
                            <?php if($questao->tipo_questao == 'multipla_escolha'): ?>
                                <?php
                                    $altRespondida = $questao->alternativas->where('id', $respostaAluno->prova_alternativa_id ?? null)->first();
                                    $altCorreta = $questao->alternativas->where('correta', true)->first();
                                ?>
                                <div class="feedback-box">
                                    <p><strong>Sua Resposta:</strong> <?php echo e($altRespondida->texto_alternativa ?? 'Não respondeu'); ?></p>
                                    <?php if(!$acertou): ?>
                                        <p class="gabarito-text">Gabarito: <?php echo e($altCorreta->texto_alternativa ?? 'N/A'); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($questao->tipo_questao == 'texto'): ?>
                                <div class="feedback-box">
                                    <p><strong>Sua Resposta:</strong></p>
                                    <div class="resposta-texto"><?php echo e(nl2br(e($respostaAluno->resposta_texto ?? 'Em branco'))); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(isset($respostaAluno->feedback_professor) || isset($respostaAluno->nota_manual)): ?>
                                <div class="feedback-manual">
                                    <strong>Nota/Feedback do Professor:</strong> <?php echo e($respostaAluno->nota_manual ?? 'Aguardando'); ?>

                                    <?php if(isset($respostaAluno->feedback_professor)): ?>
                                        <p><?php echo e($respostaAluno->feedback_professor); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <aside class="sidebar">
                <div class="card">
                    <h2><i class='bx bxs-award'></i> Seu Desempenho</h2>
                    <div class="score-display">
                        <div class="score-number"><?php echo e($tentativa->pontuacao_final ?? '0'); ?></div>
                        <div class="score-label">Pontos Finais</div>
                    </div>
                    
                    <div class="info-row">
                        <span>Total de Questões</span>
                        <strong><?php echo e($totalQuestoes); ?></strong>
                    </div>
                    <div class="info-row success-row">
                        <span>Acertos</span>
                        <strong><?php echo e($acertos); ?></strong>
                    </div>
                    <div class="info-row danger-row">
                        <span>Erros</span>
                        <strong><?php echo e($erros); ?></strong>
                    </div>
                    <?php if(isset($pendentes) && $pendentes > 0): ?>
                        <div class="info-row warning-row" style="border:none;">
                            <span>Pendentes (Correção Manual)</span>
                            <strong><?php echo e($pendentes); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2><i class='bx bx-user'></i> Informações</h2>
                    <div class="info-row">
                        <span>Aluno</span>
                        <strong><?php echo e($tentativa->aluno->nome); ?></strong>
                    </div>
                    <div class="info-row">
                        <span>Turma</span>
                        <strong><?php echo e($tentativa->prova->turma->nome_turma); ?></strong>
                    </div>
                    <div class="info-row" style="border:none;">
                        <span>Tempo Realizado</span>
                        <strong><?php echo e($tempoDecorrido ?? 'N/A'); ?></strong>
                    </div>
                </div>

                <a href="<?php echo e(route('turmas.especifica', $tentativa->prova->turma_id)); ?>" class="btn-custom btn-primary-custom">
                    <i class='bx bx-arrow-back'></i> Voltar para a Turma
                </a>
            </aside>

        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // Lógica do SweetAlert (Mantida, mas sem emojis)
            <?php if(session('success')): ?>
                Swal.fire({
                    title: 'Excelente!',
                    text: "<?php echo e(session('success')); ?>",
                    icon: 'success',
                    confirmButtonText: 'Ver Resultados',
                    confirmButtonColor: '#22c55e' 
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    title: 'Ops!',
                    text: "<?php echo e(session('error')); ?>",
                    icon: 'error',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#ef4444'
                });
            <?php endif; ?>

            <?php if(session('info')): ?>
                Swal.fire({
                    title: 'Informação',
                    text: "<?php echo e(session('info')); ?>",
                    icon: 'info',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#3b82f6'
                });
            <?php endif; ?>
        });
    </script>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/provaResultado.blade.php ENDPATH**/ ?>