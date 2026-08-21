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
        $aulaIdAtual = $aula->id_aula ?? $aula->id;
        $ehYoutube = $aula->url_video && Str::contains($aula->url_video, ['youtube.com', 'youtu.be']);
        $youtubeId = $ehYoutube ? Str::afterLast(Str::before($aula->url_video, '?'), '/') : null;

        // Duração estimada (fallback) e progresso já salvo, para retomar o vídeo do ponto certo
        $duracaoEstimada = (int) ($progressoAula->duracao_total ?? (($aula->duracao_minutos ?? 0) * 60));
        $tempoInicial = (int) ($progressoAula->tempo_assistido ?? 0);
        $porcentagemInicial = (int) ($progressoAula->porcentagem ?? 0);
        $concluidaInicial = $progressoAula->concluido ?? false;
    ?>

    <div x-data="aulaPlayer({
            aulaId: <?php echo e($aulaIdAtual); ?>,
            ehYoutube: <?php echo e($ehYoutube ? 'true' : 'false'); ?>,
            youtubeId: <?php echo e($youtubeId ? Js::from($youtubeId) : 'null'); ?>,
            tempoInicial: <?php echo e($tempoInicial); ?>,
            duracaoInicial: <?php echo e($duracaoEstimada); ?>,
            porcentagemInicial: <?php echo e($porcentagemInicial); ?>,
            concluidaInicial: <?php echo e($concluidaInicial ? 'true' : 'false'); ?>,
            urlSalvar: '<?php echo e(route('progresso.atualizar')); ?>',
            urlCertificados: '<?php echo e(route('aluno.certificados')); ?>'
        })"
         x-init="iniciar()"
         @beforeunload.window="salvarProgresso(true)"
         class="h-[calc(100vh-4rem)] flex flex-col bg-slate-50 text-gray-800 overflow-hidden">
        <div x-data="{ sidebarOpen: true, moduloAberto: <?php echo e($aula->modulo_id ?? $aula->id_modulo ?? 'null'); ?> }" class="h-full flex flex-col overflow-hidden">

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

                    <!-- Aviso de certificado liberado -->
                    <div x-show="mensagemCertificado"
                         x-transition
                         class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 flex items-center justify-between gap-4 text-xs font-semibold">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Parabéns! Você concluiu o curso e o certificado já está disponível.
                        </span>
                        <a :href="urlCertificados" class="shrink-0 py-2 px-4 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">Ver certificado</a>
                    </div>
                    
                    <!-- Container do Player com Altura Fixa/Responsiva Garantida -->
                    <div class="relative w-full h-[480px] lg:h-[560px] bg-gray-900 rounded-2xl overflow-hidden shadow-sm border border-gray-100 mx-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($aula->url_video): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ehYoutube): ?>
                                
                                <iframe id="youtube-player-<?php echo e($aulaIdAtual); ?>" class="w-full h-full"
                                        src="https://www.youtube.com/embed/<?php echo e($youtubeId); ?>?enablejsapi=1&playsinline=1"
                                        title="<?php echo e($aula->titulo); ?>"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            <?php else: ?>
                                
                                <video x-ref="videoPlayer" class="w-full h-full object-contain" controls controlsList="nodownload"
                                       @loadedmetadata="aoCarregarMetadados"
                                       @timeupdate="aoAtualizarTempo"
                                       @pause="salvarProgresso(true)"
                                       @ended="aoConcluirVideo">
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($aula->url_video): ?>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                    <template x-if="concluida">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </template>
                                    <span x-text="concluida ? 'Aula concluída' : 'Progresso da aula'"></span>
                                </span>
                                <span class="text-xs font-bold text-gray-500" x-text="porcentagem + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full transition-all duration-300"
                                     :class="concluida ? 'bg-emerald-500' : 'bg-blue-600'"
                                     :style="'width: ' + porcentagem + '%'"></div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

                <!-- BARRA FIXA/STICKY NO RODAPÉ DO PLAYER -->
                <div class="sticky bottom-0 left-0 right-0 z-20 bg-white/90 backdrop-blur-md p-4 rounded-2xl border border-gray-100 shadow-lg flex items-center justify-between gap-4 max-w-6xl mx-auto w-full shrink-0">
                    
                    <!-- Aula Anterior -->
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

                    <!-- Próxima Aula -->
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
                        <?php $modId = $modulo->id_modulo ?? $modulo->id; ?>
                        <div>
                            
                            <!-- Cabeçalho do Módulo -->
                            <button @click="moduloAberto = (moduloAberto === <?php echo e($modId); ?> ? null : <?php echo e($modId); ?>)" 
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">Módulo <?php echo e($index + 1); ?></span>
                                    <h3 class="text-xs font-bold text-gray-800 line-clamp-1"><?php echo e($modulo->titulo); ?></h3>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform" 
                                     :class="moduloAberto === <?php echo e($modId); ?> ? 'rotate-180' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Lista de Aulas do Módulo -->
                            <div x-show="moduloAberto === <?php echo e($modId); ?>" x-collapse class="bg-gray-50/50 divide-y divide-gray-100">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $modulo->aulas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemAula): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <?php 
                                        $aulaItemId = $itemAula->id_aula ?? $itemAula->id;
                                        $aulaAtualId = $aula->id_aula ?? $aula->id;
                                        $eAulaAtual = $aulaItemId === $aulaAtualId;
                                        $itemProgresso = $itemAula->progressos->first();
                                        $itemConcluida = $itemProgresso?->concluido ?? false;
                                        $itemPorcentagem = $itemProgresso?->porcentagem ?? 0;
                                    ?>
                                    <a href="<?php echo e(route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaItemId])); ?>" 
                                       class="flex items-center justify-between px-4 py-3 text-xs transition-colors <?php echo e($eAulaAtual ? 'bg-blue-50/80 text-blue-600 font-bold border-l-4 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
                                        
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2 flex-1">
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

                                            <div class="min-w-0 flex-1">
                                                <span class="truncate block"><?php echo e($itemAula->titulo); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$eAulaAtual && !$itemConcluida && $itemPorcentagem > 0): ?>
                                                    <div class="w-full bg-gray-200 rounded-full h-1 mt-1 overflow-hidden">
                                                        <div class="bg-blue-500 h-1 rounded-full" style="width: <?php echo e($itemPorcentagem); ?>%"></div>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
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
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function aulaPlayer(config) {
            return {
                aulaId: config.aulaId,
                ehYoutube: config.ehYoutube,
                youtubeId: config.youtubeId,
                urlSalvar: config.urlSalvar,
                urlCertificados: config.urlCertificados,

                tempoAtual: config.tempoInicial || 0,
                duracaoTotal: config.duracaoInicial || 0,
                porcentagem: config.porcentagemInicial || 0,
                concluida: !!config.concluidaInicial,
                mensagemCertificado: false,

                ytPlayer: null,
                ytIntervalo: null,
                ultimoSalvamento: 0,

                iniciar() {
                    if (this.ehYoutube && this.youtubeId) {
                        this.iniciarPlayerYoutube();
                    }
                    // Player HTML5 é controlado pelos eventos @loadedmetadata / @timeupdate / @ended no elemento <video>
                },

                // ---------- Player HTML5 ----------
                aoCarregarMetadados() {
                    const video = this.$refs.videoPlayer;
                    if (!video) return;

                    this.duracaoTotal = Math.floor(video.duration) || this.duracaoTotal;

                    // Retoma o vídeo do ponto salvo (evita retomar nos últimos segundos)
                    if (config.tempoInicial > 0 && config.tempoInicial < video.duration - 1) {
                        video.currentTime = config.tempoInicial;
                    }
                },

                aoAtualizarTempo() {
                    const video = this.$refs.videoPlayer;
                    if (!video) return;

                    this.tempoAtual = Math.floor(video.currentTime);
                    if (video.duration) {
                        this.duracaoTotal = Math.floor(video.duration);
                    }
                    this.atualizarPorcentagemLocal();

                    // Salva o progresso a cada ~5 segundos assistidos, sem sobrecarregar o servidor
                    if (this.tempoAtual - this.ultimoSalvamento >= 5) {
                        this.salvarProgresso();
                    }
                },

                aoConcluirVideo() {
                    const video = this.$refs.videoPlayer;
                    this.tempoAtual = video ? Math.floor(video.duration) : this.tempoAtual;
                    this.salvarProgresso(true);
                },

                // ---------- Player YouTube (via IFrame API) ----------
                iniciarPlayerYoutube() {
                    const iniciarQuandoPronto = () => {
                        this.ytPlayer = new YT.Player('youtube-player-' + this.aulaId, {
                            events: {
                                onReady: (evento) => {
                                    if (config.tempoInicial > 0) {
                                        evento.target.seekTo(config.tempoInicial, true);
                                    }
                                    this.duracaoTotal = Math.floor(evento.target.getDuration()) || this.duracaoTotal;
                                },
                                onStateChange: (evento) => {
                                    // 1 = tocando, 2 = pausado, 0 = finalizado
                                    if (evento.data === 1) {
                                        this.iniciarPollingYoutube();
                                    } else if (evento.data === 2) {
                                        this.pararPollingYoutube();
                                        this.salvarProgresso(true);
                                    } else if (evento.data === 0) {
                                        this.pararPollingYoutube();
                                        this.tempoAtual = this.duracaoTotal;
                                        this.salvarProgresso(true);
                                    }
                                },
                            },
                        });
                    };

                    if (window.YT && window.YT.Player) {
                        iniciarQuandoPronto();
                    } else {
                        const tagScript = document.createElement('script');
                        tagScript.src = 'https://www.youtube.com/iframe_api';
                        document.head.appendChild(tagScript);
                        window.onYouTubeIframeAPIReady = iniciarQuandoPronto;
                    }
                },

                iniciarPollingYoutube() {
                    this.pararPollingYoutube();
                    this.ytIntervalo = setInterval(() => {
                        if (!this.ytPlayer || typeof this.ytPlayer.getCurrentTime !== 'function') return;
                        this.tempoAtual = Math.floor(this.ytPlayer.getCurrentTime());
                        this.duracaoTotal = Math.floor(this.ytPlayer.getDuration()) || this.duracaoTotal;
                        this.atualizarPorcentagemLocal();
                        this.salvarProgresso();
                    }, 5000);
                },

                pararPollingYoutube() {
                    if (this.ytIntervalo) {
                        clearInterval(this.ytIntervalo);
                        this.ytIntervalo = null;
                    }
                },

                // ---------- Comum ----------
                atualizarPorcentagemLocal() {
                    if (this.duracaoTotal > 0) {
                        const percentualCalculado = Math.min(100, Math.round((this.tempoAtual / this.duracaoTotal) * 100));
                        // Não deixa a barra "regredir" visualmente após a aula já concluída
                        this.porcentagem = this.concluida ? Math.max(this.porcentagem, percentualCalculado) : percentualCalculado;
                    }
                },

                async salvarProgresso(forcar = false) {
                    if (!this.duracaoTotal || this.duracaoTotal <= 0) return;
                    if (!forcar && this.tempoAtual === this.ultimoSalvamento) return;

                    this.ultimoSalvamento = this.tempoAtual;

                    try {
                        const resposta = await fetch(this.urlSalvar, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                            },
                            body: JSON.stringify({
                                aula_id: this.aulaId,
                                tempo_atual: this.tempoAtual,
                                duracao_total: this.duracaoTotal,
                            }),
                            keepalive: true,
                        });

                        if (!resposta.ok) return;
                        const dados = await resposta.json();

                        this.porcentagem = dados.porcentagem ?? this.porcentagem;
                        this.concluida = dados.concluido ?? this.concluida;

                        if (dados.certificado_liberado) {
                            this.mensagemCertificado = true;
                        }
                    } catch (erro) {
                        // Falha silenciosa: o progresso será salvo na próxima tentativa
                        console.error('Não foi possível salvar o progresso da aula.', erro);
                    }
                },
            };
        }
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $attributes = $__attributesOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__attributesOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $component = $__componentOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__componentOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?>
<?php /**PATH C:\Users\otvoa\Downloads\GearUp\resources\views/aluno/aulas/show.blade.php ENDPATH**/ ?>