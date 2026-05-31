<?php

namespace App\Console\Commands;

use App\Models\Convocatoria;
use App\Models\Grupo;
use Illuminate\Console\Command;

class CrearGruposCommand extends Command
{
    protected $signature = 'cup:crear-grupos 
                          {convocatoria : ID de la convocatoria}
                          {--cantidad=3 : Cantidad de grupos a crear}
                          {--capacidad=30 : Capacidad máxima por grupo}
                          {--turno=MAÑANA : Turno (MAÑANA, TARDE, NOCHE)}';

    protected $description = 'Crea grupos automáticamente para una convocatoria';

    public function handle()
    {
        $convocatoriaId = $this->argument('convocatoria');
        $cantidad = $this->option('cantidad');
        $capacidad = $this->option('capacidad');
        $turno = $this->option('turno');

        // Buscar convocatoria
        $convocatoria = Convocatoria::findOrFail($convocatoriaId);

        // Validar que la convocatoria esté en PLANIFICADA o ACTIVA
        if (!in_array($convocatoria->estado, ['PLANIFICADA', 'ACTIVA'])) {
            $this->error("❌ La convocatoria debe estar en estado PLANIFICADA o ACTIVA");
            return 1;
        }

        // Validar que no existan grupos previos
        if ($convocatoria->grupos()->exists()) {
            $this->error("❌ Esta convocatoria ya tiene grupos creados");
            return 1;
        }

        // Calcular número de grupos necesarios
        $cantidadGrupos = max($cantidad, ceil($convocatoria->cupo_total / $capacidad));

        $this->info("📋 Creando {$cantidadGrupos} grupos para: {$convocatoria->nombre}");
        $this->info("   Capacidad por grupo: {$capacidad}");
        $this->info("   Turno: {$turno}");

        $bar = $this->output->createProgressBar($cantidadGrupos);

        try {
            for ($i = 1; $i <= $cantidadGrupos; $i++) {
                Grupo::create([
                    'convocatoria_id' => $convocatoria->id,
                    'numero_grupo' => $i,
                    'turno' => $turno,
                    'estado' => 'ACTIVO',
                    'capacidad_maxima' => $capacidad,
                    'descripcion' => "Grupo {$i} - {$convocatoria->nombre}",
                ]);

                $bar->advance();
            }

            $bar->finish();

            $this->newLine(2);
            $this->info("✅ Grupos creados exitosamente");
            $this->line("   Total: {$cantidadGrupos} grupos");
            $this->line("   Capacidad total: " . ($cantidadGrupos * $capacidad) . " postulantes");

            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error("❌ Error al crear grupos: " . $e->getMessage());
            return 1;
        }
    }
}
