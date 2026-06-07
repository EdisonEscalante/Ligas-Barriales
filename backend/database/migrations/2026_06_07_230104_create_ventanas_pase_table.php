<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventanas_pase', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('temporada_id');
            $table->date('fecha_apertura');
            $table->date('fecha_cierre');
            $table->string('estado')->default('cerrada');
            $table->timestamps();

            $table->foreign('temporada_id')->references('id')->on('temporadas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventanas_pase');
    }
};