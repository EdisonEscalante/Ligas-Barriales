<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('torneo_id');
            $table->string('nombre');
            $table->string('tipo');
            $table->integer('orden');
            $table->string('estado')->default('pendiente');
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fases');
    }
};