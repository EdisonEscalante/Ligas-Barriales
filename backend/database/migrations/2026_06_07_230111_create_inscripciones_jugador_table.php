<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones_jugador', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jugador_id');
            $table->uuid('equipo_id');
            $table->uuid('torneo_id');
            $table->string('dorsal')->nullable();
            $table->string('posicion')->nullable();
            $table->string('estado')->default('activo');
            $table->date('fecha_inscripcion');
            $table->timestamps();

            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_jugador');
    }
};