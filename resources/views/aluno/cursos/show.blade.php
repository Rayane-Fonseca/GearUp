<x-aluno-layout titulo-pagina="{{ $curso->titulo }}" subtitulo-pagina="Detalhes e Módulos do Treinamento">
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        
        <!-- Navegação de Volta -->
        <div class="flex items-center justify-between">
            <a href="{{ route('aluno.cursos') }}" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                Voltar para Meus Cursos
            </a>
        </div>

        <!-- Card de Resumo do Curso -->
        @php
            $corBarraTopo = match($curso->categoria ?? '') {
                'DevOps' => '#9B5DE5',
                'Cloud Computing', 'Cloud' => '#CA7FB0',
                'Banco de Dados' => '#FEE440',
                'Infraestrutura' => '#00BBF9',
                'Desenvolvimento de Software' => '#F15BB5',
                'Segurança da Informação' => '#00F5D4',
                'Suporte Técnico' => '#957fef',
                default => '#3B82F6',
            };
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $corBarraTopo }};"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3 max-w-3xl">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 text-[11px] font-medium text-gray-600 bg-gray-50 rounded-full border border-gray-100">
                            {{ $curso->categoria ?? 'Geral' }}
                        </span>
                        <span class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $curso->carga_horaria ?? '0' }}h de conteúdo
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $curso->titulo }}</h1>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $curso->descricao ?? 'Sem descrição cadastrada para este treinamento.' }}</p>
                    
                    @if($curso->instrutor)
                        <p class="text-xs text-gray-400 font-medium">Instrutor: <span class="text-gray-600">{{ $curso->instrutor }}</span></p>
                    @endif
                </div>

                <!-- Estatística do Curso -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 min-w-[200px] text-center space-y-1 shrink-0">
                    <span class="text-xs font-medium text-gray-400">Total de Módulos</span>
                    <p class="text-2xl font-bold text-gray-900">{{ $curso->modulos->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Módulos e Aulas -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Módulos de Aprendizado</h2>

            @forelse($curso->modulos as $index => $modulo)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    
                    <!-- Cabeçalho do Módulo -->
                    <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 text-[10px] font-bold text-blue-600 bg-blue-50 rounded-full uppercase">
                                Módulo {{ $index + 1 }}
                            </span>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $modulo->titulo }}</h3>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">
                            {{ $modulo->aulas->count() }} {{ Str::plural('aula', $modulo->aulas->count()) }}
                        </span>
                    </div>

                    <!-- Lista de Aulas -->
                    <div class="divide-y divide-gray-100">
                        @forelse($modulo->aulas as $aula)
                            @php
                                $concluida = $aula->progressos->first()?->concluido ?? false;
                            @endphp
                            <div class="p-4 flex items-center justify-between hover:bg-gray-50/60 transition-colors">
                                <div class="flex items-center gap-3">
                                    @if($concluida)
                                        <div class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                            <svg class="w-3 h-3 fill-current ml-0.5" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-800">{{ $aula->titulo }}</h4>
                                        @if($concluida)
                                            <span class="text-[10px] font-medium text-emerald-600">Concluída</span>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('aluno.aulas.show', [$curso->id_curso ?? $curso->id, $aula->id_aula ?? $aula->id]) }}"
                                   class="py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold text-xs rounded-xl flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    Assistir
                                </a>
                            </div>
                        @empty
                            <p class="p-4 text-xs text-gray-400 italic">Nenhuma aula disponível neste módulo.</p>
                        @endforelse
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 text-xs">
                    Nenhum módulo cadastrado para este curso ainda.
                </div>
            @endforelse
        </div>

    </div>
</x-aluno-layout>