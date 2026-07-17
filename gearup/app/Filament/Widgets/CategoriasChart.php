<?php

namespace App\Filament\Widgets;

use App\Models\Curso;
use Filament\Widgets\ChartWidget;

class CategoriasChart extends ChartWidget
{
    protected static ?string $maxHeight = '400px'; // Ocupa a outra metade
    protected static ?string $heading = 'Cursos por Categoria';

    protected function getData(): array
    {
        $distribuicao = Curso::selectRaw('categoria, count(*) as total')
            ->groupBy('categoria')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cursos',
                    'data' => $distribuicao->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#1e293b', // Slate
                        '#d4af37', // Gold
                        '#3b82f6', // Blue
                        '#10b981', // Emerald
                        '#f59e0b', // Amber
                    ],
                ],
            ],
            'labels' => $distribuicao->pluck('categoria')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Gráfico de rosca/pizza
    }
}