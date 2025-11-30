<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aviso_aluno', function (Blueprint $table) {
            $table->id();
            
            // FK para o Aviso
            // Ajuste 'avisos' se sua tabela se chamar 'aviso'
            $table->foreignId('aviso_id')->constrained('avisos')->onDelete('cascade');
            
            // FK para o Aluno
            // Ajuste 'aluno' se sua tabela for plural 'alunos'
            $table->foreignId('aluno_id')->constrained('aluno')->onDelete('cascade');
            
            // Campos de data para saber quando foi criado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aviso_aluno');
    }
};