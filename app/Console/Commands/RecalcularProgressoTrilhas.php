<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;

class RecalcularProgressoTrilhas extends Command
{
    protected $signature = 'lms:recalcular-trilhas';
    protected $description = 'Recacula de forma massiva o progresso de todos os alunos ativos nas trilhas';

    public function handle()
    {
        $this->info('Iniciando recálculo de progresso das trilhas...');

        // Busca usuários ativos para processar em lotes (evita estourar a memória RAM)
        Usuario::where('status', 'ativo')->chunk(100, function ($usuarios) {
            foreach ($usuarios as $usuario) {
                // Aqui entraria a sua lógica de calcular a média aritmética 
                // das aulas assistidas dentro de uma trilha específica.
                // Exemplo hipotético: $usuario->atualizarProgressoDasTrilhas();
                
                $this->line("Progresso atualizado para o ID: {$usuario->id_usuario}");
            }
        });

        $this->info('Todos os progressos foram recalculados!');
    }
}