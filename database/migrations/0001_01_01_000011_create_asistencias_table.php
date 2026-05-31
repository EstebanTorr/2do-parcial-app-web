<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulante_id')->constrained('postulante')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupo')->onDelete('cascade');
            $table->timestamp('fecha_evento');
            $table->enum('estado', ['PRESENTE', 'AUSENTE', 'JUSTIFICADO'])->default('PRESENTE');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['postulante_id', 'fecha_evento']);
            $table->index(['grupo_id', 'fecha_evento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
