<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Senha | DevVenture</title>
    
    <link href="<?php echo e(asset('css/Auth/senha.css')); ?>" rel="stylesheet">
</head>
<body>

    <div class="main-container">
        <!-- Logo -->
        <div class="brand-logo">
             <img src="<?php echo e(asset('img/logo-devventure.png')); ?>" alt="DevVenture" onerror="this.style.display='none'">
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

            <form method="POST" action="<?php echo e(route('password.update')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <input type="hidden" name="email" value="<?php echo e($email ?? old('email')); ?>">

                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" id="password" class="input-field" name="password" placeholder="Mínimo de 8 caracteres" required autofocus>
                    
                    <?php $__errorArgs = ['password'];
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

                <div class="form-group">
                    <label for="password-confirm">Confirmar Nova Senha</label>
                    <input type="password" id="password-confirm" class="input-field" name="password_confirmation" placeholder="Repita a nova senha" required>
                </div>
                
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="form-group" style="text-align: center;">
                        <span class="error-msg"><?php echo e($message); ?></span>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit" class="btn-submit">Redefinir Senha</button>
            </form>
        </div>
    </div>

</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>