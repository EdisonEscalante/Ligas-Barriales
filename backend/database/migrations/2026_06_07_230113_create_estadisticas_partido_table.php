<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas_partido', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partido_id');
            $table->uuid('jugador_id');
            $table->uuid('disciplina_id');
            $table->string('tipo_estadistica');
            $table->decimal('valor', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_partido');
    }
};