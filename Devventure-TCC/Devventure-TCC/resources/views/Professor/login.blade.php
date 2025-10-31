<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
 
  <link href="{{ asset('css/Professor/loginProfessor.css') }}" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <title>Área do Professor</title>
</head>
<body>
  @include('layouts.navbar')

  <main class="container">
    <div class="card">
      <h2 id="form-title">Entrar como Professor</h2>
      
      <form 
        method="POST" 
        id="professor-form" 
        enctype="multipart/form-data"
        action="{{ route('professor.login.action') }}" 
        data-login-url="{{ route('professor.login.action') }}"
        data-cadastro-url="{{ route('professor.cadastro.action') }}"
      >
        @csrf

        <div class="icon" id="avatar-wrapper" title="Clique para adicionar uma foto de perfil">
            <span id="avatar-preview">
    <svg width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 8.99991C16 11.2091 14.2091 12.9999 12 12.9999C9.79086 12.9999 8 11.2091 8 8.99991C8 6.79077 9.79086 4.99991 12 4.99991C14.2091 4.99991 16 6.79077 16 8.99991Z" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M12 15.9999C8.13401 15.9999 5 18.2385 5 20.9999" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M18 11.5V17.5" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M21 14.5H15" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</span>
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
        </div>

        <div id="cadastro-fields" style="display: none;">
          <div class="form-group">
            <label for="nome">Nome completo *</label>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" maxlength="50">
          </div>

          <div class="form-group">
            <label for="cpf">CPF *</label>
            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14">
            <small id="cpf-feedback"></small>
          </div>

          <div class="form-group">
            <label for="area">Área de Ensino *</label>
            <input type="text" id="area" name="area" placeholder="Ex: Matemática, Programação, etc.">
          </div>

          <div class="form-group">
            <label for="formacao">Formação acadêmica *</label>
            <textarea id="formacao" name="formacao" placeholder="Descreva sua formação e experiência" rows="4"></textarea>
          </div>

          <div class="form-group">
            <label for="telefone">Telefone (opcional)</label>
            <input type="text" id="telefone" name="telefone" placeholder="(11) 99999-9999" maxlength="15">
          </div>
        </div>
        
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" placeholder="Digite seu email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Senha *</label>
          <small id="password-feedback" class="password-feedback"></small>
          <div class="senha-wrapper">
            <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            <span class="toggle-password" onclick="togglePassword('password', this)">
              <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              <svg class="icon-eye-off d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
            </span>
          </div>
        </div>

        <div class="form-group" id="confirm-password-wrapper" style="display: none;">
          <label for="confirm_password">Confirmar senha *</label>
          <div class="senha-wrapper">
            <input type="password" id="confirm_password" name="password_confirmation" placeholder="Confirme sua senha">
            <span class="toggle-password" onclick="togglePassword('confirm_password', this)">
                <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <svg class="icon-eye-off d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
            </span>
          </div>
        </div>

        <button type="submit" id="submit-btn">Entrar</button>
      </form>

      <div class="links">
        <button type="button" id="toggle-btn">Não tem conta? <strong>Cadastre-se</strong></button>
        <a href="/esqueceu-senha">Esqueceu a senha?</a>
      </div>
    </div>
  </main>

  @if (session('cadastro_sucesso'))
        <script>
            window.flashMessage = "{{ session('cadastro_sucesso') }}";
        </script>
    @endif

  <script src="{{ asset('js/Professor/loginProfessor.js') }}"></script>

 <script>
document.addEventListener('DOMContentLoaded', function () {
 
    const errors = @json($errors->toArray());
    const oldInput = @json(session()->getOldInput() ?? []);

    // Limpa erros JS antigos
    document.querySelectorAll('.error-feedback-js').forEach(e => e.remove());

    // Verifica se há erros de validação (e não é um erro de login 'msg')
    if (Object.keys(errors).length > 0 && errors.msg === undefined) {
        
        // Força a exibição dos campos de cadastro
        const toggleBtn = document.getElementById('toggle-btn');
        if (toggleBtn && (toggleBtn.textContent.includes('Cadastre-se') || toggleBtn.innerText.includes('Cadastre-se'))) {
            toggleBtn.click();
        }

        const form = document.getElementById('professor-form');
        form.querySelectorAll('input, textarea, select').forEach(field => {
            const fieldName = field.name;

            // --- MUDANÇA 1 ---
            // Apenas o _token deve ser pulado
            if (fieldName === '_token') return;

            let existingError = field.parentNode.querySelector('.error-feedback-js');
            if (existingError) existingError.remove();

            // Verifica se este campo tem um erro
            if (errors[fieldName]) {
                const errorElement = document.createElement('small');
                errorElement.className = 'error-feedback-js';
                errorElement.innerText = errors[fieldName][0];
                
                // Adiciona a classe de borda vermelha
                field.classList.add('is-invalid'); // Você precisará de CSS para isso

                // --- MUDANÇA 2 ---
                // Lógica para posicionar a mensagem de erro
                if (fieldName === 'avatar') {
                    // Coloca o erro depois do ícone (avatar-wrapper)
                    const wrapper = document.getElementById('avatar-wrapper');
                    wrapper.classList.add('is-invalid'); // Adiciona borda vermelha no ícone
                    wrapper.parentNode.insertBefore(errorElement, wrapper.nextSibling);

                } else if (field.parentNode.classList.contains('senha-wrapper')) {
                    // Coloca o erro depois do wrapper da senha
                    field.parentNode.parentNode.appendChild(errorElement);
                } else {
                    // Coloca o erro padrão (depois do campo)
                    field.parentNode.appendChild(errorElement);
                }

            } else if (oldInput[fieldName]) {
                
                // --- MUDANÇA 3 ---
                // Não tente preencher o 'value' de um campo de arquivo!
                if (fieldName !== 'avatar') {
                    field.value = oldInput[fieldName];
                }
            }
        });

        // Limpa as senhas por segurança
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('confirm_password');
        if (passwordField) passwordField.value = '';
        if (confirmField) confirmField.value = '';
    }

    // O resto do seu código de SweetAlert (está perfeito, não mexa)
    @if ($errors->has('msg'))
        Swal.fire({
            icon: 'error',
            title: 'Oops... Algo deu errado',
            text: '{{ $errors->first('msg') }}',
            confirmButtonColor: '#d33'
        });
    @endif

    @if (session('needs_verification'))
        Swal.fire({
            icon: 'warning',
            title: 'Verificação Necessária',
            text: "{{ session('needs_verification') }}",
            showCancelButton: true,
            confirmButtonText: 'Reenviar E-mail de Verificação',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
        }).then((result) => { 
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('verification.resend') }}";
                
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    @endif

    @if (session('cadastro_sucesso'))
        Swal.fire({
            icon: 'success',
            title: 'Cadastro Realizado!',
            text: "{{ session('cadastro_sucesso') }}",
            confirmButtonColor: '#3085d6'
        });
    @endif
});

const cpfInput = document.getElementById('cpf');
const cpfFeedback = document.getElementById('cpf-feedback');


function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');

    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    let soma = 0;
    for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
    let resto = 11 - (soma % 11);
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;

    soma = 0;
    for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
    resto = 11 - (soma % 11);
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(10))) return false;

    return true;
}


cpfInput.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 3 && value.length <= 6)
        value = value.replace(/(\d{3})(\d+)/, '$1.$2');
    else if (value.length > 6 && value.length <= 9)
        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
    else if (value.length > 9)
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
    e.target.value = value.substring(0, 14);
});

cpfInput.addEventListener('blur', function () {
    const cpf = cpfInput.value.trim();
    if (!cpf) {
        cpfFeedback.textContent = '';
        return;
    }

    if (!validarCPF(cpf)) {
        cpfFeedback.textContent = '❌ CPF inválido';
        cpfFeedback.style.color = '#e74c3c';
    } else {
        cpfFeedback.textContent = '✅ CPF válido';
        cpfFeedback.style.color = '#2ecc71';
    }
});

</script>

</body>
</html>