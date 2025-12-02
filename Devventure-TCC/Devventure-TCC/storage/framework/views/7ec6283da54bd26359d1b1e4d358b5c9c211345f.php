<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código | DevVenture</title>
    
    <link href="<?php echo e(asset('css/Auth/code.css')); ?>" rel="stylesheet">
    <!-- Adicionando SweetAlert caso precise para mensagens de erro/sucesso -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="main-container">
        <!-- Logo (Opcional) -->
        <div class="brand-logo">
             <img src="<?php echo e(asset('img/logo-devventure.png')); ?>" alt="DevVenture" onerror="this.style.display='none'">
        </div>

        <div class="card">
            <!-- Ícone de Shield/Verificação -->
            <div class="icon-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>

            <h2>Verificar Código</h2>
            
            <?php if(session('email')): ?>
                <p>
                    Digite o código de 6 dígitos que enviamos para<br>
                    <strong><?php echo e(session('email')); ?></strong>
                </p>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.verify.code')); ?>">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="email" value="<?php echo e(session('email')); ?>">

                <div class="form-group">
                    <label for="code">Código de Segurança</label>
                    <!-- Input estilizado com espaçamento largo -->
                    <input type="text" id="code" class="input-code" name="code" 
                           placeholder="000000" required autofocus 
                           maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                    
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-msg">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-submit">Validar Código</button>
            </form>

            <div class="footer-actions">
                <a href="<?php echo e(route('password.request')); ?>" class="link-resend">Não recebeu? Reenviar código</a>
                
                <!-- Botão Voltar -->
                <a href="<?php echo e(url()->previous()); ?>" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/auth/passwords/verify.blade.php ENDPATH**/ ?>