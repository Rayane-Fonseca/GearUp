<?php if (isset($component)) { $__componentOriginale4ebc9ed57c5009c9a50770282541134 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale4ebc9ed57c5009c9a50770282541134 = $attributes; } ?>
<?php $component = App\View\Components\AlunoLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('aluno-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AlunoLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Perfil','subtitulo-pagina' => 'Suas informações e configurações']); ?>
    <div x-data="{ aba: '<?php echo e(session('status') || session('success') || $errors->any() || $errors->updatePassword->any() ? 'senha' : 'perfil'); ?>' }" class="p-8 max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Perfil</h2>
            <p class="text-sm text-gray-500">Suas informações e configurações</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1 space-y-6">
                <!-- Card de Avatar e Métricas -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-blue-700 to-indigo-800"></div>
                    <div class="p-5 -mt-10">
                        <div class="w-16 h-16 rounded-full bg-blue-600 border-4 border-white flex items-center justify-center text-white font-bold text-lg"><?php echo e($usuario->iniciais()); ?></div>
                        <h3 class="font-bold text-gray-900 mt-3"><?php echo e($usuario->nome); ?></h3>
                        <p class="text-xs text-gray-500"><?php echo e($usuario->cargo); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($usuario->area); ?></p>

                        <div class="grid grid-cols-3 gap-2 mt-5 text-center">
                            <div class="bg-gray-50 rounded-xl p-2.5">
                                <p class="font-bold text-gray-900"><?php echo e($cursosCount); ?></p>
                                <p class="text-[10px] text-gray-400">Cursos</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-2.5">
                                <p class="font-bold text-gray-900"><?php echo e($horasTotais); ?>h</p>
                                <p class="text-[10px] text-gray-400">Horas</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-2.5">
                                <p class="font-bold text-gray-900"><?php echo e($certificadosCount); ?></p>
                                <p class="text-[10px] text-gray-400">Certs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu de Navegação da Página -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                    <button type="button" @click="aba = 'perfil'"
                        :class="aba === 'perfil' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50'"
                        class="w-full flex items-center justify-between p-4 text-xs font-medium transition text-left">
                        <span>Informações do perfil</span>
                        <span :class="aba === 'perfil' ? 'text-blue-600' : 'text-gray-300'">&rsaquo;</span>
                    </button>

                    <a href="<?php echo e(route('aluno.notificacoes')); ?>" class="flex items-center justify-between p-4 text-xs font-medium text-gray-700 hover:bg-gray-50">
                        <span>Notificações</span>
                        <span class="text-gray-300">&rsaquo;</span>
                    </a>

                    <button type="button" @click="aba = 'senha'"
                        :class="aba === 'senha' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50'"
                        class="w-full flex items-center justify-between p-4 text-xs font-medium transition text-left">
                        <span>Alterar senha</span>
                        <span :class="aba === 'senha' ? 'text-blue-600' : 'text-gray-300'">&rsaquo;</span>
                    </button>
                </div>

                <!-- Sair da Conta -->
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full py-3 bg-red-50 text-red-600 rounded-2xl text-xs font-semibold hover:bg-red-100 transition">Sair da conta</button>
                </form>
            </div>

            
            <div class="md:col-span-2 space-y-6">

                <!-- ABA 1: Informações Profissionais e Progresso -->
                <div x-show="aba === 'perfil'" class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h4 class="font-bold text-gray-900 mb-4">Informações profissionais</h4>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Cargo</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->cargo); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Empresa</p>
                                <p class="font-semibold text-gray-800 mt-0.5">TechCorp</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Área de atuação</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->area); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">E-mail</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->email); ?></p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h4 class="font-bold text-gray-900 mb-4">Progresso por área</h4>
                        <div class="space-y-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $progressoPorArea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area => $percentual): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                            $corBarra = match($area) {
                            'DevOps' => '#9B5DE5',
                            'Cloud Computing', 'Cloud' => '#CA7FB0',
                            'Banco de Dados' => '#FEE440',
                            'Infraestrutura' => '#00BBF9',
                            'Desenvolvimento de Software', 'Desenvolvimento' => '#F15BB5',
                            'Segurança da Informação', 'Segurança' => '#00F5D4',
                            'Suporte Técnico' => '#957fef',
                            default => '#94A3B8',
                            };
                            ?>

                            <div>
                                <div class="flex justify-between text-xs font-medium mb-1">
                                    <span class="text-gray-700"><?php echo e($area); ?></span>
                                    <span class="text-gray-500"><?php echo e($percentual); ?>%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-300"
                                        style="width: <?php echo e($percentual); ?>%; background-color: <?php echo e($corBarra); ?>;">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-xs text-gray-400">Você ainda não iniciou nenhum curso.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ABA 2: Formulário de Alterar Senha -->
                <div x-show="aba === 'senha'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-900">Alterar senha</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Atualize sua senha para manter sua conta do GearUp protegida.</p>
                    </div>

                    <!-- Mensagem de Sucesso (Verde Emerald) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status') === 'password-updated' || session('status')): ?>
                    <div class="w-full p-4 mb-4 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-xs font-bold text-emerald-950">Senha alterada com sucesso!</h5>
                            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Sua nova senha já está ativa e sua conta do GearUp continua protegida.</p>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Mensagem de Erro (Vermelho Rose) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any() || $errors->updatePassword->any()): ?>
                    <div class="w-full p-4 mb-4 bg-rose-50 border border-rose-200 rounded-2xl shadow-sm flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1 text-xs">
                            <h5 class="font-bold text-rose-950 mb-1">Não foi possível alterar a senha</h5>
                            <ul class="list-disc list-inside space-y-0.5 text-rose-700 font-medium">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->updatePassword->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form action="<?php echo e(route('password.update')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Senha atual</label>
                            <input type="password" name="current_password" required
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nova senha</label>
                            <input type="password" name="password" required
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                            <ul class="mt-2 space-y-1 text-[11px] text-gray-400">
                                <li>• Mínimo de 8 caracteres</li>
                                <li>• Ao menos 1 número e 1 caractere especial (!@#$...)</li>
                                <li>• Deve ser diferente da senha atual</li>
                            </ul>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Confirmar nova senha</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                Salvar nova senha
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $attributes = $__attributesOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__attributesOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4ebc9ed57c5009c9a50770282541134)): ?>
<?php $component = $__componentOriginale4ebc9ed57c5009c9a50770282541134; ?>
<?php unset($__componentOriginale4ebc9ed57c5009c9a50770282541134); ?>
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp\resources\views/aluno/perfil.blade.php ENDPATH**/ ?>