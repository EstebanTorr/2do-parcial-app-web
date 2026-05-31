<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_postulante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupo')->onDelete('cascade');
            $table->foreignId('postulante_id')->constrained('postulante')->onDelete('cascade');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->enum('estado', ['ASIGNADO', 'RETIRADO', 'FINALIZADO'])->default('ASIGNADO');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['grupo_id', 'postulante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_postulante');
    }
};
