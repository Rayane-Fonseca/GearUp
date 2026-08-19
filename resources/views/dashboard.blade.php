<x-app-layout>
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dashboard Administrativo</h2>
            <p class="text-sm text-gray-500">Visão geral da plataforma</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-xs font-medium text-gray-400">Colaboradores</span>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2">142</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-xs font-medium text-gray-400">Cursos ativos</span>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2">28</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-xs font-medium text-gray-400">Taxa de conclusão</span>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2">68%</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-xs font-medium text-gray-400">Treinamentos pendentes</span>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2 text-amber-600">34</h3>
            </div>
        </div>
    </div>
</x-app-layout>