<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="<?php echo e(asset('css/Auth/email.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Redefinir Senha</title>
</head>
<body>
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="container">
        <div class="card">
            <h2>Redefinir Senha</h2>
            <p style="text-align: center; margin-bottom: 20px;">Digite seu e-mail e enviaremos um código de verificação para você redefinir sua senha.</p>

            
            <?php if(session('status')): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Verifique seu E-mail!',
                        text: '<?php echo e(session('status')); ?>',
                    });
                </script>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Digite seu email" required autofocus>
                    
                    <?php $__errorArgs = ['email'];
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

                <button type="submit">Enviar Código de Recuperação</button>
            </form>
            <div class="links" style="margin-top: 15px;">
                <a href="<?php echo e(url()->previous()); ?>">Voltar</a>
            </div>
        </div>
    </main>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>