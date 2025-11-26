<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvaQuestao extends Model
{
    use HasFactory;

    protected $table = 'prova_questoes';
    
    protected $fillable = [
        'prova_id',
        'enunciado',
        'imagem_apoio',
        'tipo_questao',
        'pontuacao'
    ];
        
    public function prova() {
         return $this->belongsTo(Prova::class); 
        }
        
    public function alternativas() {
         return $this->hasMany(ProvaAlternativa::class); 
        }
}
