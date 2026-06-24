<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Models\Content;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Conteúdos';
    protected static ?string $modelLabel = 'Conteúdo';
    protected static ?string $pluralModelLabel = 'Conteúdos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('Tipo')
                ->options([
                    'course' => 'Curso',
                    'book'   => 'Livro',
                    'film'   => 'Filme',
                ])
                ->required(),

            Forms\Components\Select::make('area_id')
                ->label('Área')
                ->options(Area::where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->nullable(),

            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('author')
                ->label('Autor / Instituição'),

            Forms\Components\TextInput::make('url')
                ->label('URL')
                ->url(),

            Forms\Components\TextInput::make('duration')
                ->label('Duração'),

            Forms\Components\Select::make('language')
                ->label('Idioma')
                ->options([
                    'pt' => 'Português',
                    'en' => 'Inglês',
                    'es' => 'Espanhol',
                ])
                ->default('pt'),

            Forms\Components\Toggle::make('is_free')
                ->label('Gratuito')
                ->default(true),

            Forms\Components\Toggle::make('has_certificate')
                ->label('Com certificado')
                ->default(false),

            Forms\Components\Toggle::make('is_active')
                ->label('Ativo')
                ->default(true),

            Forms\Components\Textarea::make('description')
                ->label('Descrição')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
                ->badge()
                ->color(fn ($state) => match($state) {
                    'course' => 'info',
                    'book'   => 'success',
                    'film'   => 'warning',
                }),
            Tables\Columns\TextColumn::make('title')->label('Título')->searchable()->limit(40),
            Tables\Columns\TextColumn::make('author')->label('Autor')->limit(30),
            Tables\Columns\TextColumn::make('area.name')->label('Área'),
            Tables\Columns\IconColumn::make('is_free')->label('Gratuito')->boolean(),
            Tables\Columns\IconColumn::make('has_certificate')->label('Certificado')->boolean(),
            Tables\Columns\IconColumn::make('is_active')->label('Ativo')->boolean(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('type')
                ->label('Tipo')
                ->options([
                    'course' => 'Curso',
                    'book'   => 'Livro',
                    'film'   => 'Filme',
                ]),
            Tables\Filters\SelectFilter::make('area_id')
                ->label('Área')
                ->options(Area::pluck('name', 'id')),
        ])
        ->actions([Tables\Actions\EditAction::make()])
        ->bulkActions([Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit'   => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}