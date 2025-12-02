<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação em Duas Etapas | DevVenture</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth/2fa_verify.css') }}">
</head>
<body>

    <div class="main-container">
        <div class="brand-logo">
            <img src="{{ asset('img/logo-devventure.png') }}" alt="DevVenture Logo" onerror="this.style.display='none'">
        </div>

        <div class="card">
            <div class="icon-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>

            <h2>Verificação de Segurança</h2>
            <p>Para proteger sua conta no <strong>DevVenture</strong>, insira o código de 6 dígitos enviado ao seu e-mail.</p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @error('msg')
                <div class="alert alert-error">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('2fa.verify.code') }}">
                @csrf 
                <div class="form-group">
                    <label for="code">Código de Verificação</label>
                    <input id="code" type="text" class="input-code" name="code" required autofocus 
                           autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" 
                           maxlength="6" placeholder="0 0 0 0 0 0">
                </div>
                
                <button type="submit" class="btn-submit">Verificar Acesso</button>
            </form>

            <div class="resend-container">
                <p>Não recebeu o código?</p>
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn-resend">Reenviar e-mail</button>
                </form>
            </div>
        </div>
        
        <div class="footer-link">
            <a href="/">Voltar para Home</a>
        </div>
    </div>

</body>
</html>