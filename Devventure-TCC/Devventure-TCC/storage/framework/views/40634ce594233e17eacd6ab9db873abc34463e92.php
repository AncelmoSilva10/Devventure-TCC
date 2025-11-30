<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Resultados: <?php echo e($prova->titulo); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Professor/respostasProva.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="main-wrapper">
        
        <header class="page-header">
            <div class="header-container">
                <div class="header-left">
                    <a href="<?php echo e(route('turmas.especificaID', $turma)); ?>" class="back-link">
                        <i class='bx bx-arrow-back'></i> Voltar para Turma
                    </a>
                    
                    <div class="header-info">
                        <h1><?php echo e($prova->titulo); ?></h1>
                        <p>Resultados da Turma: <?php echo e($turma->nome_turma); ?></p>
                    </div>
                </div>

                <div class="header-actions">
                    <form id="delete-prova-form" action="<?php echo e(route('professor.provas.destroy', $prova)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-delete-header">
                            <i class='bx bxs-trash'></i> Excluir Prova
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content-body">
            
            <div class="card-table">
                
                <div class="card-header-internal">
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    <h2>Tentativas dos Alunos</h2>
                </div>

                <?php if($prova->tentativas->isEmpty()): ?>
                    <div class="empty-state">
                        <i class='bx bx-info-circle'></i>
                        <p>Nenhum aluno realizou esta prova ainda.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Início</th>
                                    <th>Término</th>
                                    <th>Duração</th>
                                    <th>Pontuação</th>
                                    <th>Status</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $prova->tentativas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tentativa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;"><?php echo e($tentativa->aluno->nome); ?></div>
                                            <small style="color:#64748b;">RA: <?php echo e($tentativa->aluno->ra ?? '-'); ?></small>
                                        </td>
                                        <td><?php echo e($tentativa->hora_inicio->format('d/m/Y H:i')); ?></td>
                                        <td>
                                            <?php if($tentativa->hora_fim): ?>
                                                <?php echo e($tentativa->hora_fim->format('d/m/Y H:i')); ?>

                                            <?php else: ?>
                                                <span class="text-warning">--</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($tentativa->hora_inicio && $tentativa->hora_fim): ?>
                                                <?php echo e($tentativa->hora_inicio->diff($tentativa->hora_fim)->format('%Hh %Im')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo e($tentativa->pontuacao_final ?? '0'); ?></strong></td>
                                        <td>
                                            <?php if($tentativa->hora_fim): ?>
                                                <span class="badge bg-success">Finalizada</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Em Andamento</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $tentativa->aluno])); ?>" class="btn-action">
                                                <i class='bx bx-show'></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.getElementById('delete-prova-form');
            
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    Swal.fire({
                        title: 'Excluir Prova?',
                        text: "Isso apagará todas as notas e tentativas dos alunos. Não há como desfazer!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d32f2f', // Vermelho
                        cancelButtonColor: '#64748b',  // Cinza
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/relatorios/provaResultado.blade.php ENDPATH**/ ?>