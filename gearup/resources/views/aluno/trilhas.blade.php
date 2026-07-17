@extends('layouts.aluno')

@section('page_title', 'Trilhas de Aprendizagem')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-black text-slate-900 tracking-tight">Suas Jornadas de Estudo</h1>
    <p class="text-xs text-slate-500 mt-0.5">Trilhas de capacitação de acordo com o seu plano de carreira.</p>
</div>

<div class="space-y-4">
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-slate-300 transition">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg flex-shrink-0">
                🚀
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-base font-bold text-slate-900">Formação Especialista DevOps</h3>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-red-50 text-red-700">Obrigatória</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Trilha focada em automação de infraestrutura, CI/CD e monitoramento de ambientes.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end flex-shrink-0">
            <div class="min-w-[140px]">
                <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1">
                    <span>Progresso</span>
                    <span>40%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-purple-600 h-1.5 rounded-full" style="width: 40%"></div>
                </div>
            </div>
            <a href="{{ route('aluno.trilhas.detalhe', 1) }}" class="px-4 py-2 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800 transition">Acessar Jornada</a>
        </div>
    </div>
</div>
@endsection