<x-admin-layout titulo-pagina="Dashboard Administrativo" subtitulo-pagina="Visão geral da plataforma GearUp">
    <div class="p-8 max-w-7xl mx-auto space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900">{{ $totalColaboradores }}</span>
                <p class="text-xs text-gray-500 font-medium mt-1">Colaboradores</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900">{{ $totalCursos }}</span>
                <p class="text-xs text-gray-500 font-medium mt-1">Cursos ativos</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900">{{ $taxaConclusao }}%</span>
                <p class="text-xs text-gray-500 font-medium mt-1">Taxa de conclusão</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <span class="text-2xl font-extrabold text-gray-900">{{ $pendentes }}</span>
                <p class="text-xs text-gray-500 font-medium mt-1">Treinamentos pendentes</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Atividade mensal</h4>
                <canvas id="graficoAtividade" height="90"></canvas>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Distribuição</h4>
                <canvas id="graficoDistribuicao" height="140"></canvas>
                <div class="space-y-1.5 mt-4 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Concluídos</span>
                        <span class="font-semibold">{{ $distribuicao['concluidos'] }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Em andamento</span>
                        <span class="font-semibold">{{ $distribuicao['em_andamento'] }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Não iniciados</span>
                        <span class="font-semibold">{{ $distribuicao['nao_iniciados'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Cursos mais acessados</h4>
                <div class="space-y-3">
                    @foreach($cursosMaisAcessados as $indice => $curso)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-400 w-4">{{ $indice + 1 }}</span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $curso['titulo'] }}</p>
                                    <p class="text-gray-400">{{ $curso['categoria'] }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-blue-600">{{ $curso['percentual'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Colaboradores com pendências</h4>
                <div class="space-y-3">
                    @foreach($colaboradoresComPendencias as $colaborador)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shrink-0">{{ strtoupper(substr($colaborador['nome'], 0, 2)) }}</div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $colaborador['nome'] }}</p>
                                    <p class="text-gray-400">{{ $colaborador['area'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $colaborador['percentual'] }}%</p>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $colaborador['status'] === 'Em andamento' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }}">{{ $colaborador['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/admin-charts.js'])
    <script>
        window.dadosAtividadeMensal = @json($atividadeMensal);
        window.dadosDistribuicao = @json($distribuicao);
    </script>
</x-admin-layout>
