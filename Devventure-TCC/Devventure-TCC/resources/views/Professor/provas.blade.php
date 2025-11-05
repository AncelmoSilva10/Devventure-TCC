<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gerenciador de Provas - Criar Prova</title>
    
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/Professor/exercicioProfessor.css') }}" rel="stylesheet">
    {{-- Se você usa Bootstrap ou outro framework CSS, adicione aqui --}}
    {{-- Exemplo: <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        /* Estilos básicos para os cards e botões, se não estiverem no exercicioProfessor.css */
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .card-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .card-body {
            padding: 15px;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box; /* Garante que padding e border não aumentem a largura */
            margin-bottom: 10px;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            color: white;
            font-weight: 500;
        }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; }
        .btn-danger { background-color: #dc3545; }
        .btn-info { background-color: #17a2b8; }
        .btn-outline-danger {
            background-color: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            border-radius: 4px 0 0 4px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 4px 4px 0;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .my-4 { margin-top: 1.5rem; margin-bottom: 1.5rem; }
        .d-grid { display: grid; }
        .btn-lg { padding: 10px 20px; font-size: 1.25rem; }
        .row { display: flex; flex-wrap: wrap; margin-left: -10px; margin-right: -10px; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; padding-left: 10px; padding-right: 10px; }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="container">
        <h2 class="mb-4">Criar Nova Prova</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('professor.provas.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
            @csrf
            
            <div class="mb-3">
                <label for="turma_id" class="form-label">Selecionar Turma</label>
                <select name="turma_id" id="turma_id" class="form-select" required>
                    <option value="">-- Selecione a Turma --</option>
                    @foreach ($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->nome_turma }} ({{ $turma->ano_turma ?? 'Não especificada' }} - {{ $turma->turno ?? 'Não especificado' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título da Prova</label>
                <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="instrucoes" class="form-label">Instruções da Prova (Opcional)</label>
                <textarea name="instrucoes" id="instrucoes" class="form-control" rows="3">{{ old('instrucoes') }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="data_abertura" class="form-label">Data de Abertura</label>
                    <input type="datetime-local" name="data_abertura" id="data_abertura" class="form-control" value="{{ old('data_abertura') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="data_fechamento" class="form-label">Data de Fechamento</label>
                    <input type="datetime-local" name="data_fechamento" id="data_fechamento" class="form-control" value="{{ old('data_fechamento') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="duracao_minutos" class="form-label">Duração da Prova (em minutos)</label>
                <input type="number" name="duracao_minutos" id="duracao_minutos" class="form-control" value="{{ old('duracao_minutos') }}" min="1" required>
            </div>

            <hr class="my-4">

            <h3 class="mb-3">Questões da Prova</h3>
            <div id="questoes-container">
                {{-- Questões serão adicionadas aqui pelo JavaScript. Não tentaremos preencher com old('questoes') diretamente no Blade para simplificar o JS. --}}
            </div>

            <button type="button" id="add-questao-btn" class="btn btn-secondary mb-4">Adicionar Questão</button>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Salvar Prova</button>
            </div>
        </form>
    </div>

    {{-- Script para adicionar questões dinamicamente --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questaoIndex = 0;
            const questoesContainer = document.getElementById('questoes-container');
            const addQuestaoBtn = document.getElementById('add-questao-btn');

            function addQuestao(initialData = {}) { // Adicionado initialData para eventual pré-preenchimento
                const currentQuestaoIndex = questaoIndex++; // Usa o valor atual e depois incrementa
                const questaoDiv = document.createElement('div');
                questaoDiv.className = 'card mb-4';
                questaoDiv.innerHTML = `
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Questão #${currentQuestaoIndex + 1}
                        <button type="button" class="btn btn-danger btn-sm remove-questao-btn">&times;</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Enunciado da Questão</label>
                            <textarea name="questoes[${currentQuestaoIndex}][enunciado]" class="form-control" rows="3" required>${initialData.enunciado || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Questão</label>
                            <select name="questoes[${currentQuestaoIndex}][tipo_questao]" class="form-select tipo-questao-select" data-index="${currentQuestaoIndex}" required>
                                <option value="multipla_escolha" ${initialData.tipo_questao === 'multipla_escolha' ? 'selected' : ''}>Múltipla Escolha</option>
                                <option value="texto" ${initialData.tipo_questao === 'texto' ? 'selected' : ''}>Texto</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pontuação</label>
                            <input type="number" name="questoes[${currentQuestaoIndex}][pontuacao]" class="form-control" value="${initialData.pontuacao || 1}" min="0.1" step="0.1" required>
                        </div>
                        <div class="multipla-escolha-options" data-index="${currentQuestaoIndex}">
                            <h5>Alternativas (Marque a Correta)</h5>
                            <div id="alternativas-container-${currentQuestaoIndex}">
                            </div>
                            <button type="button" class="btn btn-info btn-sm add-alternativa-btn" data-questao-index="${currentQuestaoIndex}">Adicionar Alternativa</button>
                        </div>
                    </div>
                `;
                questoesContainer.appendChild(questaoDiv);
                
                // Se houver alternativas no initialData, usa-as
                if (initialData.alternativas && initialData.alternativas.length > 0) {
                    initialData.alternativas.forEach((alt, altIndex) => {
                        addAlternativa(currentQuestaoIndex, alt, initialData.alternativa_correta === altIndex);
                    });
                } else {
                    // Adiciona as primeiras alternativas padrão
                    addAlternativa(currentQuestaoIndex);
                    addAlternativa(currentQuestaoIndex);
                }


                updateEventListeners(); // Atualiza listeners para novos botões
                
                // Dispara o evento change para o select para exibir/ocultar as alternativas
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
                    <button type="button" class="btn btn-outline-danger remove-alternativa-btn">&times;</button>
                `;
                alternativasContainer.appendChild(alternativaDiv);
                updateEventListeners();
            }

            function updateEventListeners() {
                // Remover Questão
                document.querySelectorAll('.remove-questao-btn').forEach(button => {
                    button.onclick = function() { this.closest('.card').remove(); };
                });

                // Remover Alternativa
                document.querySelectorAll('.remove-alternativa-btn').forEach(button => {
                    button.onclick = function() { this.closest('.alternativa-item').remove(); };
                });

                // Adicionar Alternativa
                document.querySelectorAll('.add-alternativa-btn').forEach(button => {
                    button.onclick = function() {
                        const idx = this.dataset.questaoIndex;
                        addAlternativa(idx);
                    };
                });

                // Tipo de Questão (Múltipla Escolha vs Texto)
                document.querySelectorAll('.tipo-questao-select').forEach(select => {
                    select.onchange = function() {
                        const idx = this.dataset.index;
                        const multiplaEscolhaOptions = document.querySelector(`.multipla-escolha-options[data-index="${idx}"]`);
                        
                        // Reseta o atributo 'required' para os inputs de alternativa
                        multiplaEscolhaOptions.querySelectorAll('input[type="text"], input[type="radio"]').forEach(el => el.removeAttribute('required'));

                        if (this.value === 'multipla_escolha') {
                            multiplaEscolhaOptions.style.display = 'block';
                            // Define 'required' para os campos de alternativa quando é múltipla escolha
                            multiplaEscolhaOptions.querySelectorAll('input[type="text"]').forEach(el => el.setAttribute('required', 'required'));
                            // Apenas um dos radio buttons precisa ser required (o navegador garante isso para o grupo)
                             if (multiplaEscolhaOptions.querySelector('input[type="radio"]')) {
                                // Adicionamos 'required' ao primeiro radio para que a validação HTML5 funcione.
                                // Na prática, qualquer um do grupo marcado satisfaria.
                                multiplaEscolhaOptions.querySelector('input[type="radio"]').setAttribute('required', 'required');
                            }
                        } else {
                            multiplaEscolhaOptions.style.display = 'none';
                        }
                    };
                    // Dispara o change para configurar o estado inicial (importante se houver old data)
                    select.dispatchEvent(new Event('change'));
                });
            }

            addQuestaoBtn.addEventListener('click', addQuestao);
            
            // Tenta preencher com dados antigos se houver erro de validação
            // É mais complexo, mas se 'old('questoes')' retornar algo, vamos tentar recriar
            const oldQuestoes = @json(old('questoes', []));
            if (oldQuestoes.length > 0) {
                oldQuestoes.forEach(questaoData => {
                    addQuestao(questaoData);
                });
            } else {
                addQuestao(); // Adiciona a primeira questão ao carregar a página se não houver old data
            }
        });
    </script>
</body>
</html>