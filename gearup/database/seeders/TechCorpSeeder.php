<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use App\Models\Trilha;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TechCorpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. CRIAR USUÁRIOS (ADMIN E COLABORADORES)
        $admin = Usuario::create([
            'nome' => 'Rayane Fonseca',
            'email' => 'rayane@techcorp.com',
            'senha' => Hash::make('senha123'),
            'foto' => null,
            'perfil' => 'admin',
            'status' => 'ativo',
            'ultimo_acesso' => Carbon::now(),
        ]);

        $colaborador1 = Usuario::create([
            'nome' => 'Gisseli Rocha',
            'email' => 'gisseli@techcorp.com',
            'senha' => Hash::make('senha123'),
            'foto' => null,
            'perfil' => 'colaborador',
            'status' => 'ativo',
            'ultimo_acesso' => Carbon::now()->subDays(2),
        ]);

        $colaborador2 = Usuario::create([
            'nome' => 'Yasmin Fabiano',
            'email' => 'yasmin@techcorp.com',
            'senha' => Hash::make('senha123'),
            'foto' => null,
            'perfil' => 'colaborador',
            'status' => 'ativo',
            'ultimo_acesso' => Carbon::now()->subHours(5),
        ]);

        // 2. CRIAR CURSOS
        $cursoGit = Curso::create([
            'titulo' => 'Git e GitHub Essencial para Equipes',
            'descricao' => 'Aprenda controle de versão profissional, fluxo de ramificação (Git Flow), resolução de conflitos e boas práticas de pull requests no GitHub corporativo.',
            'categoria' => 'Tecnologia',
            'carga_horaria' => 8,
            'imagem' => 'cursos/git-github.png',
            'status' => 'ativo',
        ]);

        $cursoSegurança = Curso::create([
            'titulo' => 'Segurança da Informação e LGPD',
            'descricao' => 'Treinamento obrigatório de conformidade com a LGPD, engenharia social, phishing, segurança de senhas e proteção de dados sensíveis na TechCorp.',
            'categoria' => 'Conformidade',
            'carga_horaria' => 4,
            'imagem' => 'cursos/lgpd-seguranca.png',
            'status' => 'ativo',
        ]);

        $cursoSoftSkills = Curso::create([
            'titulo' => 'Comunicação Eficiente no Trabalho Remoto',
            'descricao' => 'Descubra como estruturar sua comunicação de forma assíncrona, evitar mal-entendidos no Slack e organizar reuniões altamente produtivas.',
            'categoria' => 'Soft Skills',
            'carga_horaria' => 6,
            'imagem' => 'cursos/comunicacao.png',
            'status' => 'ativo',
        ]);

        // 3. CRIAR MÓDULOS E AULAS PARA OS CURSOS
        
        // Módulos do Curso de Git
        $moduloGit1 = Modulo::create([
            'id_curso' => $cursoGit->id_curso,
            'titulo' => 'Módulo 1: Conceitos Básicos e Commits',
            'descricao' => 'Entendendo o fluxo local e versionamento.',
            'ordem' => 1,
        ]);

        $aulaGit1 = Aula::create([
            'id_modulo' => $moduloGit1->id_modulo,
            'titulo' => 'O que é Git e por que usamos?',
            'tipo' => 'video',
            'conteudo' => 'Nesta aula vamos entender a diferença entre Git e GitHub e o poder do controle de versão.',
            'url_arquivo' => 'https://vimeo.com/exemplo/git-1',
            'duracao' => 15,
            'ordem' => 1,
        ]);

        $aulaGit2 = Aula::create([
            'id_modulo' => $moduloGit1->id_modulo,
            'titulo' => 'Configurando seu ambiente local',
            'tipo' => 'pdf',
            'conteudo' => 'Siga o guia passo a passo em PDF para instalar o Git e configurar suas chaves SSH no GitHub.',
            'url_arquivo' => 'documentos/guia-configuracao-git.pdf',
            'duracao' => 10,
            'ordem' => 2,
        ]);

        $moduloGit2 = Modulo::create([
            'id_curso' => $cursoGit->id_curso,
            'titulo' => 'Módulo 2: Branches e Trabalho em Equipe',
            'descricao' => 'Dominando ramificações e fluxos colaborativos.',
            'ordem' => 2,
        ]);

        $aulaGit3 = Aula::create([
            'id_modulo' => $moduloGit2->id_modulo,
            'titulo' => 'Entendendo e Criando Branches',
            'tipo' => 'video',
            'conteudo' => 'Entenda o conceito de ramificação para trabalhar em paralelo sem quebrar o código principal.',
            'url_arquivo' => 'https://vimeo.com/exemplo/git-2',
            'duracao' => 20,
            'ordem' => 1,
        ]);

        // Módulos do Curso de Segurança
        $moduloSeg1 = Modulo::create([
            'id_curso' => $cursoSegurança->id_curso,
            'titulo' => 'Módulo Único: Protegendo a TechCorp',
            'descricao' => 'Como aplicar a segurança da informação na sua rotina diária.',
            'ordem' => 1,
        ]);

        $aulaSeg1 = Aula::create([
            'id_modulo' => $moduloSeg1->id_modulo,
            'titulo' => 'O perigo do Phishing no e-mail corporativo',
            'tipo' => 'video',
            'conteudo' => 'Aprenda a identificar e-mails suspeitos e o que fazer ao se deparar com uma possível ameaça.',
            'url_arquivo' => 'https://vimeo.com/exemplo/phishing',
            'duracao' => 25,
            'ordem' => 1,
        ]);

        $aulaSeg2 = Aula::create([
            'id_modulo' => $moduloSeg1->id_modulo,
            'titulo' => 'Conformidade LGPD na Prática',
            'tipo' => 'quiz',
            'conteudo' => 'Teste seus conhecimentos sobre o tratamento de dados de clientes segundo as regras da LGPD.',
            'url_arquivo' => null,
            'duracao' => 15,
            'ordem' => 2,
        ]);


        // 4. CRIAR TRILHAS DE APRENDIZAGEM E ASSOCIAR CURSOS
        $trilhaOnboarding = Trilha::create([
            'nome' => 'Trilha de Onboarding: Novos Desenvolvedores',
            'descricao' => 'Essa trilha consolida os conhecimentos essenciais de ferramentas, processes de desenvolvimento e cultura da TechCorp para acelerar sua integração.',
            'imagem' => 'trilhas/onboarding.png',
            'status' => 'ativo',
        ]);

        // Associar cursos à trilha com a ordem
        $trilhaOnboarding->cursos()->attach($cursoGit->id_curso, ['ordem' => 1]);
        $trilhaOnboarding->cursos()->attach($cursoSegurança->id_curso, ['ordem' => 2]);


        $trilhaCultura = Trilha::create([
            'nome' => 'Cultura e Produtividade TechCorp',
            'descricao' => 'Dicas fundamentais de comportamento, segurança de dados e alta performance no ambiente de trabalho corporativo.',
            'imagem' => 'trilhas/cultura.png',
            'status' => 'ativo',
        ]);

        $trilhaCultura->cursos()->attach($cursoSegurança->id_curso, ['ordem' => 1]);
        $trilhaCultura->cursos()->attach($cursoSoftSkills->id_curso, ['ordem' => 2]);


        // 5. SIMULAR PROGRESSO DE TESTE PARA OS COLABORADORES (Atualizado para o novo formato)
        
        // Gisseli Rocha (colaborador1) concluiu TODAS as aulas do curso de Git
        $colaborador1->aulasConcluidas()->attach($aulaGit1->id_aula, ['concluido_em' => Carbon::now()->subDays(9)]);
        $colaborador1->aulasConcluidas()->attach($aulaGit2->id_aula, ['concluido_em' => Carbon::now()->subDays(8)]);
        $colaborador1->aulasConcluidas()->attach($aulaGit3->id_aula, ['concluido_em' => Carbon::now()->subDays(7)]);

        // Gisseli Rocha (colaborador1) começou o curso de Segurança (concluiu 1 de 2 aulas = 50%)
        $colaborador1->aulasConcluidas()->attach($aulaSeg1->id_aula, ['concluido_em' => Carbon::now()->subDays(2)]);

        // Yasmin Fabiano (colaborador2) começou o curso de Git (concluiu 1 de 3 aulas = ~33%)
        $colaborador2->aulasConcluidas()->attach($aulaGit1->id_aula, ['concluido_em' => Carbon::now()->subHours(4)]);
    }
}