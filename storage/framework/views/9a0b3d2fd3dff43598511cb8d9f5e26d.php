<?php $__env->startSection('title', 'CUP — Gestión de Grupos'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-8 py-8 max-w-7xl mx-auto w-100">
    
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="ti ti-layout-grid text-[#1e3a6e]"></i>
                Gestión de Grupos
            </h1>
            <p class="text-sm text-slate-500 mt-1">Crea y asigna postulantes y docentes a grupos académicos.</p>
        </div>

        
        <div class="flex items-center gap-3">
            <form method="GET" action="<?php echo e(route('admin.grupos.index')); ?>" class="flex items-center">
                <div class="relative">
                    <select name="convocatoria_id" 
                            class="appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl text-sm font-medium focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e] transition cursor-pointer"
                            onchange="this.form.submit()">
                        <option value="">-- Seleccionar Convocatoria --</option>
                        <?php $__currentLoopData = $convocatorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($conv->id); ?>" <?php echo e((request('convocatoria_id') == $conv->id || (!$convocatoriaId && $convocatoria && $convocatoria->id == $conv->id)) ? 'selected' : ''); ?>>
                                <?php echo e($conv->nombre); ?> (<?php echo e($conv->estado); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($convocatoria): ?>
        
        <div class="bg-[#1e3a6e]/5 border border-[#1e3a6e]/15 rounded-2xl px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#1e3a6e]/10 flex items-center justify-center text-[#1e3a6e] font-bold text-lg">
                    📅
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Convocatoria Activa</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Gestión: <strong class="text-[#1e3a6e]"><?php echo e($convocatoria->nombre); ?></strong> | Cupo Total: <?php echo e($convocatoria->cupo_total); ?> postulantes</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-[#1e3a6e]/15 text-[#1e3a6e]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1e3a6e] animate-pulse"></span>
                    <?php echo e($convocatoria->estado); ?>

                </span>
            </div>
        </div>

        <?php if($grupos->isEmpty()): ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl p-10 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 border-2 border-dashed border-slate-200 mb-6">
                        <i class="ti ti-layout-grid text-3xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">No hay grupos creados</h2>
                    <p class="text-sm text-slate-500 max-w-sm mt-2">Aún no se han configurado grupos para esta convocatoria. Configura y genera los grupos utilizando el panel de la derecha para comenzar.</p>
                </div>

                
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ti ti-settings text-[#1e3a6e]"></i>
                        Generador de Grupos
                    </h3>
                    <form method="POST" action="<?php echo e(route('admin.grupos.generar')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="convocatoria_id" value="<?php echo e($convocatoria->id); ?>">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Turno Asignado</label>
                                <select name="turno" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e]">
                                    <option value="MAÑANA">🌅 Mañana</option>
                                    <option value="TARDE">☀️ Tarde</option>
                                    <option value="NOCHE">🌙 Noche</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Cantidad de Grupos</label>
                                <input type="number" name="cantidad" value="3" min="1" max="20" required 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e]">
                                <p class="text-[10px] text-slate-400 mt-1">Crea múltiples grupos del mismo turno.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Capacidad por Grupo</label>
                                <input type="number" name="capacidad" value="30" min="5" max="100" required 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e]">
                                <p class="text-[10px] text-slate-400 mt-1">Límite máximo de postulantes en cada grupo.</p>
                            </div>

                            <button type="submit" class="w-full bg-[#1e3a6e] hover:bg-[#0f2147] text-white font-semibold py-3 px-4 rounded-xl text-sm transition shadow-md shadow-[#1e3a6e]/10 flex items-center justify-center gap-2 cursor-pointer mt-2 border-0">
                                <i class="ti ti-wand"></i>
                                Generar Grupos Ahora
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 w-full lg:w-auto">
                        <div class="border-r border-slate-100 pr-6">
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Grupos</span>
                            <span class="block text-2xl font-bold text-slate-800 mt-1"><?php echo e($grupos->count()); ?></span>
                        </div>
                        <div class="sm:border-r border-slate-100 sm:pr-6">
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Postulantes Asignados</span>
                            <?php
                                $totalPostulantes = 0;
                                $capacidadTotal = 0;
                                foreach($grupos as $g) {
                                    $totalPostulantes += $g->postulantes()->count();
                                    $capacidadTotal += $g->capacidad_maxima;
                                }
                            ?>
                            <span class="block text-2xl font-bold text-slate-800 mt-1"><?php echo e($totalPostulantes); ?> / <?php echo e($capacidadTotal); ?></span>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Eficiencia de Ocupación</span>
                            <span class="block text-2xl font-bold text-emerald-600 mt-1"><?php echo e($capacidadTotal > 0 ? round(($totalPostulantes / $capacidadTotal) * 100, 1) : 0); ?>%</span>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                        <form method="POST" action="<?php echo e(route('admin.grupos.auto-asignar')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="convocatoria_id" value="<?php echo e($convocatoria->id); ?>">
                            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-sm border-0 cursor-pointer">
                                <i class="ti ti-users-group"></i>
                                Distribuir Postulantes
                            </button>
                        </form>

                        <form method="POST" action="<?php echo e(route('admin.grupos.limpiar')); ?>" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar TODOS los grupos y vaciar sus postulantes y docentes? Esta acción no se puede deshacer.')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="convocatoria_id" value="<?php echo e($convocatoria->id); ?>">
                            <button type="submit" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 px-4 rounded-xl text-sm transition border border-red-100 cursor-pointer">
                                <i class="ti ti-trash"></i>
                                Vaciar Grupos
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $grupos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $usado = $grupo->postulantes()->count();
                        $porcentaje = $grupo->capacidad_maxima > 0 ? ($usado / $grupo->capacidad_maxima) * 100 : 0;
                        
                        // Determinar color de progreso
                        $progresoColor = 'bg-emerald-500';
                        if ($porcentaje >= 90) {
                            $progresoColor = 'bg-amber-500';
                        }
                    ?>
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition duration-200">
                        <div>
                            
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-600 border border-slate-100">
                                        <i class="ti ti-layout-grid text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">Grupo <?php echo e($grupo->numero_grupo); ?></h4>
                                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1 mt-0.5">
                                            <?php if($grupo->turno == 'MAÑANA'): ?>
                                                🌅 MAÑANA
                                            <?php elseif($grupo->turno == 'TARDE'): ?>
                                                ☀️ TARDE
                                            <?php else: ?>
                                                🌙 NOCHE
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <?php echo e($grupo->estado); ?>

                                </span>
                            </div>

                            
                            <div class="mb-5">
                                <div class="flex justify-between text-xs font-semibold mb-2">
                                    <span class="text-slate-400">Capacidad Ocupada</span>
                                    <span class="text-slate-700"><?php echo e($usado); ?> / <?php echo e($grupo->capacidad_maxima); ?></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-full rounded-full <?php echo e($progresoColor); ?> transition-all" style="width: <?php echo e($porcentaje); ?>%"></div>
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-2 gap-4 bg-slate-50 border border-slate-100 rounded-xl p-3 mb-5">
                                <div class="text-center">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Postulantes</span>
                                    <span class="block text-base font-extrabold text-slate-700 mt-1 flex items-center justify-center gap-1">
                                        <i class="ti ti-id-badge text-[#1e3a6e] text-sm"></i>
                                        <?php echo e($usado); ?>

                                    </span>
                                </div>
                                <div class="text-center border-l border-slate-200">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Docentes</span>
                                    <span class="block text-base font-extrabold text-slate-700 mt-1 flex items-center justify-center gap-1">
                                        <i class="ti ti-chalkboard text-emerald-500 text-sm"></i>
                                        <?php echo e($grupo->docentes()->count()); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <a href="<?php echo e(route('admin.grupos.show', $grupo)); ?>" class="w-full inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-[#1e3a6e] hover:text-white text-slate-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition cursor-pointer text-decoration-none text-center">
                            Gestionar Grupo
                            <i class="ti ti-arrow-narrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white border border-slate-100 rounded-2xl p-10 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-6">
                <i class="ti ti-alert-triangle text-3xl"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800">No hay convocatorias planificadas u activas</h2>
            <p class="text-sm text-slate-500 max-w-sm mt-2">Crea o activa una convocatoria en la sección de Convocatorias primero para poder gestionar sus grupos académicos.</p>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/admin/grupos/index.blade.php ENDPATH**/ ?>