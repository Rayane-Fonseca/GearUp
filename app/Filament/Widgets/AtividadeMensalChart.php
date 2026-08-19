<?php

namespace App\Filament\Widgets;

use App\Models\Usuario;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AtividadeMensalChart extends ChartWidget
{
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'Novos Alunos por Mês';

    protected static ?array $options = [
        'indexAxis' => 'y',
        'plugins' => [
            'legend' => ['display' => false],
        ],
        'scales' => [
            'x' => [
                'beginAtZero' => true,
                'ticks' => ['precision' => 0],
                'grid' => ['display' => true, 'drawBorder' => false],
            ],
            'y' => [
                'grid' => ['display' => false],
            ],
        ],
    ];

    protected function getData(): array
    {
        $dadosBanco = Usuario::selectRaw('COUNT(*) as total, DATE_FORMAT(created_at, "%Y-%m") as mes')
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $labels = [];
        $totais = [];
        $backgroundColors = [];
        $borderColors = [];

        $mesAtual = Carbon::now()->format('Y-m');
        $inicio = Carbon::now()->subMonths(3)->startOfMonth();
        $fim = Carbon::now()->addMonths(3)->startOfMonth();

        while ($inicio <= $fim) {
            $chaveMes = $inicio->format('Y-m');
            
            $labels[] = ucfirst($inicio->translatedFormat('M/Y'));
            $totais[] = $dadosBanco[$chaveMes] ?? 0;

            if ($chaveMes === $mesAtual) {

                $backgroundColors[] = '#9f86c0';
                $borderColors[] = '#5e548e';
            } elseif ($chaveMes < $mesAtual) {

                $backgroundColors[] = '#468faf';
                $borderColors[] = '#014f86';
            } else {

                $backgroundColors[] = '#97a97c';
                $borderColors[] = '#718355';
            }

            $inicio->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Alunos Registrados',
                    'data' => $totais,
                    'backgroundColor' => $backgroundColors, 
                    'borderColor' => $borderColors, 
                    'borderWidth' => 1.5,
                    'borderRadius' => 6,
                    'maxBarThickness' => 32,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}