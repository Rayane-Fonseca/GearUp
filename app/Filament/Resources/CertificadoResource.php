<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificadoResource\Pages;
use Filament\Forms\Components\Select;
use App\Models\Certificado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CertificadoResource extends Resource
{
    protected static ?string $model = Certificado::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Certificados Emitidos';
    protected static ?string $modelLabel = 'Certificado';
    protected static ?string $pluralModelLabel = 'Certificados';
    protected static ?string $navigationGroup = 'Acadêmico';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        // Selecionar o Aluno (Usuario)
                        Forms\Components\Select::make('id_usuario')
                            ->relationship('usuario', 'nome')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Aluno'),

                        Select::make('id_curso')
                            ->label('Curso')
                            ->relationship('curso', 'titulo')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('codigo_autenticacao')
                            ->default(fn () => 'GEAR-' . strtoupper(Str::random(10)))
                            ->required()
                            ->unique(table: 'certificados', column: 'codigo_autenticacao', ignoreRecord: true)
                            ->label('Código de Autenticação')
                            ->readOnly(),

                        Forms\Components\DateTimePicker::make('emitido_em')
                            ->default(now())
                            ->required()
                            ->label('Data de Emissão'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo_autenticacao')
                    ->searchable()
                    ->copyable() // Permite copiar o código com 1 clique
                    ->fontFamily('mono')
                    ->label('Código'),

                Tables\Columns\TextColumn::make('usuario.nome')
                    ->searchable()
                    ->sortable()
                    ->label('Aluno'),

                Tables\Columns\TextColumn::make('curso.titulo') 
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('emitido_em') // ou 'data_emissao'
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Emitido em'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_curso')
                    ->label('Filtrar por Curso')
                    ->relationship('curso', 'titulo') // 👈 Garante o mapeamento correto
                    ->searchable()
                    ->preload(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificados::route('/'),
            'create' => Pages\CreateCertificado::route('/create'),
            'edit' => Pages\EditCertificado::route('/{record}/edit'),
        ];
    }
}