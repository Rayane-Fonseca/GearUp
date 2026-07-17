<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuloResource\Pages;
use App\Models\Modulo;
use App\Models\Curso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class ModuloResource extends Resource
{
    protected static ?string $model = Modulo::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Módulos';
    protected static ?string $modelLabel = 'Módulo';
    protected static ?string $pluralModelLabel = 'Módulos';
    
    // Mantém no mesmo grupo de navegação lateral do Curso
    protected static ?string $navigationGroup = 'Gestão de Conteúdo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Módulo')
                    ->schema([
                        Select::make('id_curso')
                            ->label('Curso Pertencente')
                            ->relationship('curso', 'titulo') // Carrega automaticamente a relação configurada na Model
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('titulo')
                            ->label('Título do Módulo')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('ordem')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->helperText('Define qual módulo aparece primeiro (ex: 1, 2, 3...)'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('curso.titulo')
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('titulo')
                    ->label('Módulo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                // Coluna extra que calcula dinamicamente quantas aulas o módulo tem cadastrado
                TextColumn::make('aulas_count')
                    ->label('Qtd. Aulas')
                    ->counts('aulas')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                // Filtro para listar apenas módulos de um curso específico
                Tables\Filters\SelectFilter::make('id_curso')
                    ->label('Filtrar por Curso')
                    ->relationship('curso', 'titulo')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModulos::route('/'),
            'create' => Pages\CreateModulo::route('/create'),
            'edit' => Pages\EditModulo::route('/{record}/edit'),
        ];
    }
}