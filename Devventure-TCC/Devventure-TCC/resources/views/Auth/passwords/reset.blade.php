<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="{{ asset('css/Auth/novaSenha.css') }}?v=2" rel="stylesheet">
    <title>Criar Nova Senha</title>
</head>
<body>
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
            </div>

            <h2>Criar Nova Senha</h2>
            <p>Sua identidade foi verificada. Defina uma nova senha forte para sua conta.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        
                        <input type="password" id="password" name="password" placeholder="Mínimo de 8 caracteres" required autofocus>

                        <button type="button" class="toggle-password-btn" data-target="password" aria-label="Mostrar senha">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirmar Nova Senha</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path><path d="m9 16 2 2 4-4"></path></svg>
                        
                        <input type="password" id="password-confirm" name="password_confirmation" placeholder="Repita a senha" required>

                        <button type="button" class="toggle-password-btn" data-target="password-confirm" aria-label="Mostrar senha">
                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                @error('email')
                    <div class="form-group"><span class="error-msg" style="text-align: center;">{{ $message }}</span></div>
                @enderror

                <button type="submit">
                    Definir Nova Senha
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SVGs para os dois estados
            const eyeOpenSVG = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            const eyeClosedSVG = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

            // Seleciona todos os botões de alternar senha
            const toggleButtons = document.querySelectorAll('.toggle-password-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Pega o ID do input alvo pelo atributo data-target
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const svgIcon = this.querySelector('svg');

                    // Verifica o estado atual e troca
                    if (input.getAttribute('type') === 'password') {
                        input.setAttribute('type', 'text');
                        svgIcon.innerHTML = eyeClosedSVG; // Muda para olho fechado/riscado
                        this.setAttribute('aria-label', 'Ocultar senha');
                    } else {
                        input.setAttribute('type', 'password');
                        svgIcon.innerHTML = eyeOpenSVG; // Volta para olho aberto
                        this.setAttribute('aria-label', 'Mostrar senha');
                    }
                });
            });
        });
    </script>
</body>
</html>