<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'turma_id',
        'titulo',
        'instrucoes',
        'data_abertura',
        'data_fechamento', 
        'duracao_minutos'
    ];
        
    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime',
    ];

    public function turma()
    { 
        return $this->belongsTo(Turma::class);
    }

    public function questoes()
    { 
        return $this->hasMany(ProvaQuestao::class); 
    }
        
    public function tentativas()
    { 
        return $this->hasMany(AlunoProvaTentativa::class); 
    }
}