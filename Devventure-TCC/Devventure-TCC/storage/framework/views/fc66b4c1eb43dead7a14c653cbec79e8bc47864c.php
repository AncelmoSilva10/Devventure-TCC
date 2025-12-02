<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Turma: <?php echo e($turma->nome_turma); ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Professor/detalheTurma.css')); ?>" rel="stylesheet">
</head>
<body>
    
    <div class="turma-wrapper">
        
        <header class="turma-header">
            <div style="width: 100%; max-width: 1300px; margin: 0 auto;">
                <a href="<?php echo e(route('professor.turmas')); ?>" class="back-link">
                    <i class='bx bx-chevron-left'></i> Voltar para Minhas Turmas
                </a>
                <div class="header-content">
                    <div class="header-info">
                        <h1><?php echo e($turma->nome_turma); ?></h1>
                        <p>Turno: <?php echo e(ucfirst($turma->turno)); ?> | <?php echo e($turma->ano_turma ?? date('Y')); ?></p>
                    </div>
                    <div class="header-actions">
                        <button class="btn-header btn-glass" id="btnAbrirModalAula"><i class='bx bx-video-plus'></i> Nova Aula</button>
                        <button class="btn-header btn-glass" id="btnAbrirModalAluno"><i class='bx bx-user-plus'></i> Convidar</button>
                        <button class="btn-header btn-white" id="btnAbrirModalAviso"><i class='bx bx-paper-plane'></i> Enviar Aviso</button>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-body">
            
            <div class="main-content">
                <div class="card" style="height: 100%;">
                    <div class="card-header">
                        <h2><i class='bx bxs-group'></i> Alunos (<?php echo e($alunos->total()); ?>)</h2>
                        <a href="<?php echo e(route('professor.turma.ranking', $turma)); ?>" class="btn-ranking-mini">
                            <i class='bx bxs-bar-chart-alt-2'></i> Ver Ranking
                        </a>
                    </div>
                    
                    <ul class="student-list">
                        <?php $__empty_1 = true; $__currentLoopData = $alunos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('professor.relatorios.aluno', ['turma' => $turma, 'aluno' => $aluno])); ?>" class="student-item">
                                <div class="student-info">
                                    <img src="<?php echo e($aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/40?u='.$aluno->id); ?>" alt="Avatar" class="avatar">
                                    <span><?php echo e($aluno->nome); ?></span>
                                </div>
                                <div class="student-progress">
                                    <small><?php echo e($aluno->progresso_percentual ?? 0); ?>%</small>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: <?php echo e($aluno->progresso_percentual ?? 0); ?>%;"></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="empty-message">Nenhum aluno matriculado.</li>
                        <?php endif; ?>
                    </ul>

                    <div class="pagination">
                        <?php echo e($alunos->appends(request()->except('alunosPage'))->links()); ?>

                    </div>
                </div>
            </div>

            <div class="sidebar-column">
                
                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-spreadsheet'></i> Exercícios</h2></div>
                    <ul class="content-list">
                        <?php $__empty_1 = true; $__currentLoopData = $exercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('professor.exercicios.respostas', $exercicio)); ?>" class="content-item">
                                <div class="content-item-flex" style="width: 100%;">
                                    <span><?php echo e(Str::limit($exercicio->nome, 20)); ?></span>
                                    <small><?php echo e(\Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m')); ?></small>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="empty-message">Vazio.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="pagination"><?php echo e($exercicios->appends(request()->except('exerciciosPage'))->links()); ?></div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-file-blank'></i> Provas</h2></div>
                    <ul class="content-list">
                        <?php $__empty_1 = true; $__currentLoopData = $provas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prova): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('Professor.relatorios.provaResultado', ['turma' => $turma->id, 'prova' => $prova->id])); ?>" class="content-item">
                                <div class="content-item-flex" style="width: 100%;">
                                    <span><?php echo e(Str::limit($prova->titulo, 20)); ?></span>
                                    <small><?php echo e(\Carbon\Carbon::parse($prova->data_fechamento)->format('d/m')); ?></small>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="empty-message">Vazio.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="pagination"><?php echo e($provas->appends(request()->except('provasPage'))->links()); ?></div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-bell'></i> Mural de Avisos</h2></div>
                    <ul class="avisos-list">
                        <?php $__empty_1 = true; $__currentLoopData = $avisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aviso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="aviso-item">
                                <div class="aviso-title"><?php echo e($aviso->titulo); ?></div>
                                <span class="aviso-date"><?php echo e($aviso->created_at->diffForHumans()); ?></span>
                                <div class="aviso-content"><?php echo nl2br(e(Str::limit($aviso->conteudo, 100))); ?></div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="empty-message">Nenhum aviso.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="pagination"><?php echo e($avisos->appends(request()->except('avisosPage'))->links()); ?></div>
                </aside>

                <aside class="card">
                    <div class="card-header"><h2><i class='bx bxs-time-five'></i> Histórico</h2></div>
                    <ul class="timeline">
                        <?php $__empty_1 = true; $__currentLoopData = $historico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="timeline-item">
                                <div class="timeline-icon"><i class='bx <?php echo e($item['tipo'] == 'aula' ? 'bx-video' : 'bx-file'); ?>'></i></div>
                                <div class="timeline-content">
                                    <span class="timeline-date"><?php echo e(\Carbon\Carbon::parse($item['data'])->format('d/m H:i')); ?></span>
                                    <h3><?php echo e(Str::limit($item['titulo'], 25)); ?></h3>
                                    </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="empty-message">Vazio.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="pagination"><?php echo e($historico->appends(request()->except('historicoPage'))->links()); ?></div>
                </aside>

            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalAdicionarAula">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Nova Aula</h2>
            <form action="<?php echo e(route('turmas.aulas.formsAula', $turma)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group"><label>Título</label><input type="text" name="titulo" required></div>
                <div class="form-group"><label>Link YouTube</label><input type="url" name="video_url" required></div>
                <div class="form-group"><label>Duração (Min,Seg)</label><input type="text" name="duracao_texto" placeholder="ex: 5,30" required></div>
                <div class="form-group"><label>Pontos</label><input type="number" name="pontos" value="5" required></div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Salvar</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalConvidarAluno">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Convidar Aluno</h2>
            <form action="<?php echo e(route('turmas.convidar', $turma)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group"><label>RA do Aluno</label><input type="text" name="ra" required></div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Enviar</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modalEnviarAviso">
        <div class="modal-content">
            <button type="button" class="modal-close"><i class='bx bx-x'></i></button>
            <h2>Novo Aviso</h2>
            <form action="<?php echo e(route('professor.avisos.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="turma_id" value="<?php echo e($turma->id); ?>">
                <div class="form-group"><label>Título</label><input type="text" name="titulo" required></div>
                <div class="form-group"><label>Mensagem</label><textarea name="conteudo" rows="4" required></textarea></div>
                <div class="form-group">
                    <label>Destinatários:</label>
                    <div class="recipient-toggle">
                        <label class="toggle-option active" id="tab-all">
                            <input type="radio" name="alcance" value="todos" checked onchange="toggleRecipient('all')"> Toda a Turma
                        </label>
                        <label class="toggle-option" id="tab-select">
                            <input type="radio" name="alcance" value="selecionados" onchange="toggleRecipient('select')"> Selecionar Alunos
                        </label>
                    </div>
                    <div id="student-list-container" class="student-selection-container">
                        <div class="student-checkbox-list">
                            <div class="checkbox-item" style="background:#f0f0f0;">
                                <input type="checkbox" id="selectAllStudents"> <label for="selectAllStudents"><strong>Todos</strong></label>
                            </div>
                            <?php $__currentLoopData = $alunos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="alunos[]" value="<?php echo e($aluno->id); ?>" class="student-checkbox" id="aluno_<?php echo e($aluno->id); ?>">
                                    <label for="aluno_<?php echo e($aluno->id); ?>"><?php echo e($aluno->nome); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons"><button type="button" class="btn-cancelar">Cancelar</button><button type="submit" class="btn-confirmar">Enviar</button></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <script>
        // Definição das mensagens flash para uso no JS externo (se necessário)
        window.flashMessages = {
            sweetSuccessConvite: "<?php echo e(session('sweet_success_convite')); ?>",
            sweetErrorConvite: "<?php echo e(session('sweet_error_convite')); ?>",
            sweetErrorAula: "<?php echo e(session('sweet_error_aula')); ?>"
        };

        // --- LÓGICA DE ALERTS (SWEETALERT) ---

        // 1. CENÁRIO: AULA CRIADA (Com opção de ir para o formulário)
        <?php if(session('aula_criada_feedback')): ?>
            const feedback = <?php echo json_encode(session('aula_criada_feedback'), 15, 512) ?>;
            Swal.fire({
                title: 'Aula Criada!',
                text: feedback.message, 
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#00796b',
                cancelButtonColor: '#6e7881',
                confirmButtonText: '<i class="bx bx-list-check"></i> ' + feedback.next_action_text,
                cancelButtonText: 'Pular / Concluir',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona para a criação do formulário
                    window.location.href = feedback.next_action_url;
                }
            });

        // 2. CENÁRIO: FORMULÁRIO CRIADO (Retorno do FormularioController)
        <?php elseif(session('formulario_criado_success')): ?>
            Swal.fire({
                title: 'Tudo pronto!',
                text: "<?php echo e(session('formulario_criado_success')); ?>",
                icon: 'success',
                confirmButtonColor: '#00796b'
            });

        // 3. CENÁRIO: GENÉRICO (Convites, Edições, Criação de Turma, etc)
        <?php elseif(session('sweet_success')): ?>
            Swal.fire({ 
                title: 'Sucesso!', 
                text: "<?php echo e(session('sweet_success')); ?>", 
                icon: 'success', 
                confirmButtonColor: '#00796b' 
            });

        // 4. CENÁRIO: ERROS
        <?php elseif(session('sweet_error_convite')): ?>
             Swal.fire({ 
                title: 'Atenção', 
                text: "<?php echo e(session('sweet_error_convite')); ?>", 
                icon: 'warning', 
                confirmButtonColor: '#ffbb33' 
            });
        <?php endif; ?>
    </script>
    <script src="<?php echo e(asset('js/Professor/detalheTurmaProfessor.js')); ?>"></script>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Professor/detalheTurma.blade.php ENDPATH**/ ?>