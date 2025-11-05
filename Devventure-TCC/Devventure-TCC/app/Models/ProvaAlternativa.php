<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvaAlternativa extends Model
{
    use HasFactory;

    protected $table = 'prova_alternativas';

    protected $fillable = ['prova_questao_id', 'texto_alternativa', 'correta'];
public function questao() { return $this->belongsTo(ProvaQuestao::class); }
}
