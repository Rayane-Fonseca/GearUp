<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoResource\Pages;
use App\Models\Curso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    // Ícone amigável para o menu lateral (um livro/chapéu de formatura)
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Cursos';
    protected static ?string $modelLabel = 'Curso';
    protected static ?string $pluralModelLabel = 'Cursos';
    
    // Agrupamento opcional no menu lateral
    protected static ?string $navigationGroup = 'Gestão de Conteúdo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Curso')
                    ->description('Cadastre os dados principais do curso.')
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título do Curso')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),

                        Textarea::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->rows(4)
                            ->columnSpan('full'),

                        TextInput::make('categoria')
                            ->label('Categoria/Setor')
                            ->placeholder('Ex: Tecnologia, Compliance, Soft Skills')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('carga_horaria')
                            ->label('Carga Horária (Horas)')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        FileUpload::make('imagem')
                            ->label('Capa do Curso')
                            ->image() // Permite apenas formatos de imagem válidos (jpeg, png, webp, gif, svg)
                            
                            // 1. Limite de tamanho (Evita que o usuário envie fotos pesadas de câmera profissional)
                            ->maxSize(2048) // Limite máximo de 2MB (em kilobytes)
                            
                            // 2. Validação de Extensões aceitas explicitamente
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            
                            // 3. Organização de pastas segura
                            ->directory('cursos')
                            
                            // 4. Ofuscação do nome do arquivo (Troca o nome original por um hash aleatório)
                            ->getUploadedFileNameForStorageUsing(
                                fn ($file): string => \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension()
                            )
                            
                            // 5. Padronização visual (Opcional - mas mantém a performance do seu Front-end)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1280')
                            ->imageResizeTargetHeight('720'),

                        Select::make('status')
                            ->label('Status de Publicação')
                            ->options([
                                'ativo' => 'Ativo',
                                'inativo' => 'Inativo',
                            ])
                            ->default('ativo')
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagem')
                    ->label('Capa')
                    ->square(),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('categoria')
                    ->label('Categoria')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('carga_horaria')
                    ->label('Duração')
                    ->suffix(' hrs')
                    ->sortable(),

                // Permite alterar o status direto pela tabela sem precisar entrar na edição
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'ativo' => 'Ativo',
                        'inativo' => 'Inativo',
                    ]),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ativo' => 'Ativos',
                        'inativo' => 'Inativos',
                    ]),
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Filtrar por Categoria')
                    ->multiple()
                    ->options(fn () => Curso::pluck('categoria', 'categoria')->unique()->toArray()),
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

    public static function getRelations(): array
    {
        return [
            // No futuro, podemos adicionar módulos como um gerenciador de relacionamento aqui!
        ];
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