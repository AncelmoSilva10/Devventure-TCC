document.addEventListener('DOMContentLoaded', () => {
    
    // ============================================================
    // 1. SELEÇÃO DE ELEMENTOS DO DOM
    // ============================================================
    const form = document.getElementById('professor-form');
    
    // Seções
    const loginSection = document.getElementById('login-section');
    const cadastroSection = document.getElementById('cadastro-section');
    const stepperIndicator = document.getElementById('stepper-indicators');
    
    // Textos e Títulos
    const formTitle = document.getElementById('form-title');
    const formSubtitle = document.getElementById('form-subtitle');
    const toggleBtn = document.getElementById('toggle-btn');
    const toggleText = document.getElementById('toggle-text');

    // Botões de Ação
    const btnSubmit = document.getElementById('btn-submit');
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    
    // Input Hidden que guarda o estado (Login ou Cadastro)
    const formTipoInput = document.getElementById('form_tipo');

    // URLs
    const loginUrl = form.dataset.loginUrl;
    const cadastroUrl = form.dataset.cadastroUrl;

    // Variáveis de Estado
    let isLoginMode = true;
    let currentStep = 1;
    const totalSteps = 3;

    // ============================================================
    // 2. LÓGICA DE NAVEGAÇÃO (LOGIN <-> CADASTRO)
    // ============================================================

    function switchMode(forceCadastro = false) {
        if (forceCadastro) {
            isLoginMode = false;
        } else {
            isLoginMode = !isLoginMode;
        }

        if (isLoginMode) {
            // --- MODO LOGIN ---
            formTitle.textContent = "Login";
            formSubtitle.textContent = "Entre com suas credenciais para acessar.";
            
            loginSection.style.display = 'block';
            cadastroSection.style.display = 'none';
            stepperIndicator.style.display = 'none';

            toggleText.textContent = "Não tem conta?";
            toggleBtn.textContent = "Cadastre-se";
            
            // Botões
            btnSubmit.style.display = 'block';
            btnSubmit.textContent = 'Entrar';
            btnNext.style.display = 'none';
            btnPrev.style.display = 'none';

            // Define Action do Form
            form.action = loginUrl;

            // Define o hidden input para 'login'
            if(formTipoInput) formTipoInput.value = 'login';

            // Habilita inputs de Login / Desabilita Cadastro
            toggleInputsState(loginSection, false); // false = habilitado
            toggleInputsState(cadastroSection, true); // true = desabilitado

        } else {
            // --- MODO CADASTRO ---
            formTitle.textContent = "Criar Conta";
            formSubtitle.textContent = "Preencha os dados abaixo em 3 etapas.";
            
            loginSection.style.display = 'none';
            cadastroSection.style.display = 'block';
            stepperIndicator.style.display = 'flex';

            toggleText.textContent = "Já tem conta?";
            toggleBtn.textContent = "Fazer Login";

            form.action = cadastroUrl;

             // Define o hidden input para 'cadastro'
            if(formTipoInput) formTipoInput.value = 'cadastro';

            // Inicializa Step 1
            currentStep = 1;
            updateStepView();

            // Habilita inputs de Cadastro / Desabilita Login
            toggleInputsState(loginSection, true);
            toggleInputsState(cadastroSection, false);
        }
    }

    // Função auxiliar para não enviar inputs ocultos
    function toggleInputsState(container, isDisabled) {
        const inputs = container.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.disabled = isDisabled;
        });
    }

    // Evento do botão de troca
    toggleBtn.addEventListener('click', () => switchMode());

    // ============================================================
    // 3. LÓGICA DO STEPPER (PASSOS DO CADASTRO)
    // ============================================================

    function updateStepView() {
        // Esconde todos os conteúdos de steps
        document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
        
        // Mostra o step atual
        const currentPanel = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if(currentPanel) currentPanel.style.display = 'block';

        // Atualiza as "Bolinhas" (Indicadores)
        document.querySelectorAll('.step-dot').forEach(dot => {
            const stepNum = parseInt(dot.dataset.step);
            
            dot.classList.remove('active');
            dot.style.background = '#eee';
            dot.style.color = '#999';
            dot.innerHTML = stepNum;

            if (stepNum === currentStep) {
                dot.classList.add('active');
                dot.style.background = 'var(--primary)'; // Pega do CSS
                dot.style.color = '#fff';
            } else if (stepNum < currentStep) {
                dot.style.background = '#2ecc71'; // Verde
                dot.style.color = '#fff';
                dot.innerHTML = '✓';
            }
        });

        // Controla Botões
        if (currentStep === 1) {
            btnPrev.style.display = 'none';
            btnNext.style.display = 'block';
            btnSubmit.style.display = 'none';
        } else if (currentStep < totalSteps) {
            btnPrev.style.display = 'block';
            btnNext.style.display = 'block';
            btnSubmit.style.display = 'none';
        } else {
            // Último passo
            btnPrev.style.display = 'block';
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'block';
            btnSubmit.textContent = 'Finalizar Cadastro';
        }
    }

    // Botão PRÓXIMO
    btnNext.addEventListener('click', () => {
        if (validateCurrentStep()) {
            currentStep++;
            updateStepView();
        }
    });

    // Botão VOLTAR
    btnPrev.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStepView();
        }
    });

    // Validação
    function validateCurrentStep() {
        const currentPanel = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const inputs = currentPanel.querySelectorAll('input[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                highlightError(input);
            } else {
                removeHighlight(input);
            }
        });

        // Validar CPF na etapa 1
        if (currentStep === 1) {
            const cpfInput = document.getElementById('cpf');
            const cpfFeedback = document.getElementById('cpf-feedback');
            if (cpfInput && (cpfInput.value.length < 14 || (cpfFeedback && cpfFeedback.classList.contains('invalido')))) {
                isValid = false;
                highlightError(cpfInput);
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'CPF Inválido ou incompleto.', showConfirmButton: false, timer: 3000 });
            }
        }
        
        // Validar Senha na etapa 3
        if (currentStep === 3) {
            const p1 = document.getElementById('password-cadastro');
            const p2 = document.getElementById('confirm_password');
            if (p1.value !== p2.value) {
                isValid = false;
                highlightError(p2);
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'As senhas não coincidem.', showConfirmButton: false, timer: 3000 });
            }
        }

        if (!isValid && !Swal.isVisible()) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Preencha os campos obrigatórios.', showConfirmButton: false, timer: 3000 });
        }

        return isValid;
    }

    function highlightError(input) {
        input.style.borderColor = '#dc3545';
        input.addEventListener('input', function() { this.style.borderColor = '#ddd'; }, { once: true });
    }
    
    function removeHighlight(input) {
        input.style.borderColor = '#ddd';
    }

    // ============================================================
    // 4. FUNCIONALIDADES ESPECÍFICAS
    // ============================================================

    // --- AVATAR ---
    const avatarWrapper = document.getElementById('avatar-wrapper');
    const avatarInput = document.getElementById('avatar');
    if (avatarWrapper && avatarInput) {
        avatarWrapper.addEventListener('click', () => avatarInput.click());
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const preview = document.getElementById('avatar-preview');
                    preview.innerHTML = `<img src="${ev.target.result}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">`;
                    avatarWrapper.style.borderColor = 'var(--primary)';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // --- MÁSCARA CPF ---
    const cpfInput = document.getElementById('cpf');
    const cpfFeedback = document.getElementById('cpf-feedback');

    if (cpfInput) {
        cpfInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;

            const cleanCPF = v.replace(/\D/g, '');
            if (cleanCPF.length === 11) {
                if (validarCPF(cleanCPF)) {
                    cpfFeedback.textContent = '✅ CPF válido';
                    cpfFeedback.style.color = '#2ecc71';
                    cpfFeedback.className = 'valido';
                    cpfInput.style.borderColor = '#2ecc71';
                } else {
                    cpfFeedback.textContent = '❌ CPF inválido';
                    cpfFeedback.style.color = '#e74c3c';
                    cpfFeedback.className = 'invalido';
                    cpfInput.style.borderColor = '#e74c3c';
                }
            } else {
                cpfFeedback.textContent = '';
                cpfFeedback.className = '';
                cpfInput.style.borderColor = '#ddd';
            }
        });
    }

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
        let soma = 0, resto;
        for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;
        soma = 0;
        for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(10, 11))) return false;
        return true;
    }

    // --- MÁSCARA TELEFONE (CORRIGIDA COM LIMITE DE 11 DÍGITOS) ---
    const phoneInput = document.getElementById('telefone');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            // Limita a 11 dígitos
            if (v.length > 11) v = v.slice(0, 11);
            // Máscara (11) 91234-5678
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = v;
        });
    }

    // ============================================================
    // 5. AUTO-DETECTAR ERROS E MANTER O CADASTRO ABERTO
    // ============================================================
    
    // Verifica se o Laravel devolveu valor 'cadastro' no input hidden
    const tipoForm = formTipoInput ? formTipoInput.value : 'login';
    
    // OU Verifica se tem erro visual (.is-invalid) nos campos exclusivos de cadastro
    const hasCadastroErrors = document.querySelector('#cadastro-section .is-invalid');

    if (tipoForm === 'cadastro' || hasCadastroErrors) {
        // Força abrir a tela de cadastro
        switchMode(true);
        
        // Se o erro for na etapa 2 ou 3, tenta avançar o stepper visualmente
        // Exemplo: se tem erro no email (Etapa 3), vamos para etapa 3
        const erroEmail = document.getElementById('email-cadastro');
        if(erroEmail && erroEmail.classList.contains('is-invalid')) {
            currentStep = 3;
            updateStepView();
        }
    } else {
        // Inicializa como Login padrão
        toggleInputsState(cadastroSection, true);
    }

});

// Função Global para o Olho da Senha
window.togglePassword = function(fieldId, btnElement) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    const iconEye = btnElement.querySelector('.icon-eye');
    const iconOff = btnElement.querySelector('.icon-eye-off');

    if (input.type === 'password') {
        input.type = 'text';
        if(iconEye) iconEye.style.display = 'none';
        if(iconOff) iconOff.style.display = 'block';
    } else {
        input.type = 'password';
        if(iconEye) iconEye.style.display = 'block';
        if(iconOff) iconOff.style.display = 'none';
    }
}