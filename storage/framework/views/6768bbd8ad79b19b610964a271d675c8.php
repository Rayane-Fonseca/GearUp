<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['tituloPagina' => 'Gerenciar Cursos','subtituloPagina' => 'Adicione, edite e organize os cursos da plataforma']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Gerenciar Cursos','subtitulo-pagina' => 'Adicione, edite e organize os cursos da plataforma']); ?>
    <div class="p-8 max-w-7xl mx-auto space-y-6" x-data="{
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
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500"><?php echo e($cursos->count()); ?> cursos cadastrados</p>
            <button @click="abrirNovo()" class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                <span>Novo curso</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-xs">
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $corStatus = $curso->status === 'Concluído' ? 'bg-emerald-50 text-emerald-600' : ($curso->status === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500');
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3.5 font-semibold text-gray-800"><?php echo e($curso->titulo); ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?php echo e($curso->categoria); ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?php echo e($curso->instrutor); ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?php echo e($curso->carga_horaria); ?>h</td>
                        <td class="px-6 py-3.5 text-gray-500"><?php echo e($curso->modulos_count); ?></td>
                        <td class="px-6 py-3.5"><span class="px-2.5 py-1 rounded-md font-medium <?php echo e($corStatus); ?>"><?php echo e($curso->status); ?></span></td>
                        <td class="px-6 py-3.5 text-right whitespace-nowrap">
                            <a href="<?php echo e(route('admin.cursos.gerenciar', $curso->id_curso)); ?>" class="px-2.5 py-1.5 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 mr-1.5 text-[11px] font-semibold">Gerenciar conteúdo</a>
                            <button @click="abrirEdicao(<?php echo \Illuminate\Support\Js::from($curso->only(['id_curso','titulo','categoria','instrutor','carga_horaria','status','descricao']))->toHtml() ?>)" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 mr-1.5">✎</button>
                            <form method="POST" action="<?php echo e(route('admin.cursos.destroy', $curso->id_curso)); ?>" class="inline" onsubmit="return confirm('Remover este curso?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Novo/Editar Curso -->
        <div x-show="modalAberto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div @click.outside="modalAberto = false" class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4">
                <h3 class="font-bold text-gray-900 text-lg" x-text="modoEdicao ? 'Editar curso' : 'Novo curso'"></h3>
                <form method="POST" :action="modoEdicao ? '/admin/cursos/' + cursoAtual.id_curso : '<?php echo e(route('admin.cursos.store')); ?>'" enctype="multipart/form-data" class="space-y-3">
                    <?php echo csrf_field(); ?>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp-corrigido (1)\GearUp-corrigido (1)\GearUp-main\resources\views/admin/cursos.blade.php ENDPATH**/ ?>