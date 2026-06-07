<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pases_jugador', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jugador_id');
            $table->uuid('ventana_pase_id');
            $table->uuid('equipo_origen_id');
            $table->uuid('equipo_destino_id');
            $table->string('estado')->default('pendiente');
            $table->timestamp('aprobacion_equipo_origen')->nullable();
            $table->uuid('aprobado_por_equipo_id')->nullable();
            $table->timestamp('aprobacion_liga')->nullable();
            $table->uuid('aprobado_por_liga_id')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();

            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
            $table->foreign('ventana_pase_id')->references('id')->on('ventanas_pase')->onDelete('cascade');
            $table->foreign('equipo_origen_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('equipo_destino_id')->references('id')->on('equipos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pases_jugador');
    }
};