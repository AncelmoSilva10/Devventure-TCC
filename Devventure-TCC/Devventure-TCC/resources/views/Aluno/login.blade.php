<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Área do Aluno - Login</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/Aluno/aluno.css') }}" rel="stylesheet"> 
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  @include('layouts.navbar')

  <main class="split-screen-container">
    
    <div class="left-panel">
        <div class="student-icon-large">
            <img 
                src="{{ asset('images/aluna.png') }}" 
                alt="Ícone Aluno" 
                class="professor-img-custom"
                onerror="this.style.display='none'; this.parentNode.innerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'white\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'width:80px;height:80px;\'><path d=\'M12 14l9-5-9-5-9 5 9 5z\'/><path d=\'M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z\'/></svg>'" 
            >
        </div>
        
        <div>
            <h2>Portal do Aluno</h2>
            <p>Acesse suas aulas, notas e interaja com professores.</p>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-content-wrapper">
            
            <div class="form-header">
                <h3 id="form-title">Login</h3>
                <p id="form-subtitle">Olá, estudante! Bem-vindo de volta.</p>
            </div>

            <div id="stepper-indicators" class="stepper" style="display: none;">
                <div class="step-dot active" data-step="1">1</div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="2">2</div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="3">3</div>
            </div>

            <form method="POST" id="aluno-form" enctype="multipart/form-data"
                action="{{ route('aluno.login') }}" 
                data-login-url="{{ route('aluno.login') }}"
                data-cadastro-url="{{ route('aluno.cadastrar') }}">
                @csrf

                <input type="hidden" name="form_tipo" id="form_tipo" value="{{ old('form_tipo', 'login') }}">

                <div id="login-section">
                    <div class="form-group">
                        <label for="email-login">E-mail</label>
                        <input type="email" id="email-login" name="email" placeholder="aluno@escola.com" value="{{ old('email') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="password-login">Senha</label>
                        <div class="senha-wrapper">
                            <input type="password" id="password-login" name="password" placeholder="Sua senha">
                            <button type="button" class="toggle-password" onclick="togglePassword('password-login', this)" tabindex="-1">
                                <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="icon-eye-off" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
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
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                            </div>
                            <small style="color: #666; font-size: 0.75rem; margin-top: 5px; display: block;">Foto (Opcional)</small>
                        </div>

                        <div class="form-group">
                            <label for="nome">Nome Completo *</label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="ra">RA / Matrícula *</label>
                            <input type="text" id="ra" name="ra" placeholder="Ex: 2023001" maxlength="20" value="{{ old('ra') }}">
                        </div>
                    </div>

                    <div class="step-content" data-step="2" style="display: none;">
                        <div class="form-group">
                            <label for="semestre">Semestre Atual *</label>
                            <select id="semestre" name="semestre">
                                <option value="">Selecione...</option>
                                <option value="1" {{ old('semestre') == '1' ? 'selected' : '' }}>1º Semestre</option>
                                <option value="2" {{ old('semestre') == '2' ? 'selected' : '' }}>2º Semestre</option>
                                <option value="3" {{ old('semestre') == '3' ? 'selected' : '' }}>3º Semestre</option>
                                <option value="4" {{ old('semestre') == '4' ? 'selected' : '' }}>4º Semestre</option>
                                <option value="5" {{ old('semestre') == '5' ? 'selected' : '' }}>5º Semestre</option>
                                <option value="6" {{ old('semestre') == '6' ? 'selected' : '' }}>6º Semestre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="telefone">Celular</label>
                            <input type="text" id="telefone" name="telefone" placeholder="(11) 99999-9999" value="{{ old('telefone') }}">
                        </div>
                    </div>

                    <div class="step-content" data-step="3" style="display: none;">
                        <div class="form-group">
                            <label for="email-cadastro">E-mail *</label>
                            <input type="email" id="email-cadastro" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label for="password-cadastro">Senha *</label>
                            <div class="senha-wrapper">
                                <input type="password" id="password-cadastro" name="password">
                                <button type="button" class="toggle-password" onclick="togglePassword('password-cadastro', this)" tabindex="-1">
                                    <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg class="icon-eye-off" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Senha *</label>
                            <div class="senha-wrapper">
                                <input type="password" id="confirm_password" name="password_confirmation">
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)" tabindex="-1">
                                    <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg class="icon-eye-off" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
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

  <!-- FORMULÁRIO OCULTO PARA REENVIO DE CÓDIGO -->
  <form id="form-reenviar-verificacao" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
      @csrf
      <!-- O value será preenchido dinamicamente se o old('email') falhar -->
      <input type="hidden" name="email" value="{{ old('email') }}" id="hidden-resend-email">
  </form>

  <script src="{{ asset('js/Aluno/loginAluno.js') }}"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
       
        @if ($errors->any())
            const errorFields = @json($errors->keys());
            if (errorFields.length > 0) {
                errorFields.forEach(function(field) {
                    const input = document.getElementsByName(field)[0];
                    if (input) input.classList.add('is-invalid');
                });
                
               
                @if(!$errors->has('msg') && !session('needs_verification'))
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção',
                    html: '<ul style="text-align: left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#007bff'
                });
                @endif
            }
        @endif
 
        @if ($errors->has('msg'))
             Swal.fire({
                icon: 'error',
                title: 'Erro de Acesso',
                text: "{{ $errors->first('msg') }}",
                confirmButtonColor: '#d33'
            });
        @endif

        @if (session('needs_verification'))
            Swal.fire({
                icon: 'warning',
                title: 'Verificação Necessária',
                text: "{{ session('needs_verification') }}", 
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
        @endif
        
        @if (session('status'))
             Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: "{{ session('status') }}",
                confirmButtonColor: '#007bff'
            });
        @endif
    });
  </script>
</body>
</html>