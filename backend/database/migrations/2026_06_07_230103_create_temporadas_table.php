<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporadas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('liga_id');
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado')->default('activa');
            $table->timestamps();

            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporadas');
    }
};