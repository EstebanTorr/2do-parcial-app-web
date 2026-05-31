<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente_materia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docente')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materia')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['docente_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_materia');
    }
};
