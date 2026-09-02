<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColaboradorResource\Pages;
use App\Filament\Resources\ColaboradorResource\RelationManagers\ProgressosRelationManager;
use App\Models\Usuario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class ColaboradorResource extends Resource
{
    protected static ?string $model = Usuario::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Colaboradores';
    protected static ?string $modelLabel = 'Colaborador';
    protected static ?string $pluralModelLabel = 'Colaboradores';
    protected static ?string $navigationGroup = 'Gestão de Pessoas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Colaborador')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255)
                            ->label('Nome Completo'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('E-mail Corporativo'),

                        Forms\Components\TextInput::make('cargo')
                            ->required()
                            ->maxLength(255)
                            ->label('Cargo'),

                        Forms\Components\Select::make('area')
                            ->options([
                                'DevOps' => 'DevOps',
                                'Cloud Computing' => 'Cloud Computing',
                                'Segurança da Informação' => 'Segurança da Informação',
                                'Desenvolvimento de Software' => 'Desenvolvimento de Software',
                                'Banco de Dados' => 'Banco de Dados',
                                'Suporte Técnico' => 'Suporte Técnico',
                            ])
                            ->required()
                            ->label('Área'),

                        Forms\Components\TextInput::make('password') // Alterado de password para senha
                            ->password()
                            ->label('Senha de Acesso')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText('Deixe em branco para manter a senha atual.'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'ativo' => 'Ativo',
                                'inativo' => 'Inativo',
                            ])
                            ->required()
                            ->default('ativo')
                            ->label('Status'),

                        Forms\Components\Hidden::make('perfil')
                            ->default('colaborador'),

                        Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->disk('public')
                            ->directory('usuarios/fotos')
                            ->avatar()
                            ->label('Foto Corporativa')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->weight('bold')
                    ->label('Colaborador'),

                Tables\Columns\TextColumn::make('cargo')
                    ->label('Cargo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
            ])
            ->filters([
                // 2. Corrigido: Filtro simplificado por status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ativo' => 'Ativo',
                        'inativo' => 'Inativo',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cargo')
                    ->options([
                        'colaborador' => 'Colaborador',
                        'gestor' => 'Gestor',
                        'admin' => 'Administrador',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver Progresso'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('perfil', '!=', 'administrador');
    }

    public static function getRelations(): array
    {
        return [
            ProgressosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColaboradors::route('/'),
            'create' => Pages\CreateColaborador::route('/create'),
            'view' => Pages\ViewColaborador::route('/{record}'),
            'edit' => Pages\EditColaborador::route('/{record}/edit'),
        ];
    }
}
