<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_final', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulante_id')->constrained('postulante')->onDelete('cascade');
            $table->foreignId('convocatoria_id')->constrained('convocatoria')->onDelete('cascade');
            $table->decimal('promedio_final', 5, 2)->nullable();
            $table->decimal('calificacion_final', 5, 2)->nullable();
            $table->enum('estado_final', ['EVALUANDO', 'APROBADO', 'RECHAZADO', 'ADMITIDO'])->default('EVALUANDO');
            $table->foreignId('carrera_asignada_id')->nullable()->constrained('carrera')->onDelete('set null');
            $table->integer('posicion_ranking')->nullable();
            $table->timestamp('fecha_calculo')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->unique(['postulante_id', 'convocatoria_id']);
            $table->index(['estado_final']);
            $table->index(['posicion_ranking']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_final');
    }
};
