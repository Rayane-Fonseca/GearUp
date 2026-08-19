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
                        '#540d6e', // Slate
                        '#ee4266', // Gold
                        '#ffd23f', // Blue
                        '#3bceac', // Emerald
                        '#0ead69', // Amber
                        '#ee964b',
                        '#9a031e',
                        '#007200',
                        '#ffa69e',
                        '#0582ca',
                        '#e27396',
                        '#9f86c0',
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