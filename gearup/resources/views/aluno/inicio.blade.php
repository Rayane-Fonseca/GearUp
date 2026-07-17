@extends('layouts.aluno')

@section('page_title', 'Visão Geral')

@section('content')
<div class="mb-8">
    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Plataforma Corp</span>
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Bem-vindo de volta, {{ explode(' ', auth()->user()->nome)[0] }}!</h1>
    <p class="text-sm text-slate-500 mt-0.5">Acompanhe suas metas de treinamento da semana.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Progresso Consolidado</p>
        <div class="flex items-center gap-4 mt-2">
            <span class="text-3xl font-black text-slate-900">{{ auth()->user()->progresso ?? 0 }}%</span>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ auth()->user()->progresso ?? 0 }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Cursos Matriculados</p>
            <span class="text-3xl font-black text-slate-900 mt-1 block">{{ $cursosAndamentoCount ?? 3 }}</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">3</div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Certificados Emitidos</p>
            <span class="text-3xl font-black text-slate-900 mt-1 block">{{ $certificadosCount ?? 1 }}</span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">1</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Em Andamento</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between min-h-[180px]">
                <div>
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-full bg-amber-50 text-amber-700">Em andamento</span>
                        <span class="text-xs text-slate-400 font-medium">18h</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base leading-snug mb-1">Docker e Kubernetes na Prática</h4>
                    <p class="text-xs text-slate-500">Área: DevOps</p>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-3">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: 72%"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-600">72% concluído</span>
                        <button class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-100 transition">Acessar aula</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Comunicados</h3>
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-5 space-y-4">
            <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">Recursos Humanos</span>
                <h5 class="text-sm font-bold text-slate-900 mt-0.5">Atualização do catálogo de trilhas obrigatórias</h5>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2">Fique atento aos prazos das novas trilhas de segurança da informação.</p>
            </div>
        </div>
    </div>
</div>
@endsection