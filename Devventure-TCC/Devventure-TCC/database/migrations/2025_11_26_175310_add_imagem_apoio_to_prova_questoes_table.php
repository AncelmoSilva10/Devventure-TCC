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
    Schema::table('prova_questoes', function (Blueprint $table) {
        $table->string('imagem_apoio')->nullable()->after('enunciado');
    });
}

public function down()
{
    Schema::table('prova_questoes', function (Blueprint $table) {
        $table->dropColumn('imagem_apoio');
    });
}
};
