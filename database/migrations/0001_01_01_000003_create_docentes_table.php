<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuario')->onDelete('set null');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique();
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('especialidad')->nullable();
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'LICENCIA'])->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
