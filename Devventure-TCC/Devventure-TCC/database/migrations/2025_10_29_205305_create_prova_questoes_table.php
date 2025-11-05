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
        Schema::create('prova_questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas')->onDelete('cascade');
            $table->text('enunciado');
            $table->enum('tipo_questao', ['multipla_escolha', 'texto']); 
            $table->decimal('pontuacao', 5, 2)->default(1.0);
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
        Schema::dropIfExists('prova_questoes');
    }
};
