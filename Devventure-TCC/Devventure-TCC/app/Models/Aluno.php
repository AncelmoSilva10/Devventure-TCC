<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// --- Imports Corrigidos ---
use App\Models\Turma;
use App\Models\Aula;
use App\Models\RespostaExercicio;
use App\Models\AlunoRespostaProva; // <-- Este é o nome correto do seu arquivo
use App\Models\AlunoProvaTentativa;
use App\Models\RespostaAluno;
// --- Fim dos Imports ---

class Aluno extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait;
    
    protected $table = 'aluno';

    protected $fillable = [
        'nome',
        'ra',
        'semestre',
        'email',
        'total_pontos', 
        'telefone',
        'password',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    // --- Relacionamentos ---

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'aluno_turma');
    }
    
    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'aula_aluno')
                    ->withPivot('segundos_assistidos', 'status', 'concluido_em')
                    ->withTimestamps();
    }
    
    public function respostas()
    {
        return $this->hasMany(RespostaAluno::class, 'aluno_id');
    }
    
    public function respostasExercicios()
    {
        return $this->hasMany(RespostaExercicio::class, 'aluno_id');
    }

    /**
     * CORREÇÃO AQUI:
     * O nome do modelo estava errado. O correto é AlunoRespostaProva::class
     */
    public function respostasProvas()
    {
        return $this->hasMany(AlunoRespostaProva::class, 'aluno_id');
    }

    public function tentativasProvas()
    {
        return $this->hasMany(AlunoProvaTentativa::class, 'aluno_id');
    }
}