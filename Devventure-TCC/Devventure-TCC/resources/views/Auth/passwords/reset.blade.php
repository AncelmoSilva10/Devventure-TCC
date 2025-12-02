<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Senha | DevVenture</title>
    
    <link href="{{ asset('css/Auth/senha.css') }}" rel="stylesheet">
</head>
<body>

    <div class="main-container">
        <!-- Logo -->
        <div class="brand-logo">
             <img src="{{ asset('img/logo-devventure.png') }}" alt="DevVenture" onerror="this.style.display='none'">
        </div>

        <div class="card">
            <!-- Ícone de Chave/Segurança -->
            <div class="icon-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>

            <h2>Criar Nova Senha</h2>
            <p>
                Defina uma nova senha forte para sua conta.
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" id="password" class="input-field" name="password" placeholder="Mínimo de 8 caracteres" required autofocus>
                    
                    @error('password')
                        <span class="error-msg">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirmar Nova Senha</label>
                    <input type="password" id="password-confirm" class="input-field" name="password_confirmation" placeholder="Repita a nova senha" required>
                </div>
                
                @error('email')
                    <div class="form-group" style="text-align: center;">
                        <span class="error-msg">{{ $message }}</span>
                    </div>
                @enderror

                <button type="submit" class="btn-submit">Redefinir Senha</button>
            </form>
        </div>
    </div>

</body>
</html>