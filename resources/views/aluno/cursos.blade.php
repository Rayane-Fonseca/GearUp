<x-aluno-layout titulo-pagina="Meus Cursos" subtitulo-pagina="Catálogo de treinamentos">
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Meus Cursos</h2>
            <p class="text-xs text-gray-400 mt-1">Catálogo de treinamentos</p>
        </div>

        <!-- Filtro de Categorias em Pills -->
        <div class="relative" x-data="{
                podeVoltar: false,
                podeAvancar: false,
                avancarFiltros() { this.$refs.filtrosScroll.scrollBy({ left: 160, behavior: 'smooth' }); },
                voltarFiltros() { this.$refs.filtrosScroll.scrollBy({ left: -160, behavior: 'smooth' }); },
                atualizarSetas() {
                    const el = this.$refs.filtrosScroll;
                    if (!el) return;
                    this.podeVoltar = el.scrollLeft > 4;
                    this.podeAvancar = el.scrollLeft + el.clientWidth < el.scrollWidth - 4;
                }
            }"
            x-init="$nextTick(() => atualizarSetas())">
            <div x-ref="filtrosScroll" @scroll="atualizarSetas()" class="sem-scrollbar flex gap-2 overflow-x-auto px-9 lg:px-0 scroll-smooth">
                <a href="{{ route('aluno.cursos') }}"
                    class="px-4 py-1.5 text-xs rounded-full whitespace-nowrap transition-colors {{ !$categoria || $categoria === 'Todos' ? 'bg-blue-600 text-white font-semibold' : 'bg-white text-gray-600 hover:bg-gray-100 font-medium' }}">
                    Todos
                </a>
                @foreach($categorias as $cat)
                <a href="{{ route('aluno.cursos', ['categoria' => $cat]) }}"
                    class="px-4 py-1.5 text-xs rounded-full whitespace-nowrap transition-colors {{ $categoria === $cat ? 'bg-blue-600 text-white font-semibold' : 'bg-white text-gray-600 hover:bg-gray-100 font-medium' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>

            <!-- Botão Anterior -->
            <button x-show="podeVoltar" x-cloak @click="voltarFiltros()" type="button" aria-label="Categorias anteriores"
                    class="lg:hidden absolute left-0 top-0 bottom-0 w-9 flex items-center justify-start bg-gradient-to-r from-slate-50 via-slate-50/90 to-transparent">
                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-sm text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            </button>

            <!-- Botão Avançar -->
            <button x-show="podeAvancar" x-cloak @click="avancarFiltros()" type="button" aria-label="Mais categorias"
                    class="lg:hidden absolute right-0 top-0 bottom-0 w-9 flex items-center justify-end bg-gradient-to-l from-slate-50 via-slate-50/90 to-transparent">
                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-sm text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Grid de Cursos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($cursos as $curso)
            @php
            $percentual = $progressosPorCurso[$curso->id_curso] ?? 0;
            $status = $percentual >= 100 ? 'Concluído' : ($percentual > 0 ? 'Em andamento' : 'Não iniciado');

            $corBadge = match($status) {
            'Concluído' => 'bg-emerald-50 text-emerald-600',
            'Em andamento' => 'bg-amber-50 text-amber-600',
            default => 'bg-red-50 text-red-600',
            };

            $corBarra = match($status) {
            'Concluído' => 'bg-emerald-500',
            'Em andamento' => 'bg-amber-500',
            default => 'bg-red-500',
            };

            $corBarraTopo = match($curso->categoria ?? '') {
            'DevOps' => '#9B5DE5',
            'Cloud Computing', 'Cloud' => '#CA7FB0',
            'Banco de Dados' => '#FEE440',
            'Infraestrutura' => '#00BBF9',
            'Desenvolvimento de Software' => '#F15BB5',
            'Segurança da Informação' => '#00F5D4',
            'Suporte Técnico' => '#957fef',
            default => $status === 'Concluído' ? '#10B981' : '#94A3B8',
            };
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between p-5 relative {{ $curso->obrigatorio ? : '' }}">
                <!-- Barra colorida no topo -->
                <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $corBarraTopo }};"></div>

                <div>
                    <!-- Cabeçalho: Categoria, Status e Badge de Obrigatório no mesmo alinhamento -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 text-[11px] font-medium text-gray-600 bg-gray-100 rounded-full">
                                {{ $curso->categoria }}
                            </span>

                            <span class="px-3 py-1 text-[11px] font-medium rounded-full {{ $corBadge }}">
                                {{ $status }}
                            </span>
                        </div>

                        @if($curso->obrigatorio)
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide bg-red-600 text-white rounded-full flex items-center gap-1 shadow-sm shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            Obrigatório
                        </span>
                        @endif
                    </div>

                    <!-- Título e Detalhes do Curso -->
                    <a href="{{ route('aluno.cursos.show', $curso->id_curso) }}" class="group block">
                        <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors">
                            {{ $curso->titulo }}
                        </h3>
                    </a>

                    <p class="text-xs text-gray-400 mb-1">{{ $curso->instrutor }}</p>
                    <p class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $curso->carga_horaria }}h de conteúdo
                    </p>
                </div>

                <!-- Progresso e Botões -->
                <div class="mt-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $corBarra }} h-1.5 rounded-full" style="width: {{ $percentual }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-500">{{ round($percentual) }}%</span>
                    </div>

                    @if($status === 'Concluído')
                    <a href="{{ route('aluno.cursos.show', $curso->id_curso) }}"
                        class="w-full py-2.5 bg-green-50 hover:bg-green-100 text-green-600 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Revisar
                    </a>
                    @elseif($status === 'Em andamento')
                    <a href="{{ route('aluno.cursos.show', $curso->id_curso) }}"
                        class="w-full py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-600 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        Continuar
                    </a>
                    @else
                    <a href="{{ route('aluno.cursos.show', $curso->id_curso) }}"
                        class="w-full py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        Iniciar
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 col-span-3">Nenhum curso encontrado para esta categoria.</p>
            @endforelse
        </div>
    </div>
</x-aluno-layout>