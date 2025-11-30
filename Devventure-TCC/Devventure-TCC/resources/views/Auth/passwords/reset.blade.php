<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="{{ asset('css/Auth/senha.css') }}" rel="stylesheet">
    <title>Criar Nova Senha</title>
</head>
<body>
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            <h2>Criar Nova Senha</h2>
            <p style="text-align: center; margin-bottom: 20px;">
                Crie uma nova senha segura para sua conta.
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="form-group">
                    <label for="password">Nova Senha *</label>
                    <input type="password" id="password" name="password" placeholder="Digite a nova senha" required autofocus>
                    
                    @error('password')
                        <span style="color: #d33; font-size: 0.9em; display: block; margin-top: 5px;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirmar Nova Senha *</label>
                   
                    <input type="password" id="password-confirm" name="password_confirmation" placeholder="Confirme a nova senha" required>
                </div>
                
                @error('email')
                    <div class="form-group" style="text-align: center; color: #d33;">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <button type="submit">Redefinir Senha</button>
            </form>
        </div>
    </main>
</body>
</html>