@extends('layouts.aluno')

@section('page_title', 'Detalhe da Trilha')

@section('content')
<div class="mb-8 border-b border-slate-200 pb-6">
    <a href="{{ route('aluno.trilhas') }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1 mb-2">&larr; Voltar para as trilhas</a>
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Formação Especialista DevOps</h1>
    <p class="text-sm text-slate-500 mt-0.5">Conclua todos os cursos obrigatórios da lista abaixo para emitir o selo agregado.</p>
</div>

<div class="space-y-8">
    <div>
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span>● Cursos Obrigatórios</span>
            <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold font-sans normal-case">Progresso Mínimo</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Docker e Kubernetes na Prática</h4>
                    <p class="text-xs text-slate-400 mt-1">DevOps • 18h</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">72% concluído</span>
                    <button class="text-xs font-bold text-blue-600 hover:underline">Estudar</button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">● Conteúdos Opcionais</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm opacity-85 flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Introdução a Metodologia Ágil</h4>
                    <p class="text-xs text-slate-400 mt-1">Business • 6h</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full">Não iniciado</span>
                    <button class="text-xs font-bold text-blue-600 hover:underline">Iniciar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection