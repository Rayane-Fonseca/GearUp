@php
    $usuario = auth()->user();
    $rotaAtual = request()->route()->getName();
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearUp - Painel Administrativo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans lg:flex min-h-screen" x-data="{ sidebarAberta: false }">
    <!-- Overlay para fechar a sidebar no mobile -->
    <div x-show="sidebarAberta" x-transition.opacity @click="sidebarAberta = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" style="display: none;"></div>

    <aside :class="sidebarAberta ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-slate-900 text-white flex flex-col justify-between p-4 shrink-0 fixed inset-y-0 left-0 z-40 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static">
        <div class="space-y-6">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow p-1">
                        <img src="{{ asset('images/Logo.png') }}" alt="GearUp Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-2xl tracking-wide">GearUp</span>
                </div>
                <button @click="sidebarAberta = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-3">
                <span class="text-[10px] font-bold tracking-widest text-amber-400 bg-amber-400/10 px-2 py-1 rounded-md">ADMINISTRADOR</span>
            </div>

            <nav class="space-y-1 text-xs">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ $rotaAtual === 'admin.dashboard' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Dashboard</a>
                <a href="{{ route('admin.cursos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ $rotaAtual === 'admin.cursos' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Cursos</a>
                <a href="{{ route('admin.colaboradores') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ $rotaAtual === 'admin.colaboradores' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Colaboradores</a>
                <a href="{{ route('admin.perfil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ $rotaAtual === 'admin.perfil' ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Perfil</a>
            </nav>
        </div>

        <div class="space-y-1">
            <div class="flex items-center gap-3 px-3 py-2 bg-slate-800 rounded-xl text-xs">
                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center font-bold shrink-0">{{ $usuario->iniciais() }}</div>
                <div class="overflow-hidden">
                    <p class="font-semibold text-white truncate">{{ $usuario->nome }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ $usuario->cargo }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl">
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-100 px-4 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="sidebarAberta = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-1 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-lg font-bold text-gray-900 truncate">{{ $tituloPagina ?? '' }}</h1>
                    <p class="text-xs text-gray-400 truncate">{{ $subtituloPagina ?? '' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center font-bold text-white text-xs">{{ $usuario->iniciais() }}</div>
            </div>
        </header>

        @if(session('status'))
            <div class="mx-8 mt-4 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl">{{ session('status') }}</div>
        @endif

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>