@extends('layouts.aluno')

@section('page_title', 'Seus Certificados')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-black text-slate-900 tracking-tight">Sua Galeria de Conclusões</h1>
    <p class="text-xs text-slate-500 mt-0.5">Baixe a comprovação oficial dos seus treinamentos concluídos a 100%.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm flex flex-col justify-between min-h-[170px]">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider px-2 py-0.5 bg-emerald-50 rounded-full">Concluído</span>
                <h3 class="text-base font-bold text-slate-900 mt-2.5 leading-snug">Python para Engenharia de Dados</h3>
                <p class="text-xs text-slate-400 mt-0.5">Carga Horária: 20 horas</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-2">
            <button class="flex-1 text-center py-2 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-100 transition">Visualizar</button>
            <a href="#" class="flex-1 text-center py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">PDF Oficial</a>
        </div>
    </div>
</div>
@endsection