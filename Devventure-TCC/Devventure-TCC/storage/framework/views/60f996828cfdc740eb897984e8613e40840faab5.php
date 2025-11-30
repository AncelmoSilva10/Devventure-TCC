<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    
    <title>Editar Perfil</title>
    
    <link href="<?php echo e(asset('css/Aluno/alunoPerfil.css')); ?>" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script> 
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="page-perfil">
        <div class="container">
        <a href="<?php echo e(route('aluno.dashboard')); ?>" class="btn-voltar-topo">
            <i class='bx bx-arrow-back'></i>
            <span>Voltar</span>
        </a>
            <h1>Editar Perfil</h1>
            <p>Mantenha suas informações sempre atualizadas.</p>

            <div class="card-perfil">
                
                <form method="POST" action="<?php echo e(route('aluno.perfil.update')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?> 

        <div class="form-header">
            <div class="avatar-upload">
    <img src="<?php echo e($aluno->avatar ? asset('storage/' . $aluno->avatar) : asset('images/avatar-default.png')); ?>" alt="Avatar" id="avatar-preview">
    <label for="avatar" class="btn-trocar-foto" title="Trocar Foto">
    <i class='bx bx-camera'></i>
    </label>
    <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
</div>
                        <div class="info-pessoal">
                             <h2><?php echo e($aluno->nome); ?></h2>
                             <p><?php echo e($aluno->email); ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?php echo e(old('nome', $aluno->nome)); ?>" required>
                        <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo e(old('email', $aluno->email)); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" value="<?php echo e(old('telefone', $aluno->telefone)); ?>">
                            <?php $__errorArgs = ['telefone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4>Alterar Senha</h4>
                    <p class="subtitle">Deixe os campos abaixo em branco para manter a senha atual.</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="password">Nova Senha</label>
                            <input type="password" id="password" name="password">
                             <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nova Senha</label>
                            <input type="password" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-salvar">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

   
    <script>
        document.getElementById('avatar').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('avatar-preview').src = URL.createObjectURL(file);
            }
        };

         <?php if(session('sweet_success')): ?>
        Swal.fire({
        title: "Sucesso!",
        text: "<?php echo e(session('sweet_success')); ?>", 
        icon: "success",
        confirmButtonText: "Ok"
 });
 <?php endif; ?>
    </script>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Aluno/perfil.blade.php ENDPATH**/ ?>