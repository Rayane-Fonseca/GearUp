<?php

namespace App\Filament\Resources\CertificadoResource\Pages;

use App\Filament\Resources\CertificadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCertificado extends EditRecord
{
    protected static string $resource = CertificadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function beforeSave(): void
    {
        try {
            \App\Models\Certificado::validarEmissao($this->record->id_usuario, $this->record->id_curso);
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Erro')->body($e->getMessage())->send();
            $this->halt();
        }
    }
}
