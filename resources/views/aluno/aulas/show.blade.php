<x-aluno-layout titulo-pagina="{{ $aula->titulo }}" subtitulo-pagina="{{ $curso->titulo }}">
    @php
        $aulaIdAtual = $aula->id_aula ?? $aula->id;
        $ehYoutube = $aula->url_video && Str::contains($aula->url_video, ['youtube.com', 'youtu.be']);
        $youtubeId = $ehYoutube ? Str::afterLast(Str::before($aula->url_video, '?'), '/') : null;

        // Duração estimada (fallback) e progresso já salvo, para retomar o vídeo do ponto certo
        $duracaoEstimada = (int) ($progressoAula->duracao_total ?? (($aula->duracao_minutos ?? 0) * 60));
        $tempoInicial = (int) ($progressoAula->tempo_assistido ?? 0);
        $porcentagemInicial = (int) ($progressoAula->porcentagem ?? 0);
        $concluidaInicial = $progressoAula->concluido ?? false;
    @endphp

    <div x-data="aulaPlayer({
            aulaId: {{ $aulaIdAtual }},
            ehYoutube: {{ $ehYoutube ? 'true' : 'false' }},
            youtubeId: {{ $youtubeId ? Js::from($youtubeId) : 'null' }},
            tempoInicial: {{ $tempoInicial }},
            duracaoInicial: {{ $duracaoEstimada }},
            porcentagemInicial: {{ $porcentagemInicial }},
            concluidaInicial: {{ $concluidaInicial ? 'true' : 'false' }},
            urlSalvar: '{{ route('progresso.atualizar') }}',
            urlCertificados: '{{ route('aluno.certificados') }}'
        })"
         x-init="iniciar()"
         @beforeunload.window="salvarProgresso(true)"
         class="h-[calc(100vh-4rem)] flex flex-col bg-slate-50 text-gray-800 overflow-hidden">
        <div x-data="{ sidebarOpen: false, moduloAberto: {{ $aula->modulo_id ?? $aula->id_modulo ?? 'null' }} }" class="h-full flex flex-col overflow-hidden">

        <!-- Header Superior do Player -->
        <header class="bg-white border-b border-gray-100 px-3 sm:px-6 py-3 flex items-center justify-between gap-2 shadow-xs shrink-0 z-10">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1">
                <a href="{{ route('aluno.cursos.show', $curso->id_curso ?? $curso->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-blue-600 transition-colors shrink-0">
                    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="hidden sm:inline">Voltar ao Curso</span>
                </a>
                <span class="text-gray-200 hidden sm:inline shrink-0">|</span>
                <h1 class="text-sm font-bold text-gray-900 truncate min-w-0">{{ $curso->titulo }}</h1>
            </div>

            <!-- Botão de Ocultar/Exibir Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-100 px-2.5 sm:px-3 py-2 rounded-xl transition-colors shrink-0">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span class="hidden sm:inline" x-text="sidebarOpen ? 'Ocultar Conteúdo' : 'Ver Conteúdo'"></span>
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
                        @if($aula->url_video)
                            @if($ehYoutube)
                                {{-- Embed do YouTube (controlado via YouTube IFrame API para salvar/retomar progresso) --}}
                                <iframe id="youtube-player-{{ $aulaIdAtual }}" class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $youtubeId }}?enablejsapi=1&playsinline=1"
                                        title="{{ $aula->titulo }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @else
                                {{-- Vídeo direto HTML5 --}}
                                <video x-ref="videoPlayer" class="w-full h-full object-contain" controls controlsList="nodownload"
                                       @loadedmetadata="aoCarregarMetadados"
                                       @timeupdate="aoAtualizarTempo"
                                       @pause="salvarProgresso(true)"
                                       @ended="aoConcluirVideo">
                                    <source src="{{ $aula->url_video }}" type="video/mp4">
                                    Seu navegador não suporta reprodução de vídeos.
                                </video>
                            @endif
                        @else
                            {{-- Placeholder quando não houver vídeo --}}
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-900 p-6 text-center">
                                <svg class="w-14 h-14 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-medium">Esta aula não possui vídeo anexado.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Barra de Progresso da Aula -->
                    @if($aula->url_video)
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
                    @endif

                    <!-- Detalhes e Descrição da Aula -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 text-[11px] font-medium text-gray-600 bg-gray-50 rounded-full border border-gray-100">
                                {{ $aula->modulo->titulo ?? 'Módulo' }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $aula->titulo }}</h2>
                        <div class="text-xs text-gray-500 leading-relaxed space-y-2">
                            {!! nl2br(e($aula->descricao ?? 'Sem descrição cadastrada para esta aula.')) !!}
                        </div>
                    </div>

                </div>

                <!-- BARRA FIXA/STICKY NO RODAPÉ DO PLAYER -->
                <div class="sticky bottom-0 left-0 right-0 z-20 bg-white/90 backdrop-blur-md p-4 rounded-2xl border border-gray-100 shadow-lg flex items-center justify-between gap-4 max-w-6xl mx-auto w-full shrink-0">
                    
                    <!-- Aula Anterior -->
                    @if(isset($aulaAnterior) && $aulaAnterior)
                        <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaAnterior->id_aula ?? $aulaAnterior->id]) }}" 
                           class="py-2.5 px-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Aula Anterior
                        </a>
                    @else
                        <div class="w-24"></div>
                    @endif

                    <!-- Próxima Aula -->
                    @if(isset($proximaAula) && $proximaAula)
                        <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $proximaAula->id_aula ?? $proximaAula->id]) }}" 
                           class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors shadow-xs">
                            Próxima Aula
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <div class="w-24"></div>
                    @endif
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
                    @forelse($modulos as $index => $modulo)
                        @php $modId = $modulo->id_modulo ?? $modulo->id; @endphp
                        <div>
                            
                            <!-- Cabeçalho do Módulo -->
                            <button @click="moduloAberto = (moduloAberto === {{ $modId }} ? null : {{ $modId }})" 
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">Módulo {{ $index + 1 }}</span>
                                    <h3 class="text-xs font-bold text-gray-800 line-clamp-1">{{ $modulo->titulo }}</h3>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform" 
                                     :class="moduloAberto === {{ $modId }} ? 'rotate-180' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Lista de Aulas do Módulo -->
                            <div x-show="moduloAberto === {{ $modId }}" x-collapse class="bg-gray-50/50 divide-y divide-gray-100">
                                @forelse($modulo->aulas as $itemAula)
                                    @php 
                                        $aulaItemId = $itemAula->id_aula ?? $itemAula->id;
                                        $aulaAtualId = $aula->id_aula ?? $aula->id;
                                        $eAulaAtual = $aulaItemId === $aulaAtualId;
                                        $itemProgresso = $itemAula->progressos->first();
                                        $itemConcluida = $itemProgresso?->concluido ?? false;
                                        $itemPorcentagem = $itemProgresso?->porcentagem ?? 0;
                                    @endphp
                                    <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaItemId]) }}" 
                                       class="flex items-center justify-between px-4 py-3 text-xs transition-colors {{ $eAulaAtual ? 'bg-blue-50/80 text-blue-600 font-bold border-l-4 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                        
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2 flex-1">
                                            @if($itemConcluida)
                                                <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @elseif($eAulaAtual)
                                                <svg class="w-4 h-4 text-blue-600 shrink-0 fill-current" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                            @else
                                                <div class="w-5 h-5 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-2.5 h-2.5 fill-current ml-0.5" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <span class="truncate block">{{ $itemAula->titulo }}</span>
                                                @if(!$eAulaAtual && !$itemConcluida && $itemPorcentagem > 0)
                                                    <div class="w-full bg-gray-200 rounded-full h-1 mt-1 overflow-hidden">
                                                        <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $itemPorcentagem }}%"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($eAulaAtual)
                                            <span class="text-[10px] font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full shrink-0">Tocando</span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-[11px] text-gray-400 italic">Nenhuma aula neste módulo.</div>
                                @endforelse
                            </div>

                        </div>
                    @empty
                        <div class="p-4 text-xs text-gray-400 text-center">
                            Nenhum módulo cadastrado neste curso.
                        </div>
                    @endforelse
                </div>

            </aside>

        </div>
        </div>
    </div>

    @push('scripts')
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
                maiorTempoAssistido: config.tempoInicial || 0,

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
                        this.duracaoTotal = Math.floor(this.ytPlayer.getDuration()) || this.duracaoTotal;

                        // Bloqueador de avanço: impede pular a aula arrastando a barra do vídeo
                        const tempoBruto = Math.floor(this.ytPlayer.getCurrentTime());
                        const tolerancia = 2; // margem de segundos para variações naturais de buffer
                        if (tempoBruto > this.maiorTempoAssistido + tolerancia) {
                            this.ytPlayer.seekTo(this.maiorTempoAssistido, true);
                            this.tempoAtual = this.maiorTempoAssistido;
                        } else {
                            this.tempoAtual = tempoBruto;
                            if (this.tempoAtual > this.maiorTempoAssistido) {
                                this.maiorTempoAssistido = this.tempoAtual;
                            }
                        }

                        this.atualizarPorcentagemLocal();

                        if (this.tempoAtual - this.ultimoSalvamento >= 5) {
                            this.salvarProgresso();
                        }
                    }, 1000);
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
    @endpush
</x-aluno-layout>