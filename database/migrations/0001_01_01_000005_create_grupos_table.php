<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convocatoria_id')->constrained('convocatoria')->onDelete('cascade');
            $table->integer('numero_grupo');
            $table->enum('turno', ['MAÑANA', 'TARDE', 'NOCHE'])->default('MAÑANA');
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'FINALIZADO'])->default('ACTIVO');
            $table->integer('capacidad_maxima')->default(30);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Índices
            $table->unique(['convocatoria_id', 'numero_grupo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
