<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsuarioResource\Pages;
use App\Models\Usuario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UsuarioResource extends Resource
{
    protected static ?string $model = Usuario::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255)
                            ->label('Nome Completo'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true) // Evita erro de duplicidade ao editar o próprio usuário
                            ->label('E-mail'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->label('Senha')
                            // Coleta o que foi digitado e joga para o campo real 'senha' que o seu banco usa
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            // Obrigatório apenas na criação de um novo registro
                            ->required(fn (string $context): bool => $context === 'create')
                            // Só atualiza o banco se o administrador preencher o campo
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255)
                            ->helperText('Deixe em branco se não quiser alterar a senha atual.'),

                        Forms\Components\Select::make('perfil')
                            ->options([
                                'admin' => 'Administrador',
                                'aluno' => 'Aluno',
                            ])
                            ->required()
                            ->label('Perfil de Acesso'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'ativo' => 'Ativo',
                                'inativo' => 'Inativo',
                            ])
                            ->required()
                            ->default('ativo')
                            ->label('Status'),

                       Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->disk('public') // 👈 Força o uso do disco público
                            ->directory('usuarios/fotos')
                            ->avatar()
                            ->imageResizeMode('force')
                            ->imageCropAspectRatio('1:1')
                            ->label('Foto de Perfil')
                            ->columnSpanFull(),
                    ])
                    ->columns(2), // Organiza o formulário em duas colunas
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular() // Mostra a foto redondinha na tabela
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('nome')
                    ->searchable()
                    ->sortable()
                    ->label('Nome'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('E-mail'),

                Tables\Columns\TextColumn::make('perfil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'aluno' => 'info',
                        'colaborador' => 'warning', // Cor laranja/amarela para colaborador
                        default => 'gray',          // Fallback seguro caso surja outro perfil
                    })
                    ->label('Perfil'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ativo' => 'success',
                        'inativo' => 'gray',
                    })
                    ->label('Status'),

                Tables\Columns\TextColumn::make('ultimo_acesso')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Último Acesso'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('perfil')
                    ->options([
                        'admin' => 'Administrador',
                        'aluno' => 'Aluno',
                    ])
                    ->label('Filtrar por Perfil'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ativo' => 'Ativos',
                        'inativo' => 'Inativos',
                    ])
                    ->label('Filtrar por Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Aqui podemos colocar históricos ou progresso no futuro
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsuarios::route('/'),
            'create' => Pages\CreateUsuario::route('/create'),
            'edit' => Pages\EditUsuario::route('/{record}/edit'),
        ];
    }
}