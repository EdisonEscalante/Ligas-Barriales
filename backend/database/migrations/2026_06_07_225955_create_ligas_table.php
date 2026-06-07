<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->date('fecha_fundacion')->nullable();
            $table->string('provincia');
            $table->string('ciudad');
            $table->string('canton');
            $table->string('parroquia');
            $table->text('descripcion')->nullable();
            $table->string('escudo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ligas');
    }
}; 