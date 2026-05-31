<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulante_id')->constrained('postulante')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupo')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materia')->onDelete('cascade');
            $table->foreignId('docente_id')->constrained('docente')->onDelete('set null')->nullable();
            $table->decimal('calificacion', 5, 2);
            $table->enum('tipo_evaluacion', ['EXAMEN', 'ACTIVIDAD', 'PROYECTO', 'PARTICIPACION'])->default('EXAMEN');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index(['postulante_id', 'grupo_id']);
            $table->index(['docente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};
