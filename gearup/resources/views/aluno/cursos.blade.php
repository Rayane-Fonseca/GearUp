@extends('layouts.aluno')

@section('page_title', 'Catálogo de Cursos')

@section('content')
@foreach($cursos as $curso)
<div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between group">
    <h4 class="font-bold text-slate-900 text-base leading-snug mt-2 mb-1">{{ $curso->titulo }}</h4>
    <p class="text-xs text-slate-500 line-clamp-2">{{ $curso->descricao }}</p>
    </div>
@endforeach
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Treinamentos Disponíveis</h1>
        <p class="text-xs text-slate-500 mt-0.5">Explore e incremente suas habilidades corporativas.</p>
    </div>
    <div class="flex gap-2 overflow-x-auto pb-1">
        <button class="px-3 py-1.5 bg-blue-600 text-white font-semibold text-xs rounded-lg shadow-sm">Todos</button>
        <button class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 font-semibold text-xs rounded-lg hover:bg-slate-50">DevOps</button>
        <button class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 font-semibold text-xs rounded-lg hover:bg-slate-50">Segurança</button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between group">
        <div>
            <div class="h-32 bg-slate-100 w-full relative flex items-center justify-center text-slate-400 font-bold text-xs uppercase tracking-wider border-b border-slate-100">
                Capa do Curso
            </div>
            <div class="p-5">
                <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">Cloud Computing</span>
                <h4 class="font-bold text-slate-900 text-base leading-snug mt-2 mb-1 group-hover:text-blue-600 transition">Arquitetura AWS Avançada</h4>
                <p class="text-xs text-slate-500 line-clamp-2">Aprenda a desenhar infraestruturas escaláveis e altamente resilientes.</p>
            </div>
        </div>
        <div class="p-5 pt-0">
            <div class="flex items-center justify-between text-xs text-slate-400 font-medium mb-4">
                <span>Carga: 24 horas</span>
                <span>• 8 Módulos</span>
            </div>
            <button class="w-full text-center py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">Matricular-se</button>
        </div>
    </div>
</div>
@endsection