<?php

namespace App\Filament\Resources\CertificadoResource\Pages;

use App\Filament\Resources\CertificadoResource;
use App\Models\Certificado;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Exception;

class CreateCertificado extends CreateRecord
{
    protected static string $resource = CertificadoResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->data;

        try {
            // Se o aluno selecionado não tiver 100% no curso selecionado, trava o salvamento
            Certificado::validarEmissao($data['id_usuario'], $data['id_curso']);
        } catch (Exception $e) {
            Notification::make()
                ->danger()
                ->title('Erro de Requisitos')
                ->body($e->getMessage())
                ->send();

            $this->halt(); // Cancela o processo no Filament
        }
    }
}