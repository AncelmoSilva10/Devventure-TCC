<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="{{ asset('css/Auth/email.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Redefinir Senha</title>
</head>
<body>
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            <h2>Redefinir Senha</h2>
            <p style="text-align: center; margin-bottom: 20px;">Digite seu e-mail e enviaremos um código de verificação para você redefinir sua senha.</p>

            
            @if (session('status'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Verifique seu E-mail!',
                        text: '{{ session('status') }}',
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Digite seu email" required autofocus>
                    
                    @error('email')
                        <span style="color: #d33; font-size: 0.9em; display: block; margin-top: 5px;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit">Enviar Código de Recuperação</button>
            </form>
            <div class="links" style="margin-top: 15px;">
                <a href="{{ url()->previous() }}">Voltar</a>
            </div>
        </div>
    </main>
</body>
</html>