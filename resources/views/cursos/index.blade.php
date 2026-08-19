<x-aluno-layout titulo-pagina="Conteúdo dos Cursos" subtitulo-pagina="Categorias, módulos e aulas disponíveis">
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Grade Curricular</h2>
            <p class="text-xs text-gray-400 mt-1">Navegue pelas categorias, módulos e aulas</p>
        </div>

        <div class="space-y-4">
            @foreach($categorias as $categoria)
                <!-- NÍVEL 1: CATEGORIA -->
                <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200" open>
                    <summary class="flex justify-between items-center p-5 font-bold text-base text-gray-900 bg-gray-50/60 hover:bg-gray-100/80 cursor-pointer select-none border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <span>{{ $categoria->nome }}</span>
                        </div>
                        <span class="px-3 py-1 text-[11px] bg-blue-50 text-blue-600 rounded-full font-semibold">
                            {{ $categoria->cursos->count() }} {{ Str::plural('curso', $categoria->cursos->count()) }}
                        </span>
                    </summary>

                    <div class="p-5 space-y-4 bg-white">
                        @foreach($categoria->cursos as $curso)
                            <!-- NÍVEL 2: CURSO -->
                            <details class="group/curso bg-gray-50/50 border border-gray-200/60 rounded-xl overflow-hidden transition-all">
                                <summary class="flex justify-between items-center p-4 font-semibold text-sm text-gray-800 hover:bg-gray-100/60 cursor-pointer select-none">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                        <span>{{ $curso->titulo }}</span>
                                    </div>
                                    <span class="px-2.5 py-0.5 text-[11px] bg-gray-200/70 text-gray-600 rounded-md font-medium">
                                        {{ $curso->modulos->count() }} {{ Str::plural('módulo', $curso->modulos->count()) }}
                                    </span>
                                </summary>

                                <div class="p-4 space-y-3 bg-white border-t border-gray-100">
                                    @foreach($curso->modulos as $modulo)
                                        <!-- NÍVEL 3: MÓDULO -->
                                        <details class="group/modulo border border-gray-100 rounded-lg overflow-hidden">
                                            <summary class="flex justify-between items-center p-3 font-medium text-xs text-gray-700 bg-gray-50 hover:bg-gray-100/80 cursor-pointer select-none">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    Módulo {{ $loop->iteration }}: {{ $modulo->titulo }}
                                                </span>
                                                <span class="text-[11px] text-gray-400 font-normal">
                                                    {{ $modulo->aulas->count() }} {{ Str::plural('aula', $modulo->aulas->count()) }}
                                                </span>
                                            </summary>

                                            <!-- NÍVEL 4: AULAS E PLAYER -->
                                            <ul class="divide-y divide-gray-100 p-2 bg-white">
                                                @foreach($modulo->aulas as $aula)
                                                    @php
                                                        $progresso = $aula->progressos->first();
                                                        $concluido = $progresso?->concluido;
                                                    @endphp
                                                    <li class="py-2.5 px-3 flex items-center justify-between hover:bg-gray-50/80 rounded-lg transition-colors">
                                                        <div class="flex items-center gap-3">
                                                            @if($concluido)
                                                                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </div>
                                                            @else
                                                                <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center shrink-0">
                                                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                                                </div>
                                                            @endif
                                                            <span class="text-xs font-medium text-gray-700">
                                                                {{ $aula->titulo }}
                                                            </span>
                                                        </div>

                                                        <!-- LINK PARA O PLAYER DE VÍDEO -->
                                                        <a href="{{ route('aulas.show', $aula->id) }}" 
                                                           class="px-3 py-1.5 text-xs font-semibold rounded-lg flex items-center gap-1.5 transition-colors {{ $concluido ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' : ($progresso ? 'bg-amber-50 hover:bg-amber-100 text-amber-600' : 'bg-blue-600 hover:bg-blue-700 text-white') }}">
                                                            <svg class="w-3 h-3 fill-current shrink-0" viewBox="0 0 24 24">
                                                                <path d="M8 5v14l11-7z" />
                                                            </svg>
                                                            <span>{{ $concluido ? 'Reassistir' : ($progresso ? 'Continuar' : 'Assistir') }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</x-aluno-layout>