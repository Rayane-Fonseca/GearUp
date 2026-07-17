<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearUp - Plataforma de Aprendizado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-slate-900 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0f172a] text-white flex flex-col justify-between hidden md:flex flex-shrink-0">
        <div>
            <div class="p-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center font-black text-white tracking-tighter">GU</div>
                <span class="text-xl font-black tracking-wider bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">GearUp</span>
            </div>
            
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('aluno.inicio') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-sm transition {{ request()->routeIs('aluno.inicio') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Início
                </a>
                <a href="{{ route('aluno.cursos') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-sm transition {{ request()->routeIs('aluno.cursos') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Catálogo de Cursos
                </a>
                <a href="{{ route('aluno.trilhas') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-sm transition {{ request()->routeIs('aluno.trilhas*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Minhas Trilhas
                </a>
                <a href="{{ route('aluno.certificados') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-sm transition {{ request()->routeIs('aluno.certificados') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Certificados
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('aluno.perfil') }}" class="flex items-center gap-3 w-full hover:bg-slate-800 p-2 rounded-lg transition overflow-hidden">
                <img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nome).'&background=1d4ed8&color=fff' }}" class="w-10 h-10 rounded-full object-cover border border-slate-700 flex-shrink-0">
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->nome }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->cargo ?? 'Colaborador' }}</p>
                </div>
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 z-10 flex-shrink-0 shadow-sm">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                @livewire('database-notifications')
            </div>
        </header>

        <main class="p-8 flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>
    @if(auth()->user()->perfil === 'admin')
    <div class="pt-4 mt-4 border-t border-slate-800">
        <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-sm text-amber-400 hover:bg-slate-800 hover:text-amber-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
            Painel Administrador
        </a>
    </div>
    @endif

</body>
</html>