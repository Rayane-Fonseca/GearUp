<x-admin-layout titulo-pagina="Gerenciar Colaboradores" subtitulo-pagina="Acompanhe o progresso de cada membro da equipe">
    <div class="p-8 max-w-7xl mx-auto space-y-6" x-data="{ modalAberto: false }">
        <form method="GET" action="{{ route('admin.colaboradores') }}" class="flex flex-wrap gap-3 items-center justify-between">
            <div class="flex gap-3 flex-1">
                <input type="text" name="busca" value="{{ $busca }}" placeholder="Buscar colaborador..." class="flex-1 max-w-xs px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                <select name="area" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    <option value="">Filtrar por área</option>
                    @foreach($areas as $a)
                    <option value="{{ $a }}" @selected($area===$a)>{{ $a }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200">Filtrar</button>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 font-medium">{{ $colaboradores->count() }} colaboradores</span>
                <button type="button" @click="modalAberto = true" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700">+ Novo colaborador</button>
            </div>
        </form>

        @if(session('status'))
        <div class="px-4 py-3 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl border border-emerald-100">
            {{ session('status') }}
        </div>
        @endif
        @if(session('erro'))
        <div class="px-4 py-3 bg-red-50 text-red-700 text-xs font-semibold rounded-xl border border-red-100">
            {{ session('erro') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Colaborador</th>
                        <th class="text-left px-6 py-3 font-semibold">Cargo</th>
                        <th class="text-left px-6 py-3 font-semibold">Área</th>
                        <th class="text-left px-6 py-3 font-semibold">E-mail</th>
                        <th class="text-right px-6 py-3 font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($colaboradores as $colaborador)
                    @php
                    $corStatus = $colaborador->status === 'Concluído' ? 'bg-emerald-50 text-emerald-600' : ($colaborador->status === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">{{ $colaborador->iniciais() }}</div>
                                <span class="font-semibold text-gray-800">{{ $colaborador->nome }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $colaborador->cargo }}</td>
                        <td class="px-6 py-3.5"><span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md font-medium">{{ $colaborador->area }}</span></td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $colaborador->email }}</td>

                        <td class="px-6 py-3.5 text-right">
                            <form method="POST" action="{{ route('admin.colaboradores.destroy', $colaborador->id_usuario) }}"
                                onsubmit="return confirm('Tem certeza que deseja excluir {{ $colaborador->nome }}? Essa ação não pode ser desfeita.');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">Nenhum colaborador encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="modalAberto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div @click.outside="modalAberto = false" class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-lg">Novo colaborador</h3>
                <form method="POST" action="{{ route('admin.colaboradores.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Nome</label>
                        <input type="text" name="nome" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">E-mail</label>
                        <input type="email" name="email" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Cargo</label>
                            <input type="text" name="cargo" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Área</label>
                            <input type="text" name="area" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400">A senha inicial será <strong>senha123</strong> — o colaborador pode alterá-la depois no perfil.</p>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="modalAberto = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>