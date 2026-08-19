<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Usuario;
use App\Models\Curso;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Colaboradores', Usuario::where('status', 'ativo')->count())
                ->description('+3 este mês')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Cursos Ativos', Curso::count())
                ->description('6 novos módulos')
                ->color('info'),

            Stat::make('Taxa de Conclusão', '68%')
                ->description('+15% vs. ano anterior')
                ->color('success'),

            Stat::make('Treinamentos Pendentes', '34')
                ->description('Ações necessárias')
                ->color('danger'),
        ];
    }
}