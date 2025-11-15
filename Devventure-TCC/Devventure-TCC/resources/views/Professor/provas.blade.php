<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Criar Prova</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/Professor/criarProva.css') }}" rel="stylesheet">
</head>
<body>

    <div class="container">
        <form action="{{ route('professor.provas.store') }}" method="POST">
            @csrf
            
            <div class="content-grid">
                <div class="main-content">
                
                    <div class="card">
                        <div class="card-header">
                            <h2><i class='bx bxs-file-plus'></i> Criar Nova Prova</h2>
                            <a href="{{ route('professorDashboard') }}" class="btn btn-back">
                                <i class='bx bx-arrow-back'></i> Voltar
                            </a>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class='bx bxs-error-alt'></i>
                                    <div>
                                        <strong>Ocorreram alguns erros:</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="turma_id" class="form-label">Selecionar Turma *</label>
                                <select name="turma_id" id="turma_id" class="form-select" required>
                                    <option value="">-- Selecione a Turma --</option>
                                    @foreach ($turmas as $turma)
                                        <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                            {{ $turma->nome_turma }} ({{ $turma->ano_turma ?? 'N/A' }} - {{ $turma->turno ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Prova *</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="instrucoes" class="form-label">Instruções da Prova (Opcional)</label>
                                <textarea name="instrucoes" id="instrucoes" class="form-control" rows="4">{{ old('instrucoes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3><i class='bx bxs-help-circle'></i> Questões da Prova *</h3>
                        </div>
                        <div class="card-body">
                            <div id="questoes-container">
                                {{-- Questões serão adicionadas aqui pelo JavaScript --}}
                            </div>
                            <button type="button" id="add-questao-btn" class="btn btn-secondary-custom mb-4">
                                <i class='bx bx-plus-circle'></i> Adicionar Questão
                            </button>
                        </div>
                    </div>

                </div>

                <aside class="sidebar">
                    <div class="card">
                        <h3><i class='bx bxs-cog'></i> Configurações</h3>
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="data_abertura" class="form-label">Data de Abertura *</label>
                                <input type="datetime-local" name="data_abertura" id="data_abertura" class="form-control" value="{{ old('data_abertura') }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="data_fechamento" class="form-label">Data de Fechamento *</label>
                                <input type="datetime-local" name="data_fechamento" id="data_fechamento" class="form-control" value="{{ old('data_fechamento') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="duracao_minutos" class="form-label">Duração (em minutos) *</label>
                            <input type="number" name="duracao_minutos" id="duracao_minutos" class="form-control" value="{{ old('duracao_minutos') }}" min="1" required>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h3><i class='bx bxs-save'></i> Publicar</h3>
                        <p>Revise todas as informações antes de salvar. As questões não poderão ser editadas após a criação.</p>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class='bx bx-check'></i> Salvar Prova
                        </button>
                    </div>
                </aside>
                
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questaoIndex = 0;
            const questoesContainer = document.getElementById('questoes-container');
            const addQuestaoBtn = document.getElementById('add-questao-btn');

            function addQuestao(initialData = {}) { 
                const currentQuestaoIndex = questaoIndex++; 
                const questaoDiv = document.createElement('div');
                questaoDiv.className = 'card mb-4';
                questaoDiv.innerHTML = `
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Questão #${currentQuestaoIndex + 1}
                        <button type="button" class="btn btn-danger-custom btn-sm remove-questao-btn">&times;</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Enunciado da Questão *</label>
                            <textarea name="questoes[${currentQuestaoIndex}][enunciado]" class="form-control" rows="3" required>${initialData.enunciado || ''}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Questão *</label>
                                <select name="questoes[${currentQuestaoIndex}][tipo_questao]" class="form-select tipo-questao-select" data-index="${currentQuestaoIndex}" required>
                                    <option value="multipla_escolha" ${initialData.tipo_questao === 'multipla_escolha' ? 'selected' : ''}>Múltipla Escolha</option>
                                    <option value="texto" ${initialData.tipo_questao === 'texto' ? 'selected' : ''}>Texto</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pontuação *</label>
                                <input type="number" name="questoes[${currentQuestaoIndex}][pontuacao]" class="form-control" value="${initialData.pontuacao || 1}" min="0.1" step="0.1" required>
                            </div>
                        </div>
                        <div class="multipla-escolha-options" data-index="${currentQuestaoIndex}">
                            <h5 style="font-size: 1rem; font-weight: 500; margin-bottom: 10px;">Alternativas (Marque a Correta) *</h5>
                            <div id="alternativas-container-${currentQuestaoIndex}">
                            </div>
                            <button type="button" class="btn btn-info-custom btn-sm add-alternativa-btn" data-questao-index="${currentQuestaoIndex}">Adicionar Alternativa</button>
                        </div>
                    </div>
                `;
                questoesContainer.appendChild(questaoDiv);
                
                if (initialData.alternativas && initialData.alternativas.length > 0) {
                    initialData.alternativas.forEach((alt, altIndex) => {
                        addAlternativa(currentQuestaoIndex, alt, initialData.alternativa_correta == altIndex);
                    });
                } else {
                    addAlternativa(currentQuestaoIndex, {}, true);
                    addAlternativa(currentQuestaoIndex);
                }
                updateEventListeners();
                questaoDiv.querySelector('.tipo-questao-select').dispatchEvent(new Event('change'));
            }

            function addAlternativa(questaoIdx, initialData = {}, isCorreta = false) {
                let alternativaIndex = document.querySelectorAll(`#alternativas-container-${questaoIdx} .alternativa-item`).length;
                const alternativasContainer = document.getElementById(`alternativas-container-${questaoIdx}`);
                const alternativaDiv = document.createElement('div');
                alternativaDiv.className = 'input-group mb-2 alternativa-item';
                alternativaDiv.innerHTML = `
                    <div class="input-group-text">
                        <input type="radio" name="questoes[${questaoIdx}][alternativa_correta]" value="${alternativaIndex}" class="form-check-input mt-0" ${isCorreta ? 'checked' : ''} required>
                    </div>
                    <input type="text" name="questoes[${questaoIdx}][alternativas][${alternativaIndex}][texto]" class="form-control" placeholder="Texto da alternativa" value="${initialData.texto || ''}" required>
                    <button type="button" class="btn btn-outline-danger remove-alternativa-btn" style="padding: 0.5rem 0.75rem;">&times;</button>
                `;
                alternativasContainer.appendChild(alternativaDiv);
                updateEventListeners();
            }

            function updateEventListeners() {
                document.querySelectorAll('.remove-questao-btn').forEach(button => {
                    button.onclick = function() { this.closest('.card').remove(); };
                });
                document.querySelectorAll('.remove-alternativa-btn').forEach(button => {
                    button.onclick = function() { this.closest('.alternativa-item').remove(); };
                });
                document.querySelectorAll('.add-alternativa-btn').forEach(button => {
                    button.onclick = function() {
                        const idx = this.dataset.questaoIndex;
                        addAlternativa(idx);
                    };
                });
                document.querySelectorAll('.tipo-questao-select').forEach(select => {
                    select.onchange = function() {
                        const idx = this.dataset.index;
                        const multiplaEscolhaOptions = document.querySelector(`.multipla-escolha-options[data-index="${idx}"]`);
                        
                        multiplaEscolhaOptions.querySelectorAll('input[type="text"], input[type="radio"]').forEach(el => el.removeAttribute('required'));

                        if (this.value === 'multipla_escolha') {
                            multiplaEscolhaOptions.style.display = 'block';
                            multiplaEscolhaOptions.querySelectorAll('input[type="text"]').forEach(el => el.setAttribute('required', 'required'));
                             if (multiplaEscolhaOptions.querySelector('input[type="radio"]')) {
                                multiplaEscolhaOptions.querySelector('input[type="radio"]').setAttribute('required', 'required');
                            }
                        } else {
                            multiplaEscolhaOptions.style.display = 'none';
                        }
                    };
                    select.dispatchEvent(new Event('change'));
                });
            }

            addQuestaoBtn.addEventListener('click', addQuestao);
            
            const oldQuestoes = @json(old('questoes', []));
            if (oldQuestoes.length > 0) {
                oldQuestoes.forEach(questaoData => {
                    addQuestao(questaoData);
                });
            } else {
                addQuestao();
            }
        });
    </script>

    @if (session('sweet_success'))
    <script>
        Swal.fire({
            title: "Sucesso!",
            text: "{{ session('sweet_success') }}",
            icon: "success",
            confirmButtonColor: '#6f42c1', // Cor roxa
            confirmButtonText: "Ok"
        });
    </script>
    @endif

</body>
</html>