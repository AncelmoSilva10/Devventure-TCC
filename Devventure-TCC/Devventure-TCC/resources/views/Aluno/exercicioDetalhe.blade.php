<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $exercicio->nome }}</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/Aluno/exercicioDetalhe.css') }}" rel="stylesheet">
</head>
<body>

    <div class="page-wrapper">
        
        <header class="page-header-blue">
            <div class="header-container">
                <a href="{{ route('turmas.especifica', $exercicio->turma_id) }}" class="back-link">
                    <i class='bx bx-arrow-back'></i> Voltar para a Turma
                </a>
                
                <div class="header-info">
                    <h1>{{ $exercicio->nome }}</h1>
                    <p>Prazo: {{ \Carbon\Carbon::parse($exercicio->data_fechamento)->format('d/m/Y \à\s H:i') }}</p>
                    
                    @if($respostaAnterior)
                        <div class="header-status"><i class='bx bx-check-circle'></i> Entregue</div>
                    @elseif(now()->isAfter($exercicio->data_fechamento))
                        <div class="header-status"><i class='bx bx-time'></i> Encerrado</div>
                    @else
                        <div class="header-status"><i class='bx bx-pencil'></i> Aberto</div>
                    @endif
                </div>
            </div>
        </header>

        <main class="page-content">
            
            <div class="main-left">
                <div class="card">
                    <div class="card-section">
                        <h2><i class='bx bx-text'></i> Instruções</h2>
                        <p>{{ $exercicio->descricao ?: 'Sem descrição.' }}</p>
                    </div>
                    
                    <div class="card-section">
                        <h2><i class='bx bx-paperclip'></i> Materiais de Apoio</h2>
                        <div class="materials-list">
                            @forelse($exercicio->imagensApoio as $img)
                                <a href="{{ asset('storage/' . $img->imagem_path) }}" target="_blank" class="material-item">
                                    <i class='bx bxs-image'></i> Ver Imagem
                                </a>
                            @empty
                            @endforelse

                            @forelse($exercicio->arquivosApoio as $arq)
                                <a href="{{ asset('storage/' . $arq->arquivo_path) }}" target="_blank" class="material-item">
                                    <i class='bx bxs-file-pdf'></i> {{ $arq->nome_original }}
                                </a>
                            @empty
                            @endforelse

                            @if($exercicio->imagensApoio->isEmpty() && $exercicio->arquivosApoio->isEmpty())
                                <p style="color:#999; font-style:italic;">Nenhum material anexado.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                
                @if ($respostaAnterior && $respostaAnterior->conceito)
                    <div class="card feedback-card">
                        <div class="card-section">
                            <h2><i class='bx bxs-award'></i> Avaliação</h2>
                            <div class="grade-summary">
                                <div class="grade-item">
                                    <small>Nota</small>
                                    <strong>{{ $respostaAnterior->nota }}</strong>
                                </div>
                                <div class="grade-item">
                                    <small>Conceito</small>
                                    <span class="conceito-badge">{{ $respostaAnterior->conceito }}</span>
                                </div>
                            </div>
                            @if($respostaAnterior->feedback)
                                <div class="feedback-text">
                                    <strong>Comentário:</strong><br>
                                    {{ $respostaAnterior->feedback }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card submission-card">
                    <div class="card-section">
                        <h2>Situação da Entrega</h2>
                        
                        @if ($respostaAnterior)
                            <div class="status-badge status-delivered">
                                <i class='bx bxs-check-circle'></i> Entregue para avaliação
                            </div>
                            <p class="submission-date">Enviado em: {{ $respostaAnterior->created_at->format('d/m/Y H:i') }}</p>
                            
                            <div class="submitted-files">
                                <strong>Seus arquivos:</strong>
                                <ul>
                                    @foreach ($respostaAnterior->arquivos as $arq)
                                        <li><a href="{{ asset('storage/' . $arq->arquivo_path) }}" target="_blank"><i class='bx bx-file'></i> {{ basename($arq->arquivo_path) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                        @elseif (now()->isAfter($exercicio->data_fechamento))
                             <div class="status-badge status-late">
                                <i class='bx bxs-error-circle'></i> Prazo Encerrado
                            </div>
                            <p style="text-align:center; color:#777;">Não é mais possível enviar respostas.</p>
                        @else
                            <div class="status-badge status-pending">
                                <i class='bx bxs-time'></i> Pendente de Envio
                            </div>
                        @endif
                    </div>

                    @if (now()->isBefore($exercicio->data_fechamento))
                        <div class="card-section action-area">
                            <h3>{{ $respostaAnterior ? 'Reenviar Trabalho' : 'Enviar Resposta' }}</h3>
                            
                            @if ($errors->any())
                                <div class="error-box" style="color:red; font-size:0.9rem;">Erro no upload. Tente novamente.</div>
                            @endif

                            <form action="{{ route('aluno.exercicios.responder', $exercicio->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="arquivo_resposta" class="file-drop-area">
                                        <i class='bx bxs-cloud-upload'></i>
                                        <span>Clique para selecionar arquivos</span>
                                        <input name="arquivos_resposta[]" type="file" id="arquivo_resposta" class="input-file" required multiple />
                                    </label>
                                    <div id="file-list"></div>
                                </div>
                                <button type="submit" class="btn-enviar">
                                    <i class='bx bx-send'></i> {{ $respostaAnterior ? 'Atualizar' : 'Enviar' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </aside>
        </main>
    </div>

    <script>
        const inputArquivo = document.getElementById('arquivo_resposta');
        const fileListContainer = document.getElementById('file-list');
        
        if (inputArquivo) {
            inputArquivo.addEventListener('change', function() {
                fileListContainer.innerHTML = '';
                if (this.files.length > 0) {
                    const list = document.createElement('ul');
                    for (const file of this.files) {
                        const li = document.createElement('li');
                        li.innerHTML = `<i class='bx bx-file'></i> ${file.name}`;
                        list.appendChild(li);
                    }
                    fileListContainer.appendChild(list);
                }
            });
        }
    </script>

    @if (session('sweet_success'))
        <script>
            Swal.fire({ title: "Sucesso!", text: "{{ session('sweet_success') }}", icon: "success", confirmButtonColor: "#1a62ff" });
        </script>
    @endif
</body>
</html>