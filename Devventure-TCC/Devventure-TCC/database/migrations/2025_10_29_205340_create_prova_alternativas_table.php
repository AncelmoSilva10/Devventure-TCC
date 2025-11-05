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
        Schema::create('prova_alternativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_questao_id')->constrained('prova_questoes')->onDelete('cascade');
            $table->text('texto_alternativa');
            $table->boolean('correta')->default(false); 
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
        Schema::dropIfExists('prova_alternativas');
    }
};
