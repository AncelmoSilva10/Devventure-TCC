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
    <h1>Relatório de Entregas - <?php echo e($turma->nome_turma); ?></h1>
    <div class="meta">
        Data de Emissão: <?php echo e(date('d/m/Y H:i')); ?> <br>
        Total de Alunos: <?php echo e($alunos->count()); ?> | Total de Exercícios: <?php echo e($exercicios->count()); ?>

    </div>

       <table>
        <thead>
            <tr>
                <th class="aluno-col">Aluno</th>
                <?php $__currentLoopData = $exercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e(Str::limit($ex->nome, 15)); ?></th> 
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>Total Pts</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $alunos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aluno): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="aluno-col"><?php echo e($aluno->nome); ?></td>
                
                <?php $__currentLoopData = $exercicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Procura a resposta na coleção do aluno
                        $resposta = $aluno->respostasExercicios->where('exercicio_id', $ex->id)->first();
                    ?>

                    <?php if($resposta): ?>
                        <td class="entregue"><?php echo e(number_format($resposta->nota, 1, ',', '')); ?></td>
                    <?php else: ?>
                        <td class="pendente">PENDENTE</td>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <td><?php echo e($aluno->total_pontos); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\Users\ancel\Documents\MeusProjetos\Devventure---TCC\Devventure-TCC\Devventure-TCC\resources\views/Professor/relatorios/pdf_export.blade.php ENDPATH**/ ?>