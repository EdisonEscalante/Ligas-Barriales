<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liga_disciplina', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('liga_id');
            $table->uuid('disciplina_id');
            $table->timestamps();

            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liga_disciplina');
    }
};