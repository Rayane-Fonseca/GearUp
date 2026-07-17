<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Notifications\AtividadePendenteNotification;
use Carbon\Carbon;

class VarrerAtividadesPendentes extends Command
{
    // O comando que será digitado no terminal
    protected $signature = 'lms:varrer-atividades';
    protected $description = 'Varre o banco em busca de atividades pendentes vencendo em 3 dias e avisa os alunos';

    public function handle()
    {
        $this->info('Iniciando varredura de atividades...');

        // 1. Simulação de query buscando alunos com prazos apertados
        // No seu sistema real, você faria um Join entre matriculas, atividades e entregas.
        $alunosComPendente = Usuario::where('status', 'ativo')->take(5)->get(); 

        foreach ($alunosComPendente as $aluno) {
            $dadosNotificacao = [
                'nome_atividade' => 'Avaliação Prática de Arquitetura de Software',
                'nome_curso' => 'Formação Laravel Core',
                'data_limite' => Carbon::now()->addDays(3)->format('d/m/Y'),
                'url_link' => '/painel/atividades/pendentes'
            ];

            // Dispara a notificação que criamos no passo anterior
            $aluno->notify(new AtividadePendenteNotification($dadosNotificacao));
            
            $this->line("Notificação enviada para: {$aluno->email}");
        }

        $this->info('Varredura concluída com sucesso!');
    }
}