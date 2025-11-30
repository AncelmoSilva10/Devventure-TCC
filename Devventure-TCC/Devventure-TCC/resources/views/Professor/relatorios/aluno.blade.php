<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de {{ $aluno->nome }}</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/relatorioAluno.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>


    <header class="reports-header">
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('turmas.especificaID', $turma) }}" class="back-link">
                    <i class='bx bx-chevron-left'></i> Voltar para Turma
                </a>
                
                <div class="header-info">
                    <h1>Relatório Individual</h1>
                    <p>{{ $aluno->nome }} - {{ $turma->nome_turma }}</p>
                </div>
            </div>

            <div class="header-actions">
                <span class="export-label" style="color: rgba(255,255,255,0.9); font-weight: 600; font-size: 0.9rem;">Exportar:</span>
                
                <a href="{{ route('professor.relatorios.exportarIndividual', ['turma' => $turma->id, 'aluno' => $aluno->id, 'formato' => 'pdf']) }}" class="btn-export pdf" target="_blank">
                    <i class='bx bxs-file-pdf'></i> PDF
                </a>
                
                <a href="{{ route('professor.relatorios.exportarIndividual', ['turma' => $turma->id, 'aluno' => $aluno->id, 'formato' => 'csv']) }}" class="btn-export csv">
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
                                @forelse($aluno->respostasExercicios as $resposta)
                                    <tr>
                                        <td>{{ $resposta->exercicio->nome }}</td>
                                        <td>{{ $resposta->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $resposta->nota ?? 'N/A' }}</td>
                                        <td>
                                            @if($resposta->conceito)
                                                <span class="conceito-tag conceito-{{ strtolower($resposta->conceito) }}">{{ $resposta->conceito }}</span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-message">Nenhum exercício entregue.</td>
                                    </tr>
                                @endforelse
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
                                @forelse($aluno->tentativasProvas as $tentativa)
                                    @php
                                        $respostas = $tentativa->respostasQuestoes;
                                        $acertos = $respostas->where('correta', true)->count();
                                        $erros = $respostas->where('correta', false)->count();
                                        $totalQuestoes = $respostas->count();
                                    @endphp
                                    <tr>
                                        <td>{{ $tentativa->prova->titulo ?? 'N/A' }}</td>
                                        <td>{{ $tentativa->hora_fim ? $tentativa->hora_fim->format('d/m/Y') : 'N/A' }}</td>
                                        <td><strong>{{ $tentativa->pontuacao_final ?? 'N/A' }}</strong></td>
                                        <td>
                                            @if($totalQuestoes > 0)
                                                <div style="font-size: 0.85rem;">
                                                    <span class="text-success"><i class='bx bx-check'></i> {{ $acertos }}</span> &nbsp;|&nbsp; 
                                                    <span class="text-danger"><i class='bx bx-x'></i> {{ $erros }}</span>
                                                </div>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($erros > 0)
                                                <ul class="erros-detalhes-list">
                                                    @foreach ($respostas->where('correta', false) as $respostaQuestao)
                                                        <li>
                                                            <i class='bx bxs-x-circle'></i> 
                                                            {{ Str::limit($respostaQuestao->provaQuestao->enunciado ?? 'Questão removida', 40) }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="nenhum-erro"><i class='bx bx-check-circle'></i> Gabaritou!</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty-message">Nenhuma prova finalizada.</td>
                                    </tr>
                                @endforelse
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
                                @php $aulasConcluidas = 0; @endphp
                                @forelse($aluno->aulas as $aula)
                                    @if($aula->pivot->status == 'concluido')
                                    @php $aulasConcluidas++; @endphp
                                    <tr>
                                        <td>{{ $aula->titulo }}</td>
                                        <td><i class='bx bx-check-double' style="color:var(--success-color)"></i> {{ \Carbon\Carbon::parse($aula->pivot->concluido_em)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endif
                                @empty
                                    @endforelse

                                @if($aulasConcluidas == 0)
                                    <tr>
                                        <td colspan="2" class="empty-message">Nenhuma aula assistida ainda.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                
                <div class="card summary-card">
                    <img src="{{ $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/150?u='.$aluno->id }}" alt="Avatar" class="summary-avatar">
                    <h3>{{ $aluno->nome }}</h3>
                    
                    <div class="summary-stats">
                        <div class="stat-item">
                            <strong>{{ $aluno->total_pontos }}</strong>
                            <small>Pontos Totais</small>
                        </div>
                        <div class="stat-item">
                            <strong>{{ round($aluno->respostasExercicios->avg('nota'), 1) }}</strong>
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
                        @if($aluno->respostasExercicios->count() > 1)
                            <canvas id="notasAlunoChart"></canvas>
                        @else
                            <div class="empty-message">
                                <p>Dados insuficientes para gerar o gráfico.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <script>
        @if($aluno->respostasExercicios->count() > 1)
            const ctx = document.getElementById('notasAlunoChart').getContext('2d');
            
            // Gradiente Verde
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(0, 121, 107, 0.4)');
            gradient.addColorStop(1, 'rgba(0, 121, 107, 0.0)');

            const notasData = {
                labels: [
                    @foreach($aluno->respostasExercicios as $resposta)
                        '{{ Str::limit($resposta->exercicio->nome, 10) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Nota',
                    data: [
                        @foreach($aluno->respostasExercicios as $resposta)
                            {{ $resposta->nota ?? 0 }},
                        @endforeach
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
        @endif
    </script>

</body>
</html>