<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="<?php echo e(asset('css/Auth/code.css')); ?>" rel="stylesheet">
    <title>Verificar Código</title>
</head>
<body>
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="container">
        <div class="card">
            <h2>Verificar Código</h2>
            
            <?php if(session('email')): ?>
                <p style="text-align: center; margin-bottom: 20px;">
                    Digite o código de 6 dígitos que enviamos para <strong><?php echo e(session('email')); ?></strong>.
                </p>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.verify.code')); ?>">
                <?php echo csrf_field(); ?>

                
                <input type="hidden" name="email" value="<?php echo e(session('email')); ?>">

                <div class="form-group">
                    <label for="code">Código de Verificação *</label>
                    <input type="text" id="code" name="code" placeholder="_ _ _ _ _ _" required autofocus maxlength="6" style="text-align: center; letter-spacing: 10px; font-size: 1.2em;">
                    
                    <?php $__errorArgs = ['code'];
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

                <button type="submit">Verificar Código</button>
            </form>
             <div class="links" style="margin-top: 15px;">
                <a href="<?php echo e(route('password.request')); ?>">Reenviar código</a>
            </div>
        </div>
    </main>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/auth/passwords/verify.blade.php ENDPATH**/ ?>