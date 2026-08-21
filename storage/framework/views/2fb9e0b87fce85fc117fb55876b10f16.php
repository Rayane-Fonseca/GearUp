<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['tituloPagina' => 'Dashboard Administrativo','subtituloPagina' => 'Visão geral da plataforma GearUp']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Dashboard Administrativo','subtitulo-pagina' => 'Visão geral da plataforma GearUp']); ?>
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900"><?php echo e($totalColaboradores); ?></span>
                <p class="text-xs text-gray-500 font-medium mt-1">Colaboradores</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900"><?php echo e($totalCursos); ?></span>
                <p class="text-xs text-gray-500 font-medium mt-1">Cursos ativos</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900"><?php echo e($taxaConclusao); ?>%</span>
                <p class="text-xs text-gray-500 font-medium mt-1">Taxa de conclusão</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900"><?php echo e($pendentes); ?></span>
                <p class="text-xs text-gray-500 font-medium mt-1">Treinamentos pendentes</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Atividade mensal</h4>
                <canvas id="graficoAtividade" height="90"></canvas>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Distribuição</h4>
                <canvas id="graficoDistribuicao" height="140"></canvas>
                <div class="space-y-1.5 mt-4 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Concluídos</span>
                        <span class="font-semibold"><?php echo e($distribuicao['concluidos']); ?>%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Em andamento</span>
                        <span class="font-semibold"><?php echo e($distribuicao['em_andamento']); ?>%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Não iniciados</span>
                        <span class="font-semibold"><?php echo e($distribuicao['nao_iniciados']); ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Cursos mais acessados</h4>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cursosMaisAcessados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indice => $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-400 w-4"><?php echo e($indice + 1); ?></span>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo e($curso['titulo']); ?></p>
                                    <p class="text-gray-400"><?php echo e($curso['categoria']); ?></p>
                                </div>
                            </div>
                            <span class="font-bold text-blue-600"><?php echo e($curso['percentual']); ?>%</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Colaboradores com pendências</h4>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $colaboradoresComPendencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colaborador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0"><?php echo e(strtoupper(substr($colaborador['nome'], 0, 2))); ?></div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo e($colaborador['nome']); ?></p>
                                    <p class="text-gray-400"><?php echo e($colaborador['area']); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800"><?php echo e($colaborador['percentual']); ?>%</p>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full <?php echo e($colaborador['status'] === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500'); ?>"><?php echo e($colaborador['status']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin-charts.js']); ?>
    <script>
        window.dadosAtividadeMensal = <?php echo json_encode($atividadeMensal, 15, 512) ?>;
        window.dadosDistribuicao = <?php echo json_encode($distribuicao, 15, 512) ?>;
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php /**PATH C:\Users\otvoa\Downloads\GearUp-postgresql-atualizado\GearUp-postgresql\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>