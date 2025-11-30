<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corrigir: {{ $exercicio->nome }}</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/Professor/respostasExercicio.css') }}" rel="stylesheet">

    <style>
        /* Estilo limpo para o botão de arquivo */
        .arquivo-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #0f766e;
            background-color: #f0fdfa;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #ccfbf1;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
            width: auto; /* Não precisa mais ocupar tudo */
        }

        .arquivo-link:hover {
            background-color: #0f766e;
            color: #ffffff;
            border-color: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.1);
        }

        .arquivo-link i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    <div class="correcao-wrapper">
        
        <header class="turma-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="{{ url('/professorExercicios') }}" class="back-link">
                        <i class='bx bx-chevron-left'></i> Voltar
                    </a>
                    <div class="header-info">
                        <h1>{{ $exercicio->nome }}</h1>
                        <p>Turma: {{ $exercicio->turma->nome_turma }} | Pontos: {{ $exercicio->pontos }}</p>
                    </div>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn-delete" onclick="confirmarExclusao()">
                        <i class='bx bx-trash'></i> Excluir Exercício
                    </button>
                    
                    <form id="form-excluir" action="{{ route('professor.exercicios.destroy', $exercicio->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="respostas-grid">
                @forelse ($exercicio->respostas as $resposta)
                    <div class="card-aluno">
                        <div class="aluno-info">
                            <img src="{{ $resposta->aluno->avatar ? asset('storage/' . $resposta->aluno->avatar) : 'https://i.pravatar.cc/150?u='.$resposta->aluno->id }}" alt="Avatar" class="avatar">
                            <div class="aluno-details">
                                <h4>{{ $resposta->aluno->nome }}</h4>
                                <small>Enviado em: {{ $resposta->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="arquivos-enviados">
                            <h5>Arquivo Enviado:</h5>
                            @forelse($resposta->arquivos as $arquivo)
                                
                                @php
                                    // Pega a extensão do arquivo (ex: pdf, png, docx)
                                    $extensao = pathinfo($arquivo->arquivo_path, PATHINFO_EXTENSION);
                                @endphp

                                <a href="{{ asset('storage/' . $arquivo->arquivo_path) }}" 
                                   target="_blank" 
                                   class="arquivo-link" 
                                   download>
                                   
                                    <i class='bx bxs-file-blank'></i> 
                                    
                                    Visualizar Arquivo (.{{ $extensao }})
                                </a>

                            @empty
                                <p style="font-size:0.9rem; color:#64748b; font-style:italic;">Apenas texto.</p>
                            @endforelse
                        </div>

                        <form action="{{ route('professor.respostas.avaliar', $resposta->id) }}" method="POST" class="form-avaliacao">
                            @csrf
                            <h5>Avaliação</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Conceito</label>
                                    <select name="conceito" required>
                                        <option value="" disabled {{ !$resposta->conceito ? 'selected' : '' }}>Selecione</option>
                                        <option value="MB" {{ $resposta->conceito == 'MB' ? 'selected' : '' }}>MB</option>
                                        <option value="B" {{ $resposta->conceito == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="R" {{ $resposta->conceito == 'R' ? 'selected' : '' }}>R</option>
                                        <option value="I" {{ $resposta->conceito == 'I' ? 'selected' : '' }}>I</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nota (0-{{ $exercicio->pontos }})</label>
                                    <input type="number" name="nota" value="{{ $resposta->nota }}" max="{{ $exercicio->pontos }}" min="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Feedback</label>
                                <textarea name="feedback" rows="2" placeholder="Comentário...">{{ $resposta->feedback }}</textarea>
                            </div>
                            <button type="submit" class="btn-salvar-avaliacao">
                                <i class='bx bx-check'></i> Salvar
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class='bx bx-inbox'></i>
                        <p>Nenhuma resposta enviada ainda.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    @include('layouts.footer')

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

        @if (session('sweet_success'))
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success',
                title: "{{ session('sweet_success') }}",
                showConfirmButton: false, timer: 3000, timerProgressBar: true
            });
        @endif
    </script>
</body>
</html>