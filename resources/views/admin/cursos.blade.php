<x-admin-layout titulo-pagina="Gerenciar Cursos" subtitulo-pagina="Adicione, edite e organize os cursos da plataforma">
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6" x-data="{
        modalAberto: false,
        modoEdicao: false,
        cursoAtual: { id_curso: null, titulo: '', categoria: '', instrutor: '', carga_horaria: '', status: 'Não iniciado', descricao: '' },
        abrirNovo() {
            this.modoEdicao = false;
            this.cursoAtual = { id_curso: null, titulo: '', categoria: '', instrutor: '', carga_horaria: '', status: 'Não iniciado', descricao: '' };
            this.modalAberto = true;
        },
        abrirEdicao(curso) {
            this.modoEdicao = true;
            this.cursoAtual = { ...curso };
            this.modalAberto = true;
        }
    }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">{{ $cursos->count() }} cursos cadastrados</p>
            <button @click="abrirNovo()" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                <span>+ Novo curso</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            {{-- Versão em tabela (telas médias/grandes) --}}
            <table class="hidden md:table w-full text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Curso</th>
                        <th class="text-left px-6 py-3 font-semibold">Área</th>
                        <th class="text-left px-6 py-3 font-semibold">Instrutor</th>
                        <th class="text-left px-6 py-3 font-semibold">Duração</th>
                        <th class="text-left px-6 py-3 font-semibold">Módulos</th>
                        <th class="text-left px-6 py-3 font-semibold">Status</th>
                        <th class="text-right px-6 py-3 font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cursos as $curso)
                    @php
                    $corStatus = $curso->status === 'Concluído' ? 'bg-emerald-50 text-emerald-600' : ($curso->status === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $curso->titulo }}</td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $curso->categoria }}</td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $curso->instrutor }}</td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $curso->carga_horaria }}h</td>
                        <td class="px-6 py-3.5 text-gray-500">{{ $curso->modulos_count }}</td>
                        <td class="px-6 py-3.5"><span class="px-2.5 py-1 rounded-md font-medium {{ $corStatus }}">{{ $curso->status }}</span></td>
                        <td class="px-6 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('admin.cursos.gerenciar', $curso->id_curso) }}" class="px-2.5 py-1.5 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 mr-1.5 text-[11px] font-semibold">Gerenciar conteúdo</a>
                            <button @click="abrirEdicao(@js($curso->only(['id_curso','titulo','categoria','instrutor','carga_horaria','status','descricao'])))" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 mr-1.5">✎</button>
                            <form method="POST" action="{{ route('admin.cursos.destroy', $curso->id_curso) }}" class="inline" onsubmit="return confirm('Remover este curso?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Versão em cartões (mobile) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($cursos as $curso)
                @php
                $corStatus = $curso->status === 'Concluído' ? 'bg-emerald-50 text-emerald-600' : ($curso->status === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500');
                @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 text-xs">{{ $curso->titulo }}</p>
                            <p class="text-gray-400 text-[11px] mt-0.5">{{ $curso->categoria }} • {{ $curso->instrutor }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-md font-medium text-[11px] shrink-0 {{ $corStatus }}">{{ $curso->status }}</span>
                    </div>
                    {{-- Opção com separador vertical e espaço de verdade --}}
                    <div class="flex items-center text-[11px] text-gray-500">
                        <span>{{ $curso->carga_horaria }}h de duração</span>
                        &nbsp;|&nbsp;
                        <span>{{ $curso->modulos_count }} módulo(s)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <a href="{{ route('admin.cursos.gerenciar', $curso->id_curso) }}" class="px-2.5 py-1.5 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-[11px] font-semibold">Gerenciar conteúdo</a>
                        <button @click="abrirEdicao(@js($curso->only(['id_curso','titulo','categoria','instrutor','carga_horaria','status','descricao'])))" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">✎</button>
                        <form method="POST" action="{{ route('admin.cursos.destroy', $curso->id_curso) }}" class="inline" onsubmit="return confirm('Remover este curso?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Modal Novo/Editar Curso -->
        <div x-show="modalAberto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div @click.outside="modalAberto = false" class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-lg" x-text="modoEdicao ? 'Editar curso' : 'Novo curso'"></h3>
                <form method="POST" :action="modoEdicao ? '/admin/cursos/' + cursoAtual.id_curso : '{{ route('admin.cursos.store') }}'" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <template x-if="modoEdicao"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Título</label>
                        <input type="text" name="titulo" x-model="cursoAtual.titulo" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Área</label>
                            <input type="text" name="categoria" x-model="cursoAtual.categoria" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Instrutor</label>
                            <input type="text" name="instrutor" x-model="cursoAtual.instrutor" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Carga horária</label>
                            <input type="number" name="carga_horaria" x-model="cursoAtual.carga_horaria" required min="1" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Status</label>
                            <select name="status" x-model="cursoAtual.status" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                                <option>Não iniciado</option>
                                <option>Em andamento</option>
                                <option>Concluído</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Descrição</label>
                        <textarea name="descricao" x-model="cursoAtual.descricao" rows="2" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="modalAberto = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>