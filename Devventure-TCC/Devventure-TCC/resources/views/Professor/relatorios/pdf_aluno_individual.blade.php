<!DOCTYPE html>
<html>
<head>
    <title>Relatório Individual</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .header p { color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #00796b; color: white; }
        
        .status-pendente { color: #d32f2f; font-weight: bold; background-color: #ffebee; text-align: center; }
        .status-entregue { color: #1b5e20; font-weight: bold; text-align: center; }
        
        .summary { margin-bottom: 20px; padding: 10px; background-color: #f1f5f9; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Desempenho Individual</h1>
        <p>Aluno: <strong>{{ $aluno->nome }}</strong> | Turma: {{ $turma->nome_turma }}</p>
        <p>Data de Emissão: {{ date('d/m/Y') }}</p>
    </div>

    <div class="summary">
        <strong>Resumo:</strong><br>
        Total de Exercícios da Turma: {{ $dados->count() }}<br>
        Entregues: {{ $dados->where('status', 'Entregue')->count() }}<br>
        Pendentes: {{ $dados->where('status', 'PENDENTE')->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="40%">Exercício</th>
                <th width="15%">Status</th>
                <th width="20%">Data Envio</th>
                <th width="10%">Nota</th>
                <th width="15%">Conceito</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $item)
            <tr>
                <td>{{ $item['titulo'] }}</td>
                
                @if($item['status'] == 'PENDENTE')
                    <td class="status-pendente">PENDENTE</td>
                @else
                    <td class="status-entregue">Entregue</td>
                @endif

                <td>{{ $item['data_envio'] }}</td>
                <td>{{ $item['nota'] }}</td>
                <td>{{ $item['conceito'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>