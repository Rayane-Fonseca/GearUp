<x-aluno-layout titulo-pagina="Trilhas de Aprendizagem" subtitulo-pagina="Detalhes da trilha">
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <!-- Botão Voltar -->
        <div>
            <a href="{{ route('aluno.trilhas') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm text-xs font-semibold transition-all">
                Voltar para trilhas
            </a>
        </div>

        @php
            $progresso = (int) $progressoTrilha;
            $statusTrilha = $progresso >= 100 ? 'Concluído' : ($progresso > 0 ? 'Em andamento' : 'Não iniciado');

            // Cor da faixa no topo por categoria (HEX)
            $corBarraTopo = match($trilha->categoria ?? $trilha->area ?? '') {
                'DevOps' => '#9B5DE5',
                'Cloud Computing', 'Cloud' => '#CA7FB0',
                'Banco de Dados' => '#FEE440',
                'Infraestrutura' => '#00BBF9',
                'Desenvolvimento de Software' => '#F15BB5',
                'Segurança da Informação' => '#00F5D4',
                'Suporte Técnico' => '#957FEF',
                default => $statusTrilha === 'Concluído' ? '#10B981' : '#94A3B8',
            };

            // Cores dinâmicas por status da Trilha
            $estiloTrilha = match($statusTrilha) {
                'Concluído' => [
                    'badge' => 'bg-emerald-50 text-emerald-600',
                    'barra' => 'bg-emerald-500',
                ],
                'Em andamento' => [
                    'badge' => 'bg-amber-50 text-amber-600',
                    'barra' => 'bg-amber-500',
                ],
                default => [
                    'badge' => 'bg-red-50 text-red-600',
                    'barra' => 'bg-red-500',
                ],
            };
        @endphp

        <!-- Header Card da Trilha -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-6 relative">
            <!-- Linha de Destaque no Topo -->
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $corBarraTopo }};"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 text-[11px] font-medium text-gray-600 rounded-full bg-gray-100">
                            {{ $trilha->categoria ?? $trilha->area ?? 'Geral' }}
                        </span>
                        
                        <span class="px-3 py-1 text-[11px] font-medium rounded-full {{ $estiloTrilha['badge'] }}">
                            {{ $statusTrilha }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900">{{ $trilha->titulo }}</h2>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $obrigatorios->count() }} obrigatório(s) • {{ $opcionais->count() }} opcional(is)
                    </p>
                </div>

                <!-- Indicador de Progresso da Trilha -->
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 min-w-[240px]">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-gray-500">Progresso Geral</span>
                        <span class="text-sm font-bold text-gray-900">{{ $progresso }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="{{ $estiloTrilha['barra'] }} h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $progresso }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listas de Cursos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cursos Obrigatórios -->
            <div class="space-y-3">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                    <span class="text-amber-500">★</span> Obrigatórios
                </h3>

                @forelse($obrigatorios as $curso)
                    @php
                        $p = $progressosPorCurso[$curso->id_curso] ?? 0;
                        $statusCurso = $p >= 100 ? 'Concluído' : ($p > 0 ? 'Em andamento' : 'Não iniciado');

                        $estiloCurso = match($statusCurso) {
                            'Concluído' => [
                                'badge' => 'bg-emerald-50 text-emerald-600',
                                'barra' => 'bg-emerald-500',
                            ],
                            'Em andamento' => [
                                'badge' => 'bg-amber-50 text-amber-600',
                                'barra' => 'bg-amber-500',
                            ],
                            default => [
                                'badge' => 'bg-red-50 text-red-600',
                                'barra' => 'bg-red-500',
                            ],
                        };
                    @endphp

                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 text-[10px] font-semibold rounded-full {{ $estiloCurso['badge'] }}">
                                    {{ $statusCurso }}
                                </span>
                                <span class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $curso->carga_horaria }}h
                                </span>
                            </div>

                            <h4 class="font-bold text-gray-900 text-sm truncate mb-2">{{ $curso->titulo }}</h4>
                            
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $estiloCurso['barra'] }} h-1.5 rounded-full transition-all duration-300" style="width: {{ $p }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-500">{{ $p }}%</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-xs text-gray-400">
                        Nenhum curso obrigatório cadastrado.
                    </div>
                @endforelse
            </div>

            <!-- Cursos Opcionais -->
            <div class="space-y-3">
                <h3 class="font-bold text-gray-900 text-sm">Opcionais</h3>

                @forelse($opcionais as $curso)
                    @php
                        $p = $progressosPorCurso[$curso->id_curso] ?? 0;
                        $statusCurso = $p >= 100 ? 'Concluído' : ($p > 0 ? 'Em andamento' : 'Não iniciado');

                        $estiloCurso = match($statusCurso) {
                            'Concluído' => [
                                'badge' => 'bg-emerald-50 text-emerald-600',
                                'barra' => 'bg-emerald-500',
                            ],
                            'Em andamento' => [
                                'badge' => 'bg-amber-50 text-amber-600',
                                'barra' => 'bg-amber-500',
                            ],
                            default => [
                                'badge' => 'bg-red-50 text-red-600',
                                'barra' => 'bg-red-500',
                            ],
                        };
                    @endphp

                    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 text-[10px] font-semibold rounded-full {{ $estiloCurso['badge'] }}">
                                    {{ $statusCurso }}
                                </span>
                                <span class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $curso->carga_horaria }}h
                                </span>
                            </div>

                            <h4 class="font-bold text-gray-900 text-sm truncate mb-2">{{ $curso->titulo }}</h4>
                            
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $estiloCurso['barra'] }} h-1.5 rounded-full transition-all duration-300" style="width: {{ $p }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-500">{{ $p }}%</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 text-xs text-gray-400">
                        Nenhum curso opcional cadastrado.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-aluno-layout>