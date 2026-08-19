<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => ['tituloPagina' => 'Perfil','subtituloPagina' => 'Gerencie suas credenciais e preferências de administração']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['titulo-pagina' => 'Perfil','subtitulo-pagina' => 'Gerencie suas credenciais e preferências de administração']); ?>
    <div x-data="{ aba: '<?php echo e(session('status') || session('success') || $errors->any() || $errors->updatePassword->any() ? 'senha' : 'perfil'); ?>' }" class="p-8 max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Perfil do Administrador</h2>
            <p class="text-sm text-gray-500">Gerencie suas credenciais e preferências de administração</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1 space-y-6">
                <!-- Card de Avatar -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-blue-700 to-indigo-800"></div>
                    <div class="p-5 -mt-10">
                        <div class="w-16 h-16 rounded-full bg-blue-600 border-4 border-white flex items-center justify-center text-white font-bold text-lg"><?php echo e($usuario->iniciais()); ?></div>
                        <h3 class="font-bold text-gray-900 mt-3"><?php echo e($usuario->nome); ?></h3>
                        <p class="text-xs font-semibold text-blue-600">Administrador</p>
                        <p class="text-xs text-gray-400"><?php echo e($usuario->email); ?></p>
                    </div>
                </div>

                <!-- Menu de Navegação -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                    <button type="button" @click="aba = 'perfil'"
                        :class="aba === 'perfil' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50'"
                        class="w-full flex items-center justify-between p-4 text-xs font-medium transition text-left">
                        <span>Informações do perfil</span>
                        <span :class="aba === 'perfil' ? 'text-blue-600' : 'text-gray-300'">&rsaquo;</span>
                    </button>

                    <button type="button" @click="aba = 'notificacoes'"
                        :class="aba === 'notificacoes' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50'"
                        class="w-full flex items-center justify-between p-4 text-xs font-medium transition text-left">
                        <span>Alertas do sistema</span>
                        <span :class="aba === 'notificacoes' ? 'text-blue-600' : 'text-gray-300'">&rsaquo;</span>
                    </button>

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

                <!-- ABA 1: Informações do Administrador -->
                <div x-show="aba === 'perfil'" class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h4 class="font-bold text-gray-900 mb-4">Informações de acesso</h4>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Nome completo</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->nome); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Nível de Acesso</p>
                                <p class="font-semibold text-gray-800 mt-0.5">Administrador Geral</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">Cargo</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->cargo ?? 'Gestor de Treinamento'); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-gray-400">E-mail</p>
                                <p class="font-semibold text-gray-800 mt-0.5"><?php echo e($usuario->email); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABA 2: Notificações Administrativas -->
                <div x-show="aba === 'notificacoes'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-900">Alertas do sistema</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Defina os relatórios e avisos administrativos que deseja receber.</p>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-800">Novos colaboradores cadastrados</p>
                                <p class="text-gray-400 text-[11px]">Receba alertas sempre que um novo usuário for inserido no sistema.</p>
                            </div>
                            <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-800">Relatórios semanais de desempenho</p>
                                <p class="text-gray-400 text-[11px]">Resumo consolidado das métricas de progresso da equipe.</p>
                            </div>
                            <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-800">Alertas de segurança e acessos</p>
                                <p class="text-gray-400 text-[11px]">Notificações sobre tentativas de login ou alterações de permissão.</p>
                            </div>
                            <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                        </div>
                    </div>
                </div>

                <!-- ABA 3: Alterar Senha -->
                <div x-show="aba === 'senha'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-900">Alterar senha administrativa</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Atualize sua senha para manter o acesso ao painel de administração seguro.</p>
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
                            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Sua nova senha já está ativa e o painel administrativo continua protegido.</p>
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
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?><?php /**PATH C:\Users\otvoa\Downloads\GearUp-corrigido (1)\GearUp-corrigido (1)\GearUp-main\resources\views/admin/perfil.blade.php ENDPATH**/ ?>