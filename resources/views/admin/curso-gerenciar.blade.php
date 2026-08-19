<x-admin-layout titulo-pagina="Conteúdo: {{ $curso->titulo }}" subtitulo-pagina="Gerencie os módulos e aulas deste curso">
    <div class="p-8 max-w-5xl mx-auto space-y-6"
        x-data="{
            modalModuloAberto: false,
            modoEdicaoModulo: false,
            moduloAtual: { id_modulo: null, titulo: '', descricao: '', ordem: '' },
            abrirNovoModulo() {
                this.modoEdicaoModulo = false;
                this.moduloAtual = { id_modulo: null, titulo: '', descricao: '', ordem: '' };
                this.modalModuloAberto = true;
            },
            abrirEdicaoModulo(modulo) {
                this.modoEdicaoModulo = true;
                this.moduloAtual = { ...modulo };
                this.modalModuloAberto = true;
            },
            modalAulaAberto: false,
            modoEdicaoAula: false,
            aulaAtual: { id: null, id_modulo: null, titulo: '', descricao: '', url_video: '', duracao_minutos: '' },
            abrirNovaAula(idModulo) {
                this.modoEdicaoAula = false;
                this.aulaAtual = { id: null, id_modulo: idModulo, titulo: '', descricao: '', url_video: '', duracao_minutos: '' };
                this.modalAulaAberto = true;
            },
            abrirEdicaoAula(aula) {
                this.modoEdicaoAula = true;
                this.aulaAtual = { ...aula };
                this.modalAulaAberto = true;
            }
        }">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.cursos') }}" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Voltar para cursos
            </a>
            <button @click="abrirNovoModulo()" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors">Novo módulo</button>
        </div>

        @if($curso->modulos->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-xs text-gray-400">
            Nenhum módulo cadastrado ainda. Clique em "Novo módulo" para começar.
        </div>
        @endif

        @foreach($curso->modulos as $modulo)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    @if($modulo->capa)
                    <img src="{{ asset('storage/'.$modulo->capa) }}" class="w-10 h-10 rounded-lg object-cover" alt="">
                    @endif
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $modulo->ordem }}. {{ $modulo->titulo }}</p>
                        @if($modulo->descricao)
                        <p class="text-[11px] text-gray-400">{{ $modulo->descricao }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button @click="abrirNovaAula({{ $modulo->id_modulo }})" class="px-2.5 py-1.5 text-[11px] font-semibold rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">+ Aula</button>
                    <button @click="abrirEdicaoModulo(@js($modulo->only(['id_modulo','titulo','descricao','ordem'])))" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">✎</button>
                    <form method="POST" action="{{ route('admin.modulos.destroy', $modulo->id_modulo) }}" onsubmit="return confirm('Remover este módulo e todas as suas aulas?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Excluir módulo">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($modulo->aulas as $aula)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-700">{{ $aula->ordem }}. {{ $aula->titulo }}</p>
                        <a href="{{ $aula->url_video }}" target="_blank" class="text-[11px] text-blue-500 hover:underline truncate block max-w-md">{{ $aula->url_video }}</a>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($aula->duracao_minutos)
                        <span class="text-[11px] text-gray-400 mr-2">{{ $aula->duracao_minutos }} min</span>
                        @endif
                        <button @click="abrirEdicaoAula(@js($aula->only(['id','id_modulo','titulo','descricao','url_video','duracao_minutos'])))" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">✎</button>
                        <form method="POST" action="{{ route('admin.aulas.destroy', $aula->id) }}" onsubmit="return confirm('Remover esta aula?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Excluir aula">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="px-6 py-3 text-[11px] text-gray-400">Nenhuma aula neste módulo ainda.</p>
                @endforelse
            </div>
        </div>
        @endforeach

        <!-- Modal Novo/Editar Módulo -->
        <div x-show="modalModuloAberto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div @click.outside="modalModuloAberto = false" class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-lg" x-text="modoEdicaoModulo ? 'Editar módulo' : 'Novo módulo'"></h3>
                <form method="POST"
                    :action="modoEdicaoModulo ? '/admin/modulos/' + moduloAtual.id_modulo : '{{ route('admin.modulos.store', $curso->id_curso) }}'"
                    enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <template x-if="modoEdicaoModulo"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Título</label>
                        <input type="text" name="titulo" x-model="moduloAtual.titulo" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Descrição</label>
                        <textarea name="descricao" x-model="moduloAtual.descricao" rows="2" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Ordem</label>
                        <input type="number" name="ordem" x-model="moduloAtual.ordem" min="1" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Imagem de capa</label>
                            <input type="file" name="capa" accept="image/*" class="w-full mt-1 text-[11px] text-gray-500 file:mr-2 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-600 file:text-[11px] file:font-semibold">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Imagem de fundo</label>
                            <input type="file" name="fundo" accept="image/*" class="w-full mt-1 text-[11px] text-gray-500 file:mr-2 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-600 file:text-[11px] file:font-semibold">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="modalModuloAberto = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Nova/Editar Aula -->
        <div x-show="modalAulaAberto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div @click.outside="modalAulaAberto = false" class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-lg" x-text="modoEdicaoAula ? 'Editar aula' : 'Nova aula'"></h3>
                <form method="POST"
                    :action="modoEdicaoAula ? '/admin/aulas/' + aulaAtual.id : '/admin/modulos/' + aulaAtual.id_modulo + '/aulas'"
                    class="space-y-3">
                    @csrf
                    <template x-if="modoEdicaoAula"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Título</label>
                        <input type="text" name="titulo" x-model="aulaAtual.titulo" required class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Link do vídeo</label>
                        <input type="url" name="url_video" x-model="aulaAtual.url_video" required placeholder="https://..." class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Duração (minutos)</label>
                            <input type="number" name="duracao_minutos" x-model="aulaAtual.duracao_minutos" min="1" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-700">Descrição</label>
                        <textarea name="descricao" x-model="aulaAtual.descricao" rows="2" class="w-full mt-1 px-3 py-2 text-xs rounded-xl border border-gray-200 outline-none focus:border-blue-600"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="modalAulaAberto = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>