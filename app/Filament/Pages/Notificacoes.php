<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class Notificacoes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell'; // Ícone do menu
    protected static ?string $navigationLabel = 'Meus Avisos';    // Nome na aba lateral
    protected static ?string $title = 'Central de Notificações';

    protected static string $view = 'filament.pages.notificacoes';

    /**
     * Monta a tabela puxando as notificações do banco de dados
     */
    public function table(Table $table): Table
    {
        return $table
            // Começamos direto pelo Builder do Model de Notificações, filtrando pelo usuário logado
            ->query(
                DatabaseNotification::where('notifiable_type', auth()->user()->getMorphClass())
                    ->where('notifiable_id', auth()->id())
            ) 
            ->columns([
                TextColumn::make('data.mensagem')
                    ->label('Mensagem')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Recebido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}