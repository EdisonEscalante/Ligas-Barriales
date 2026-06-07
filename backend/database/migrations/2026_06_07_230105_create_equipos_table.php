<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('liga_id');
            $table->string('nombre');
            $table->string('escudo_url')->nullable();
            $table->string('color_principal')->nullable();
            $table->timestamps();

            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};