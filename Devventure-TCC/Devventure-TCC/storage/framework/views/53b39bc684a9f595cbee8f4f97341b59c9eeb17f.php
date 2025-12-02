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

  <main class="split-screen-container">
    
    <div class="left-panel">
        <div class="admin-icon-large">
            <img 
                src="<?php echo e(asset('images/admin.png')); ?>" 
                alt="Ícone Admin" 
                class="admin-img-custom"
                onerror="this.style.display='none'; this.parentNode.innerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'white\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'width:100px;height:100px;\'><path d=\'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z\'/><path d=\'M12 6v6l4 2\'/></svg>'" 
            >
        </div>
        <div>
            <h2>Portal Admin</h2>
            <p>Gerenciamento completo do sistema e usuários.</p>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-content-wrapper">
            
            <div class="form-header">
                <h3>Login</h3>
                <p>Bem-vindo de volta, administrador!</p>
            </div>

            <form id="login-form" action="<?php echo e(url('/login-adm')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="admin@sistema.com" required />
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="senha-wrapper">
                        <input type="password" id="password" name="password" placeholder="Sua senha" required />
                        
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                            <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg class="icon-eye-off d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="submit-btn">Entrar</button>
            </form>
        </div>
    </div>
  </main>
  
  <script src="<?php echo e(asset('js/Adm/loginAdmin.js')); ?>"></script>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Adm/login.blade.php ENDPATH**/ ?>