<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoResource\Pages;
use App\Models\Curso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Cursos';
    protected static ?string $modelLabel = 'Curso';
    protected static ?string $pluralModelLabel = 'Cursos';
    protected static ?string $navigationGroup = 'Acadêmico';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Gerais')
                    ->schema([
                        Forms\Components\TextInput::make('titulo') // Corrigido de nome para titulo
                            ->required()
                            ->maxLength(255)
                            ->label('Nome do Curso'),

                        Forms\Components\Select::make('categoria')
                            ->options([
                                'DevOps' => 'DevOps',
                                'Cloud Computing' => 'Cloud Computing',
                                'Segurança da Informação' => 'Segurança da Informação',
                                'Desenvolvimento de Software' => 'Desenvolvimento de Software',
                                'Banco de Dados' => 'Banco de Dados',
                                'Suporte Técnico' => 'Suporte Técnico',
                            ])
                            ->required()
                            ->label('Área / Categoria'),

                        Forms\Components\Textarea::make('descricao')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Descrição do Curso'),
                    ])->columns(2),

                Forms\Components\Section::make('Configurações e Mídia')
                    ->schema([
                        Forms\Components\TextInput::make('carga_horaria')
                            ->numeric()
                            ->required()
                            ->suffix('horas')
                            ->label('Carga Horária'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'Em andamento' => 'Em andamento',
                                'Concluído' => 'Concluído',
                                'Não iniciado' => 'Não iniciado',
                            ])
                            ->required()
                            ->default('Não iniciado')
                            ->label('Status'),

                        Forms\Components\FileUpload::make('capa')
                            ->image()
                            ->disk('public')
                            ->directory('cursos/capas')
                            ->label('Imagem de Capa')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('capa')
                    ->disk('public')
                    ->label('Capa'),

                Tables\Columns\TextColumn::make('titulo') 
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Curso'),

                Tables\Columns\TextColumn::make('categoria')
                    ->badge()
                    ->color('primary')
                    ->label('Área'),

                Tables\Columns\TextColumn::make('carga_horaria')
                    ->suffix('h')
                    ->sortable()
                    ->label('Duração'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Concluído' => 'success',
                        'Em andamento' => 'warning',
                        'Não iniciado' => 'gray',
                        default => 'gray',
                    })
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Em andamento' => 'Em andamento',
                        'Concluído' => 'Concluído',
                        'Não iniciado' => 'Não iniciado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCursos::route('/'),
            'create' => Pages\CreateCurso::route('/create'),
            'edit' => Pages\EditCurso::route('/{record}/edit'),
        ];
    }
}