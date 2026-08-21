<?php if (isset($component)) { $__componentOriginale4ebc9ed57c5009c9a50770282541134 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4ebc9ed57c5009c9a50770282541134 = $attributes; } ?>
<?php $component = App\View\Components\AlunoLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('aluno-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AlunoLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => ''.e($aula->titulo).'','subtitulo-pagina' => ''.e($curso->titulo).'']); ?>
    <?php
        $moduloAtivoId = $aula->modulo->id_modulo ?? $aula->modulo->id ?? $aula->id_modulo ?? $aula->modulo_id ?? null;
        $aulaAtualId = $aula->id_aula ?? $aula->id;
        $usuarioIdAuth = auth()->user()->id_usuario ?? auth()->id();
    ?>

    <div x-data="{ sidebarOpen: true, moduloAberto: <?php echo e(json_encode($moduloAtivoId)); ?> }" class="h-[calc(100vh-4rem)] flex flex-col bg-slate-50 text-gray-800 overflow-hidden">
        
        <!-- Header Superior do Player -->
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between shadow-xs shrink-0 z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('aluno.cursos.show', $curso->id_curso ?? $curso->id)); ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-blue-600 transition-colors">
                    Voltar ao Curso
                </a>
                <span class="text-gray-200">|</span>
                <h1 class="text-sm font-bold text-gray-900 truncate max-w-xl"><?php echo e($curso->titulo); ?></h1>
            </div>

            <!-- Botão de Ocultar/Exibir Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-100 px-3 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span x-text="sidebarOpen ? 'Ocultar Conteúdo' : 'Ver Conteúdo'"></span>
            </button>
        </header>

        <!-- Conteúdo Principal + Sidebar -->
        <div class="flex-1 flex overflow-hidden relative">
            
            <!-- ÁREA DO PLAYER / CONTEÚDO -->
            <main class="flex-1 flex flex-col overflow-y-auto p-6 relative space-y-6">
                <div class="max-w-6xl mx-auto w-full space-y-6 flex-1">
                    
                    <!-- Container do Player -->
                    <div class="relative w-full h-[480px] lg:h-[560px] bg-gray-900 rounded-2xl overflow-hidden shadow-sm border border-gray-100 mx-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($aula->url_video): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Str::contains($aula->url_video, ['youtube.com', 'youtu.be'])): ?>
                                <?php
                                    $srcYoutube = Str::contains($aula->url_video, 'embed')
                                        ? $aula->url_video
                                        : 'https://www.youtube.com/embed/' . Str::afterLast($aula->url_video, '/');
                                    $srcYoutube .= (Str::contains($srcYoutube, '?') ? '&' : '?') . 'enablejsapi=1';
                                ?>
                                <iframe id="player-video-youtube" class="w-full h-full" 
                                        src="<?php echo e($srcYoutube); ?>" 
                                        title="<?php echo e($aula->titulo); ?>" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            <?php else: ?>
                                <video id="player-video-html5" class="w-full h-full object-contain" controls controlsList="nodownload">
                                    <source src="<?php echo e($aula->url_video); ?>" type="video/mp4">
                                    Seu navegador não suporta reprodução de vídeos.
                                </video>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-900 p-6 text-center">
                                <svg class="w-14 h-14 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-medium">Esta aula não possui vídeo anexado.</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Barra de Progresso da Aula -->
                    <?php
                        $progressoInicial = $progressoAula->porcentagem ?? 0;
                        $concluidaInicial = ($progressoAula->concluido ?? false) || $progressoInicial >= 90;
                    ?>
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span id="chip-status-aula" class="px-2.5 py-1 text-[10px] font-semibold rounded-full border <?php echo e($concluidaInicial ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-100'); ?>">
                                <?php echo e($concluidaInicial ? 'Aula concluída' : 'Em andamento'); ?>

                            </span>
                            <span id="texto-progresso-aula" class="text-xs font-semibold text-gray-500"><?php echo e(round($progressoInicial)); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div id="barra-progresso-aula" class="h-2 rounded-full transition-all duration-300 <?php echo e($concluidaInicial ? 'bg-emerald-500' : 'bg-blue-600'); ?>" style="width: <?php echo e($progressoInicial); ?>%"></div>
                        </div>
                    </div>

                    <!-- Detalhes e Descrição da Aula -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 text-[11px] font-medium text-gray-600 bg-gray-50 rounded-full border border-gray-100">
                                <?php echo e($aula->modulo->titulo ?? 'Módulo'); ?>

                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900"><?php echo e($aula->titulo); ?></h2>
                        <div class="text-xs text-gray-500 leading-relaxed space-y-2">
                            <?php echo nl2br(e($aula->descricao ?? 'Sem descrição cadastrada para esta aula.')); ?>

                        </div>
                    </div>

                </div>

                <!-- BARRA FIXA NO RODAPÉ -->
                <div class="sticky bottom-0 left-0 right-0 z-20 bg-white/90 backdrop-blur-md p-4 rounded-2xl border border-gray-100 shadow-lg flex items-center justify-between gap-4 max-w-6xl mx-auto w-full shrink-0">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($aulaAnterior) && $aulaAnterior): ?>
                        <a href="<?php echo e(route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaAnterior->id_aula ?? $aulaAnterior->id])); ?>" 
                           class="py-2.5 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Aula Anterior
                        </a>
                    <?php else: ?>
                        <div class="w-24"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($proximaAula) && $proximaAula): ?>
                        <a href="<?php echo e(route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $proximaAula->id_aula ?? $proximaAula->id])); ?>" 
                           class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors shadow-xs">
                            Próxima Aula
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <div class="w-24"></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </main>

            <!-- SIDEBAR LATERAL DE MÓDULOS -->
            <aside x-show="sidebarOpen" 
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-150"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="translate-x-full"
                   class="w-80 lg:w-96 bg-white border-l border-gray-100 flex flex-col h-full overflow-y-auto shadow-sm shrink-0">
                
                <div class="p-4 border-b border-gray-100 font-bold text-gray-900 text-sm">
                    Conteúdo do Curso
                </div>

                <div class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php 
                            $modId = $modulo->id_modulo ?? $modulo->id; 
                        ?>
                        <div>
                            <button @click="moduloAberto = (moduloAberto === <?php echo e(json_encode($modId)); ?> ? null : <?php echo e(json_encode($modId)); ?>)" 
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">Módulo <?php echo e($index + 1); ?></span>
                                    <h3 class="text-xs font-bold text-gray-800 line-clamp-1"><?php echo e($modulo->titulo); ?></h3>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform" 
                                     :class="moduloAberto === <?php echo e(json_encode($modId)); ?> ? 'rotate-180' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="moduloAberto === <?php echo e(json_encode($modId)); ?>" x-collapse class="bg-gray-50/50 divide-y divide-gray-100">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $modulo->aulas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemAula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <?php 
                                        $itemAulaId = $itemAula->id_aula ?? $itemAula->id;
                                        $eAulaAtual = (int)$itemAulaId === (int)$aulaAtualId;
                                        
                                        // CORREÇÃO SIDEBAR: Busca o progresso específico deste usuário nesta aula
                                        $progItem = $itemAula->progressos
                                            ->where('usuario_id', $usuarioIdAuth)
                                            ->first();

                                        $itemConcluida = $progItem && ($progItem->concluido || $progItem->porcentagem >= 90);
                                    ?>
                                    <a href="<?php echo e(route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $itemAulaId])); ?>" 
                                       class="flex items-center justify-between px-4 py-3 text-xs transition-colors <?php echo e($eAulaAtual ? 'bg-blue-50/80 text-blue-600 font-bold border-l-4 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
                                        
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($itemConcluida): ?>
                                                <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            <?php elseif($eAulaAtual): ?>
                                                <svg class="w-4 h-4 text-blue-600 shrink-0 fill-current" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                            <?php else: ?>
                                                <div class="w-5 h-5 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-2.5 h-2.5 fill-current ml-0.5" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <span class="truncate"><?php echo e($itemAula->titulo); ?></span>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($eAulaAtual): ?>
                                            <span class="text-[10px] font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full shrink-0">Tocando</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <div class="px-4 py-3 text-[11px] text-gray-400 italic">Nenhuma aula neste módulo.</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-xs text-gray-400 text-center">
                            Nenhum módulo cadastrado neste curso.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </aside>

        </div>
    </div>

    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const AULA_ID = <?php echo e((int) ($aula->id_aula ?? $aula->id)); ?>;
        const TEMPO_INICIAL = <?php echo e((float) ($progressoAula->tempo_assistido ?? 0)); ?>;
        const URL_ATUALIZAR = "<?php echo e(route('progresso.atualizar')); ?>";
        const URL_CERTIFICADOS = "<?php echo e(route('aluno.certificados')); ?>";

        let concluidaLocal = <?php echo e((($progressoAula->concluido ?? false) || ($progressoAula->porcentagem ?? 0) >= 90) ? 'true' : 'false'); ?>;
        let ultimoEnvio = 0;

        const barraProgresso = document.getElementById('barra-progresso-aula');
        const textoProgresso = document.getElementById('texto-progresso-aula');
        const chipStatus = document.getElementById('chip-status-aula');

        function atualizarBarraUI(porcentagem, concluida) {
            porcentagem = Math.max(0, Math.min(100, Math.round(porcentagem || 0)));

            if (barraProgresso) {
                barraProgresso.style.width = porcentagem + '%';
                barraProgresso.classList.toggle('bg-emerald-500', concluida);
                barraProgresso.classList.toggle('bg-blue-600', !concluida);
            }
            if (textoProgresso) {
                textoProgresso.textContent = porcentagem + '%';
            }
            if (chipStatus) {
                chipStatus.textContent = concluida ? 'Aula concluída' : 'Em andamento';
                chipStatus.className = 'px-2.5 py-1 text-[10px] font-semibold rounded-full border ' + 
                    (concluida ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-100');
            }
        }

        function salvarProgresso(tempoAtual, duracaoTotal, forcar) {
            if (!duracaoTotal || duracaoTotal <= 0 || isNaN(tempoAtual)) return;

            const pctCliente = Math.min(100, (tempoAtual / duracaoTotal) * 100);
            const jaConcluiu = concluidaLocal || pctCliente >= 90;

            atualizarBarraUI(pctCliente, jaConcluiu);

            const agora = Date.now();
            // Permite o envio se forçar (pause/ended) ou se já passaram 3 segundos
            if (!forcar && (agora - ultimoEnvio) < 3000) return;
            ultimoEnvio = agora;

            fetch(URL_ATUALIZAR, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    aula_id: AULA_ID,
                    tempo_atual: tempoAtual,
                    duracao_total: duracaoTotal,
                }),
            })
            .then(res => res.json())
            .then(dados => {
                if (!dados.sucesso) return;

                const pctFinal = dados.porcentagem !== undefined ? dados.porcentagem : pctCliente;
                const estaConcluido = dados.concluido !== undefined ? dados.concluido : jaConcluiu;

                atualizarBarraUI(pctFinal, estaConcluido);

                if (estaConcluido) {
                    concluidaLocal = true;
                }
            })
            .catch(() => {});
        }

        atualizarBarraUI(<?php echo e((float) ($progressoAula->porcentagem ?? 0)); ?>, concluidaLocal);

        // --- PLAYER HTML5 ---
        const videoEl = document.getElementById('player-video-html5');
        if (videoEl) {
            videoEl.addEventListener('loadedmetadata', function () {
                if (TEMPO_INICIAL > 0 && TEMPO_INICIAL < (videoEl.duration - 2)) {
                    videoEl.currentTime = TEMPO_INICIAL;
                }
            });
            videoEl.addEventListener('timeupdate', function () {
                salvarProgresso(videoEl.currentTime, videoEl.duration, false);
            });
            videoEl.addEventListener('pause', function () {
                salvarProgresso(videoEl.currentTime, videoEl.duration, true);
            });
            videoEl.addEventListener('ended', function () {
                salvarProgresso(videoEl.duration, videoEl.duration, true);
            });
        }

        // --- PLAYER YOUTUBE EMBED ---
        const iframeYoutube = document.getElementById('player-video-youtube');
        if (iframeYoutube) {
            function inicializarYTPlayer() {
                let intervaloSalvar = null;

                const player = new YT.Player('player-video-youtube', {
                    events: {
                        onReady: function (evento) {
                            if (TEMPO_INICIAL > 0) {
                                evento.target.seekTo(TEMPO_INICIAL, true);
                            }
                        },
                        onStateChange: function (evento) {
                            if (intervaloSalvar) {
                                clearInterval(intervaloSalvar);
                                intervaloSalvar = null;
                            }

                            if (evento.data === YT.PlayerState.PLAYING) {
                                intervaloSalvar = setInterval(function () {
                                    if (player && player.getCurrentTime) {
                                        salvarProgresso(player.getCurrentTime(), player.getDuration(), false);
                                    }
                                }, 3000);
                            } else if (evento.data === YT.PlayerState.PAUSED) {
                                salvarProgresso(player.getCurrentTime(), player.getDuration(), true);
                            } else if (evento.data === YT.PlayerState.ENDED) {
                                salvarProgresso(player.getDuration(), player.getDuration(), true);
                            }
                        }
                    }
                });
            }

            if (window.YT && window.YT.Player) {
                inicializarYTPlayer();
            } else {
                const scriptTag = document.createElement('script');
                scriptTag.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(scriptTag);
                window.onYouTubeIframeAPIReady = inicializarYTPlayer;
            }
        }
    });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $attributes = $__attributesOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__attributesOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $component = $__componentOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__componentOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp-postgresql\resources\views/aluno/aulas/show.blade.php ENDPATH**/ ?>