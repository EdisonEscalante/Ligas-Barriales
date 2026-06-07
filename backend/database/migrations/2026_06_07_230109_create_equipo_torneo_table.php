<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipo_torneo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('equipo_id');
            $table->uuid('torneo_id');
            $table->string('estado')->default('activo');
            $table->timestamps();

            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipo_torneo');
    }
};