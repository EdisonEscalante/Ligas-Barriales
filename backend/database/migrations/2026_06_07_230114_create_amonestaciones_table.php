<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amonestaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('partido_id');
            $table->uuid('jugador_id');
            $table->string('tipo');
            $table->integer('minuto')->nullable();
            $table->text('motivo')->nullable();
            $table->boolean('cumplida')->default(false);
            $table->timestamps();

            $table->foreign('partido_id')->references('id')->on('partidos')->onDelete('cascade');
            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amonestaciones');
    }
};