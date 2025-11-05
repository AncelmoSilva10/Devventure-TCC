<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Convites Enviados</title>
    
    {{-- 1. PUXANDO SEU ARQUIVO CSS DEDICADO --}}
    <link rel="stylesheet" href="{{ asset('css/Professor/convites-enviados.css') }}">
    
    {{-- 2. ADICIONANDO FONT AWESOME (PARA OS ÍCONES) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
</head>
<body>

    @include('layouts.navbar')

    {{-- 3. CONTEÚDO PRINCIPAL DA PÁGINA --}}
    <main class="convites-container">

    <a href="javascript:history.back()" class="btn-voltar">
        <i class="fa-solid fa-arrow-left"></i>
        Voltar
    </a>
        
        <header class="convites-header">
            {{-- Ícone adicionado ao cabeçalho --}}
            <i class="fa-solid fa-envelope-paper header-icon"></i>
            <div>
                <h2>Meus Convites Enviados</h2>
                <p>Acompanhe aqui o status dos convites que você enviou para seus alunos.</p>
            </div>
        </header>

        {{-- Bloco para Alertas (HTML sem alteração) --}}
        @if (session('sucesso'))
            <div class="alert alert-sucesso" role="alert">
                <span>{{ session('sucesso') }}</span>
                <button type="button" class="alert-close-btn">&times;</button>
            </div>
        @endif
        @if (session('erro'))
            <div class="alert alert-erro" role="alert">
                <span>{{ session('erro') }}</span>
                <button type="button" class="alert-close-btn">&times;</button>
            </div>
        @endif

        {{-- 4. A LISTA DE CONVITES --}}
        <div class="convites-list-wrapper">
            @if ($convites->isEmpty())
                {{-- Estado Vazio (Empty State) melhorado --}}
                <div class="convite-item-empty">
                    <i class="fa-solid fa-box-open empty-icon"></i>
                    <p>Você ainda não enviou nenhum convite.</p>
                </div>
            @else
                <ul class="convites-list">
                    @foreach ($convites as $convite)
                        <li class="convite-item">
                            
                            {{-- Informações do Convite (Estrutura atualizada com ícones) --}}
                            <div class="convite-info">
                                <div class="info-linha">
                                    <i class="fa-solid fa-chalkboard-user info-icon"></i>
                                    <span class="info-texto">
                                        Turma: <strong>{{ $convite->turma->nome ?? 'Turma Apagada' }}</strong>
                                    </span>
                                </div>
                                <div class="info-linha">
                                    <i class="fa-solid fa-user-graduate info-icon"></i>
                                    <span class="info-texto">
                                        Para: <strong>{{ $convite->aluno->nome ?? 'Aluno Apagado' }}</strong> 
                                        (RA: {{ $convite->aluno->ra ?? 'N/A' }})
                                    </span>
                                </div>
                                <div class="info-linha info-data">
                            <i class="fa-solid fa-calendar-day info-icon"></i>
                            <span class="info-texto">
                                Enviado em: 
                                
                                {{ $convite->created_at->timezone('America/Sao_Paulo')->isoFormat('D [de] MMMM [de] YYYY, \à\s HH:mm') }}
                            </span>
                        </div>
                            </div>
                            
                            {{-- Status e Ação (Badges atualizados com ícones) --}}
                            <div class="convite-status-acao">
                                @if ($convite->status == 'pendente')
                                    <span class="badge badge-pendente">
                                        <i class="fa-solid fa-clock badge-icon"></i>
                                        Pendente
                                    </span>
                                @elseif ($convite->status == 'aceito')
                                    <span class="badge badge-aceito">
                                        <i class="fa-solid fa-check-circle badge-icon"></i>
                                        Aceito
                                    </span>
                                @elseif ($convite->status == 'recusado') 
                                    <span class="badge badge-recusado">
                                        <i class="fa-solid fa-times-circle badge-icon"></i>
                                        Recusado
                                    </span>
                                @endif

                                @if ($convite->status == 'pendente')
                                    <form action="{{ route('professor.convites.cancelar', $convite) }}" method="POST" class="form-cancelar-convite">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-cancelar">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </main>

    @include('layouts.footer')

    {{-- O JS continua o mesmo, não precisa de alteração --}}
    <script src="{{ asset('js/Professor/convites-enviados.js') }}"></script>

</body>
</html>