<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($turma->nome_turma); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo e(asset('css/Aluno/alunoTurmaEspecifica.css')); ?>" rel="stylesheet">

    <style>
        /* Classe para quando tem notificação */
        .tab-link.notification-active {
            background-color: #ff9f43 !important; /* Laranja */
            color: #fff !important;
            border-color: #ff9f43 !important;
            position: relative;
            animation: pulse-orange 2s infinite;
            font-weight: 600;
        }

        .tab-link.notification-active i {
            animation: ring-bell 2s infinite ease-in-out;
        }

        /* Animação de Pulsar */
        @keyframes pulse-orange {
            0% { box-shadow: 0 0 0 0 rgba(255, 159, 67, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 159, 67, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 159, 67, 0); }
        }

        /* Animação do Sininho */
        @keyframes ring-bell {
            0% { transform: rotate(0); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-15deg); }
            30% { transform: rotate(10deg); }
            40% { transform: rotate(-10deg); }
            50% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }
    </style>
</head>
<body>
    
    <div class="turma-wrapper">
        <header class="turma-header">
            <div class="header-overlay"></div>
            <div class="header-content">
                <a href="<?php echo e(route('aluno.turma')); ?>" class="back-link"><i class='bx bx-chevron-left'></i> Voltar</a>
                <div class="header-info">
                    <h1><?php echo e($turma->nome_turma); ?></h1>
                    <p>Professor(a): <?php echo e($turma->professor->nome); ?></p>
                </div>
                <div class="header-stats">
                    <div class="stat-item"><i class='bx bxs-group'></i><span><?php echo e($alunos->total()); ?> Alunos</span></div>
                    <div class="stat-item"><i class='bx bxs-book-content'></i><span><?php echo e($exercicios->total()); ?> Exercícios</span></div>
                    <div class="stat-item"><i class='bx bxs-videos'></i><span><?php echo e($aulas->total()); ?> Aulas</span></div>
                    <div class="stat-item"><i class='bx bxs-file-blank'></i><span><?php echo e($provas->total()); ?> Provas</span></div>
                </div>
            </div>
        </header>

        <main class="page-body">
            <div class="main-content">
                <div class="tabs-navigation">
                    <button class="tab-link <?php echo e(request('tab', 'exercicios') == 'exercicios' ? 'active' : ''); ?>" data-tab="exercicios"><i class='bx bxs-pencil'></i> Exercícios</button>
                    <button class="tab-link <?php echo e(request('tab') == 'aulas' ? 'active' : ''); ?>" data-tab="aulas"><i class='bx bxs-videos'></i> Aulas</button>
                    
                    <?php
                        // Pega a data do aviso mais recente (se houver) para comparar no JavaScript
                        // Assumindo que $avisos vem ordenado do mais recente para o mais antigo
                        $ultimoAviso = $avisos->first();
                        $timestampUltimoAviso = $ultimoAviso ? $ultimoAviso->created_at->timestamp : 0;
                    ?>

                    <button id="btnTabAvisos" 
                            class="tab-link <?php echo e(request('tab') == 'avisos' ? 'active' : ''); ?>" 
                            data-tab="avisos"
                            data-latest-aviso="<?php echo e($timestampUltimoAviso); ?>"
                            data-turma-id="<?php echo e($turma->id); ?>">
                        <i class='bx bxs-bell'></i> Mural de Avisos
                    </button>

                    <button class="tab-link <?php echo e(request('tab') == 'provas' ? 'active' : ''); ?>" data-tab="provas"><i class='bx bxs-file-blank'></i> Provas</button>
                </div>

                <div class="tabs-content">
                    <div class="tab-pane <?php echo e(request('tab', 'exercicios') == 'exercicios' ? 'active' : ''); ?>" id="exercicios">
                        <div class="content-grid">
                            <?php $__empty_1 = true; $__currentLoopData = $exercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $statusClass = 'status-pending';
                                    $statusText = 'Pendente';
                                    if ($exercicio->respostas->isNotEmpty()) {
                                        $statusClass = 'status-delivered';
                                        $statusText = 'Concluído';
                                    } elseif (now()->isAfter($exercicio->data_fechamento)) {
                                        $statusClass = 'status-late';
                                        $statusText = 'Prazo Encerrado';
                                    }
                                ?>
                                <a href="<?php echo e(route('aluno.exercicios.mostrar', $exercicio->id)); ?>" class="exercise-card <?php echo e($statusClass); ?>">
                                    <div class="card-content">
                                        <div class="card-header">
                                            <h3><?php echo e($exercicio->nome); ?></h3>
                                            <span class="status-tag"><?php echo e($statusText); ?></span>
                                        </div>
                                        <p class="card-description"><?php echo e(Str::limit($exercicio->descricao, 100)); ?></p>
                                        <div class="card-footer">
                                            <div class="deadline-info">
                                                <i class='bx bxs-time-five'></i>
                                                <span>Entregar até: <?php echo e(\Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y')); ?></span>
                                            </div>
                                            <i class='bx bx-right-arrow-alt card-arrow'></i>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhum exercício postado nesta turma ainda.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="pagination">
                                <?php echo e($exercicios->appends(['tab' => 'exercicios'])->appends(request()->except('exerciciosPage'))->links()); ?>

                            </div>
                        </div>

                        <div class="tab-pane <?php echo e(request('tab') == 'provas' ? 'active' : ''); ?>" id="provas">
                            <div class="content-grid">
                                <?php $__empty_1 = true; $__currentLoopData = $provas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provaItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pendente';
                                        $linkRoute = route('aluno.provas.show', $provaItem->id); 
    
                                        $tentativaAluno = $provaItem->tentativas->first();
    
                                        if ($tentativaAluno) {
                                            if ($tentativaAluno->hora_fim !== null) {
                                                $statusClass = 'status-delivered';
                                                $statusText = 'Concluída';
                                                $linkRoute = route('aluno.provas.resultado', $tentativaAluno->id);
                                            } else {
                                                $statusClass = 'status-in-progress';
                                                $statusText = 'Em Andamento';
                                                $linkRoute = route('aluno.provas.fazer', $tentativaAluno->id);
                                            }
                                        } elseif (now()->isBefore($provaItem->data_abertura)) {
                                            $statusClass = 'status-upcoming';
                                            $statusText = 'Em Breve';
                                            $linkRoute = '#';
                                        } elseif (now()->isAfter($provaItem->data_fechamento)) {
                                            $statusClass = 'status-late';
                                            $statusText = 'Prazo Encerrado';
                                            $linkRoute = '#';
                                        }
                                    ?>
                                    <a href="<?php echo e($linkRoute); ?>" class="exercise-card <?php echo e($statusClass); ?>">
                                        <div class="card-content">
                                            <div class="card-header">
                                                <h3><?php echo e($provaItem->titulo); ?></h3>
                                                <span class="status-tag"><?php echo e($statusText); ?></span>
                                            </div>
                                            <p class="card-description"><?php echo e(Str::limit($provaItem->instrucoes, 100)); ?></p>
                                            <div class="card-footer">
                                                <div class="deadline-info">
                                                    <i class='bx bxs-hourglass-bottom'></i>
                                                    <span>Duração: <?php echo e($provaItem->duracao_minutos); ?> min</span>
                                                </div>
                                                <div class="deadline-info">
                                                    <i class='bx bxs-time-five'></i>
                                                    <span>Fecha em: <?php echo e(\Carbon\Carbon::parse($provaItem->data_fechamento)->format('d/m/Y H:i')); ?></span>
                                                </div>
                                                <i class='bx bx-right-arrow-alt card-arrow'></i>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="empty-state">
                                        <i class='bx bx-info-circle'></i>
                                        <p>Nenhuma prova postada nesta turma ainda.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="pagination">
                                <?php echo e($provas->appends(['tab' => 'provas'])->appends(request()->except('provasPage'))->links()); ?>

                            </div>
                        </div>

                    <div class="tab-pane <?php echo e(request('tab') == 'aulas' ? 'active' : ''); ?>" id="aulas">
                        <div class="content-grid">
                            <?php $__empty_1 = true; $__currentLoopData = $aulas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('aulas.view', $aula)); ?>" class="lesson-card">
                                    <div class="card-content">
                                        <div class="lesson-icon">
                                            <i class='bx bxs-movie-play'></i>
                                        </div>
                                        <div class="lesson-info">
                                            <h3><?php echo e($aula->titulo); ?></h3>
                                            <p>Clique para assistir à aula</p>
                                        </div>
                                        <i class='bx bx-right-arrow-alt card-arrow'></i>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                 <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhuma aula disponível.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pagination">
                             <?php echo e($aulas->appends(['tab' => 'aulas'])->appends(request()->except('aulasPage'))->links()); ?>

                        </div>
                    </div>

                    <div class="tab-pane <?php echo e(request('tab') == 'avisos' ? 'active' : ''); ?>" id="avisos">
                        <div class="avisos-list">
                             <?php $__empty_1 = true; $__currentLoopData = $avisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aviso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="card-aviso">
                                    <div class="card-aviso-header">
                                        <h3><?php echo e($aviso->titulo); ?></h3>
                                        <span class="data-aviso">
                                            <?php echo e($aviso->created_at->diffForHumans()); ?> </span>
                                    </div>
                                    <div class="card-aviso-body">
                                        <p><?php echo nl2br(e($aviso->conteudo)); ?></p>
                                    </div>
                                    <div class="card-aviso-footer">
                                        <span>Enviado por: <?php echo e($aviso->professor->nome); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="empty-state">
                                    <i class='bx bx-info-circle'></i>
                                    <p>Nenhum aviso postado nesta turma ainda.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pagination">
                             <?php echo e($avisos->appends(['tab' => 'avisos'])->appends(request()->except('avisosPage'))->links()); ?>

                        </div>
                    </div>

                </div>
            </div>

            <aside class="sidebar">
                <div class="card ranking-card">
                    <a href="<?php echo e(route('aluno.turma.ranking', $turma)); ?>" class="btn-ranking">
                        <i class='bx bxs-bar-chart-alt-2'></i>
                        <span>Ver Ranking da Turma</span>
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-section">
                        <h2><i class='bx bxs-group'></i> Colegas de Turma</h2>
                        <ul class="classmates-list">
                            <?php $__empty_1 = true; $__currentLoopData = $alunos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alunoItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> <li>
                                    <img src="<?php echo e($alunoItem->avatar ? asset('storage/' . $alunoItem->avatar) : 'https://i.pravatar.cc/40?u='.$alunoItem->id); ?>" alt="Avatar" class="avatar">
                                    <span><?php echo e($alunoItem->nome); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="empty-message">Nenhum outro aluno na turma.</li>
                            <?php endif; ?>
                        </ul>
                         <div class="pagination">
                             <?php echo e($alunos->appends(request()->except('colegasPage'))->links()); ?>

                        </div>
                    </div>
                </div>
            </aside>
        </main>
    </div>
    
    <script>
        // --- 1. Lógica das Abas ---
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                const tab = link.getAttribute('data-tab');

                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(item => item.classList.remove('active'));

                link.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });

        // --- 2. Lógica de Notificação (Inteligente) ---
        document.addEventListener('DOMContentLoaded', function() {
            const btnAvisos = document.getElementById('btnTabAvisos');
            const iconAvisos = btnAvisos.querySelector('i');
            
            // Pega o timestamp (data) do último aviso enviado pelo PHP
            const serverLatest = parseInt(btnAvisos.getAttribute('data-latest-aviso'));
            const turmaId = btnAvisos.getAttribute('data-turma-id');
            const storageKey = 'ultimo_aviso_visto_turma_' + turmaId;

            // Pega o que está salvo no navegador do aluno
            const localLatest = localStorage.getItem(storageKey);

            // SE a data do servidor for maior que a do local (tem aviso novo) 
            // E a data não for 0 (tem algum aviso)
            // E a aba atual não for 'avisos'
            const currentTab = "<?php echo e(request('tab')); ?>"; // Pega a aba atual do PHP para o primeiro load
            
            if (serverLatest > 0 && (!localLatest || serverLatest > localLatest)) {
                
                // Se ele já carregou na aba de avisos, atualiza direto e não mostra laranja
                if (currentTab === 'avisos') {
                    localStorage.setItem(storageKey, serverLatest);
                } else {
                    // Senão, ativa o modo laranja
                    btnAvisos.classList.add('notification-active');
                    iconAvisos.classList.remove('bxs-bell');
                    iconAvisos.classList.add('bxs-bell-ring');
                }
            }

            // Quando clica no botão para ver
            btnAvisos.addEventListener('click', function() {
                // Remove a cor laranja
                this.classList.remove('notification-active');
                iconAvisos.classList.remove('bxs-bell-ring');
                iconAvisos.classList.add('bxs-bell');

                // Salva no navegador que ele já viu esse aviso
                localStorage.setItem(storageKey, serverLatest);
            });
        });
    </script>

    <?php if(session('sweet_success')): ?>
    <script>
        Swal.fire({
            title: 'Parabéns!',
            text: "<?php echo e(session('sweet_success')); ?>",
            icon: 'success',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Ótimo!'
        });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Aluno/turmaEspecifica.blade.php ENDPATH**/ ?>