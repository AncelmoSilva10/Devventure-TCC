<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depoimentos', function (Blueprint $table) {
            $table->id();
            $table->string('autor');
            $table->text('texto');
            $table->boolean('aprovado')->default(true); //utilizar para moderação de depoimentos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depoimentos');
    }
};
