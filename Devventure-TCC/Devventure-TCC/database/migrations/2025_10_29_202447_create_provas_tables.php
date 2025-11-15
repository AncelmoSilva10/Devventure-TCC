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
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('provas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('turma_id')->constrained('turmas')->onDelete('cascade');
        $table->string('titulo');
        $table->text('instrucoes')->nullable();
        $table->dateTime('data_abertura');
        $table->dateTime('data_fechamento'); 
        $table->integer('duracao_minutos'); 
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
        Schema::dropIfExists('provas');
    }
};