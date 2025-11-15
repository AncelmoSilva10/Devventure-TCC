<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoRespostaProva extends Model
{
    use HasFactory;

    protected $table = 'aluno_respostas_provas'; 

    protected $fillable = [
        'aluno_prova_tentativa_id',
        'prova_questao_id',
        'prova_alternativa_id',
        'resposta_texto',
        'correta', 
    ];

    protected $casts = [
        'correta' => 'boolean',
    ];

    public function tentativaProva()
    {
        return $this->belongsTo(AlunoProvaTentativa::class, 'aluno_prova_tentativa_id');
    }

    public function questao()
    {
        return $this->belongsTo(ProvaQuestao::class, 'prova_questao_id');
    }

    public function provaAlternativa()
    {
        return $this->belongsTo(ProvaAlternativa::class, 'prova_alternativa_id');
    }
}