<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechas_encuentro', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fase_id');
            $table->integer('numero_fecha');
            $table->date('fecha_programada')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamps();

            $table->foreign('fase_id')->references('id')->on('fases')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas_encuentro');
    }
};