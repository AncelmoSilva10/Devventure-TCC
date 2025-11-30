<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de <?php echo e($aluno->nome); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?php echo e(asset('css/Professor/relatorioAluno.css')); ?>" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>


    <header class="reports-header">
        <div class="header-container">
            <div class="header-left">
                <a href="<?php echo e(route('turmas.especificaID', $turma)); ?>" class="back-link">
                    <i class='bx bx-chevron-left'></i> Voltar para Turma
                </a>
                
                <div class="header-info">
                    <h1>Relatório Individual</h1>
                    <p><?php echo e($aluno->nome); ?> - <?php echo e($turma->nome_turma); ?></p>
                </div>
            </div>

            <div class="header-actions">
                <span class="export-label" style="color: rgba(255,255,255,0.9); font-weight: 600; font-size: 0.9rem;">Exportar:</span>
                
                <a href="<?php echo e(route('professor.relatorios.exportarIndividual', ['turma' => $turma->id, 'aluno' => $aluno->id, 'formato' => 'pdf'])); ?>" class="btn-export pdf" target="_blank">
                    <i class='bx bxs-file-pdf'></i> PDF
                </a>
                
                <a href="<?php echo e(route('professor.relatorios.exportarIndividual', ['turma' => $turma->id, 'aluno' => $aluno->id, 'formato' => 'csv'])); ?>" class="btn-export csv">
                    <i class='bx bxs-spreadsheet'></i> Excel
                </a>
            </div>
        </div>
    </header>

    <div class="reports-wrapper">
        <main class="report-aluno-grid">
            
            <div class="report-main-content"> 
                
                <div class="card">
                    <div class="card-header">
                        <i class='bx bxs-spreadsheet'></i> 
                        <h3>Desempenho nos Exercícios</h3>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Exercício</th>
                                    <th>Data de Envio</th>
                                    <th>Pontos</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $aluno->respostasExercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resposta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($resposta->exercicio->nome); ?></td>
                                        <td><?php echo e($resposta->created_at->format('d/m/Y')); ?></td>
                                        <td><?php echo e($resposta->nota ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($resposta->conceito): ?>
                                                <span class="conceito-tag conceito-<?php echo e(strtolower($resposta->conceito)); ?>"><?php echo e($resposta->conceito); ?></span>
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="empty-message">Nenhum exercício entregue.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class='bx bxs-file-find'></i>
                        <h3>Desempenho em Provas</h3>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Prova</th>
                                    <th>Data</th>
                                    <th>Pontuação</th>
                                    <th>Resumo</th>
                                    <th>Erros</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $aluno->tentativasProvas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tentativa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $respostas = $tentativa->respostasQuestoes;
                                        $acertos = $respostas->where('correta', true)->count();
                                        $erros = $respostas->where('correta', false)->count();
                                        $totalQuestoes = $respostas->count();
                                    ?>
                                    <tr>
                                        <td><?php echo e($tentativa->prova->titulo ?? 'N/A'); ?></td>
                                        <td><?php echo e($tentativa->hora_fim ? $tentativa->hora_fim->format('d/m/Y') : 'N/A'); ?></td>
                                        <td><strong><?php echo e($tentativa->pontuacao_final ?? 'N/A'); ?></strong></td>
                                        <td>
                                            <?php if($totalQuestoes > 0): ?>
                                                <div style="font-size: 0.85rem;">
                                                    <span class="text-success"><i class='bx bx-check'></i> <?php echo e($acertos); ?></span> &nbsp;|&nbsp; 
                                                    <span class="text-danger"><i class='bx bx-x'></i> <?php echo e($erros); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($erros > 0): ?>
                                                <ul class="erros-detalhes-list">
                                                    <?php $__currentLoopData = $respostas->where('correta', false); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $respostaQuestao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <i class='bx bxs-x-circle'></i> 
                                                            <?php echo e(Str::limit($respostaQuestao->provaQuestao->enunciado ?? 'Questão removida', 40)); ?>

                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="nenhum-erro"><i class='bx bx-check-circle'></i> Gabaritou!</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="empty-message">Nenhuma prova finalizada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class='bx bxs-videos'></i>
                        <h3>Aulas Concluídas</h3>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Aula</th>
                                    <th>Conclusão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $aulasConcluidas = 0; ?>
                                <?php $__empty_1 = true; $__currentLoopData = $aluno->aulas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($aula->pivot->status == 'concluido'): ?>
                                    <?php $aulasConcluidas++; ?>
                                    <tr>
                                        <td><?php echo e($aula->titulo); ?></td>
                                        <td><i class='bx bx-check-double' style="color:var(--success-color)"></i> <?php echo e(\Carbon\Carbon::parse($aula->pivot->concluido_em)->format('d/m/Y H:i')); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>

                                <?php if($aulasConcluidas == 0): ?>
                                    <tr>
                                        <td colspan="2" class="empty-message">Nenhuma aula assistida ainda.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                
                <div class="card summary-card">
                    <img src="<?php echo e($aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/150?u='.$aluno->id); ?>" alt="Avatar" class="summary-avatar">
                    <h3><?php echo e($aluno->nome); ?></h3>
                    
                    <div class="summary-stats">
                        <div class="stat-item">
                            <strong><?php echo e($aluno->total_pontos); ?></strong>
                            <small>Pontos Totais</small>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo e(round($aluno->respostasExercicios->avg('nota'), 1)); ?></strong>
                            <small>Média Geral</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class='bx bx-line-chart'></i>
                        <h3>Evolução</h3>
                    </div>
                    <div class="chart-container">
                        <?php if($aluno->respostasExercicios->count() > 1): ?>
                            <canvas id="notasAlunoChart"></canvas>
                        <?php else: ?>
                            <div class="empty-message">
                                <p>Dados insuficientes para gerar o gráfico.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <script>
        <?php if($aluno->respostasExercicios->count() > 1): ?>
            const ctx = document.getElementById('notasAlunoChart').getContext('2d');
            
            // Gradiente Verde
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(0, 121, 107, 0.4)');
            gradient.addColorStop(1, 'rgba(0, 121, 107, 0.0)');

            const notasData = {
                labels: [
                    <?php $__currentLoopData = $aluno->respostasExercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resposta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e(Str::limit($resposta->exercicio->nome, 10)); ?>',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                datasets: [{
                    label: 'Nota',
                    data: [
                        <?php $__currentLoopData = $aluno->respostasExercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resposta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($resposta->nota ?? 0); ?>,
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    ],
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#00796b',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00796b',
                    borderWidth: 2,
                    tension: 0.4
                }]
            };

            const configAluno = {
                type: 'line',
                data: notasData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            max: 100,
                            grid: { color: '#f0f0f0' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            };

            new Chart(ctx, configAluno);
        <?php endif; ?>
    </script>

</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/relatorios/aluno.blade.php ENDPATH**/ ?>