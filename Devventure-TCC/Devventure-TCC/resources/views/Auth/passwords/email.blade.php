<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha | DevVenture</title>
    
    <link href="{{ asset('css/Auth/email.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="main-container">
        <div class="brand-logo">
             <img src="{{ asset('img/logo-devventure.png') }}" alt="DevVenture" onerror="this.style.display='none'">
        </div>

        <div class="card">
            <div class="icon-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>

            <h2>Redefinir Senha</h2>
            <p>Esqueceu sua senha? Sem problemas. Digite seu e-mail abaixo e enviaremos um link de recuperação.</p>

            @if (session('status'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'E-mail enviado!',
                        text: '{{ session('status') }}',
                        confirmButtonColor: '#007bff'
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail Cadastrado</label>
                    <input type="email" id="email" class="input-field" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
                    
                    @error('email')
                        <span class="error-msg">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
            </form>

            <div class="back-container">
                <a href="{{ url()->previous() }}" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Voltar 
                </a>
            </div>
        </div>
    </div>

</body>
</html>