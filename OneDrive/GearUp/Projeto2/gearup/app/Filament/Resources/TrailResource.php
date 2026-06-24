<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrailResource\Pages;
use App\Models\Trail;
use App\Models\Area;
use App\Models\Content;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrailResource extends Resource
{
    protected static ?string $model = Trail::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Trilhas';
    protected static ?string $modelLabel = 'Trilha';
    protected static ?string $pluralModelLabel = 'Trilhas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required()
                ->columnSpanFull(),

            Forms\Components\Select::make('area_id')
                ->label('Área')
                ->options(Area::where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->nullable(),

            Forms\Components\Select::make('type')
                ->label('Tipo')
                ->options([
                    'preset' => 'Predefinida pela empresa',
                    'custom' => 'Personalizada',
                ])
                ->default('preset')
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Ativa')
                ->default(true),

            Forms\Components\Textarea::make('description')
                ->label('Descrição')
                ->columnSpanFull(),

            Forms\Components\Repeater::make('items')
                ->label('Conteúdos da trilha')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('content_id')
                        ->label('Conteúdo')
                        ->options(Content::where('is_active', true)->pluck('title', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('order')
                        ->label('Ordem')
                        ->numeric()
                        ->default(0),
                ])
                ->orderColumn('order')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
            Tables\Columns\TextColumn::make('area.name')->label('Área'),
            Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
                ->badge()
                ->color(fn ($state) => $state === 'preset' ? 'info' : 'warning'),
            Tables\Columns\TextColumn::make('items_count')
                ->label('Conteúdos')
                ->counts('items'),
            Tables\Columns\IconColumn::make('is_active')->label('Ativa')->boolean(),
        ])
        ->actions([Tables\Actions\EditAction::make()])
        ->bulkActions([Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTrails::route('/'),
            'create' => Pages\CreateTrail::route('/create'),
            'edit'   => Pages\EditTrail::route('/{record}/edit'),
        ];
    }
}