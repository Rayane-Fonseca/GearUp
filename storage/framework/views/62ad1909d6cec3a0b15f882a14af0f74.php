<?php if (isset($component)) { $__componentOriginale4ebc9ed57c5009c9a50770282541134 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4ebc9ed57c5009c9a50770282541134 = $attributes; } ?>
<?php $component = App\View\Components\AlunoLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('aluno-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AlunoLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Certificados','subtitulo-pagina' => ''.e($certificados->count()).' certificados conquistados']); ?>
    <div x-data="{ modalOpen: false, certUrl: '', certTitulo: '' }" class="p-8 max-w-7xl mx-auto space-y-6">
        
        <!-- Cabeçalho -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Certificados</h2>
            <p class="text-sm text-gray-500"><?php echo e($certificados->count()); ?> <?php echo e(Str::plural('certificado conquistado', $certificados->count())); ?></p>
        </div>

        <!-- Grid de Certificados -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $certificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Mapeamento exato das cores por Categoria
                    $categoria = $certificado->curso->categoria ?? $certificado->curso->area ?? 'Geral';

                    $corBarraTopo = match($categoria) {
                        'DevOps' => '#9B5DE5',
                        'Cloud Computing', 'Cloud' => '#CA7FB0',
                        'Banco de Dados' => '#FEE440',
                        'Infraestrutura' => '#00BBF9',
                        'Desenvolvimento de Software' => '#F15BB5',
                        'Segurança da Informação' => '#00F5D4',
                        'Suporte Técnico' => '#957fef',
                        default => '#3B82F6',
                    };

                    // Tratamento de contraste para fundos muito claros (Yellow e Mint)
                    $isCorClara = in_array($corBarraTopo, ['#FEE440', '#00F5D4']);
                    $corTextoTopo = $isCorClara ? 'text-gray-900' : 'text-white';
                    $corBadgeTopo = $isCorClara ? 'text-gray-800/80 font-bold' : 'text-white/80';
                    $corBordaIcone = $isCorClara ? 'border-gray-900/20 bg-black/5' : 'border-white/20 bg-white/10';

                    // Definição de URLs Seguras
                    $idCert = $certificado->id_certificado ?? $certificado->id;

                    $urlDownload = Route::has('aluno.certificados.download') 
                        ? route('aluno.certificados.download', $idCert) 
                        : url('/aluno/certificados/' . $idCert . '/download');

                    $urlVisualizar = Route::has('aluno.certificados.preview') 
                        ? route('aluno.certificados.preview', $idCert) 
                        : url('/aluno/certificados/' . $idCert . '/preview');
                ?>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    
                    <!-- Topo do Card com a Cor Exata da Categoria -->
                    <div class="p-5 <?php echo e($corTextoTopo); ?>" style="background-color: <?php echo e($corBarraTopo); ?>;">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[10px] uppercase tracking-widest <?php echo e($corBadgeTopo); ?>">
                                    <?php echo e($certificado->empresa ?? 'TECHCORP'); ?>

                                </span>
                                <h3 class="font-bold mt-1 leading-tight text-base line-clamp-2 pr-2">
                                    <?php echo e($certificado->curso->titulo); ?>

                                </h3>
                            </div>
                            <div class="w-8 h-8 rounded-full border <?php echo e($corBordaIcone); ?> flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 <?php echo e($corTextoTopo); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Metadados -->
                    <div class="p-5 space-y-3 flex-1 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-gray-400 font-medium">Área</p>
                                <p class="font-semibold text-gray-800 truncate mt-0.5"><?php echo e($categoria); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-medium">Instrutor</p>
                                <p class="font-semibold text-gray-800 truncate mt-0.5"><?php echo e($certificado->curso->instrutor); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-medium">Carga</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($certificado->curso->carga_horaria); ?>h</p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-medium">Conclusão</p>
                                <p class="font-semibold text-gray-800 mt-0.5">
                                    <?php echo e(\Carbon\Carbon::parse($certificado->emitido_em ?? $certificado->created_at)->translatedFormat('d M Y')); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="p-5 pt-0 grid grid-cols-2 gap-2">
                        <button type="button"
                                @click="modalOpen = true; certUrl = '<?php echo e($urlVisualizar); ?>'; certTitulo = '<?php echo e(addslashes($certificado->curso->titulo)); ?>'"
                                class="py-2 text-xs font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors text-center">
                            Visualizar
                        </button>
                        <a href="<?php echo e($urlDownload); ?>" 
                           target="_blank" 
                           class="py-2 text-xs font-semibold rounded-xl transition-opacity hover:opacity-90 text-center <?php echo e($corTextoTopo); ?>"
                           style="background-color: <?php echo e($corBarraTopo); ?>;">
                            Baixar PDF
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Card do Próximo Certificado -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proximoCertificado): ?>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center text-center p-6 space-y-3 hover:border-blue-300 transition-colors">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm">Próximo certificado</h4>
                    <p class="text-xs text-gray-500">Conclua <strong class="text-gray-700"><?php echo e($proximoCertificado->curso->titulo); ?></strong> para desbloquear.</p>
                    
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo e($proximoCertificado->porcentagem); ?>%"></div>
                    </div>
                    <span class="text-xs text-gray-400 font-semibold"><?php echo e((int) $proximoCertificado->porcentagem); ?>% concluído</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Modal de Preview -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="modalOpen = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden">
                
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-sm truncate" x-text="certTitulo"></h3>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 bg-gray-100 relative">
                    <iframe :src="certUrl" class="w-full h-full border-0" title="Visualização do Certificado"></iframe>
                </div>
            </div>
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
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp-postgresql-atualizado\GearUp-postgresql\resources\views/aluno/certificados.blade.php ENDPATH**/ ?>