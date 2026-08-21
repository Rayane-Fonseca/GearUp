@php
$hora = now()->timezone('America/Sao_Paulo')->hour;

if ($hora >= 5 && $hora < 12) {
    $saudacao='Bom dia' ;
    } elseif ($hora>= 12 && $hora < 18) {
        $saudacao='Boa tarde' ;
        } else {
        $saudacao='Boa noite' ;
        }
        @endphp

        <x-aluno-layout titulo-pagina="Início" subtitulo-pagina="{{ $saudacao . ', ' . explode(' ', $usuario->nome)[0] }}">
        <div class="p-8 max-w-7xl mx-auto space-y-8 text-gray-800 w-full">

            {{-- CARDS DE RESUMO / MÉTRICAS DO TOPO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- CARD DE SAUDAÇÃO --}}
                <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl p-5 text-white shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <span class="text-[11px] font-semibold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full backdrop-blur-sm">
                            {{ $saudacao }} 👋
                        </span>
                        <h2 class="text-xl font-bold mt-2 truncate">{{ $usuario->nome }}</h2>
                        <p class="text-xs text-blue-200 mt-0.5 truncate">{{ $usuario->cargo }}{{ $usuario->area ? ' • ' . $usuario->area : '' }}</p>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-blue-100 mb-1 font-medium">
                            <span>Progresso geral</span>
                            <span>{{ $progressoGeral }}%</span>
                        </div>
                        <div class="w-full bg-blue-950/40 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-400 h-2 rounded-full transition-all duration-300" style="width: {{ $progressoGeral }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- EM ANDAMENTO --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Em andamento</span>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $emAndamento->count() }}</h3>
                        <p class="text-xs text-amber-600 font-medium mt-1">Cursos em progresso</p>
                    </div>
                </div>

                {{-- CONCLUÍDOS --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Concluídos</span>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $concluidos->count() }}</h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1">{{ $certificadosCount }} certificados emitidos</p>
                    </div>
                </div>

                {{-- HORAS DE TREINAMENTO --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Horas totais</span>
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-3xl font-extrabold text-gray-900">{{ $horasTotais }}h</h3>
                        <p class="text-xs text-gray-400 font-medium mt-1">Acumuladas na plataforma</p>
                    </div>
                </div>

            </div>

            {{-- ALERTA DE CURSOS OBRIGATÓRIOS PENDENTES (de acordo com a área do aluno) --}}
            @if($cursosObrigatoriosPendentes->isNotEmpty())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-red-600 text-white rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-700 text-sm">
                            {{ $cursosObrigatoriosPendentes->count() }} curso{{ $cursosObrigatoriosPendentes->count() > 1 ? 's' : '' }} obrigatório{{ $cursosObrigatoriosPendentes->count() > 1 ? 's' : '' }} para sua área ({{ $usuario->area }}) ainda {{ $cursosObrigatoriosPendentes->count() > 1 ? 'não foram concluídos' : 'não foi concluído' }}
                        </h4>
                        <p class="text-xs text-red-600/80 mt-1">
                            {{ $cursosObrigatoriosPendentes->pluck('titulo')->take(3)->implode(' • ') }}{{ $cursosObrigatoriosPendentes->count() > 3 ? ' • ...' : '' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('aluno.cursos') }}" class="shrink-0 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl transition-colors text-center">
                    Ver cursos obrigatórios
                </a>
            </div>
            @endif

            {{-- CURSOS EM ANDAMENTO (LIMITADO A 3) --}}
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Continuar estudando</h3>
                    <a href="{{ route('aluno.cursos') }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 text-white bg-blue-600 rounded-xl text-xs font-semibold transition-all duration-200">Ver todos</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($emAndamento->take(3) as $progresso)
                    @php
                    // Aplicação da paleta de cores por categoria
                    $corCategoria = match($progresso->curso->categoria ?? '') {
                    'DevOps' => '#9B5DE5',
                    'Cloud Computing', 'Cloud' => '#CA7FB0',
                    'Banco de Dados' => '#FEE440',
                    'Infraestrutura' => '#00BBF9',
                    'Desenvolvimento de Software', 'Desenvolvimento' => '#F15BB5',
                    'Segurança da Informação', 'Segurança' => '#00F5D4',
                    'Suporte Técnico' => '#957fef',
                    default => '#3B82F6',
                    };
                    @endphp

                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-between space-y-4 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 py-1 text-[11px] font-semibold rounded-lg text-white" style="background-color: {{ $corCategoria }};">
                                {{ $progresso->curso->categoria }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ $progresso->curso->carga_horaria }}h</span>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $progresso->curso->titulo }}</h4>
                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $progresso->curso->descricao ?? 'Curso em andamento.' }}</p>
                        </div>

                        <div>
                            <div class="w-full bg-slate-100 rounded-full h-2 mb-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-300" style="width: {{ $progresso->porcentagem }}%; background-color: {{ $corCategoria }};"></div>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <span class="text-xs text-gray-500 font-medium">{{ $progresso->porcentagem }}% concluído</span>

                                <a href="{{ route('aluno.cursos') }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 text-white bg-blue-600 rounded-xl text-xs font-semibold transition-all duration-200">
                                    <span>Continuar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 bg-white p-6 rounded-2xl border border-gray-100 text-center">
                        <p class="text-xs text-gray-400">Nenhum curso em andamento no momento.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- SEÇÃO INFERIOR: ATIVIDADES E RECOMENDADOS (AGORA EM 2 COLUNAS) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- ATIVIDADES PENDENTES --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h4 class="font-bold text-gray-900 text-sm">Atividades pendentes</h4>
                    <div class="space-y-3">
                        @forelse($emAndamento->sortByDesc('porcentagem')->take(2) as $progresso)
                        <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between text-xs">
                            <div class="truncate mr-2">
                                <p class="font-semibold text-gray-800 truncate">{{ $progresso->curso->titulo }}</p>
                                <span class="text-amber-600 font-medium text-[11px]">{{ 100 - $progresso->porcentagem }}% restante</span>
                            </div>
                            <a href="{{ route('aluno.cursos') }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 text-white bg-blue-600 rounded-xl text-xs font-semibold transition-all duration-200">
                                Abrir
                            </a>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400">Nenhuma pendência no momento.</p>
                        @endforelse
                    </div>
                </div>

                {{-- CURSO RECOMENDADO --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h4 class="font-bold text-gray-900 text-sm">Recomendado para você</h4>
                    <div class="space-y-3">
                        @if($recomendado)
                        @php
                        $corRec = match($recomendado->categoria ?? '') {
                        'DevOps' => '#9B5DE5',
                        'Cloud Computing', 'Cloud' => '#CA7FB0',
                        'Banco de Dados' => '#FEE440',
                        'Infraestrutura' => '#00BBF9',
                        'Desenvolvimento de Software', 'Desenvolvimento' => '#F15BB5',
                        'Segurança da Informação', 'Segurança' => '#00F5D4',
                        'Suporte Técnico' => '#957fef',
                        default => '#3B82F6',
                        };
                        @endphp
                        <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between text-xs">
                            <div class="truncate mr-2">
                                <p class="font-semibold text-gray-800 truncate">{{ $recomendado->titulo }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full inline-block" style="background-color: {{ $corRec }};"></span>
                                    <span class="text-gray-500 text-[11px]">{{ $recomendado->categoria }} • {{ $recomendado->carga_horaria }}h</span>
                                </div>
                            </div>
                            <a href="{{ route('aluno.cursos') }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 text-white bg-blue-600 rounded-xl text-xs font-semibold transition-all duration-200">
                                Ver
                            </a>
                        </div>
                        @else
                        <p class="text-xs text-gray-400">Você já está matriculado em todos os cursos disponíveis.</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>
        </x-aluno-layout>