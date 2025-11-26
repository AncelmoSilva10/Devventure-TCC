<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Entregas</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; } /* Fonte menor para caber */
        h1 { color: #333; font-size: 16px; margin-bottom: 5px; }
        .meta { color: #666; margin-bottom: 15px; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; table-layout: fixed; } /* Layout fixo ajuda */
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; word-wrap: break-word; }
        
        th { background-color: #00796b; color: white; font-weight: bold; font-size: 9px; }
        
        .aluno-col { text-align: left; width: 150px; font-weight: bold; }
        
        /* Status Colors */
        .pendente { color: #d32f2f; background-color: #ffebee; font-weight: bold; font-size: 9px; }
        .entregue { color: #1b5e20; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Relatório de Entregas - {{ $turma->nome_turma }}</h1>
    <div class="meta">
        Data de Emissão: {{ date('d/m/Y H:i') }} <br>
        Total de Alunos: {{ $alunos->count() }} | Total de Exercícios: {{ $exercicios->count() }}
    </div>

       <table>
        <thead>
            <tr>
                <th class="aluno-col">Aluno</th>
                @foreach($exercicios as $ex)
                    <th>{{ Str::limit($ex->nome, 15) }}</th> 
                @endforeach
                <th>Total Pts</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alunos as $aluno)
            <tr>
                <td class="aluno-col">{{ $aluno->nome }}</td>
                
                @foreach($exercicios as $ex)
                    @php
                        // Procura a resposta na coleção do aluno
                        $resposta = $aluno->respostasExercicios->where('exercicio_id', $ex->id)->first();
                    @endphp

                    @if($resposta)
                        <td class="entregue">{{ number_format($resposta->nota, 1, ',', '') }}</td>
                    @else
                        <td class="pendente">PENDENTE</td>
                    @endif
                @endforeach

                <td>{{ $aluno->total_pontos }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>