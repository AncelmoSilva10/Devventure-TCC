<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - {{ $turma->nome_turma }}</title>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- CSS (Substitua pelo caminho do seu arquivo atualizado) -->
    <link rel="stylesheet" href="{{ asset('css/Aluno/alunoRanking.css') }}">
</head>

<body>

    @include('layouts.navbar')

    <main class="ranking-wrapper">

        <!-- NOVO HEADER (Estilo Painel do Aluno) -->
        <header class="page-header">
            <a href="{{ $backRoute }}" class="btn-voltar-link">
                <i class="fas fa-arrow-left"></i> Voltar para Turma
            </a>
            <h1>Ranking da Turma</h1>
            <p>Confira o desempenho geral em {{ $turma->nome_turma }}</p>
        </header>

        <div class="ranking-container">
            <div class="ranking-card">
                
                <!-- Cabeçalho interno do card -->
                <div class="card-internal-header">
                    <h2>Classificação Geral</h2>
                    <p>{{ count($alunosRanking) }} alunos participantes</p>
                </div>

                <div class="ranking-list">
                    
                    @foreach($alunosRanking as $index => $aluno)
                        @php
                            $pos = $index + 1;
                            $isCurrentUser = Auth::guard('aluno')->check() && $aluno->id == Auth::guard('aluno')->id();
                            
                            // Classes de estilo
                            $rowClass = 'pos-other';
                            if($pos == 1) $rowClass = 'pos-1';
                            if($pos == 2) $rowClass = 'pos-2';
                            if($pos == 3) $rowClass = 'pos-3';
                            if($isCurrentUser) $rowClass .= ' current-user';

                            // Avatar
                            $avatarUrl = $aluno->avatar ? asset('storage/' . $aluno->avatar) : 'https://i.pravatar.cc/150?u='.$aluno->id;
                            
                            // Cor da nota
                            $scoreColor = $aluno->total_pontos > 0 ? 'text-green' : 'text-blue'; 
                        @endphp

                        <div class="rank-item {{ $rowClass }}">
                            <!-- Posição -->
                            <div class="rank-pos">
                                @if($pos <= 3) 
                                    <i class="fas fa-medal medal-icon"></i> 
                                @endif
                                {{ $pos }}º
                            </div>

                            <!-- Avatar -->
                            <img src="{{ $avatarUrl }}" alt="{{ $aluno->nome }}" class="student-avatar">

                            <!-- Informações do Aluno -->
                            <div class="student-info">
                                <span class="student-name">
                                    {{ $aluno->nome }}
                                    @if($isCurrentUser)
                                        <span class="badge-you">VOCÊ</span>
                                    @endif
                                </span>
                                
                                <!-- Meta Dados (Vindos do Controller atualizado) -->
                                <div class="student-meta">
                                    <span title="Frequência na Turma">
                                        <i class="far fa-calendar-check"></i> 
                                        Frequência: {{ $aluno->frequencia_formatada ?? '0%' }}
                                    </span>
                                    <span class="badge-pill" title="Exercícios Concluídos">
                                        {{ $aluno->exercicios_concluidos ?? 0 }}/{{ $aluno->total_exercicios_turma ?? 0 }} exercícios
                                    </span>
                                </div>
                            </div>

                            <!-- Pontuação -->
                            <div class="student-score-box">
                                <div class="score-val {{ $scoreColor }}">{{ $aluno->total_pontos }}</div>
                                <div class="score-label">PONTOS</div>
                            </div>
                        </div>

                    @endforeach

                </div>
            </div>
        </div>

    </main>

    @include('layouts.footer')

</body>
</html>