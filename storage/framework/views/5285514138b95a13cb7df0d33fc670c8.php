<?php if (isset($component)) { $__componentOriginale4ebc9ed57c5009c9a50770282541134 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4ebc9ed57c5009c9a50770282541134 = $attributes; } ?>
<?php $component = App\View\Components\AlunoLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('aluno-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AlunoLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Trilhas de Aprendizagem','subtitulo-pagina' => 'Conteúdos organizados por área de atuação']); ?>
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Trilhas de Aprendizagem</h2>
            <p class="text-xs text-gray-400 mt-1">Conteúdos organizados por área de atuação</p>
        </div>

        <!-- Grid de Trilhas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $trilhas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trilha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $progresso = (int) $trilha->progresso;
                    $status = $progresso >= 100 ? 'Concluído' : ($progresso > 0 ? 'Em andamento' : 'Não iniciado');
                    
                    // Cor da faixa no topo do card por categoria (HEX)
                    $corBarraTopo = match($trilha->categoria ?? $trilha->area ?? '') {
                        'DevOps' => '#9B5DE5',
                        'Cloud Computing', 'Cloud' => '#CA7FB0',
                        'Banco de Dados' => '#FEE440',
                        'Infraestrutura' => '#00BBF9',
                        'Desenvolvimento de Software' => '#F15BB5',
                        'Segurança da Informação' => '#00F5D4',
                        'Suporte Técnico' => '#957FEF',
                        default => $status === 'Concluído' ? '#10B981' : '#94A3B8',
                    };

                    // Cores dinâmicas de acordo com o STATUS/PROGRESSO
                    $estiloStatus = match($status) {
                        'Concluído' => [
                            'badge' => 'bg-emerald-50 text-emerald-600',
                            'barra' => 'bg-emerald-500',
                            'botao' => 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600',
                        ],
                        'Em andamento' => [
                            'badge' => 'bg-amber-50 text-amber-600',
                            'barra' => 'bg-amber-500',
                            'botao' => 'bg-amber-50 hover:bg-amber-100 text-amber-600',
                        ],
                        default => [
                            'badge' => 'bg-red-50 text-red-600',
                            'barra' => 'bg-red-500',
                            'botao' => 'bg-red-50 hover:bg-red-100 text-red-600',
                        ],
                    };
                ?>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between p-5 relative">
                    <!-- Linha de destaque colorida no topo (Categoria) -->
                    <div class="absolute top-0 left-0 right-0 h-1" style="background-color: <?php echo e($corBarraTopo); ?>;"></div>

                    <div>
                        <!-- Categoria e Status -->
                        <div class="flex justify-between items-center mb-3">
                            <span class="px-3 py-1 text-[11px] font-medium text-gray-600 rounded-full bg-gray-100">
                                <?php echo e($trilha->categoria ?? $trilha->area ?? 'Geral'); ?>

                            </span>
                            
                            <span class="px-3 py-1 text-[11px] font-medium rounded-full <?php echo e($estiloStatus['badge']); ?>">
                                <?php echo e($status); ?>

                            </span>
                        </div>

                        <!-- Título e Informações -->
                        <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1"><?php echo e($trilha->titulo); ?></h3>
                        <p class="text-xs text-gray-400 mb-1">
                            <?php echo e($trilha->obrigatoriosCount); ?> obrigatório(s) • <?php echo e($trilha->opcionaisCount); ?> opcional(is)
                        </p>
                    </div>

                    <!-- Progresso e Ação -->
                    <div class="mt-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="<?php echo e($estiloStatus['barra']); ?> h-1.5 rounded-full transition-all duration-300" 
                                     style="width: <?php echo e($progresso); ?>%;"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-500"><?php echo e($progresso); ?>%</span>
                        </div>

                        <a href="<?php echo e(route('aluno.trilha-detalhe', $trilha->id)); ?>" 
                           class="w-full py-2.5 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors <?php echo e($estiloStatus['botao']); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'Concluído'): ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Ver Detalhes
                            <?php elseif($status === 'Em andamento'): ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Ver Detalhes
                            <?php else: ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Ver Detalhes
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-400 col-span-3">Nenhuma trilha encontrada.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $attributes = $__attributesOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__attributesOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $component = $__componentOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__componentOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp-corrigido (1)\GearUp-corrigido (1)\GearUp-main\resources\views/aluno/trilhas.blade.php ENDPATH**/ ?>