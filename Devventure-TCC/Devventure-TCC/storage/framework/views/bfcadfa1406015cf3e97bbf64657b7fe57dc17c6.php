<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Área do Professor - Login</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?php echo e(asset('css/Professor/loginProfessor.css')); ?>" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <main class="split-screen-container">
    
    <div class="left-panel">
        <div class="professor-icon-large">
            <img 
                src="<?php echo e(asset('images/professor.png')); ?>" 
                alt="Ícone Professor" 
                class="professor-img-custom"
                onerror="this.style.display='none'; this.parentNode.innerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'white\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'width:80px;height:80px;\'><path d=\'M22 10v6M2 10l10-5 10 5-10 5z\'/><path d=\'M6 12v5c3 3 9 3 12 0v-5\'/></svg>'" 
            >
        </div>
        <div>
            <h2>Portal do Professor</h2>
            <p>Gerencie turmas e acompanhe o desempenho dos alunos.</p>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-content-wrapper">
            
            <div class="form-header">
                <h3 id="form-title">Login</h3>
                <p id="form-subtitle">Bem-vindo de volta, professor!</p>
            </div>

            <div id="stepper-indicators" class="stepper" style="display: none;">
                <div class="step-dot active" data-step="1">1</div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="2">2</div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="3">3</div>
            </div>

            <form method="POST" id="professor-form" enctype="multipart/form-data"
                action="<?php echo e(route('professor.login.action')); ?>" 
                data-login-url="<?php echo e(route('professor.login.action')); ?>"
                data-cadastro-url="<?php echo e(route('professor.cadastro.action')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="form_tipo" id="form_tipo" value="<?php echo e(old('form_tipo', 'login')); ?>">

                <div id="login-section">
                    <div class="form-group">
                        <label for="email-login">E-mail Institucional</label>
                        <input type="email" id="email-login" name="email" placeholder="professor@escola.com" value="<?php echo e(old('email')); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password-login">Senha</label>
                        <div class="senha-wrapper">
                            <input type="password" id="password-login" name="password" placeholder="Sua senha">
                            <button type="button" class="toggle-password" onclick="togglePassword('password-login', this)" tabindex="-1">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        <div style="text-align: right; margin-top: 5px;">
                            <a href="/esqueceu-senha" style="font-size: 0.8rem; color: #555; text-decoration: none;">Esqueceu a senha?</a>
                        </div>
                    </div>
                </div>

                <div id="cadastro-section" style="display: none;">
                    
                    <div class="step-content" data-step="1">
                        <div style="text-align: center; margin-bottom: 15px;">
                            <div class="avatar-circle" id="avatar-wrapper" title="Adicionar Foto">
                                <span id="avatar-preview">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 8.99991C16 11.2091 14.2091 12.9999 12 12.9999C9.79086 12.9999 8 11.2091 8 8.99991C8 6.79077 9.79086 4.99991 12 4.99991C14.2091 4.99991 16 6.79077 16 8.99991Z"/>
                                        <path d="M12 15.9999C8.13401 15.9999 5 18.2385 5 20.9999"/>
                                        <path d="M18 11.5V17.5"/><path d="M21 14.5H15"/>
                                    </svg>
                                </span>
                                <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                            </div>
                            <small style="color: #666; font-size: 0.75rem; margin-top: 5px; display: block;">Foto (Opcional)</small>
                        </div>

                        <div class="form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" value="<?php echo e(old('nome')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="cpf">CPF *</label>
                            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" value="<?php echo e(old('cpf')); ?>">
                            <small id="cpf-feedback" style="display: block; height: 15px; font-size: 0.75rem; margin-top: 2px; color: var(--error);"></small>
                        </div>
                    </div>

                    <div class="step-content" data-step="2" style="display: none;">
                        <div class="form-group">
                            <label for="area">Área de Ensino *</label>
                            <input type="text" id="area" name="area" placeholder="Ex: Matemática" value="<?php echo e(old('area')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="formacao">Formação *</label>
                            <textarea id="formacao" name="formacao" rows="2" style="resize: none;"><?php echo e(old('formacao')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Celular</label>
                            <input type="text" id="telefone" name="telefone" placeholder="(11) 99999-9999" value="<?php echo e(old('telefone')); ?>">
                        </div>
                    </div>

                    <div class="step-content" data-step="3" style="display: none;">
                        <div class="form-group">
                            <label for="email-cadastro">E-mail *</label>
                            <input type="email" id="email-cadastro" name="email" value="<?php echo e(old('email')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password-cadastro">Senha *</label>
                            <div class="senha-wrapper">
                                <input type="password" id="password-cadastro" name="password">
                                <button type="button" class="toggle-password" onclick="togglePassword('password-cadastro', this)" tabindex="-1">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Senha *</label>
                            <div class="senha-wrapper">
                                <input type="password" id="confirm_password" name="password_confirmation">
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)" tabindex="-1">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="buttons-row">
                    <button type="button" id="btn-prev" class="btn-secondary btn-block" style="display: none;">Voltar</button>
                    <button type="submit" id="btn-submit" class="btn-primary btn-block">Entrar</button>
                    <button type="button" id="btn-next" class="btn-primary btn-block" style="display: none;">Próximo</button>
                </div>

            </form>

            <div style="text-align: center; margin-top: 15px;">
                <span id="toggle-text" style="color: #666; font-size: 0.85rem;">Ainda não tem conta?</span>
                <button type="button" id="toggle-btn" style="background: none; border: none; color: var(--primary); font-weight: 700; cursor: pointer; text-decoration: underline; margin-left: 5px; font-size: 0.85rem;">Cadastre-se</button>
            </div>

        </div>
    </div>
  </main>

  <!-- Formulário oculto para reenvio -->
  <form id="form-reenviar-verificacao" action="<?php echo e(route('verification.resend')); ?>" method="POST" style="display: none;">
      <?php echo csrf_field(); ?>
      <!-- Este input value será preenchido dinamicamente se o old() falhar -->
      <input type="hidden" name="email" value="<?php echo e(old('email')); ?>" id="hidden-resend-email">
  </form>

  <script src="<?php echo e(asset('js/Professor/loginProfessor.js')); ?>"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        
        <?php if($errors->any()): ?>
            const errorFields = <?php echo json_encode($errors->keys(), 15, 512) ?>;
            if (errorFields.length > 0) {
                errorFields.forEach(function(field) {
                    const input = document.getElementsByName(field)[0];
                    if (input) input.classList.add('is-invalid');
                });
                
                <?php if(!$errors->has('msg') && !session('needs_verification')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção',
                    html: '<ul style="text-align: left;"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>',
                    confirmButtonColor: '#00796B'
                });
                <?php endif; ?>
            }
        <?php endif; ?>

        
        <?php if($errors->has('msg')): ?>
             Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: "<?php echo e($errors->first('msg')); ?>",
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>

        
        <?php if(session('needs_verification')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Verificação Necessária',
                text: "<?php echo e(session('needs_verification')); ?>", 
                showCancelButton: true,
                confirmButtonText: 'Reenviar Código',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#d33',
                preConfirm: () => {
                    
                    const loginEmail = document.getElementById('email-login').value;
                    const hiddenEmail = document.getElementById('hidden-resend-email');
                    
                   
                    if (!hiddenEmail.value && loginEmail) {
                        hiddenEmail.value = loginEmail;
                    }
                    
                    document.getElementById('form-reenviar-verificacao').submit();
                }
            });
        <?php endif; ?>

        
        <?php if(session('status')): ?>
             Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: "<?php echo e(session('status')); ?>",
                confirmButtonColor: '#00796B'
            });
        <?php endif; ?>
    });
  </script>
</body>
</html><?php /**PATH /Users/Vini/ws_development~/Devventure-TCC/Devventure-TCC/Devventure-TCC/resources/views/Professor/login.blade.php ENDPATH**/ ?>