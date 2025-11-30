<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo e($exercicio->nome); ?></title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo e(asset('css/Aluno/exercicioDetalhe.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="page-wrapper">
        
        <header class="page-header-blue">
            <div class="header-container">
                <a href="<?php echo e(route('turmas.especifica', $exercicio->turma_id)); ?>" class="back-link">
                    <i class='bx bx-arrow-back'></i> Voltar para a Turma
                </a>
                
                <div class="header-info">
                    <h1><?php echo e($exercicio->nome); ?></h1>
                    <p>Prazo: <?php echo e(\Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y \à\s H:i')); ?></p>
                    
                    <?php if($respostaAnterior): ?>
                        <div class="header-status"><i class='bx bx-check-circle'></i> Entregue</div>
                    <?php elseif(now()->isAfter($exercicio->data_fechamento)): ?>
                        <div class="header-status"><i class='bx bx-time'></i> Encerrado</div>
                    <?php else: ?>
                        <div class="header-status"><i class='bx bx-pencil'></i> Aberto</div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="page-content">
            
            <div class="main-left">
                <div class="card">
                    <div class="card-section">
                        <h2><i class='bx bx-text'></i> Instruções</h2>
                        <p><?php echo e($exercicio->descricao ?: 'Sem descrição.'); ?></p>
                    </div>
                    
                    <div class="card-section">
                        <h2><i class='bx bx-paperclip'></i> Materiais de Apoio</h2>
                        <div class="materials-list">
                            <?php $__empty_1 = true; $__currentLoopData = $exercicio->imagensApoio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(asset('storage/' . $img->imagem_path)); ?>" target="_blank" class="material-item">
                                    <i class='bx bxs-image'></i> Ver Imagem
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>

                            <?php $__empty_1 = true; $__currentLoopData = $exercicio->arquivosApoio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(asset('storage/' . $arq->arquivo_path)); ?>" target="_blank" class="material-item">
                                    <i class='bx bxs-file-pdf'></i> <?php echo e($arq->nome_original); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>

                            <?php if($exercicio->imagensApoio->isEmpty() && $exercicio->arquivosApoio->isEmpty()): ?>
                                <p style="color:#999; font-style:italic;">Nenhum material anexado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                
                <?php if($respostaAnterior && $respostaAnterior->conceito): ?>
                    <div class="card feedback-card">
                        <div class="card-section">
                            <h2><i class='bx bxs-award'></i> Avaliação</h2>
                            <div class="grade-summary">
                                <div class="grade-item">
                                    <small>Nota</small>
                                    <strong><?php echo e($respostaAnterior->nota); ?></strong>
                                </div>
                                <div class="grade-item">
                                    <small>Conceito</small>
                                    <span class="conceito-badge"><?php echo e($respostaAnterior->conceito); ?></span>
                                </div>
                            </div>
                            <?php if($respostaAnterior->feedback): ?>
                                <div class="feedback-text">
                                    <strong>Comentário:</strong><br>
                                    <?php echo e($respostaAnterior->feedback); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card submission-card">
                    <div class="card-section">
                        <h2>Situação da Entrega</h2>
                        
                        <?php if($respostaAnterior): ?>
                            <div class="status-badge status-delivered">
                                <i class='bx bxs-check-circle'></i> Entregue para avaliação
                            </div>
                            <p class="submission-date">Enviado em: <?php echo e($respostaAnterior->created_at->format('d/m/Y H:i')); ?></p>
                            
                            <div class="submitted-files">
                                <strong>Seus arquivos:</strong>
                                <ul>
                                    <?php $__currentLoopData = $respostaAnterior->arquivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e(asset('storage/' . $arq->arquivo_path)); ?>" target="_blank"><i class='bx bx-file'></i> <?php echo e(basename($arq->arquivo_path)); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                        <?php elseif(now()->isAfter($exercicio->data_fechamento)): ?>
                             <div class="status-badge status-late">
                                <i class='bx bxs-error-circle'></i> Prazo Encerrado
                            </div>
                            <p style="text-align:center; color:#777;">Não é mais possível enviar respostas.</p>
                        <?php else: ?>
                            <div class="status-badge status-pending">
                                <i class='bx bxs-time'></i> Pendente de Envio
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(now()->isBefore($exercicio->data_fechamento)): ?>
                        <div class="card-section action-area">
                            <h3><?php echo e($respostaAnterior ? 'Reenviar Trabalho' : 'Enviar Resposta'); ?></h3>
                            
                            <?php if($errors->any()): ?>
                                <div class="error-box" style="color:red; font-size:0.9rem;">Erro no upload. Tente novamente.</div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('aluno.exercicios.responder', $exercicio->id)); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="arquivo_resposta" class="file-drop-area">
                                        <i class='bx bxs-cloud-upload'></i>
                                        <span>Clique para selecionar arquivos</span>
                                        <input name="arquivos_resposta[]" type="file" id="arquivo_resposta" class="input-file" required multiple />
                                    </label>
                                    <div id="file-list"></div>
                                </div>
                                <button type="submit" class="btn-enviar">
                                    <i class='bx bx-send'></i> <?php echo e($respostaAnterior ? 'Atualizar' : 'Enviar'); ?>

                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

            </aside>
        </main>
    </div>

    <script>
        const inputArquivo = document.getElementById('arquivo_resposta');
        const fileListContainer = document.getElementById('file-list');
        
        if (inputArquivo) {
            inputArquivo.addEventListener('change', function() {
                fileListContainer.innerHTML = '';
                if (this.files.length > 0) {
                    const list = document.createElement('ul');
                    for (const file of this.files) {
                        const li = document.createElement('li');
                        li.innerHTML = `<i class='bx bx-file'></i> ${file.name}`;
                        list.appendChild(li);
                    }
                    fileListContainer.appendChild(list);
                }
            });
        }
    </script>

    <?php if(session('sweet_success')): ?>
        <script>
            Swal.fire({ title: "Sucesso!", text: "<?php echo e(session('sweet_success')); ?>", icon: "success", confirmButtonColor: "#1a62ff" });
        </script>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/exercicioDetalhe.blade.php ENDPATH**/ ?>