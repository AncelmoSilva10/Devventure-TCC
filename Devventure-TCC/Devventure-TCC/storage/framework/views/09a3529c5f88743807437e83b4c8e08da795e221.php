<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar Moderna</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="<?php echo e(asset('css/layouts/navbar.css')); ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">

        
        <a href="/" class="navbar-logo">
            <img src="<?php echo e(asset('images/logoDevventure.png')); ?>" alt="Logo Devventure">
        </a>

        
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        
     <div class="navbar-links" id="navbar-links">
    <a href="/"><i class="fa fa-home"></i><span>Home</span></a>

    
    <?php if(auth()->guard('aluno')->check()): ?>
        <a href="<?php echo e(route('aluno.dashboard')); ?>">
            <i class="fa fa-home"></i><span>Painel Aluno</span>
        </a>

        <a href="<?php echo e(route('aluno.turma')); ?>">
            <i class="fa fa-users"></i><span>Turmas</span>
        </a>
    <?php endif; ?>

    
    <?php if(!Auth::guard('aluno')->check() && !Auth::guard('professor')->check()): ?>
        <a href="<?php echo e(route('login.aluno')); ?>">
            <i class="fa fa-graduation-cap"></i><span>Login Aluno</span>
        </a>

        <a href="<?php echo e(route('login.professor')); ?>">
            <i class="fa fa-user"></i><span>Login Professor</span>
        </a>
    <?php endif; ?>
</div>

        
        <?php if(auth()->guard('aluno')->check()): ?>

            <div class="navbar-profile">
                <button id="profile-dropdown-btn-aluno" class="profile-button">
                    <img src="<?php echo e(Auth::guard('aluno')->user()->avatar ? asset('storage/' . Auth::guard('aluno')->user()->avatar) : asset('images/default-avatar.png')); ?>" 
                         alt="Foto de Perfil" class="profile-avatar">
                    <span class="profile-name"><?php echo e(Auth::guard('aluno')->user()->nome); ?></span>
                    <i class='bx bx-chevron-down'></i>
                </button>

             
                <div id="profile-dropdown-aluno" class="profile-dropdown-content">
                    <a href="<?php echo e(route('aluno.perfil.edit')); ?>" class="dropdown-item">
                        <i class='bx bxs-edit'></i>
                        <span>Editar Perfil</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="<?php echo e(route('aluno.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item dropdown-item-logout">
                            <i class='bx bx-log-out'></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if(auth()->guard('professor')->check()): ?>
             <div class="navbar-links" id="navbar-links">

           <a href="<?php echo e(route('professorDashboard')); ?>">
            <i class="fa fa-home"></i><span>Painel Professor</span>
        </a>

            </div>
        
            <div class="navbar-profile">
                <button id="profile-dropdown-btn-professor" class="profile-button">
                    <img src="<?php echo e(Auth::guard('professor')->user()->avatar ? asset('storage/' . Auth::guard('professor')->user()->avatar) : asset('images/default-avatar.png')); ?>" 
                         alt="Foto de Perfil" class="profile-avatar">
                    <span class="profile-name"><?php echo e(Auth::guard('professor')->user()->nome); ?></span>
                    <i class='bx bx-chevron-down'></i>
                </button>

                <div id="profile-dropdown-professor" class="profile-dropdown-content">
                    <a href="<?php echo e(route('professor.perfil.edit')); ?>" class="dropdown-item">
                        <i class='bx bxs-edit'></i>
                        <span>Editar Perfil</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="<?php echo e(route('professor.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item dropdown-item-logout">
                            <i class='bx bx-log-out'></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</nav>

<script src="<?php echo e(asset('js/layouts/navbar.js')); ?>"></script>

</body>
</html>
<?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>