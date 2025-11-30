<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="<?php echo e(asset('css/Auth/senha.css')); ?>" rel="stylesheet">
    <title>Criar Nova Senha</title>
</head>
<body>
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="container">
        <div class="card">
            <h2>Criar Nova Senha</h2>
            <p style="text-align: center; margin-bottom: 20px;">
                Crie uma nova senha segura para sua conta.
            </p>

            <form method="POST" action="<?php echo e(route('password.update')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <input type="hidden" name="email" value="<?php echo e($email ?? old('email')); ?>">

                <div class="form-group">
                    <label for="password">Nova Senha *</label>
                    <input type="password" id="password" name="password" placeholder="Digite a nova senha" required autofocus>
                    
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span style="color: #d33; font-size: 0.9em; display: block; margin-top: 5px;">
                            <strong><?php echo e($message); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirmar Nova Senha *</label>
                   
                    <input type="password" id="password-confirm" name="password_confirmation" placeholder="Confirme a nova senha" required>
                </div>
                
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="form-group" style="text-align: center; color: #d33;">
                        <span><?php echo e($message); ?></span>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit">Redefinir Senha</button>
            </form>
        </div>
    </main>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>