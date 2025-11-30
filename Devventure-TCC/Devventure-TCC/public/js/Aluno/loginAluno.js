document.addEventListener('DOMContentLoaded', () => {
    
    // ================= SELEÇÃO DOM =================
    const form = document.getElementById('aluno-form');
    const loginSection = document.getElementById('login-section');
    const cadastroSection = document.getElementById('cadastro-section');
    const stepperIndicator = document.getElementById('stepper-indicators');
    const formTitle = document.getElementById('form-title');
    const formSubtitle = document.getElementById('form-subtitle');
    const toggleBtn = document.getElementById('toggle-btn');
    const toggleText = document.getElementById('toggle-text');
    const btnSubmit = document.getElementById('btn-submit');
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    const formTipoInput = document.getElementById('form_tipo');

    // URLs
    const loginUrl = form.dataset.loginUrl;
    const cadastroUrl = form.dataset.cadastroUrl;

    // Estado
    let isLoginMode = true;
    let currentStep = 1;
    const totalSteps = 3;

    // ================= NAVEGAÇÃO =================
    function switchMode(forceCadastro = false) {
        if (forceCadastro) {
            isLoginMode = false;
        } else {
            isLoginMode = !isLoginMode;
        }

        if (isLoginMode) {
            // MODO LOGIN
            formTitle.textContent = "Entrar";
            formSubtitle.textContent = "Bem-vindo de volta, estudante.";
            loginSection.style.display = 'block';
            cadastroSection.style.display = 'none';
            stepperIndicator.style.display = 'none';
            toggleText.textContent = "Não tem conta?";
            toggleBtn.textContent = "Cadastre-se";
            btnSubmit.style.display = 'block';
            btnSubmit.textContent = 'Entrar';
            btnNext.style.display = 'none';
            btnPrev.style.display = 'none';
            
            form.action = loginUrl;
            if(formTipoInput) formTipoInput.value = 'login';
            
            toggleInputsState(loginSection, false);
            toggleInputsState(cadastroSection, true);
        } else {
            // MODO CADASTRO
            formTitle.textContent = "Criar Conta";
            formSubtitle.textContent = "Preencha seus dados acadêmicos.";
            loginSection.style.display = 'none';
            cadastroSection.style.display = 'block';
            stepperIndicator.style.display = 'flex';
            toggleText.textContent = "Já tem conta?";
            toggleBtn.textContent = "Fazer Login";
            
            form.action = cadastroUrl;
            if(formTipoInput) formTipoInput.value = 'cadastro';
            
            currentStep = 1;
            updateStepView();
            
            toggleInputsState(loginSection, true);
            toggleInputsState(cadastroSection, false);
        }
    }

    function toggleInputsState(container, isDisabled) {
        const inputs = container.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.disabled = isDisabled;
        });
    }

    toggleBtn.addEventListener('click', () => switchMode());

    // ================= STEPPER =================
    function updateStepView() {
        document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
        const currentPanel = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        if(currentPanel) currentPanel.style.display = 'block';

        document.querySelectorAll('.step-dot').forEach(dot => {
            const stepNum = parseInt(dot.dataset.step);
            dot.classList.remove('active');
            dot.style.background = '#eee';
            dot.style.color = '#999';
            dot.innerHTML = stepNum;

            if (stepNum === currentStep) {
                dot.classList.add('active');
                dot.style.background = 'var(--primary)';
                dot.style.color = '#fff';
            } else if (stepNum < currentStep) {
                dot.style.background = '#2ecc71';
                dot.style.color = '#fff';
                dot.innerHTML = '✓';
            }
        });

        if (currentStep === 1) {
            btnPrev.style.display = 'none';
            btnNext.style.display = 'block';
            btnSubmit.style.display = 'none';
        } else if (currentStep < totalSteps) {
            btnPrev.style.display = 'block';
            btnNext.style.display = 'block';
            btnSubmit.style.display = 'none';
        } else {
            btnPrev.style.display = 'block';
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'block';
            btnSubmit.textContent = 'Finalizar Cadastro';
        }
    }

    btnNext.addEventListener('click', () => {
        if (validateCurrentStep()) {
            currentStep++;
            updateStepView();
        }
    });

    btnPrev.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStepView();
        }
    });

    // ================= VALIDAÇÃO =================
    function validateCurrentStep() {
        const currentPanel = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const inputs = currentPanel.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                highlightError(input);
            } else {
                removeHighlight(input);
            }
        });
        
        // Validação de Senha (Passo 3)
        if (currentStep === 3) {
            const p1 = document.getElementById('password-cadastro');
            const p2 = document.getElementById('confirm_password');
            if (p1.value !== p2.value) {
                isValid = false;
                highlightError(p2);
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Senhas não conferem', showConfirmButton: false, timer: 3000 });
            }
        }

        if (!isValid && !Swal.isVisible()) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Preencha os campos obrigatórios', showConfirmButton: false, timer: 3000 });
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

    // ================= EXTRAS (Avatar, Máscara) =================

    // Avatar
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

    // MÁSCARA TELEFONE (A que você pediu)
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = v;
        });
    }

    // ================= INICIALIZAÇÃO =================
    const tipoForm = formTipoInput ? formTipoInput.value : 'login';
    const hasCadastroErrors = document.querySelector('#cadastro-section .is-invalid');

    if (tipoForm === 'cadastro' || hasCadastroErrors) {
        switchMode(true);
    } else {
        toggleInputsState(cadastroSection, true);
    }
});

// Olho da Senha Global
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