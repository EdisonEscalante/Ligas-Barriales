<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fecha_encuentro_id');
            $table->uuid('equipo_local_id');
            $table->uuid('equipo_visitante_id');
            $table->time('hora')->nullable();
            $table->string('cancha')->nullable();
            $table->string('estado')->default('pendiente');
            $table->integer('marcador_local')->default(0);
            $table->integer('marcador_visitante')->default(0);
            $table->timestamps();

            $table->foreign('fecha_encuentro_id')->references('id')->on('fechas_encuentro')->onDelete('cascade');
            $table->foreign('equipo_local_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('equipo_visitante_id')->references('id')->on('equipos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};