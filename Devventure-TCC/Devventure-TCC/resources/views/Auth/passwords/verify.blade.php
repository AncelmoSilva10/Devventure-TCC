<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="{{ asset('css/Auth/code.css') }}" rel="stylesheet">
    <title>Verificar Código</title>
</head>
<body>
    @include('layouts.navbar')

    <main class="container">
        <div class="card">
            <h2>Verificar Código</h2>
            
            @if (session('email'))
                <p style="text-align: center; margin-bottom: 20px;">
                    Digite o código de 6 dígitos que enviamos para <strong>{{ session('email') }}</strong>.
                </p>
            @endif

            <form method="POST" action="{{ route('password.verify.code') }}">
                @csrf

                
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="form-group">
                    <label for="code">Código de Verificação *</label>
                    <input type="text" id="code" name="code" placeholder="_ _ _ _ _ _" required autofocus maxlength="6" style="text-align: center; letter-spacing: 10px; font-size: 1.2em;">
                    
                    @error('code')
                        <span style="color: #d33; font-size: 0.9em; display: block; margin-top: 5px;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit">Verificar Código</button>
            </form>
             <div class="links" style="margin-top: 15px;">
                <a href="{{ route('password.request') }}">Reenviar código</a>
            </div>
        </div>
    </main>
</body>
</html>