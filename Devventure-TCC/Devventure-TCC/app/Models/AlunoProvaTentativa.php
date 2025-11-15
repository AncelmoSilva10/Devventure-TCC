<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aluno; 
use App\Models\Prova;
use App\Models\AlunoRespostaProva;

class AlunoProvaTentativa extends Model
{
    use HasFactory;

    protected $fillable = ['prova_id', 'aluno_id', 'hora_inicio', 'hora_fim', 'pontuacao_final'];

    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fim' => 'datetime',
        'data_abertura' => 'datetime', 
        'data_fechamento' => 'datetime', 
    ];
        
    public function prova() {
         return $this->belongsTo(Prova::class); 
        }

    public function aluno() {
         return $this->belongsTo(Aluno::class, 'aluno_id');
         }
         
    public function respostas() {
         return $this->hasMany(AlunoRespostaProva::class); 
        }

    public function respostasQuestoes()
        {
            return $this->hasMany(AlunoRespostaProva::class, 'aluno_prova_tentativa_id');
        }
}
