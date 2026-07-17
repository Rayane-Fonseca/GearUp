@extends('layouts.aluno')

@section('page_title', 'Seu Perfil')

@section('content')
<div class="max-w-3xl bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="h-24 bg-gradient-to-r from-slate-900 to-blue-900"></div>
    
    <div class="p-6 relative pt-0">
        <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 mb-6">
            <img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nome).'&background=1d4ed8&color=fff' }}" class="w-20 h-20 rounded-xl object-cover border-4 border-white bg-white shadow-md flex-shrink-0">
            <div class="mb-1">
                <h2 class="text-xl font-black text-slate-900 leading-none">{{ auth()->user()->nome }}</h2>
                <p class="text-xs text-slate-500 font-semibold mt-1">{{ auth()->user()->cargo ?? 'Colaborador' }} • {{ auth()->user()->area ?? 'Tecnologia' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">E-mail Corporativo</label>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ auth()->user()->email }}</p>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Identificação / ID Usuário</label>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">#{{ auth()->user()->id_usuario ?? auth()->id() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection