<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use App\Models\Trilha;
use App\Models\Progresso;
use App\Models\Certificado;
use App\Models\Notificacao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TechCorpSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================================
        // 1. USUÁRIOS (Administrador + Colaboradores)
        // ==========================================================
        $admin = Usuario::create([
            'nome' => 'Rayane Fonseca',
            'email' => 'admin@techcorp.com.br',
            'password' => Hash::make('senha123'),
            'perfil' => 'administrador',
            'cargo' => 'Gerente de Pessoas',
            'area' => 'Recursos Humanos',
            'status' => 'ativo',
        ]);

        $colaboradoresData = [
            ['nome' => 'Lucas Andrade', 'email' => 'lucas@techcorp.com.br', 'cargo' => 'Desenvolvedor Sênior', 'area' => 'Desenvolvimento de Software'],
            ['nome' => 'Ana Costa', 'email' => 'ana@techcorp.com.br', 'cargo' => 'Cloud Architect', 'area' => 'Cloud Computing'],
            ['nome' => 'Rafael Torres', 'email' => 'rafael@techcorp.com.br', 'cargo' => 'Analista de Segurança', 'area' => 'Segurança da Informação'],
            ['nome' => 'Fernanda Lima', 'email' => 'fernanda@techcorp.com.br', 'cargo' => 'Engenheira de Dados', 'area' => 'Banco de Dados'],
            ['nome' => 'Carlos Mendes', 'email' => 'carlos@techcorp.com.br', 'cargo' => 'DevOps Engineer', 'area' => 'DevOps'],
            ['nome' => 'Marcos Vieira', 'email' => 'marcos@techcorp.com.br', 'cargo' => 'Sysadmin', 'area' => 'Infraestrutura'],
            ['nome' => 'Juliana Souza', 'email' => 'juliana@techcorp.com.br', 'cargo' => 'Analista de Suporte', 'area' => 'Suporte Técnico'],
            ['nome' => 'Paulo Ramos', 'email' => 'paulo@techcorp.com.br', 'cargo' => 'Arquiteto de Redes', 'area' => 'Infraestrutura'],
        ];

        $colaboradores = [];
        foreach ($colaboradoresData as $dados) {
            $colaboradores[$dados['nome']] = Usuario::create([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'password' => Hash::make('senha123'),
                'perfil' => 'colaborador',
                'cargo' => $dados['cargo'],
                'area' => $dados['area'],
                'status' => 'ativo',
            ]);
        }

        $lucas = $colaboradores['Lucas Andrade'];

        // ==========================================================
        // 2. CURSOS (catálogo ativo + histórico usado em certificados)
        // ==========================================================
        $cursosData = [
            ['titulo' => 'Docker e Kubernetes na Prática', 'categoria' => 'DevOps', 'instrutor' => 'Carlos Mendes', 'carga_horaria' => 18, 'status' => 'Em andamento',
                'descricao' => 'Conteinerização com Docker e orquestração com Kubernetes para times de DevOps.'],
            ['titulo' => 'AWS Solutions Architect', 'categoria' => 'Cloud Computing', 'instrutor' => 'Ana Paula Costa', 'carga_horaria' => 32, 'status' => 'Em andamento',
                'descricao' => 'Arquitetura de soluções em nuvem AWS, alta disponibilidade e boas práticas de custo.'],
            ['titulo' => 'Segurança Ofensiva — Pentest', 'categoria' => 'Segurança da Informação', 'instrutor' => 'Rafael Torres', 'carga_horaria' => 24, 'status' => 'Não iniciado',
                'descricao' => 'Fundamentos de testes de invasão, reconhecimento e exploração de vulnerabilidades.'],
            ['titulo' => 'Python para Engenharia de Dados', 'categoria' => 'Banco de Dados', 'instrutor' => 'Fernanda Lima', 'carga_horaria' => 20, 'status' => 'Concluído',
                'descricao' => 'Pipelines de dados, ETL e manipulação de grandes volumes com Python.'],
            ['titulo' => 'React e TypeScript Avançado', 'categoria' => 'Desenvolvimento de Software', 'instrutor' => 'Lucas Andrade', 'carga_horaria' => 22, 'status' => 'Em andamento',
                'descricao' => 'Componentização avançada, hooks customizados e tipagem forte em aplicações React.'],
            ['titulo' => 'Infraestrutura como Código', 'categoria' => 'Infraestrutura', 'instrutor' => 'Marcos Vieira', 'carga_horaria' => 16, 'status' => 'Não iniciado',
                'descricao' => 'Provisionamento de infraestrutura com Terraform e automação de ambientes.'],
            ['titulo' => 'Suporte Nível 2 — Windows Server', 'categoria' => 'Suporte Técnico', 'instrutor' => 'Paulo Ramos', 'carga_horaria' => 12, 'status' => 'Em andamento',
                'descricao' => 'Administração de servidores Windows, diretórios ativos e resolução de incidentes.'],
            ['titulo' => 'Fundamentos de Redes TCP/IP', 'categoria' => 'Infraestrutura', 'instrutor' => 'Paulo Ramos', 'carga_horaria' => 12, 'status' => 'Concluído',
                'descricao' => 'Conceitos essenciais de redes, protocolos TCP/IP e diagnósticos de conectividade.'],
            ['titulo' => 'Git e GitHub para Times', 'categoria' => 'DevOps', 'instrutor' => 'Juliana Souza', 'carga_horaria' => 8, 'status' => 'Concluído',
                'descricao' => 'Controle de versão, fluxo de ramificação e boas práticas de pull request em equipe.'],
        ];

        $cursos = [];
        foreach ($cursosData as $dados) {
            $cursos[$dados['titulo']] = Curso::create($dados);
        }

        // ==========================================================
        // 3. MÓDULOS E AULAS (estrutura mínima por curso)
        // ==========================================================
        foreach ($cursos as $curso) {
            $modulo1 = Modulo::create([
                'id_curso' => $curso->id_curso,
                'titulo' => 'Módulo 1: Fundamentos',
                'descricao' => 'Introdução aos principais conceitos do curso.',
                'ordem' => 1,
            ]);
            Aula::create(['id_modulo' => $modulo1->id_modulo, 'titulo' => 'Aula 1: Visão geral', 'duracao' => '15:00', 'duracao_minutos' => 15, 'ordem' => 1]);
            Aula::create(['id_modulo' => $modulo1->id_modulo, 'titulo' => 'Aula 2: Conceitos práticos', 'duracao' => '25:00', 'duracao_minutos' => 25, 'ordem' => 2]);

            $modulo2 = Modulo::create([
                'id_curso' => $curso->id_curso,
                'titulo' => 'Módulo 2: Aplicação Prática',
                'descricao' => 'Exercícios e estudos de caso aplicados ao dia a dia da TechCorp.',
                'ordem' => 2,
            ]);
            Aula::create(['id_modulo' => $modulo2->id_modulo, 'titulo' => 'Aula 3: Estudo de caso', 'duracao' => '30:00', 'duracao_minutos' => 30, 'ordem' => 1]);
        }

        // ==========================================================
        // 4. TRILHAS DE APRENDIZAGEM
        // ==========================================================
        $trilhasData = [
            'DevOps' => ['obrigatorios' => ['Docker e Kubernetes na Prática'], 'opcionais' => ['Git e GitHub para Times']],
            'Cloud Computing' => ['obrigatorios' => ['AWS Solutions Architect'], 'opcionais' => []],
            'Segurança da Informação' => ['obrigatorios' => ['Segurança Ofensiva — Pentest'], 'opcionais' => []],
            'Banco de Dados' => ['obrigatorios' => ['Python para Engenharia de Dados'], 'opcionais' => []],
            'Desenvolvimento de Software' => ['obrigatorios' => ['React e TypeScript Avançado'], 'opcionais' => []],
            'Infraestrutura' => ['obrigatorios' => ['Infraestrutura como Código'], 'opcionais' => ['Fundamentos de Redes TCP/IP']],
            'Suporte Técnico' => ['obrigatorios' => ['Suporte Nível 2 — Windows Server'], 'opcionais' => []],
        ];

        foreach ($trilhasData as $categoria => $grupos) {
            $trilha = Trilha::create([
                'titulo' => $categoria,
                'descricao' => "Conteúdos organizados para a área de {$categoria}.",
                'categoria' => $categoria,
                'ativo' => true,
            ]);

            foreach ($grupos['obrigatorios'] as $tituloCurso) {
                $trilha->cursos()->attach($cursos[$tituloCurso]->id_curso, ['obrigatorio' => true]);
            }
            foreach ($grupos['opcionais'] as $tituloCurso) {
                $trilha->cursos()->attach($cursos[$tituloCurso]->id_curso, ['obrigatorio' => false]);
            }
        }

        // ==========================================================
        // 5. PROGRESSO DO LUCAS ANDRADE (usuário de demonstração)
        // ==========================================================
        $progressoLucas = [
            'Docker e Kubernetes na Prática' => 72,
            'AWS Solutions Architect' => 45,
            'Python para Engenharia de Dados' => 100,
            'React e TypeScript Avançado' => 28,
            'Suporte Nível 2 — Windows Server' => 55,
            'Fundamentos de Redes TCP/IP' => 100,
            'Git e GitHub para Times' => 100,
        ];

        foreach ($progressoLucas as $tituloCurso => $porcentagem) {
            Progresso::create([
                'usuario_id' => $lucas->id_usuario,
                'curso_id' => $cursos[$tituloCurso]->id_curso,
                'porcentagem' => $porcentagem,
                'concluido' => $porcentagem >= 100,
                'concluido_em' => $porcentagem >= 100 ? now()->subMonths(rand(1, 6)) : null,
            ]);
        }

        // Progresso ilustrativo dos demais colaboradores (para o painel administrativo)
        $progressoOutros = [
            'Ana Costa' => ['AWS Solutions Architect', 45],
            'Rafael Torres' => ['Segurança Ofensiva — Pentest', 0],
            'Fernanda Lima' => ['Python para Engenharia de Dados', 100],
            'Carlos Mendes' => ['Docker e Kubernetes na Prática', 72],
            'Marcos Vieira' => ['Infraestrutura como Código', 15],
            'Juliana Souza' => ['Suporte Nível 2 — Windows Server', 80],
            'Paulo Ramos' => ['Fundamentos de Redes TCP/IP', 60],
        ];

        foreach ($progressoOutros as $nome => [$tituloCurso, $porcentagem]) {
            if ($porcentagem <= 0) {
                continue;
            }
            Progresso::create([
                'usuario_id' => $colaboradores[$nome]->id_usuario,
                'curso_id' => $cursos[$tituloCurso]->id_curso,
                'porcentagem' => $porcentagem,
                'concluido' => $porcentagem >= 100,
                'concluido_em' => $porcentagem >= 100 ? now()->subMonths(rand(1, 6)) : null,
            ]);
        }

        // Espalha datas de criação dos progressos ao longo do ano para alimentar o gráfico "Atividade mensal"
        $mes = 1;
        foreach (Progresso::all() as $progresso) {
            $data = Carbon::now()->startOfYear()->addMonths(($mes % 7))->addDays(rand(1, 25));
            $progresso->created_at = $data;
            $progresso->updated_at = $data;
            $progresso->save();
            $mes++;
        }

        // ==========================================================
        // 6. CERTIFICADOS DO LUCAS ANDRADE
        // ==========================================================
        Certificado::create([
            'id_usuario' => $lucas->id_usuario,
            'id_curso' => $cursos['Python para Engenharia de Dados']->id_curso,
            'codigo_autenticacao' => strtoupper('GEAR-' . Str::random(10)),
            'emitido_em' => Carbon::parse('2025-06-12'),
        ]);
        Certificado::create([
            'id_usuario' => $lucas->id_usuario,
            'id_curso' => $cursos['Fundamentos de Redes TCP/IP']->id_curso,
            'codigo_autenticacao' => strtoupper('GEAR-' . Str::random(10)),
            'emitido_em' => Carbon::parse('2025-03-03'),
        ]);
        Certificado::create([
            'id_usuario' => $lucas->id_usuario,
            'id_curso' => $cursos['Git e GitHub para Times']->id_curso,
            'codigo_autenticacao' => strtoupper('GEAR-' . Str::random(10)),
            'emitido_em' => Carbon::parse('2025-01-18'),
        ]);

        // ==========================================================
        // 7. NOTIFICAÇÕES DO LUCAS ANDRADE
        // ==========================================================
        Notificacao::create([
            'usuario_id' => $lucas->id_usuario,
            'titulo' => 'Nova trilha DevOps disponível!',
            'mensagem' => 'Trilha atualizada com o novo curso de Git e GitHub para Times.',
            'lida' => false,
        ]);
        Notificacao::create([
            'usuario_id' => $lucas->id_usuario,
            'titulo' => 'Certificado emitido',
            'mensagem' => 'Seu certificado de Python para Engenharia de Dados já está disponível.',
            'lida' => false,
        ]);
        Notificacao::create([
            'usuario_id' => $lucas->id_usuario,
            'titulo' => 'Prazo se aproximando',
            'mensagem' => 'Faltam poucos dias para concluir Docker e Kubernetes na Prática.',
            'lida' => false,
        ]);
    }
}
