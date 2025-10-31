 <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/Aluno/aluno.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <title>Área do Aluno</title>
</head>
<body>
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            <h2 id="form-title">Entrar como Aluno</h2>

            <form method="POST" id="aluno-form" enctype="multipart/form-data"
                action="{{ route('aluno.login') }}" 
                data-login-url="{{ route('aluno.login') }}" 
                data-cadastro-url="{{ route('aluno.cadastrar') }}">
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
                        <label for="ra">RA/Matrícula *</label>
                        <input type="text" id="ra" name="ra" placeholder="Digite seu RA ou matrícula" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="semestre">Semestre *</label>
                        <select id="semestre" name="semestre">
                            <option value="">Selecione seu semestre</option>
                            <option value="1">1º Semestre</option>
                            <option value="2">2º Semestre</option>
                            <option value="3">3º Semestre</option>
                            <option value="4">4º Semestre</option>
                            <option value="5">5º Semestre</option>
                            <option value="6">6º Semestre</option>
                        </select>
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
                <a href="/esqueceu-senha">Esqueceu a senha</a>
            </div>
        </div>
    </main>

    @if (session('cadastro_sucesso'))
        <script>
            window.flashMessage = "{{ session('cadastro_sucesso') }}";
        </script>
    @endif

    <script src="{{ asset('js/Aluno/loginAluno.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const errors = @json($errors->toArray());
        const oldInput = @json(session()->getOldInput() ?? []);

        document.querySelectorAll('.error-feedback-js').forEach(e => e.remove());

        // Lógica principal para reabrir o formulário de cadastro se a validação falhar
        if (Object.keys(errors).length > 0 && errors.msg === undefined) {
            
            // Força a abertura do formulário (simula o clique no botão "Cadastre-se")
            const toggleBtn = document.getElementById('toggle-btn');
            if (toggleBtn && (toggleBtn.textContent.includes('Cadastre-se') || toggleBtn.innerText.includes('Cadastre-se'))) {
                toggleBtn.click();
            }

            // Itera sobre os campos para aplicar erros ou dados antigos
            const form = document.getElementById('aluno-form');
            form.querySelectorAll('input, textarea, select').forEach(field => {
                const fieldName = field.name;

                // --- MUDANÇA 1: Apenas o _token é ignorado ---
                if (fieldName === '_token') return;

                let existingError = field.parentNode.querySelector('.error-feedback-js');
                if(existingError) existingError.remove();

                // Se houver erro para este campo
                if (errors[fieldName]) {
                    field.classList.add('is-invalid'); // Adiciona borda vermelha
                    
                    const errorElement = document.createElement('small');
                    errorElement.className = 'error-feedback-js';
                    errorElement.innerText = errors[fieldName][0];
                    
                    // --- MUDANÇA 2: Lógica especial para posicionar erro do avatar ---
                    if (fieldName === 'avatar') {
                        const wrapper = document.getElementById('avatar-wrapper');
                        wrapper.classList.add('is-invalid'); // Adiciona borda no ícone
                        wrapper.parentNode.insertBefore(errorElement, wrapper.nextSibling);
                    
                    } else if(field.parentNode.classList.contains('senha-wrapper')) {
                        // Posiciona erro da senha
                        field.parentNode.parentNode.appendChild(errorElement);
                    } else {
                        // Posiciona erro padrão
                        field.parentNode.appendChild(errorElement);
                    }
                    
                } else if (oldInput[fieldName]) {
                    
                    // --- MUDANÇA 3: Não tenta preencher 'value' de arquivo ---
                    if (fieldName !== 'avatar') {
                        field.value = oldInput[fieldName]; // Preenche o campo que estava correto
                    }
                }
            });
            
            // Limpa campos de senha por segurança
            document.getElementById('password').value = '';
            document.getElementById('confirm_password').value = '';
        }
        
        // --- LÓGICA DOS POP-UPS (SWEETALERT) ---

        // Exibe pop-up de SUCESSO (ex: "E-mail verificado com sucesso!")
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: "{{ session('status') }}", 
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Exibe pop-up de ERRO DE LOGIN (ex: "Senha inválida" ou "Conta bloqueada")
        @if ($errors->has('msg'))
            Swal.fire({
                icon: 'error',
                title: 'Oops... Algo deu errado',
                text: '{{ $errors->first('msg') }}',
                confirmButtonColor: '#d33'
            });
        @endif

        // Exibe pop-up de VERIFICAÇÃO PENDENTE
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
                    // Cria e submete um formulário dinâmico para reenviar o e-mail
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

        // Exibe pop-up de SUCESSO NO CADASTRO (se houver)
        @if (session('cadastro_sucesso'))
            Swal.fire({
                icon: 'success',
                title: 'Cadastro Realizado!',
                text: "{{ session('cadastro_sucesso') }}",
                confirmButtonColor: '#3085d6'
            });
        @endif
    });
    </script>
</body>
</html>