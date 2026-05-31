<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupo')->onDelete('cascade');
            $table->foreignId('docente_id')->constrained('docente')->onDelete('cascade');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['grupo_id', 'docente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_docente');
    }
};
