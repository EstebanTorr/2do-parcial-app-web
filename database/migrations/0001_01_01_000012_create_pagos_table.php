<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulante_id')->constrained('postulante')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->enum('tipo_pago', ['TRANSFERENCIA', 'EFECTIVO', 'CHEQUE', 'DEPOSITO'])->default('TRANSFERENCIA');
            $table->string('referencia_transaccion')->nullable();
            $table->enum('estado', ['PENDIENTE', 'VALIDADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->timestamp('fecha_pago')->nullable();
            $table->string('comprobante_ruta')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['postulante_id']);
            $table->index(['estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
