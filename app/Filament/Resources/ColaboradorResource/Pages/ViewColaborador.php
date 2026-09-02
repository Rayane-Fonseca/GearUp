<?php

namespace App\Filament\Resources\ColaboradorResource\Pages;

use App\Filament\Resources\ColaboradorResource;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewColaborador extends ViewRecord
{
    protected static string $resource = ColaboradorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informações do Colaborador')
                    ->schema([
                        ImageEntry::make('foto')
                            ->circular()
                            ->label('Foto'),

                        TextEntry::make('nome')
                            ->label('Nome Completo')
                            ->weight('bold'),

                        TextEntry::make('email')
                            ->label('E-mail Corporativo'),

                        TextEntry::make('cargo')
                            ->label('Cargo'),

                        TextEntry::make('area')
                            ->label('Área')
                            ->badge(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state) => $state === 'ativo' ? 'success' : 'gray'),
                    ])
                    ->columns(3),

                Section::make('Resumo do Progresso')
                    ->description('Panorama geral do desempenho do colaborador nos cursos da plataforma.')
                    ->schema([
                        TextEntry::make('total_cursos')
                            ->label('Cursos Iniciados')
                            ->state(fn ($record) => $record->progressos()->count())
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('cursos_concluidos')
                            ->label('Concluídos')
                            ->state(fn ($record) => $record->progressos()->where('porcentagem', '>=', 100)->count())
                            ->badge()
                            ->color('success'),

                        TextEntry::make('cursos_em_andamento')
                            ->label('Em Andamento')
                            ->state(fn ($record) => $record->progressos()
                                ->where('porcentagem', '>', 0)
                                ->where('porcentagem', '<', 100)
                                ->count())
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('progresso_medio')
                            ->label('Progresso Médio')
                            ->state(fn ($record) => round($record->progressos()->avg('porcentagem') ?? 0) . '%')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('certificados_emitidos')
                            ->label('Certificados Emitidos')
                            ->state(fn ($record) => $record->certificados()->count())
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(5),
            ]);
    }
}