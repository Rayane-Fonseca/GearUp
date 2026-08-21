<?php

namespace App\Console\Commands;

use App\Models\Curso;
use App\Models\Progresso;
use Illuminate\Console\Command;

class ZerarProgressoCursosSemAula extends Command
{
    protected $signature = 'progresso:zerar-sem-aula {--dry-run : Apenas mostra o que seria alterado, sem salvar}';

    protected $description = 'Zera o progresso (tabela progressos) de cursos que não possuem módulos/aulas cadastradas';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        // Cursos sem nenhuma aula cadastrada (via módulos)
        $cursosSemAula = Curso::whereDoesntHave('modulos.aulas')->pluck('id_curso');

        if ($cursosSemAula->isEmpty()) {
            $this->info('Nenhum curso sem aulas encontrado.');
            return self::SUCCESS;
        }

        $progressos = Progresso::whereIn('curso_id', $cursosSemAula)
            ->where(function ($query) {
                $query->where('porcentagem', '>', 0)->orWhere('concluido', true);
            })
            ->get();

        if ($progressos->isEmpty()) {
            $this->info('Nenhum progresso para zerar (cursos sem aula já estão com progresso 0).');
            return self::SUCCESS;
        }

        $this->table(
            ['usuario_id', 'curso_id', 'porcentagem_atual', 'concluido_atual'],
            $progressos->map(fn ($p) => [$p->usuario_id, $p->curso_id, $p->porcentagem, $p->concluido ? 'sim' : 'não'])
        );

        if ($dryRun) {
            $this->warn('Modo --dry-run: nada foi alterado. Rode sem --dry-run para aplicar.');
            return self::SUCCESS;
        }

        foreach ($progressos as $progresso) {
            $progresso->porcentagem = 0;
            $progresso->concluido = false;
            $progresso->concluido_em = null;
            $progresso->save();
        }

        $this->info("{$progressos->count()} registro(s) de progresso zerado(s) com sucesso.");

        return self::SUCCESS;
    }
}
