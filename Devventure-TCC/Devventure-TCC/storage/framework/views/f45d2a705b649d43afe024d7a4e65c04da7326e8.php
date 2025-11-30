<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin</title>
  <link href="<?php echo e(asset('css/Adm/loginAdm.css')); ?>" rel="stylesheet">
</head>
<body>
  <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <main class="container">
    <div class="card">
      <div class="card-header">
        <div class="icon-circle">
          <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
          </svg>
        </div>
        <h2>Login Admin</h2>
      </div>

      <div class="card-content">
    <form id="login-form" action="<?php echo e(url('/login-adm')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="form-group">
            <input type="email" id="email" name="email" class="form-input" placeholder=" " required />
            <label for="email" class="form-label">Email</label>
        </div>
        
        <div class="form-group">
            <div class="senha-wrapper">
                <input type="password" id="password" name="password" class="form-input" placeholder=" " required />
                <label for="password" class="form-label">Senha</label>
                
                <span class="toggle-password" onclick="togglePassword('password', this)">
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <svg class="icon-eye-off d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                </span>
            </div>
        </div>

        <button type="submit" id="submit-btn">Entrar</button>
    </form>
</div>
  </main>
  
  
  <script src="<?php echo e(asset('js/Adm/loginAdmin.js')); ?>"></script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Adm/login.blade.php ENDPATH**/ ?>