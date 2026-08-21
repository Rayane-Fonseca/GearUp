<?php
$usuario = auth()->user();
$naoLidas = $usuario ? $usuario->notificacoes()->where('lida', false)->count() : 0;
$rotaAtual = request()->route()->getName();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>GearUp - Área do Colaborador</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="bg-slate-50 font-sans flex min-h-screen">
    <!-- Sidebar / Navegação -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between p-4 shrink-0">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow p-1">
                    <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="GearUp Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-2xl tracking-wide">GearUp</span>
            </div>

            <div class="px-3">
                <span class="text-[10px] font-bold tracking-widest text-blue-400">COLABORADOR</span>
            </div>

            <nav class="space-y-1 text-xs">
                <a href="<?php echo e(route('aluno.inicio')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'aluno.inicio' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Início</a>
                <a href="<?php echo e(route('aluno.cursos')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'aluno.cursos' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Cursos</a>
                <a href="<?php echo e(route('aluno.trilhas')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e(in_array($rotaAtual, ['aluno.trilhas', 'aluno.trilha-detalhe']) ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Trilhas</a>
                <a href="<?php echo e(route('aluno.certificados')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'aluno.certificados' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Certificados</a>
                <a href="<?php echo e(route('aluno.perfil')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'aluno.perfil' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Perfil</a>
            </nav>
        </div>

        <div class="space-y-1">
            <a href="<?php echo e(route('aluno.perfil')); ?>" class="flex items-center gap-3 px-3 py-2 bg-slate-800 rounded-xl text-xs">
                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center font-bold shrink-0"><?php echo e($usuario->iniciais()); ?></div>
                <div class="overflow-hidden">
                    <p class="font-semibold text-white truncate"><?php echo e($usuario->nome); ?></p>
                    <p class="text-[10px] text-slate-400 truncate"><?php echo e($usuario->cargo); ?></p>
                </div>
            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl">
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-900"><?php echo e($tituloPagina ?? ''); ?></h1>
                <p class="text-xs text-gray-400"><?php echo e($subtituloPagina ?? ''); ?></p>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('aluno.notificacoes')); ?>" class="relative w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($naoLidas > 0): ?>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] rounded-full flex items-center justify-center font-bold"><?php echo e($naoLidas); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
                <a href="<?php echo e(route('aluno.perfil')); ?>" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center font-bold text-white text-xs"><?php echo e($usuario->iniciais()); ?></a>
            </div>
        </header>

        <!-- Conteúdo Dinâmico -->
        <main class="flex-1 overflow-y-auto">
            <?php echo e($slot); ?>

        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\Users\otvoa\Downloads\GearUp-postgresql\resources\views/components/aluno-layout.blade.php ENDPATH**/ ?>