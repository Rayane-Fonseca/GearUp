<x-admin-layout
    titulo-pagina="Gerenciar Colaboradores"
    subtitulo-pagina="Acompanhe o progresso de cada membro da equipe">
    <div
        class="w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6"
        x-data="{ modalAberto: false }">

        {{-- Barra de filtros e ações --}}
        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="p-4 sm:p-5">

                <form
                    method="GET"
                    action="{{ route('admin.colaboradores') }}"
                    class="w-full">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_12rem_auto]">

                        {{-- Campo de busca --}}
                        <div class="w-full min-w-0">
                            <label for="busca" class="sr-only">
                                Buscar colaborador
                            </label>

                            <input
                                id="busca"
                                type="text"
                                name="busca"
                                value="{{ $busca }}"
                                placeholder="Buscar colaborador..."
                                class="block w-full min-w-0 px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 bg-white text-gray-700 outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>

                        {{-- Filtro por área --}}
                        <div class="w-full min-w-0">
                            <label for="area" class="sr-only">
                                Filtrar por área
                            </label>

                            <select
                                id="area"
                                name="area"
                                onchange="this.form.submit()"
                                class="block w-full min-w-0 px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 bg-white text-gray-700 outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                                <option value="">Filtrar por área</option>

                                @foreach($areas as $a)
                                <option
                                    value="{{ $a }}"
                                    @selected($area===$a)>
                                    {{ $a }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botão filtrar --}}
                        <button
                            type="submit"
                            class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-semibold transition hover:bg-gray-200">
                            Filtrar
                        </button>
                    </div>
                </form>

                {{-- Contador e botão de novo colaborador --}}
                <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-xs font-medium text-gray-400">
                        {{ $colaboradores->count() }}
                        {{ $colaboradores->count() == 1 ? 'colaborador' : 'colaboradores' }}
                    </span>

                    <button
                        type="button"
                        @click="modalAberto = true"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-semibold shadow-sm transition hover:bg-blue-700">
                        + Novo colaborador
                    </button>
                </div>
            </div>
        </div>

        {{-- Lista de colaboradores --}}
        <div class="w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            {{-- Tabela para desktop --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[760px] text-left text-xs">
                    <thead class="border-b border-gray-100 bg-gray-50 text-[10px] uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Colaborador</th>
                            <th class="px-6 py-3.5 font-semibold">Cargo</th>
                            <th class="px-6 py-3.5 font-semibold">Área</th>
                            <th class="px-6 py-3.5 font-semibold">E-mail</th>
                            <th class="px-6 py-3.5 text-right font-semibold">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($colaboradores as $colaborador)
                        <tr class="transition hover:bg-gray-50/80">
                            <td class="whitespace-nowrap px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                        {{ $colaborador->iniciais() }}
                                    </div>

                                    <span class="font-semibold text-gray-800">
                                        {{ $colaborador->nome }}
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-3.5 text-gray-500">
                                {{ $colaborador->cargo ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-3.5">
                                <span class="rounded-md bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
                                    {{ $colaborador->area ?? 'Não informada' }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-3.5 text-gray-500">
                                {{ $colaborador->email }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.colaboradores.progresso', $colaborador->id_usuario) }}"
                                        title="Ver progresso"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100">
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.colaboradores.destroy', $colaborador->id_usuario) }}"
                                        class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja excluir {{ addslashes($colaborador->nome) }}? Essa ação não pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Excluir colaborador"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                Nenhum colaborador encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Cards para mobile --}}
            <div class="divide-y divide-gray-100 md:hidden">
                @forelse($colaboradores as $colaborador)
                <div class="space-y-3 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 flex-1 items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                                {{ $colaborador->iniciais() }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-semibold text-gray-800">
                                    {{ $colaborador->nome }}
                                </p>

                                <p class="truncate text-[11px] text-gray-400">
                                    {{ $colaborador->cargo ?? 'Sem cargo' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <a
                                href="{{ route('admin.colaboradores.progresso', $colaborador->id_usuario) }}"
                                title="Ver progresso"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100">
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.colaboradores.destroy', $colaborador->id_usuario) }}"
                                onsubmit="return confirm('Tem certeza que deseja excluir {{ addslashes($colaborador->nome) }}? Essa ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    title="Excluir colaborador"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-[11px]">
                        <span class="rounded-md bg-indigo-50 px-2 py-0.5 font-medium text-indigo-600">
                            {{ $colaborador->area ?? 'Sem área' }}
                        </span>

                        <span class="max-w-full truncate text-gray-400 sm:max-w-[200px]">
                            {{ $colaborador->email }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="px-4 py-8 text-center text-xs text-gray-400">
                    Nenhum colaborador encontrado.
                </p>
                @endforelse
            </div>
        </div>

        {{-- Modal --}}
        <div
            x-show="modalAberto"
            x-cloak
            x-trap.noscroll="modalAberto"
            x-transition.opacity
            @keydown.escape.window="modalAberto = false"
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm sm:items-center">
            <div
                x-show="modalAberto"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0"
                @click.outside="modalAberto = false"
                class="my-4 w-full max-w-md rounded-2xl border border-gray-100 bg-white p-5 shadow-xl sm:my-8 sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-base font-bold text-gray-900 sm:text-lg">
                        Novo colaborador
                    </h3>

                    <button
                        type="button"
                        @click="modalAberto = false"
                        class="shrink-0 rounded-lg p-1 text-gray-400 transition hover:text-gray-600">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.colaboradores.store') }}"
                    class="mt-5 space-y-3.5">
                    @csrf

                    <div>
                        <label for="nome" class="mb-1 block text-xs font-semibold text-gray-700">
                            Nome
                        </label>

                        <input
                            id="nome"
                            type="text"
                            name="nome"
                            required
                            placeholder="Nome completo"
                            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-xs font-semibold text-gray-700">
                            E-mail
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            required
                            placeholder="colaborador@empresa.com"
                            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>

                    <div>
                        <div>
                            <label for="cargo" class="mb-1 block text-xs font-semibold text-gray-700">
                                Cargo
                            </label>

                            <input
                                id="cargo"
                                type="text"
                                name="cargo"
                                placeholder="Ex.: Desenvolvedor"
                                class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>

                        <div>
                            <label for="area_modal" class="mb-1 block text-xs font-semibold text-gray-700">
                                Área
                            </label>

                            <input
                                id="area_modal"
                                type="text"
                                name="area"
                                placeholder="Ex.: Tecnologia"
                                class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>
                    </div>
                    <div>
                        <label for="area_modal" class="mb-1 block text-xs font-semibold text-gray-700">
                            Informações de login
                        </label>

                        <p class="rounded-xl border border-gray-100 bg-gray-50 p-2.5 text-[11px] text-gray-500">
                            A senha inicial será
                            <strong class="text-gray-700">senha123</strong>.
                            O colaborador poderá alterá-la no primeiro acesso.
                        </p>
                    </div>
                    <div class="flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:items-center sm:justify-end">
                        <button
                            type="button"
                            @click="modalAberto = false"
                            class="w-full rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-100 sm:w-auto">
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 sm:w-auto">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>