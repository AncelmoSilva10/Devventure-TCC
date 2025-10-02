<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Duas Etapas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* =================================== */
        /* CSS COMPLETO E AUTOSSUFICIENTE      */
        /* =================================== */

        /* --- Estilos Base e Reset --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            /* Técnica moderna para centralizar tudo na tela */
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        /* --- Card Principal --- */
        .card {
            background-color: #ffffff;
            padding: 2.5rem 3rem;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            text-align: center;
            border-top: 4px solid #007bff; /* Detalhe de cor primária */
        }

        /* --- Tipografia --- */
        .card h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .card p {
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* --- Alerta de Erro --- */
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        /* --- Formulário e Inputs --- */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #475569;
        }

        .input-code {
            font-family: 'Poppins', monospace;
            font-size: 2.5rem;
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.5em; /* Espaçamento entre os dígitos */
            padding: 0.75rem;
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-code::placeholder {
            color: #cbd5e1;
        }

        .input-code:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.15);
        }

        /* --- Botão de Envio --- */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Verificação de Duas Etapas</h2>
        <p>Enviamos um código de 6 dígitos para o seu e-mail. Por favor, insira-o abaixo para completar o login.</p>

        <form method="POST" action="/verificar-codigo">
            @csrf 
            <div class="form-group">
                <label for="code">Código de Verificação</label>
                <input id="code" 
                       type="text" 
                       class="input-code" 
                       name="code" 
                       required 
                       autofocus 
                       autocomplete="one-time-code" 
                       inputmode="numeric" 
                       pattern="[0-9]{6}" 
                       maxlength="6" 
                       placeholder="------">
            </div>
            
            <button type="submit" class="btn-submit">
                Verificar Código
            </button>
        </form>
    </div>

</body>
</html>