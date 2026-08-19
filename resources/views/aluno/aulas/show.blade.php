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
                    
                    <!-- Container do Player com Altura Fixa/Responsiva Garantida -->
                    <div class="relative w-full h-[480px] lg:h-[560px] bg-gray-900 rounded-2xl overflow-hidden shadow-sm border border-gray-100 mx-auto">
                        @if($aula->url_video)
                            @if(Str::contains($aula->url_video, ['youtube.com', 'youtu.be']))
                                {{-- Embed do YouTube --}}
                                <iframe class="w-full h-full" 
                                        src="{{ Str::contains($aula->url_video, 'embed') ? $aula->url_video : 'https://www.youtube.com/embed/' . Str::afterLast($aula->url_video, '/') }}" 
                                        title="{{ $aula->titulo }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            @else
                                {{-- Vídeo direto HTML5 --}}
                                <video class="w-full h-full object-contain" controls controlsList="nodownload">
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

                    <!-- Botão Marcar como Concluída (FIXO) -->
                    @php
                        $concluida = $aula->progressos->first()?->concluido ?? false;
                    @endphp
                    <form action="{{ route('progresso.atualizar') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="aula_id" value="{{ $aula->id_aula ?? $aula->id }}">
                        <button type="submit" 
                                class="py-2.5 px-6 font-semibold text-xs rounded-xl flex items-center gap-2 transition-all {{ $concluida ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-xs' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $concluida ? 'Aula Concluída' : 'Marcar como Concluída' }}
                        </button>
                    </form>

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
                                        $itemConcluida = $itemAula->progressos->first()?->concluido ?? false;
                                    @endphp
                                    <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aulaItemId]) }}" 
                                       class="flex items-center justify-between px-4 py-3 text-xs transition-colors {{ $eAulaAtual ? 'bg-blue-50/80 text-blue-600 font-bold border-l-4 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                        
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
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

                                            <span class="truncate">{{ $itemAula->titulo }}</span>
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
</x-aluno-layout>