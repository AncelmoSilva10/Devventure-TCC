<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil do Professor</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    
    <link href="<?php echo e(asset('css/Professor/professorPerfil.css')); ?>" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 

    <main class="page-perfil-professor">
        <div class="container">
            <a href="<?php echo e(route('professorDashboard')); ?>" class="btn-voltar-topo">
                <i class='bx bx-arrow-back'></i>
                <span>Voltar</span>
            </a>

            <h1>Editar Perfil</h1>
            <p>Mantenha suas informações profissionais sempre atualizadas.</p>

            <div class="card-perfil">
                <form method="POST" action="<?php echo e(route('professor.perfil.update')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?> 

                    <div class="form-header">
                        <div class="avatar-upload">
                            <img src="<?php echo e($professor->avatar ? asset('storage/' . $professor->avatar) : asset('images/avatar-default-professor.png')); ?>" alt="Avatar" id="avatar-preview">
                            <label for="avatar" class="btn-trocar-foto" title="Trocar Foto">
                                <i class='bx bx-camera'></i>
                            </label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
                        </div>
                        <div class="info-pessoal">
                             <h2><?php echo e($professor->nome); ?></h2>
                             <p><?php echo e($professor->email); ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?php echo e(old('nome', $professor->nome)); ?>" required>
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
                            <input type="email" id="email" name="email" value="<?php echo e(old('email', $professor->email)); ?>" required>
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
                            <input type="text" id="telefone" name="telefone" value="<?php echo e(old('telefone', $professor->telefone)); ?>">
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

                    
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo e(old('cpf', $professor->cpf)); ?>" required>
                        <?php $__errorArgs = ['cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <input type="text" id="areaEnsino" name="areaEnsino" value="<?php echo e(old('areaEnsino', $professor->areaEnsino)); ?>" required>
                        <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="formacao">Formação Acadêmica</label>
                        <textarea id="formacao" name="formacao" rows="4" required><?php echo e(old('formacao', $professor->formacao)); ?></textarea>
                        <?php $__errorArgs = ['formacao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error-message"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Script para pré-visualização do avatar
        document.getElementById('avatar').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('avatar-preview').src = URL.createObjectURL(file);
            }
        };

        // Script para SweetAlert2
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
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Professor/Perfil.blade.php ENDPATH**/ ?>