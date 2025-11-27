<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/Auth/redefinir.css') }}?v=4" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Recuperar Acesso</title>
</head>
<body>
    
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <h1>Esqueceu a senha?</h1>
            <p class="subtitle">Não se preocupe, enviaremos as instruções de recuperação para o seu e-mail.</p>

            @if (session('status'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'E-mail Enviado!',
                        text: '{{ session('status') }}',
                        confirmButtonColor: '#2563eb'
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">E-mail Cadastrado</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="exemplo@email.com" required autofocus>
                    </div>
                    
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Enviar Código de Recuperação
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>

            <div class="footer-links">
                <a href="{{ url()->previous() }}" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Voltar para o Login
                </a>
            </div>
        </div>
    </main>
</body>
</html>