<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->integer('creditos')->default(3);
            $table->enum('estado', ['ACTIVA', 'INACTIVA'])->default('ACTIVA');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materia');
    }
};
