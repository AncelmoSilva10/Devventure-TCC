<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaAluno extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'respostas_exercicios'; 

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aluno_id',
        'pergunta_id',
        'resposta',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    
    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id');
    }
}