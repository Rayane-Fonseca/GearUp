<?php
    $usuario = auth()->user();
    $rotaAtual = request()->route()->getName();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearUp - Painel Administrativo</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-slate-50 font-sans flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between p-4 shrink-0">
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow p-1">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="GearUp Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-2xl tracking-wide">GearUp</span>
            </div>

            <div class="px-3">
                <span class="text-[10px] font-bold tracking-widest text-amber-400 bg-amber-400/10 px-2 py-1 rounded-md">ADMINISTRADOR</span>
            </div>

            <nav class="space-y-1 text-xs">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'admin.dashboard' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Dashboard</a>
                <a href="<?php echo e(route('admin.cursos')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'admin.cursos' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Cursos</a>
                <a href="<?php echo e(route('admin.colaboradores')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'admin.colaboradores' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Colaboradores</a>
                <a href="<?php echo e(route('admin.perfil')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium <?php echo e($rotaAtual === 'admin.perfil' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300'); ?>">Perfil</a>
            </nav>
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-3 px-3 py-2 bg-slate-800 rounded-xl text-xs">
                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center font-bold shrink-0"><?php echo e($usuario->iniciais()); ?></div>
                <div class="overflow-hidden">
                    <p class="font-semibold text-white truncate"><?php echo e($usuario->nome); ?></p>
                    <p class="text-[10px] text-slate-400 truncate"><?php echo e($usuario->cargo); ?></p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl">
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-900"><?php echo e($tituloPagina ?? ''); ?></h1>
                <p class="text-xs text-gray-400"><?php echo e($subtituloPagina ?? ''); ?></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center font-bold text-white text-xs"><?php echo e($usuario->iniciais()); ?></div>
            </div>
        </header>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
            <div class="mx-8 mt-4 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl"><?php echo e(session('status')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <main class="flex-1 overflow-y-auto">
            <?php echo e($slot); ?>

        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\otvoa\Downloads\GearUp-postgresql-atualizado\GearUp-postgresql\resources\views/components/admin-layout.blade.php ENDPATH**/ ?>