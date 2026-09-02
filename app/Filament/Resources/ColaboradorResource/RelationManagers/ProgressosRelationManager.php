<?php

namespace App\Filament\Resources\ColaboradorResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProgressosRelationManager extends RelationManager
{
    protected static string $relationship = 'progressos';

    protected static ?string $title = 'Progresso nos Cursos';

    protected static ?string $modelLabel = 'progresso';

    protected static ?string $icon = 'heroicon-o-chart-bar';

    // Os registros de progresso são gerados automaticamente pelo colaborador
    // ao consumir os cursos na plataforma, então esta aba é apenas de leitura.
    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('curso'))
            ->columns([
                Tables\Columns\TextColumn::make('curso.titulo')
                    ->label('Curso')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('curso.categoria')
                    ->label('Categoria')
                    ->badge()
                    ->color(fn ($record) => $record->curso?->cor_categoria ?? '#6B7280'),

                Tables\Columns\TextColumn::make('porcentagem')
                    ->label('Progresso')
                    ->suffix('%')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->state(fn ($record) => $record->status())
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Concluído' => 'success',
                        'Em andamento' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('obrigatorio')
                    ->label('Obrigatório')
                    ->state(fn ($record) => $record->curso
                        ? $record->curso->ehObrigatorioPara($this->getOwnerRecord())
                        : false)
                    ->boolean(),

                Tables\Columns\TextColumn::make('concluido_em')
                    ->label('Concluído em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última atividade')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'concluido' => 'Concluído',
                        'em_andamento' => 'Em andamento',
                        'nao_iniciado' => 'Não iniciado',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'concluido' => $query->where('porcentagem', '>=', 100),
                            'em_andamento' => $query->where('porcentagem', '>', 0)->where('porcentagem', '<', 100),
                            'nao_iniciado' => $query->where('porcentagem', 0),
                            default => $query,
                        };
                    }),

                Tables\Filters\SelectFilter::make('curso_id')
                    ->label('Curso')
                    ->relationship('curso', 'titulo'),
            ])
            ->defaultSort('porcentagem', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
