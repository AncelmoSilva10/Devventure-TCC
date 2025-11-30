<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrigir: <?php echo e($exercicio->nome); ?></title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo e(asset('css/Professor/respostasExercicio.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="correcao-wrapper">
        
        <header class="turma-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="<?php echo e(url('/professorExercicios')); ?>" class="back-link">
                        <i class='bx bx-chevron-left'></i> Voltar
                    </a>
                    <div class="header-info">
                        <h1><?php echo e($exercicio->nome); ?></h1>
                        <p>Turma: <?php echo e($exercicio->turma->nome_turma); ?> | Pontos: <?php echo e($exercicio->pontos); ?></p>
                    </div>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn-delete" onclick="confirmarExclusao()">
                        <i class='bx bx-trash'></i> Excluir Exercício
                    </button>
                    
                    <form id="form-excluir" action="<?php echo e(route('professor.exercicios.destroy', $exercicio->id)); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                    </form>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="respostas-grid">
                <?php $__empty_1 = true; $__currentLoopData = $exercicio->respostas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resposta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card-aluno">
                        <div class="aluno-info">
                            <img src="<?php echo e($resposta->aluno->avatar ? asset('storage/' . $resposta->aluno->avatar) : 'https://i.pravatar.cc/150?u='.$resposta->aluno->id); ?>" alt="Avatar" class="avatar">
                            <div class="aluno-details">
                                <h4><?php echo e($resposta->aluno->nome); ?></h4>
                                <small>Enviado em: <?php echo e($resposta->created_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>

                        <div class="arquivos-enviados">
                            <h5>Arquivo Enviado:</h5>
                            <?php $__empty_2 = true; $__currentLoopData = $resposta->arquivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arquivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <a href="<?php echo e(asset('storage/' . $arquivo->arquivo_path)); ?>" target="_blank" class="arquivo-link" download>
                                    <i class='bx bxs-file-pdf'></i> <?php echo e(basename($arquivo->arquivo_path)); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <p style="font-size:0.9rem; color:#64748b; font-style:italic;">Apenas texto.</p>
                            <?php endif; ?>
                        </div>

                        <form action="<?php echo e(route('professor.respostas.avaliar', $resposta->id)); ?>" method="POST" class="form-avaliacao">
                            <?php echo csrf_field(); ?>
                            <h5>Avaliação</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Conceito</label>
                                    <select name="conceito" required>
                                        <option value="" disabled <?php echo e(!$resposta->conceito ? 'selected' : ''); ?>>Selecione</option>
                                        <option value="MB" <?php echo e($resposta->conceito == 'MB' ? 'selected' : ''); ?>>MB</option>
                                        <option value="B" <?php echo e($resposta->conceito == 'B' ? 'selected' : ''); ?>>B</option>
                                        <option value="R" <?php echo e($resposta->conceito == 'R' ? 'selected' : ''); ?>>R</option>
                                        <option value="I" <?php echo e($resposta->conceito == 'I' ? 'selected' : ''); ?>>I</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nota (0-<?php echo e($exercicio->pontos); ?>)</label>
                                    <input type="number" name="nota" value="<?php echo e($resposta->nota); ?>" max="<?php echo e($exercicio->pontos); ?>" min="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Feedback</label>
                                <textarea name="feedback" rows="2" placeholder="Comentário..."><?php echo e($resposta->feedback); ?></textarea>
                            </div>
                            <button type="submit" class="btn-salvar-avaliacao">
                                <i class='bx bx-check'></i> Salvar
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <i class='bx bx-inbox'></i>
                        <p>Nenhuma resposta enviada ainda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        function confirmarExclusao() {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Isso apagará o exercício e todas as respostas dos alunos!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-excluir').submit();
                }
            })
        }

        <?php if(session('sweet_success')): ?>
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: "<?php echo e(session('sweet_success')); ?>",
                showConfirmButton: false, timer: 3000, timerProgressBar: true
            });
        <?php endif; ?>
    </script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/respostasExercicio.blade.php ENDPATH**/ ?>