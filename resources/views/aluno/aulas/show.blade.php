<x-aluno-layout titulo-pagina="{{ $aula->titulo }}" subtitulo-pagina="{{ $curso->titulo }}">
    <div x-data="{ sidebarOpen: true, moduloAberto: {{ $aula->modulo_id ?? $aula->id_modulo ?? 'null' }} }" class="h-[calc(100vh-4rem)] flex flex-col bg-slate-50 text-gray-800 overflow-hidden">

        <!-- Header Superior do Player -->
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between shadow-xs shrink-0 z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('aluno.cursos.show', $curso->id_curso ?? $curso->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-blue-600 transition-colors">
                    Voltar ao Curso
                </a>
                <span class="text-gray-200">|</span>
                <h1 class="text-sm font-bold text-gray-900 truncate max-w-xl">{{ $curso->titulo }}</h1>
            </div>

            <!-- Botão de Ocultar/Exibir Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-100 px-3 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
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
                        @if(!empty($aula->url_video))
                            @if(Str::contains($aula->url_video, ['youtube.com', 'youtu.be']))
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $aula->url_video, $matches);
                                    $youtubeId = $matches[1] ?? null;
                                @endphp

                                @if($youtubeId)
                                    <div id="youtube-player" data-video-id="{{ $youtubeId }}" class="w-full h-full"></div>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-900 p-6 text-center">
                                        <span class="text-xs font-medium text-red-400">URL do YouTube inválida ou não reconhecida.</span>
                                    </div>
                                @endif
                            @else
                                <video id="html5-player" class="w-full h-full object-contain" controls controlsList="nodownload">
                                    <source src="{{ $aula->url_video }}" type="video/mp4">
                                    Seu navegador não suporta reprodução de vídeos.
                                </video>
                            @endif
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-900 p-6 text-center">
                                <svg class="w-14 h-14 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-medium">Esta aula não possui vídeo anexado.</span>
                            </div>
                        @endif
                    </div>

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Aula Anterior
                    </a>
                    @else
                    <div class="w-24"></div>
                    @endif

                    <!-- Barra de Progresso do Vídeo -->
                    <div class="flex-1 max-w-xs flex flex-col items-center">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div id="progress-bar" class="bg-blue-600 h-3 text-[10px] font-medium text-blue-100 text-center leading-none rounded-full transition-all duration-300" style="width: 0%">
                                0%
                            </div>
                        </div>

                        <div id="status-concluido" class="mt-1 text-xs text-emerald-600 font-bold hidden flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            Aula Concluída!
                        </div>
                    </div>

                    <!-- Próxima Aula -->
                    @if(isset($proximaAula) && $proximaAula)
                    <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $proximaAula->id_aula ?? $proximaAula->id]) }}"
                        class="py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors shadow-xs">
                        Próxima Aula
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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

                <div class="divide-y divide-gray-100 flex-1">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Lista de Aulas do Módulo -->
                        <div x-show="moduloAberto === {{ $modId }}" x-collapse class="bg-gray-50/50 divide-y divide-gray-100">
                            @forelse($modulo->aulas as $itemAula)
                            @php
                            $aulaItemId = $itemAula->id_aula ?? $itemAula->id;
                            $aulaAtualId = $aula->id_aula ?? $aula->id;
                            $eAulaAtual = $aulaItemId === $aulaAtualId;
                            $itemConcluida = $itemAula->progressos->first()?->concluido ?? false;
                            @endphp
                            <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaItemId]) }}"
                                id="item-aula-{{ $aulaItemId }}"
                                class="flex items-center justify-between px-4 py-3 text-xs transition-colors {{ $eAulaAtual ? 'bg-blue-50/80 text-blue-600 font-bold border-l-4 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">

                                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                    <div id="icone-aula-{{ $aulaItemId }}">
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
                                    </div>

                                    <span class="truncate">{{ $itemAula->titulo }}</span>
                                </div>

                                @if($eAulaAtual)
                                <span id="badge-tocando-{{ $aulaItemId }}" class="text-[10px] font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full shrink-0">Tocando</span>
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

    <!-- SCRIPT DE REPRODUÇÃO E PROGRESSO -->
    <script>
        @php
            $progressoAtual = $progresso ?? $aula->progressos->first();
        @endphp

        let player;
        let maxTimeWatched = {{ $progressoAtual->segundo_atual ?? 0 }};
        let percentWatched = {{ $progressoAtual->porcentagem ?? 0 }};
        const aulaId = {{ $aula->id_aula ?? $aula->id }};
        const aulaJaConcluida = {{ ($progressoAtual->concluido ?? false) ? 'true' : 'false' }};
        const proximaAulaUrl = @json(isset($proximaAula) && $proximaAula ? route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $proximaAula->id_aula ?? $proximaAula->id]) : null);

        let updateInterval;
        let aulaRedirecionada = false;

        // Configuração para Player HTML5 padrão (.mp4)
        document.addEventListener('DOMContentLoaded', function() {
            const videoElem = document.getElementById('html5-player');
            if (videoElem) {
                if (maxTimeWatched > 0 && !aulaJaConcluida) {
                    videoElem.currentTime = maxTimeWatched;
                }
                updateProgressBar(percentWatched);

                videoElem.addEventListener('timeupdate', function() {
                    trackHtml5Progress(videoElem);
                });
            }
        });

        function trackHtml5Progress(videoElem) {
            const currentTime = videoElem.currentTime;
            const duration = videoElem.duration;

            if (!duration) return;

            if (currentTime > maxTimeWatched) {
                maxTimeWatched = currentTime;
            }

            percentWatched = Math.min(100, Math.round((maxTimeWatched / duration) * 100));
            updateProgressBar(percentWatched);

            if (percentWatched >= 100 && !aulaRedirecionada) {
                aulaRedirecionada = true;
                salvarProgresso(duration, 100, true);
            } else if (Math.floor(currentTime) % 5 === 0) {
                salvarProgresso(maxTimeWatched, percentWatched, false);
            }
        }

        // Funções para Player do YouTube
        function onYouTubeIframeAPIReady() {
            const playerElem = document.getElementById('youtube-player');
            if (!playerElem) return;

            const videoId = playerElem.dataset.videoId;
            if (!videoId) return;

            player = new YT.Player('youtube-player', {
                videoId: videoId,
                playerVars: {
                    'controls': 1,
                    'rel': 0,
                    'disablekb': 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            if (aulaJaConcluida) {
                const desejaReassistir = confirm("Você já concluiu esta aula. Deseja assistir novamente?");
                if (!desejaReassistir) {
                    if (proximaAulaUrl) {
                        window.location.href = proximaAulaUrl;
                    } else {
                        alert("Você já concluiu todas as aulas deste curso!");
                    }
                    return;
                }
            }

            if (maxTimeWatched > 0 && !aulaJaConcluida) {
                player.seekTo(maxTimeWatched);
            }
            updateProgressBar(percentWatched);
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.PLAYING) {
                updateInterval = setInterval(trackProgress, 1000);
            } else {
                clearInterval(updateInterval);
            }
        }

        function trackProgress() {
            if (!player || !player.getCurrentTime) return;

            const currentTime = player.getCurrentTime();
            const duration = player.getDuration();

            if (!duration) return;

            if (currentTime > maxTimeWatched) {
                maxTimeWatched = currentTime;
            }

            percentWatched = Math.min(100, Math.round((maxTimeWatched / duration) * 100));
            updateProgressBar(percentWatched);

            if (percentWatched >= 100 && !aulaRedirecionada) {
                aulaRedirecionada = true;
                clearInterval(updateInterval);
                salvarProgresso(duration, 100, true);
                return;
            }

            if (Math.floor(currentTime) % 5 === 0) {
                salvarProgresso(maxTimeWatched, percentWatched, false);
            }
        }

        function updateProgressBar(percent) {
            const progressBar = document.getElementById('progress-bar');
            if (progressBar) {
                progressBar.style.width = percent + '%';
                progressBar.innerText = percent + '%';
            }

            if (percent >= 90) {
                const statusDiv = document.getElementById('status-concluido');
                if (statusDiv) statusDiv.classList.remove('hidden');
                atualizarVisualSidebar();
            }
        }

        function atualizarVisualSidebar() {
            const iconeContainer = document.getElementById(`icone-aula-${aulaId}`);
            if (iconeContainer) {
                iconeContainer.innerHTML = `
                    <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                `;
            }
        }

        function salvarProgresso(segundos, porcentagem, redirecionar = false) {
            axios.post('/api/progresso/atualizar', {
                aula_id: aulaId,
                segundo_atual: Math.floor(segundos),
                porcentagem: porcentagem
            })
            .then(response => {
                if (response.data.concluido) {
                    const statusDiv = document.getElementById('status-concluido');
                    if (statusDiv) statusDiv.classList.remove('hidden');
                    atualizarVisualSidebar();
                }

                if (redirecionar) {
                    if (proximaAulaUrl) {
                        window.location.href = proximaAulaUrl;
                    } else {
                        alert('Parabéns! Você concluiu todas as aulas deste curso.');
                    }
                }
            })
            .catch(error => console.error('Erro ao salvar progresso:', error));
        }
    </script>
    
    <!-- API do YouTube carregada após a declaração do script JS -->
    <script src="https://www.youtube.com/iframe_api"></script>
</x-aluno-layout>