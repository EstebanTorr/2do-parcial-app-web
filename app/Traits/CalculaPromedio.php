<?php

namespace App\Traits;

use App\Models\ResultadoFinal;

trait CalculaPromedio
{
    /**
     * CU15 - Calcula el promedio de un postulante
     * @return float|null
     */
    public function calcularPromedio(): ?float
    {
        $notas = $this->notas()->get();

        if ($notas->isEmpty()) {
            return null;
        }

        // Agrupar notas por materia y tomar la última (más reciente)
        $notasUnicas = $notas->groupBy('materia_id')->map(fn($grupo) => $grupo->last());

        $promedio = $notasUnicas->avg('calificacion');

        return round($promedio, 2);
    }

    /**
     * Calcula el estado del postulante basado en el promedio
     * @return string
     */
    public function calcularEstado(): string
    {
        $promedio = $this->calcularPromedio();

        if ($promedio === null) {
            return 'PROCESO';
        }

        // Nota mínima de aprobación: 51
        if ($promedio >= 51) {
            return 'ADMITIDO';
        }

        return 'REPROBADO_CUP';
    }

    /**
     * Obtiene o crea el resultado final
     */
    public function obtenerResultadoFinal()
    {
        return ResultadoFinal::firstOrCreate(
            [
                'postulante_id' => $this->id,
                'convocatoria_id' => $this->convocatoria_id,
            ],
            [
                'estado_admision' => 'PROCESO',
            ]
        );
    }

    /**
     * Actualiza el resultado final del postulante
     */
    public function actualizarResultadoFinal(): ResultadoFinal
    {
        $resultado = $this->obtenerResultadoFinal();
        $promedio = $this->calcularPromedio();

        $resultado->update([
            'promedio_total' => $promedio,
            'estado_admision' => $this->calcularEstado(),
            'calculado_en' => now(),
        ]);

        return $resultado;
    }
}
