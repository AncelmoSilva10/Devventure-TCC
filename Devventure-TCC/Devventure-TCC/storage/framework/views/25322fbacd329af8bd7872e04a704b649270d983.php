<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Código de Acesso</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; color: #333; }
        .container { padding: 20px; max-width: 400px; margin: auto; border: 1px solid #ddd; border-radius: 8px; }
        h1 { color: #00796B; }
        .code { font-size: 2.5em; font-weight: bold; letter-spacing: 5px; margin: 20px 0; padding: 10px; background-color: #f1f1f1; border-radius: 5px; }
        p { font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seu Código de Acesso</h1>
        <p>Use o código abaixo para completar o seu login na plataforma.</p>
        <div class="code"><?php echo e($code); ?></div>
        <p>Este código expira em 10 minutos.</p>
    </div>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/emails/two_factor_code.blade.php ENDPATH**/ ?>