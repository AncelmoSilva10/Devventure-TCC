<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_respostas_provas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_prova_tentativa_id')->constrained('aluno_prova_tentativas')->onDelete('cascade');
            $table->foreignId('prova_questao_id')->constrained('prova_questoes')->onDelete('cascade');
            $table->foreignId('prova_alternativa_id')->nullable()->constrained('prova_alternativas');
            $table->text('resposta_texto')->nullable();
            $table->boolean('correta')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aluno_respostas_provas');
    }
};
