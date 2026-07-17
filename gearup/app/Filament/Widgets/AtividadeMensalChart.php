<?php

namespace App\Filament\Widgets;

use App\Models\Usuario;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AtividadeMensalChart extends ChartWidget
{
    protected int | string | array $columnSpan = 1; // Ocupa metade da tela
    protected static ?string $heading = 'Novos Alunos por Mês';
    protected static string $color = 'info';

    protected function getData(): array
    {
        // Se você tiver o pacote `flowframe/laravel-trend` instalado, pode usar a query abaixo.
        // Caso contrário, usamos um agrupamento nativo do Eloquent de forma simples:
        $dadosMensais = Usuario::selectRaw('COUNT(*) as total, DATE_FORMAT(created_at, "%Y-%m") as mes')
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->take(6)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Alunos Registrados',
                    'data' => $dadosMensais->pluck('total')->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => $dadosMensais->pluck('mes')->map(fn ($mes) => date('M/Y', strtotime($mes . "-01")))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}