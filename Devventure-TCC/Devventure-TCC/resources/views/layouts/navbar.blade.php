<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar Moderna</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="{{ asset('css/layouts/navbar.css') }}" rel="stylesheet">
</head>
<body>
    @php
        $currentPath = Request::path();
        
        $colorClass = '';

        // Define a classe de cor/estilo do professor
        if (Str::startsWith($currentPath, 'professorDashboard') || Str::startsWith($currentPath, 'perfilProfessor')) {
        $colorClass = 'professor-nav';
        } elseif (Str::startsWith($currentPath, 'aluno') || Str::startsWith($currentPath, 'perfilAluno')) {
            $colorClass = 'aluno-nav';
        }
                     
        // Define o status da nav
        $statusClass = (Str::startsWith ($currentPath, 'professorDashboard') 
        || Str::startsWith($currentPath, 'alunoDashboard') 
        || Str::startsWith($currentPath, 'perfil') 
        || Str::startsWith($currentPath, 'aluno/perfil')) ? 'status-nav' : '';
        
        $navClasses = trim($colorClass . ' ' . $statusClass);
    @endphp

<nav class="navbar {{ $navClasses }}">
    <div class="navbar-container">

        {{-- LOGO --}}
        <a href="/" class="navbar-logo">
            <img src="{{ asset('images/logoDevventure.png') }}" alt="Logo Devventure">
        </a>

        {{-- BOTÃO MOBILE --}}
        <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        {{-- LINKS PRINCIPAIS --}}
     <div class="navbar-links" id="navbar-links">
    <a href="/"><i class="fa fa-home"></i><span>Home</span></a>

    {{-- Se for aluno logado --}}
    @auth('aluno')
        <a href="{{ route('aluno.dashboard') }}">
            <i class="fa fa-user-graduate"></i><span>Painel Aluno</span>
        </a>

        <a href="{{ route('aluno.turma') }}">
            <i class="fa fa-users"></i><span>Turmas</span>
        </a>
    @endauth

    {{-- Exibir apenas se NENHUM usuário estiver autenticado --}}
    @if(!Auth::guard('aluno')->check() && !Auth::guard('professor')->check())
        <a href="{{ route('login.aluno') }}">
            <i class="fa fa-graduation-cap"></i><span>Login Aluno</span>
        </a>

        <a href="{{ route('login.professor') }}">
            <i class="fa fa-user"></i><span>Login Professor</span>
        </a>
    @endif
</div>

        {{-- PERFIL DO ALUNO --}}
        @auth('aluno')

            <div class="navbar-profile">
                <button id="profile-dropdown-btn-aluno" class="profile-button">
                    <img src="{{ Auth::guard('aluno')->user()->avatar ? asset('storage/' . Auth::guard('aluno')->user()->avatar) : asset('images/default-avatar.png') }}" 
                         alt="Foto de Perfil" class="profile-avatar">
                    <span class="profile-name">{{ Auth::guard('aluno')->user()->nome }}</span>
                    <i class='bx bx-chevron-down'></i>
                </button>

             
                <div id="profile-dropdown-aluno" class="profile-dropdown-content">
                    <a href="{{ route('aluno.perfil.edit') }}" class="dropdown-item">
                        <i class='bx bxs-edit'></i>
                        <span>Editar Perfil</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('aluno.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-item-logout">
                            <i class='bx bx-log-out'></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        {{-- PERFIL DO PROFESSOR --}}
        @auth('professor')
             <div class="navbar-links" id="navbar-links">

           <a href="{{ route('professorDashboard') }}">
            <i class="fas fa-chalkboard-teacher"></i><span>Painel Professor</span>
        </a>

            </div>
        
            <div class="navbar-profile">
                <button id="profile-dropdown-btn-professor" class="profile-button">
                    <img src="{{ Auth::guard('professor')->user()->avatar ? asset('storage/' . Auth::guard('professor')->user()->avatar) : asset('images/default-avatar.png') }}" 
                         alt="Foto de Perfil" class="profile-avatar">
                    <span class="profile-name">{{ Auth::guard('professor')->user()->nome }}</span>
                    <i class='bx bx-chevron-down'></i>
                </button>

                <div id="profile-dropdown-professor" class="profile-dropdown-content">
                    <a href="{{ route('professor.perfil.edit') }}" class="dropdown-item">
                        <i class='bx bxs-edit'></i>
                        <span>Editar Perfil</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('professor.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-item-logout">
                            <i class='bx bx-log-out'></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
 
    </div>
</nav>

<script src="{{ asset('js/layouts/navbar.js') }}"></script>

</body>
</html>