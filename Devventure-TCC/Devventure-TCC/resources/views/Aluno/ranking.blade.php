<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - {{ $turma->nome_turma }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Aluno/alunoRanking.css') }}">

    @if(request()->is('professor*'))
    <style>
        :root {
            --primary-blue: #00796b !important;
            --primary-hover: #004d40 !important;
            --primary-light: #e0f2f1 !important;
            --score-blue: #00796b !important;
        }
        .turma-header {
            background: linear-gradient(135deg, #00796b, #004d40) !important;
            box-shadow: 0 6px 20px rgba(0, 121, 107, 0.2) !important;
        }
        .back-link:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }
        .header-badge {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        /* Cor da nota */
        .student-score-box .score-val {
            color: #00796b !important;
        }
        /* Ícones da lista */
        .student-meta i {
            color: #00796b !important;
        }
        /* Seleção do item */
        .rank-item.current-user {
            background-color: #e0f2f1 !important;
            border-left-color: #00796b !important;
        }
        .rank-item:hover {
            border-color: #b2dfdb !important;
        }
    </style>
    @endif
</head>

<body>


    <div class="ranking-wrapper">

        <header class="turma-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="{{ $backRoute }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    
                    <div class="header-info">
                        <h1>Ranking da Turma</h1>
                        <p>{{ $turma->nome_turma }} - Professor(a): {{ $turma->professor->nome ?? 'Docente' }}</p>
                    </div>
                </div>

                <div class="header-actions">
                    <div class="header-badge">
                        <i class="fas fa-trophy"></i> {{ count($alunosRanking) }} Alunos
                    </div>
                </div>
            </div>
        </header>

        <main class="ranking-container">
            <div class="ranking-card">
                
                <div class="card-header">
                    <div>
                        <h2>Classificação Geral</h2>
                        <p>Baseado na pontuação total acumulada</p>
                    </div>
                </div>

                <div class="ranking-list">
                    
                    @foreach($alunosRanking as $index => $aluno)
                        @php
                            $pos = $index + 1;
                            
                            // Verifica se é Aluno Logado (para destacar "VOCÊ")
                            $isCurrentUser = false;
                            if(Auth::guard('aluno')->check()) {
                                $isCurrentUser = Auth::guard('aluno')->id() == $aluno->id;
                            }
                            
                            $rowClass = 'pos-other';
                            if($pos == 1) $rowClass = 'pos-1';
                            if($pos == 2) $rowClass = 'pos-2';
                            if($pos == 3) $rowClass = 'pos-3';
                            
                            if($isCurrentUser) $rowClass .= ' current-user';

                            $avatarUrl = $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/150?u='.$aluno->id;
                            
                            // Detecção pela URL também no PHP
                            $isProfessorRoute = request()->is('professor*');
                            
                            $scoreColor = $aluno->total_pontos > 0 ? 'text-green' : 'text-blue'; 
                        @endphp

                        <div class="rank-item {{ $rowClass }}">
                            <div class="rank-pos">
                                @if($pos <= 3) 
                                    <i class="fas fa-medal medal-icon"></i> 
                                @endif
                                {{ $pos }}º
                            </div>

                            <img src="{{ $avatarUrl }}" alt="{{ $aluno->nome }}" class="student-avatar">

                            <div class="student-info">
                                <span class="student-name">
                                    {{ $aluno->nome }}
                                    @if($isCurrentUser)
                                        <span class="badge-you">VOCÊ</span>
                                    @endif
                                </span>
                                
                                <div class="student-meta">
                                    @if($isProfessorRoute && isset($aluno->frequencia_formatada))
                                        <span title="Frequência"><i class="far fa-calendar-check"></i> {{ $aluno->frequencia_formatada }} Freq.</span>
                                        <span class="dot" style="margin:0 5px">•</span>
                                        <span title="Exercícios"><i class="far fa-check-circle"></i> {{ $aluno->exercicios_concluidos }}/{{ $aluno->total_exercicios_turma }} Ativ.</span>
                                    @else
                                        <span>Aluno matriculado</span>
                                    @endif
                                </div>
                            </div>

                            <div class="student-score-box">
                                <div class="score-val {{ $scoreColor }}">
                                    {{ $aluno->total_pontos }}
                                </div>
                                <div class="score-label">PONTOS</div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </main>
    </div>

    @include('layouts.footer')

</body>
</html>