<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo e($prova->titulo); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo e(asset('css/Aluno/iniciandoProva.css')); ?>" rel="stylesheet"> 
</head>
<body>

    <div class="page-wrapper">
        
        <header class="page-header-blue">
            <div class="header-container max-width-container">
                <a href="<?php echo e(route('turmas.especifica', $prova->turma_id)); ?>" class="back-link">
                    <i class='bx bx-arrow-back'></i> Voltar para a Turma
                </a>
                
                <div class="header-info">
                    <h1><?php echo e($prova->titulo); ?></h1>
                    <p>
                        <span><i class='bx bxs-chalkboard'></i> <?php echo e($prova->turma->nome_turma); ?></span>
                        <span style="margin: 0 10px;">|</span>
                        <span><i class='bx bxs-time'></i> Duração: <?php echo e($prova->duracao_minutos); ?> min</span>
                    </p>
                </div>
            </div>
        </header>

        <div class="content-grid max-width-container">
            
            <div class="main-column">
                
                <?php if(session('success')): ?> <div class="alert alert-success"><i class='bx bx-check-circle'></i> <?php echo e(session('success')); ?></div> <?php endif; ?>
                <?php if(session('error')): ?> <div class="alert alert-danger"><i class='bx bx-error-alt'></i> <?php echo e(session('error')); ?></div> <?php endif; ?>
                <?php if(session('info')): ?> <div class="alert alert-info"><i class='bx bx-info-circle'></i> <?php echo e(session('info')); ?></div> <?php endif; ?>

                <div class="card">
                    <h2><i class='bx bx-clipboard'></i> Detalhes da Avaliação</h2>
                    <div class="deadline-info">
                        <i class='bx bx-alarm-exclamation'></i> 
                        Prazo final: <?php echo e(\Carbon\Carbon::parse($prova->data_fechamento)->format('d/m/Y \à\s H:i')); ?>

                    </div>

                    <p class="card-text"><strong>Período:</strong> <?php echo e(\Carbon\Carbon::parse($prova->data_abertura)->format('d/m/Y H:i')); ?> até <?php echo e(\Carbon\Carbon::parse($prova->data_fechamento)->format('d/m/Y H:i')); ?></p>
                    
                    <h3>Instruções do Professor</h3>
                    <p class="card-text instructions-text">
                        <?php echo $prova->instrucoes ? nl2br(e($prova->instrucoes)) : 'Nenhuma instrução específica fornecida.'; ?>

                    </p>

                    <div class="button-group">
                        <form id="form-iniciar-prova" action="<?php echo e(route('aluno.provas.iniciar', $prova)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="button" class="btn btn-primary-custom" id="iniciarProvaBtn">
                                <i class='bx bx-play-circle'></i> Iniciar Prova Agora
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <aside class="sidebar-column">
                <div class="card status-card">
                    <h3><i class='bx bx-status'></i> Status</h3>
                    
                    <?php
                        // ... (A lógica PHP foi mantida) ...
                        $agora = \Carbon\Carbon::now();
                        $statusClass = 'pending'; 
                        $statusText = 'Pendente';
                        $msg = 'Você ainda não iniciou.';

                        $tentativa = Auth::user()->tentativasProvas()->where('prova_id', $prova->id)->first();

                        if ($tentativa) {
                            if ($tentativa->hora_fim) {
                                $statusClass = 'realizada'; $statusText = 'Concluída'; $msg = 'Prova finalizada.';
                            } else {
                                $statusClass = 'iniciada'; $statusText = 'Em Andamento'; $msg = 'Prova em curso. Retome agora!';
                            }
                        } elseif ($agora->isAfter($prova->data_fechamento)) {
                             $statusClass = 'fechada'; $statusText = 'Expirada'; $msg = 'O prazo acabou.';
                        }
                    ?>

                    <div class="status-badge <?php echo e($statusClass); ?>">
                        <i class='bx bx-info-circle'></i> <?php echo e($statusText); ?>

                    </div>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-top:10px;"><?php echo e($msg); ?></p>
                </div>

                <div class="card">
                    <h3><i class='bx bxs-award'></i> Pontuação</h3>
                    <?php if($tentativa && $tentativa->hora_fim): ?>
                        <div style="text-align:center;">
                            <span class="final-score">
                                <?php echo e($tentativa->pontuacao_final ?? '?'); ?>

                            </span>
                            <small class="score-label">Pontos obtidos</small>
                            <a href="<?php echo e(route('aluno.provas.resultado', $tentativa->id)); ?>" class="link-resultado">Ver Gabarito &rarr;</a>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center; color:var(--text-muted); padding:10px 0;">
                            Disponível após conclusão.
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iniciarProvaBtn = document.getElementById('iniciarProvaBtn');
            const form = document.getElementById('form-iniciar-prova');

            if (iniciarProvaBtn) {
                iniciarProvaBtn.addEventListener('click', function(event) {
                    event.preventDefault(); // Impede a submissão nativa

                    Swal.fire({
                        // Removido o Emoji ⚠️
                        title: 'Confirmação de Início',
                        text: "Você está prestes a iniciar a prova. O tempo de duração de <?php echo e($prova->duracao_minutos); ?> minutos começará a contar imediatamente. Deseja prosseguir?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1a62ff',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Sim, Iniciar Prova!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/provaShow.blade.php ENDPATH**/ ?>