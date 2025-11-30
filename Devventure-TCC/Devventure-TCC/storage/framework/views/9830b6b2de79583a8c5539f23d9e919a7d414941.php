<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Exercícios</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Professor/exercicioProfessor.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="main-wrapper">
        
        <header class="page-header">
            <div class="header-container">
                <div class="header-left">
                    <a href="/professorDashboard" class="back-link">
                        
                        <i class='bx bx-arrow-back'></i> 
                        Voltar ao Painel
                    </a>
                    
                    <div class="header-info">
                        <h1>Meus Exercícios</h1>
                        <p>Gerencie as atividades curriculares das suas turmas.</p>
                    </div>
                </div>

                <div class="header-actions">
                    <button class="btn-novo-header" onclick="document.getElementById('modal').style.display='flex'">
                        <i class='bx bx-plus-circle'></i> Criar Exercício
                    </button>
                </div>
            </div>
        </header>

        <main class="content-body">
            
            <section class="stats-bar">
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Total Listado</span>
                        <strong><?php echo e($exercicios->count()); ?></strong>
                    </div>
                    <i class='bx bx-list-ul stat-icon'></i>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Filtro Atual</span>
                        <strong><?php echo e(ucfirst($status ?? 'Geral')); ?></strong>
                    </div>
                    <i class='bx bx-filter-alt stat-icon'></i>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Data de Hoje</span>
                        <strong style="font-size: 1.2rem;"><?php echo e(date('d/m/Y')); ?></strong>
                    </div>
                    <i class='bx bx-calendar stat-icon'></i>
                </div>
            </section>

            <section class="toolbar">
                <div class="toggle-group">
                    <a href="<?php echo e(url('/professorExercicios')); ?>?status=disponiveis" 
                       class="toggle-btn <?php echo e(request('status') == 'disponiveis' ? 'active' : ''); ?>">
                        Em Aberto
                    </a>

                    <a href="<?php echo e(url('/professorExercicios')); ?>?status=concluidas" 
                       class="toggle-btn <?php echo e(request('status') == 'concluidas' ? 'active' : ''); ?>">
                        Concluídas
                    </a>

                    <a href="<?php echo e(url('/professorExercicios')); ?>?status=todos" 
                       class="toggle-btn <?php echo e(!request('status') || request('status') == 'todos' ? 'active' : ''); ?>">
                        Todas
                    </a>
                </div>

                <form action="<?php echo e(url('/professorExercicios')); ?>" method="GET" class="search-box">
                    <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                    <input type="text" name="search" placeholder="Pesquisar por título ou turma..." value="<?php echo e(request('search')); ?>">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </section>

            <section class="grid-cards">
                <?php $__empty_1 = true; $__currentLoopData = $exercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $agora = \Carbon\Carbon::now();
                        $fechamento = \Carbon\Carbon::parse($exercicio->data_fechamento);
                        $aberto = $agora->lt($fechamento);
                        $statusClass = $aberto ? 'status-aberto' : 'status-fechado';
                        $corTexto = $aberto ? '#10b981' : '#ef4444';
                    ?>

                    <div class="card-exercicio <?php echo e($statusClass); ?>" onclick="window.location='<?php echo e(route('professor.exercicios.respostas', $exercicio)); ?>'">
                        <div class="card-status-bar"></div>

                        <div class="card-header">
                            <span class="turma-tag"><?php echo e($exercicio->turma->nome_turma); ?> • <?php echo e(ucfirst($exercicio->turma->turno)); ?></span>
                            <div class="pontos-badge">
                                <i class='bx bxs-star'></i> <?php echo e($exercicio->pontos ?? 0); ?> pts
                            </div>
                        </div>

                        <div class="card-body">
                            <i class='bx bx-file-blank card-icon-bg'></i>
                            <h3 class="card-title"><?php echo e($exercicio->nome); ?></h3>

                            <div class="dates-grid">
                                <div class="date-item">
                                    <span class="date-label">Publicação</span>
                                    <span class="date-value">
                                        <i class='bx bx-calendar-check'></i> 
                                        <?php echo e(\Carbon\Carbon::parse($exercicio->data_publicacao)->format('d/m H:i')); ?>

                                    </span>
                                </div>
                                <div class="date-item">
                                    <span class="date-label">Entrega</span>
                                    <span class="date-value" style="color: <?php echo e($corTexto); ?>">
                                        <i class='bx bx-time-five'></i> 
                                        <?php echo e($fechamento->format('d/m H:i')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="anexos-count">
                                <?php if($exercicio->imagensApoio->count() > 0 || $exercicio->arquivosApoio->count() > 0): ?>
                                    <i class='bx bx-paperclip'></i> 
                                    <?php echo e($exercicio->imagensApoio->count() + $exercicio->arquivosApoio->count()); ?> Anexos
                                <?php else: ?>
                                    <span style="opacity: 0.5;">Sem anexos</span>
                                <?php endif; ?>
                            </div>
                            <span class="btn-detalhes">
                                Gerenciar <i class='bx bx-chevron-right'></i>
                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state" style="grid-column: 1/-1; text-align:center; padding:4rem; background:white; border-radius:12px; border:2px dashed #ccc;">
                        <i class='bx bx-ghost' style="font-size:4rem; color:#ccc;"></i>
                        <h3 style="color:#666; margin-bottom:0.5rem;">Nenhum exercício encontrado</h3>
                        <p style="color:#888;">Tente mudar os filtros ou crie uma nova atividade.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <?php if($errors->any()): ?>
                <div style="background:#fee2e2; color:#b91c1c; padding:1rem; border-radius:8px; margin-bottom:1.5rem;">
                    <strong>Erro ao criar:</strong>
                    <ul style="margin-top:0.5rem; padding-left:1.5rem;"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('professor.exercicios.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <h2>Novo Exercício</h2>

                <div class="form-group">
                    <label>Título da Atividade</label>
                    <input name="nome" type="text" class="form-control" placeholder="Ex: Lista de Exercícios 01" required />
                </div>

                <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label>Turma</label>
                        <select name="turma_id" class="form-control" required>
                            <option value="" disabled selected>Selecione...</option>
                            <?php $__currentLoopData = $turmas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $turma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($turma->id); ?>"><?php echo e($turma->nome_turma); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pontos</label>
                        <input name="pontos" type="number" class="form-control" value="10" min="0" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" placeholder="Instruções..."></textarea>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label>Data de Abertura</label>
                        <input name="data_publicacao" type="datetime-local" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Data de Entrega</label>
                        <input name="data_fechamento" type="datetime-local" class="form-control" required />
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem; margin-top:1rem;">
                    <label class="upload-box" for="arquivos_apoio">
                        <i class='bx bx-file'></i>
                        <p id="txt-arquivos">Adicionar Arquivos</p>
                        <input type="file" name="arquivos_apoio[]" id="arquivos_apoio" multiple style="display:none" onchange="updateLabel(this, 'txt-arquivos', 'arquivos')">
                    </label>

                    <label class="upload-box" for="imagens_apoio">
                        <i class='bx bx-image'></i>
                        <p id="txt-imagens">Adicionar Imagens</p>
                        <input type="file" name="imagens_apoio[]" id="imagens_apoio" accept="image/*" multiple style="display:none" onchange="updateLabel(this, 'txt-imagens', 'imagens')">
                    </label>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modal').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn-confirm">Criar Atividade</button>
                </div>
            </form>
        </div>
    </div>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="<?php echo e(asset('js/Professor/exercicioProfessor.js')); ?>"></script>
    <script>
        function updateLabel(input, labelId, type) {
            const label = document.getElementById(labelId);
            if(input.files.length > 0) {
                label.innerText = input.files.length + (type === 'imagens' ? " imagem(ns)" : " arquivo(s)");
                label.style.fontWeight = "bold";
                label.style.color = "#00796B";
            } else {
                label.innerText = type === 'imagens' ? "Adicionar Imagens" : "Adicionar Arquivos";
            }
        }

        <?php if($errors->any()): ?>
            document.getElementById('modal').style.display = 'flex';
        <?php endif; ?>

        <?php if(session('sweet_success')): ?>
            Swal.fire({
                title: "Sucesso!",
                text: "<?php echo e(session('sweet_success')); ?>",
                icon: "success",
                confirmButtonColor: "#00796B"
            });
        <?php endif; ?>
    </script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/Exercicio.blade.php ENDPATH**/ ?>