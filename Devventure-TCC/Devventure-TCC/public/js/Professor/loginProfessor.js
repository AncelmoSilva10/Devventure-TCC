// public/js/Professor/loginProfessor.js

document.addEventListener('DOMContentLoaded', () => {
    // --- SELEÇÃO DE ELEMENTOS ---
    const form = document.getElementById('professor-form');
    const formTitle = document.getElementById('form-title');
    const submitBtn = document.getElementById('submit-btn');
    const toggleBtn = document.getElementById('toggle-btn');
    const cadastroFields = document.getElementById('cadastro-fields');
    const confirmPasswordWrapper = document.getElementById('confirm-password-wrapper');
    const avatarWrapper = document.getElementById('avatar-wrapper');
    const loginUrl = form.dataset.loginUrl;
    const cadastroUrl = form.dataset.cadastroUrl;

    let isLogin = true;

    function toggleFormView() {
        isLogin = !isLogin;
        if (isLogin) {
            formTitle.textContent = 'Login Professor';
            submitBtn.textContent = 'Entrar';
            toggleBtn.innerHTML = 'Não tem conta? <strong>Cadastre-se</strong>';
            form.action = loginUrl;
            cadastroFields.style.display = 'none';
            confirmPasswordWrapper.style.display = 'none';
            avatarWrapper.style.display = 'none';
        } else {
            formTitle.textContent = 'Cadastro Professor';
            submitBtn.textContent = 'Cadastrar';
            toggleBtn.innerHTML = 'Já tem conta? <strong>Faça login</strong>';
            form.action = cadastroUrl;
            cadastroFields.style.display = 'block';
            confirmPasswordWrapper.style.display = 'block';
            avatarWrapper.style.display = 'flex';
        }
    }

    cadastroFields.style.display = 'none';
    confirmPasswordWrapper.style.display = 'none';
    avatarWrapper.style.display = 'none';
    toggleBtn.addEventListener('click', toggleFormView);

    // --- LÓGICA DO AVATAR ---
    const avatarInput = document.getElementById('avatar');
    avatarWrapper.addEventListener('click', () => {
        if (!isLogin) {
            avatarInput.click();
        }
    });
    avatarInput.addEventListener('change', () => {
        const file = avatarInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        }
    });

    // --- MÁSCARAS E VALIDAÇÕES ---
    const cpfInput = document.getElementById('cpf');
    const telefoneInput = document.getElementById('telefone');
    const cpfFeedback = document.getElementById('cpf-feedback');

    cpfInput.addEventListener('input', (e) => {
        let v = e.target.value.replace(/\D/g, '');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = v;

        const cpfLimpo = v.replace(/\D/g, '');
        if (cpfLimpo.length === 11) {
            if (validaCPF(cpfLimpo)) {
                cpfFeedback.textContent = '✅ CPF válido';
                cpfFeedback.className = 'valido';
            } else {
                cpfFeedback.textContent = '❌ CPF inválido';
                cpfFeedback.className = 'invalido';
            }
        } else {
            cpfFeedback.textContent = '';
            cpfFeedback.className = '';
        }
    });

    telefoneInput.addEventListener('input', (e) => {
        let v = e.target.value.replace(/\D/g, '');
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        v = v.replace(/(\d)(\d{4})$/, '$1-$2');
        e.target.value = v;
    });

    // --- ALERTA SWEETALERT2 ---
    if (window.flashMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Cadastro Realizado!',
            text: window.flashMessage,
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }

    // --- VALIDAÇÃO DE SENHA ---
    const passwordInput = document.getElementById('password');
    const passwordFeedback = document.getElementById('password-feedback');

    if (passwordInput && passwordFeedback) {
        passwordInput.addEventListener('input', () => {
            if (!isLogin) {
                const senha = passwordInput.value;
                if (senha.length === 0) {
                    passwordFeedback.textContent = '';
                    passwordFeedback.className = 'password-feedback';
                } else if (senha.length < 8) {
                    passwordFeedback.textContent = 'A senha deve ter no mínimo 8 caracteres.';
                    passwordFeedback.className = 'password-feedback invalido';
                } else {
                    passwordFeedback.textContent = '✓ Tamanho de senha válido!';
                    passwordFeedback.className = 'password-feedback valido';
                }
            } else {
                passwordFeedback.textContent = '';
            }
        });
    }
});

// --- FUNÇÕES GLOBAIS ---
function togglePassword(fieldId, iconContainer) {
    const passwordField = document.getElementById(fieldId);
    const iconEye = iconContainer.querySelector('.icon-eye');
    const iconEyeOff = iconContainer.querySelector('.icon-eye-off');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        iconEye.classList.add('d-none');
        iconEyeOff.classList.remove('d-none');
    } else {
        passwordField.type = 'password';
        iconEye.classList.remove('d-none');
        iconEyeOff.classList.add('d-none');
    }
}

function validaCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }
    let soma = 0;
    let resto;
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