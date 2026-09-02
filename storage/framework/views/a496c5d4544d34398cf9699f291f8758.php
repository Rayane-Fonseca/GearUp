<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['tituloPagina' => 'Progresso: '.e($colaborador->nome).'','subtituloPagina' => 'Acompanhe o desempenho do colaborador nos cursos da plataforma']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Progresso: '.e($colaborador->nome).'','subtitulo-pagina' => 'Acompanhe o desempenho do colaborador nos cursos da plataforma']); ?>
    <div class="w-full max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center justify-between">
            <a
                href="<?php echo e(route('admin.colaboradores')); ?>"
                class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Voltar para colaboradores
            </a>
        </div>

        
        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($colaborador->foto): ?>
                    <img
                        src="<?php echo e(asset('storage/' . $colaborador->foto)); ?>"
                        alt="<?php echo e($colaborador->nome); ?>"
                        class="h-14 w-14 rounded-full object-cover">
                    <?php else: ?>
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                        <?php echo e($colaborador->iniciais()); ?>

                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <p class="text-sm font-bold text-gray-900"><?php echo e($colaborador->nome); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($colaborador->cargo ?? 'Sem cargo'); ?> &middot; <?php echo e($colaborador->email); ?></p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-md bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
                        <?php echo e($colaborador->area ?? 'Sem área'); ?>

                    </span>

                    <span class="rounded-md px-2.5 py-1 text-[11px] font-medium <?php echo e($colaborador->status === 'ativo' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500'); ?>">
                        <?php echo e(ucfirst($colaborador->status)); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Cursos iniciados</p>
                <p class="mt-1 text-xl font-bold text-gray-900"><?php echo e($totalCursos); ?></p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Concluídos</p>
                <p class="mt-1 text-xl font-bold text-green-600"><?php echo e($concluidos); ?></p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Em andamento</p>
                <p class="mt-1 text-xl font-bold text-amber-500"><?php echo e($emAndamento); ?></p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Não iniciados</p>
                <p class="mt-1 text-xl font-bold text-gray-400"><?php echo e($naoIniciados); ?></p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Progresso médio</p>
                <p class="mt-1 text-xl font-bold text-blue-600"><?php echo e($progressoMedio); ?>%</p>
            </div>
        </div>

        
        <div class="w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-900">Progresso por curso</h3>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($colaborador->progressos->isEmpty()): ?>
            <p class="px-6 py-12 text-center text-xs text-gray-400">
                Este colaborador ainda não iniciou nenhum curso.
            </p>
            <?php else: ?>
            <div class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $colaborador->progressos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progresso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span
                                class="h-2 w-2 shrink-0 rounded-full"
                                style="background-color: <?php echo e($progresso->curso->cor_categoria ?? '#6B7280'); ?>;"></span>

                            <p class="truncate text-xs font-semibold text-gray-800">
                                <?php echo e($progresso->curso->titulo ?? 'Curso removido'); ?>

                            </p>
                        </div>

                        <p class="mt-1 text-[11px] text-gray-400">
                            <?php echo e($progresso->curso->categoria ?? '—'); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($progresso->concluido_em): ?>
                            &middot; Concluído em <?php echo e($progresso->concluido_em->format('d/m/Y')); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 sm:w-64">
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100">
                            <div
                                class="h-full rounded-full <?php echo e($progresso->porcentagem >= 100 ? 'bg-green-500' : ($progresso->porcentagem > 0 ? 'bg-amber-400' : 'bg-gray-300')); ?>"
                                style="width: <?php echo e($progresso->porcentagem); ?>%;"></div>
                        </div>

                        <span class="w-10 shrink-0 text-right text-xs font-semibold text-gray-700">
                            <?php echo e($progresso->porcentagem); ?>%
                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($colaborador->certificados->isNotEmpty()): ?>
        <div class="w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-900">Certificados emitidos</h3>
            </div>

            <div class="divide-y divide-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $colaborador->certificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-5">
                    <p class="text-xs font-semibold text-gray-800">
                        <?php echo e($certificado->curso->titulo ?? 'Curso removido'); ?>

                    </p>

                    <p class="text-[11px] text-gray-400">
                        Emitido em <?php echo e($certificado->emitido_em?->format('d/m/Y')); ?>

                    </p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
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
<?php /**PATH C:\Users\otvoa\Downloads\GearUp\resources\views/admin/colaborador-progresso.blade.php ENDPATH**/ ?>