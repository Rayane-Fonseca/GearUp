<x-aluno-layout tituloPagina="Minhas Notificações" subtituloPagina="Confira os avisos e atualizações da sua conta">
    <div class="p-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Histórico de Avisos</h2>
            
            {{-- Exemplo de lista de notificações --}}
            <div class="divide-y divide-gray-100">
                @forelse(auth()->user()->notificacoes ?? [] as $notificacao)
                    <div class="py-4 flex items-start gap-4">
                        <div class="w-3 h-3 rounded-full bg-blue-600 mt-1 shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $notificacao->titulo }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notificacao->mensagem }}</p>
                            <span class="text-[10px] text-gray-400 mt-2 block">
                                {{ $notificacao->created_at ? $notificacao->created_at->diffForHumans() : 'Hoje' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-gray-400">
                        Você não possui nenhuma notificação no momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-aluno-layout>