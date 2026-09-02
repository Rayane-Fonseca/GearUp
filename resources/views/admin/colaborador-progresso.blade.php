<x-admin-layout
    titulo-pagina="Progresso: {{ $colaborador->nome }}"
    subtitulo-pagina="Acompanhe o desempenho do colaborador nos cursos da plataforma">
    <div class="w-full max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="flex items-center justify-between">
            <a
                href="{{ route('admin.colaboradores') }}"
                class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Voltar para colaboradores
            </a>
        </div>

        {{-- Cabeçalho com dados do colaborador --}}
        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    @if($colaborador->foto)
                    <img
                        src="{{ asset('storage/' . $colaborador->foto) }}"
                        alt="{{ $colaborador->nome }}"
                        class="h-14 w-14 rounded-full object-cover">
                    @else
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                        {{ $colaborador->iniciais() }}
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $colaborador->nome }}</p>
                        <p class="text-xs text-gray-400">{{ $colaborador->cargo ?? 'Sem cargo' }} &middot; {{ $colaborador->email }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-md bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
                        {{ $colaborador->area ?? 'Sem área' }}
                    </span>

                    <span class="rounded-md px-2.5 py-1 text-[11px] font-medium {{ $colaborador->status === 'ativo' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($colaborador->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Cards de resumo --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Cursos iniciados</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ $totalCursos }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Concluídos</p>
                <p class="mt-1 text-xl font-bold text-green-600">{{ $concluidos }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Em andamento</p>
                <p class="mt-1 text-xl font-bold text-amber-500">{{ $emAndamento }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Não iniciados</p>
                <p class="mt-1 text-xl font-bold text-gray-400">{{ $naoIniciados }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-[11px] font-medium text-gray-400">Progresso médio</p>
                <p class="mt-1 text-xl font-bold text-blue-600">{{ $progressoMedio }}%</p>
            </div>
        </div>

        {{-- Lista de progresso por curso --}}
        <div class="w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-900">Progresso por curso</h3>
            </div>

            @if($colaborador->progressos->isEmpty())
            <p class="px-6 py-12 text-center text-xs text-gray-400">
                Este colaborador ainda não iniciou nenhum curso.
            </p>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($colaborador->progressos as $progresso)
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span
                                class="h-2 w-2 shrink-0 rounded-full"
                                style="background-color: {{ $progresso->curso->cor_categoria ?? '#6B7280' }};"></span>

                            <p class="truncate text-xs font-semibold text-gray-800">
                                {{ $progresso->curso->titulo ?? 'Curso removido' }}
                            </p>
                        </div>

                        <p class="mt-1 text-[11px] text-gray-400">
                            {{ $progresso->curso->categoria ?? '—' }}
                            @if($progresso->concluido_em)
                            &middot; Concluído em {{ $progresso->concluido_em->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-3 sm:w-64">
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100">
                            <div
                                class="h-full rounded-full {{ $progresso->porcentagem >= 100 ? 'bg-green-500' : ($progresso->porcentagem > 0 ? 'bg-amber-400' : 'bg-gray-300') }}"
                                style="width: {{ $progresso->porcentagem }}%;"></div>
                        </div>

                        <span class="w-10 shrink-0 text-right text-xs font-semibold text-gray-700">
                            {{ $progresso->porcentagem }}%
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Certificados emitidos --}}
        @if($colaborador->certificados->isNotEmpty())
        <div class="w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-900">Certificados emitidos</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($colaborador->certificados as $certificado)
                <div class="flex items-center justify-between p-5">
                    <p class="text-xs font-semibold text-gray-800">
                        {{ $certificado->curso->titulo ?? 'Curso removido' }}
                    </p>

                    <p class="text-[11px] text-gray-400">
                        Emitido em {{ $certificado->emitido_em?->format('d/m/Y') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-admin-layout>
