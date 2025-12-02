<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha | DevVenture</title>
    
    <link href="<?php echo e(asset('css/Auth/email.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="main-container">
        <div class="brand-logo">
             <img src="<?php echo e(asset('img/logo-devventure.png')); ?>" alt="DevVenture" onerror="this.style.display='none'">
        </div>

        <div class="card">
            <div class="icon-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>

            <h2>Redefinir Senha</h2>
            <p>Esqueceu sua senha? Sem problemas. Digite seu e-mail abaixo e enviaremos um link de recuperação.</p>

            <?php if(session('status')): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'E-mail enviado!',
                        text: '<?php echo e(session('status')); ?>',
                        confirmButtonColor: '#007bff'
                    });
                </script>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email">E-mail Cadastrado</label>
                    <input type="email" id="email" class="input-field" name="email" value="<?php echo e(old('email')); ?>" placeholder="seu@email.com" required autofocus>
                    
                    <?php $__errorArgs = ['email'];
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

                <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
            </form>

            <div class="back-container">
                <a href="<?php echo e(url()->previous()); ?>" class="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Voltar 
                </a>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>