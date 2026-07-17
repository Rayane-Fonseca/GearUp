<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AulaResource\Pages;
use App\Models\Aula;
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

class AulaResource extends Resource
{
    protected static ?string $model = Aula::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Aulas';
    protected static ?string $modelLabel = 'Aula';
    protected static ?string $pluralModelLabel = 'Aulas';
    
    protected static ?string $navigationGroup = 'Gestão de Conteúdo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cadastro de Aula')
                    ->schema([
                        // Select auxiliar (não salvo no banco) apenas para filtrar os módulos abaixo
                        Select::make('id_curso_auxiliar')
                            ->label('Selecione o Curso')
                            ->options(Curso::pluck('titulo', 'id_curso'))
                            ->searchable()
                            ->reactive() // Torna o campo dinâmico
                            ->afterStateUpdated(fn (callable $set) => $set('id_modulo', null)) // Limpa o módulo se o curso mudar
                            ->dehydrated(false), // Não envia esse campo no submit

                        Select::make('id_modulo')
                            ->label('Módulo Correspondente')
                            ->options(function (callable $get) {
                                $cursoId = $get('id_curso_auxiliar');
                                
                                if (!$cursoId) {
                                    return Modulo::pluck('titulo', 'id_modulo'); // Mostra todos se nenhum curso for pré-selecionado
                                }
                                
                                return Modulo::where('id_curso', $cursoId)->pluck('titulo', 'id_modulo');
                            })
                            ->searchable()
                            ->required(),

                        TextInput::make('titulo')
                            ->label('Título da Aula')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),

                        TextInput::make('url_video')
                            ->label('URL do Vídeo (YouTube/Vimeo/S3)')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('duracao')
                            ->label('Duração (ex: 10:30 ou HH:MM:SS)')
                            ->maxLength(10)
                            ->placeholder('15:00'),

                        TextInput::make('ordem')
                            ->label('Ordem da Aula')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('modulo.curso.titulo')
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('modulo.titulo')
                    ->label('Módulo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('titulo')
                    ->label('Aula')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('duracao')
                    ->label('Duração')
                    ->alignCenter(),

                TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_modulo')
                    ->label('Filtrar por Módulo')
                    ->relationship('modulo', 'titulo')
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
            'index' => Pages\ListAulas::route('/'),
            'create' => Pages\CreateAula::route('/create'),
            'edit' => Pages\EditAula::route('/{record}/edit'),
        ];
    }
}