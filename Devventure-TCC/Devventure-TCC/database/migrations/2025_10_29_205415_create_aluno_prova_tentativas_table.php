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
        Schema::create('aluno_prova_tentativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas')->onDelete('cascade');
            $table->foreignId('aluno_id')->constrained('aluno')->onDelete('cascade');
            $table->dateTime('hora_inicio');
            $table->dateTime('hora_fim')->nullable(); 
            $table->decimal('pontuacao_final', 5, 2)->nullable(); 
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
        Schema::dropIfExists('aluno_prova_tentativas');
    }
};
