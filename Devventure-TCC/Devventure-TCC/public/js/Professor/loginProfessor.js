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
        input.classList.add('is-invalid'); // Usa classe CSS para borda vermelha
        input.style.borderColor = '#dc3545';
        input.addEventListener('input', function() { 
            this.style.borderColor = '#ddd'; 
            this.classList.remove('is-invalid');
        }, { once: true });
    }
    
    function removeHighlight(input) {
        input.style.borderColor = '#ddd';
        input.classList.remove('is-invalid');
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
                    // O CSS garante que a imagem preencha corretamente com object-fit: cover
                    preview.innerHTML = `<img src="${ev.target.result}" alt="Preview">`;
                    avatarWrapper.style.borderColor = 'var(--primary)';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // --- MÁSCARA E VALIDAÇÃO CPF (COM SWEETALERT) ---
    const cpfInput = document.getElementById('cpf');
    const cpfFeedback = document.getElementById('cpf-feedback');

    if (cpfInput) {
        // 1. MÁSCARA (Enquanto digita)
        cpfInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;

            // Limpa erros visuais enquanto digita
            if (cpfInput.classList.contains('is-invalid')) {
                removeHighlight(cpfInput);
            }
        });

        // 2. VALIDAÇÃO (Ao sair do campo)
        cpfInput.addEventListener('blur', function() {
            const cleanCPF = this.value.replace(/\D/g, '');
            
            // Se estiver vazio, não faz nada (a validação de required cuida disso)
            if (cleanCPF.length === 0) return;

            // Se for inválido
            if (cleanCPF.length < 11 || !validarCPF(cleanCPF)) {
                
                // DISPARA O SWEETALERT (Estilizado pelo CSS global)
                Swal.fire({
                    icon: 'error',
                    title: 'CPF Inválido',
                    text: 'O número informado está incorreto. Verifique e tente novamente.',
                    confirmButtonText: 'Corrigir',
                    confirmButtonColor: '#00796B' // Cor verde professor
                });

                // Limpa o campo e marca erro
                this.value = '';
                highlightError(this);
                if (cpfFeedback) {
                    cpfFeedback.textContent = '';
                }

            } else {
                // Sucesso
                if (cpfFeedback) {
                    cpfFeedback.textContent = 'CPF válido';
                    cpfFeedback.style.color = '#2ecc71';
                }
                this.style.borderColor = '#2ecc71';
            }
        });
    }

    function validarCPF(cpf) {
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        let soma = 0, resto;
        for (let i = 1; i <= 9; i++) soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;
        soma = 0;
        for (let i = 1; i <= 10; i++) soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(10, 11))) return false;
        return true;
    }

    // --- MÁSCARA TELEFONE ---
    const phoneInput = document.getElementById('telefone');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = v;
        });
    }

    // ============================================================
    // 5. AUTO-DETECTAR ERROS E MANTER O CADASTRO ABERTO
    // ============================================================
    
    const tipoForm = formTipoInput ? formTipoInput.value : 'login';
    const hasCadastroErrors = document.querySelector('#cadastro-section .is-invalid');

    if (tipoForm === 'cadastro' || hasCadastroErrors) {
        switchMode(true);
        const erroEmail = document.getElementById('email-cadastro');
        if(erroEmail && erroEmail.classList.contains('is-invalid')) {
            currentStep = 3;
            updateStepView();
        }
    } else {
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