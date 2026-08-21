# GearUp — Plataforma de Treinamento Corporativo (LMS)

Sistema de treinamento corporativo (LMS — *Learning Management System*) construído em **Laravel 12 + Filament 3 + PostgreSQL + Tailwind + Alpine.js**.

A plataforma tem duas áreas separadas por perfil de usuário:

- **Colaborador (aluno)**: assiste aos cursos da empresa, acompanha seu progresso, conclui trilhas de aprendizagem e recebe certificados.
- **Administrador**: cadastra cursos, módulos e aulas, gerencia colaboradores e acompanha as métricas gerais de treinamento da empresa.

---

## Sumário

- [Stack utilizada](#stack-utilizada)
- [Como rodar o projeto](#como-rodar-o-projeto)
- [Contas de teste](#contas-de-teste)
- [Funcionalidades — Área do Colaborador](#funcionalidades--área-do-colaborador)
- [Funcionalidades — Área do Administrador](#funcionalidades--área-do-administrador)
- [Como funciona o progresso do aluno](#como-funciona-o-progresso-do-aluno)
- [Cursos obrigatórios por área](#cursos-obrigatórios-por-área)
- [Certificados](#certificados)
- [Comandos artisan disponíveis](#comandos-artisan-disponíveis)
- [Estrutura do banco de dados](#estrutura-do-banco-de-dados)
- [Estrutura de pastas relevante](#estrutura-de-pastas-relevante)

---

## Stack utilizada

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Banco de dados | PostgreSQL (testado com [Neon](https://neon.tech)) |
| Painel administrativo avançado | Filament 3 |
| Front-end | Blade + Tailwind CSS + Alpine.js |
| Geração de PDF | barryvdh/laravel-dompdf |
| Autenticação | Laravel Breeze (sessão/cookie, sem SPA) |
| Build de assets | Vite |

---

## Como rodar o projeto

```bash
composer install
npm install
cp .env.example .env   # se ainda não existir um .env
php artisan key:generate
```

Configure a conexão com o PostgreSQL no `.env` (por padrão já vem apontando para as variáveis de um banco Neon — ajuste `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` para o seu banco):

```
DB_CONNECTION=pgsql
DB_HOST=seu-host
DB_PORT=5432
DB_DATABASE=gearup
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha
DB_SSLMODE=require
```

Rode as migrations (e opcionalmente o seeder de dados de demonstração):

```bash
php artisan migrate           # cria as tabelas
php artisan db:seed           # opcional: popula com dados de exemplo (TechCorpSeeder)
npm run build                 # compila os assets (ou `npm run dev` em desenvolvimento)
php artisan serve
```

Acesse `http://localhost:8000`.

> Se for a primeira vez rodando o projeto e quiser recriar o banco do zero com dados de exemplo: `php artisan migrate:fresh --seed`.

---

## Contas de teste

Se você rodar o seeder (`TechCorpSeeder`), as seguintes contas ficam disponíveis (senha para todas: `senha123`):

| Perfil | E-mail | Observação |
|---|---|---|
| Colaborador (demo) | `lucas@techcorp.com.br` | Usuário principal de demonstração, com progresso, trilhas e certificados |
| Administrador | `admin@techcorp.com.br` | Acessa `/admin` (painel personalizado) e `/gestao` (Filament) |
| Colaboradores extras | `ana@`, `rafael@`, `fernanda@`, `carlos@`, `marcos@`, `juliana@`, `paulo@techcorp.com.br` | Aparecem em Gerenciar Colaboradores, cada um com uma área de atuação diferente |

A tela de login tem dois botões — **"Entrar como Colaborador"** e **"Entrar como Administrador"** — e valida se a conta realmente tem aquele perfil antes de deixar entrar.

---

## Funcionalidades — Área do Colaborador

Tudo em `/aluno/*`, protegido pelo middleware `perfil:colaborador` (um colaborador não consegue acessar as rotas de administrador, e vice-versa).

### Início (`/aluno/inicio`)
Painel com saudação personalizada, progresso geral do aluno, cursos em andamento, cursos concluídos, horas totais de treinamento, certificados emitidos, atividades pendentes, um curso recomendado e um **alerta de cursos obrigatórios pendentes** para a área do aluno (ver seção [Cursos obrigatórios por área](#cursos-obrigatórios-por-área)).

### Meus Cursos (`/aluno/cursos`)
Catálogo de todos os cursos, com filtro por categoria. Cada card mostra:
- Categoria e status (Não iniciado / Em andamento / Concluído);
- Barra de progresso real do aluno naquele curso;
- Selo **"Obrigatório"** quando o curso é obrigatório para a área do aluno — esses cursos aparecem destacados (borda vermelha) e são ordenados no topo da lista enquanto não forem concluídos.

### Detalhe do curso (`/aluno/cursos/{curso}`)
Lista os módulos e aulas do curso, com indicação de quais aulas o aluno já concluiu, e também exibe o selo de curso obrigatório quando aplicável.

### Player da aula (`/aluno/cursos/{curso}/aulas/{aula}`)
- Toca vídeos tanto em formato direto (HTML5 `<video>`) quanto embeds do YouTube (via YouTube IFrame API).
- Mostra uma **barra de progresso da aula** (não existe mais um botão manual de "marcar como concluída" — o progresso é detectado automaticamente pelo quanto do vídeo o aluno já assistiu).
- **Retoma o vídeo de onde o aluno parou**: o tempo assistido é salvo periodicamente, e ao reabrir a aula o player já inicia no ponto salvo.
- Navegação para aula anterior/próxima e sidebar com todos os módulos/aulas do curso, mostrando o progresso de cada uma.
- Quando o curso é concluído durante a aula, um banner avisa que o certificado já está disponível.

### Trilhas (`/aluno/trilhas` e `/aluno/trilhas/{id}`)
Uma trilha agrupa vários cursos (obrigatórios e opcionais). A tela mostra o progresso médio da trilha e a lista de cursos que a compõem.

### Certificados (`/aluno/certificados`)
Lista os certificados já emitidos (com opção de pré-visualizar e baixar o PDF) e mostra qual é o próximo certificado mais próximo de ser conquistado.

### Perfil e notificações
`/aluno/perfil` para editar dados da conta e `/aluno/notificacoes` para ver avisos do sistema (ex: prazos de atividades).

---

## Funcionalidades — Área do Administrador

Existem **dois painéis administrativos**, ambos restritos ao perfil `administrador`:

### Painel personalizado (`/admin`)
Feito sob medida com o mesmo visual da plataforma (sidebar escura), pensado para o dia a dia:
- **Dashboard**: cards de resumo, gráfico de atividade mensal (barras) e distribuição de cursos por categoria (rosca), via Chart.js.
- **Gerenciar Cursos**: CRUD completo de cursos, módulos e aulas (criar, editar, excluir), tudo em modais.
- **Gerenciar Colaboradores**: busca e filtro por área de atuação, cadastro de novos colaboradores.

### Painel Filament (`/gestao`)
Painel administrativo mais avançado, para gestão detalhada de:
- Cursos, Módulos e Aulas (Resources completos com formulários e tabelas);
- Colaboradores;
- Certificados emitidos;
- Notificações;
- Widgets de estatísticas gerais e gráficos.

---

## Como funciona o progresso do aluno

O progresso é calculado em duas camadas:

1. **Progresso por aula** (tabela `aula_progressos`): a cada alguns segundos de reprodução (e sempre que o vídeo é pausado, termina, ou a página é fechada), o player salva quanto tempo o aluno já assistiu daquela aula específica. Uma aula é considerada **concluída** ao atingir 90% do vídeo assistido — esse limite evita que o aluno fique "preso" por não ter assistido os últimos 1-2 segundos.

2. **Progresso do curso** (tabela `progressos`, a mesma usada em "Meus Cursos"): recalculado automaticamente toda vez que o progresso de qualquer aula do curso é salvo. É a **média do percentual assistido em todas as aulas do curso** — ou seja, o aluno já vê o progresso subir aos poucos, mesmo tendo assistido só metade de uma aula, sem precisar concluir nada para o número começar a mudar. O curso só é marcado como 100% concluído quando **todas** as aulas foram de fato concluídas (≥90% cada).

Isso significa que a tela "Meus Cursos", a tela "Início" e o player da aula estão sempre olhando para o mesmo dado real, sem duplicidade.

---

## Cursos obrigatórios por área

Um curso é considerado **obrigatório** para um colaborador quando a **categoria do curso** (`cursos.categoria`) é igual à **área de atuação do colaborador** (`usuarios.area`) — por exemplo, um colaborador da área "DevOps" tem como obrigatórios todos os cursos cadastrados com categoria "DevOps".

Isso é resolvido automaticamente (sem precisar de cadastro manual) pelo método `Curso::ehObrigatorioPara($usuario)`, e aparece em três lugares:
- Banner de alerta na tela **Início**, listando os cursos obrigatórios ainda não concluídos;
- Selo vermelho **"Obrigatório"** nos cards da tela **Meus Cursos** (que também sobem para o topo da listagem);
- Selo **"Obrigatório para sua área"** na tela de **detalhe do curso**.

O alerta desaparece automaticamente assim que o colaborador conclui o curso.

> Se no futuro a obrigatoriedade precisar ser configurada manualmente pelo administrador (em vez de casar automaticamente pelo nome da categoria/área), isso pode ser trocado por uma tabela de relacionamento dedicada — hoje a lógica é propositalmente simples porque os dados de área e categoria já seguem o mesmo vocabulário no sistema.

---

## Certificados

O certificado de um curso é **emitido automaticamente**, sem qualquer ação manual do aluno, no momento em que a última aula pendente do curso é concluída. O sistema:

1. Detecta que todas as aulas do curso foram concluídas;
2. Gera o certificado (`GerarPdfCertificadoJob`), com um código de autenticação único;
3. Deixa disponível na hora em `/aluno/certificados`, com link para pré-visualizar e baixar o PDF.

Existe também uma rota de solicitação manual (`POST /aluno/certificados/{idCurso}/solicitar`) como *fallback*, útil caso um certificado precise ser reemitido ou gerado fora do fluxo automático — ela valida se o curso realmente está 100% concluído antes de gerar.

---

## Comandos artisan disponíveis

| Comando | O que faz |
|---|---|
| `php artisan progresso:zerar-sem-aula` | Zera o progresso (tabela `progressos`) de cursos que não têm nenhuma aula cadastrada (útil para limpar dados inconsistentes). Use `--dry-run` para ver o que seria alterado sem aplicar nada. |
| `php artisan lms:recalcular-trilhas` | Recalcula em lote o progresso dos colaboradores nas trilhas de aprendizagem. |
| `php artisan lms:varrer-atividades` | Varre o banco em busca de atividades/cursos com prazo vencendo em 3 dias e notifica os alunos. |

---

## Estrutura do banco de dados

Tabelas principais e como se relacionam:

```
usuarios (id_usuario, nome, email, perfil, cargo, area, status, ...)
cursos (id_curso, titulo, categoria, instrutor, carga_horaria, status, ...)
modulos (id_modulo, id_curso, titulo, ordem, ...)
aulas (id, id_modulo, titulo, url_video, duracao_minutos, ordem, ...)

aula_progressos (usuario_id, aula_id, curso_id, tempo_assistido, duracao_total, porcentagem, concluido, concluido_em)
    -> progresso individual do aluno em CADA aula (tempo de vídeo assistido)

progressos (usuario_id, curso_id, porcentagem, concluido, concluido_em)
    -> progresso CONSOLIDADO do aluno em cada curso, recalculado a partir de aula_progressos
    -> é o que a tela "Meus Cursos" e o dashboard "Início" leem

certificados (id_usuario, id_curso, codigo_autenticacao, emitido_em, ...)
    -> emitido automaticamente quando progressos.concluido vira true

trilhas (id, titulo, categoria, ativo, ...)
trilha_curso (id_trilha, id_curso, obrigatorio)
    -> pivô N:N entre trilhas e cursos

notificacoes (usuario_id, titulo, mensagem, lida, ...)
logs (...)
```

---

## Estrutura de pastas relevante

```
app/
  Console/Commands/          Comandos artisan (zerar progresso, recalcular trilhas, avisos)
  Filament/                  Resources, Pages e Widgets do painel /gestao
  Http/Controllers/          Controllers da área pública/aluno/admin
  Http/Controllers/Api/      ProgressoController (salva progresso da aula via fetch/AJAX)
  Http/Middleware/           PerfilMiddleware (bloqueia acesso cruzado aluno/admin)
  Jobs/                      GerarPdfCertificadoJob
  Models/                    Eloquent models (Usuario, Curso, Modulo, Aula, AulaProgresso, Progresso, Certificado, Trilha, ...)

database/
  migrations/                Schema do banco
  seeders/                   TechCorpSeeder (dados de demonstração)

resources/views/
  aluno/                     Views da área do colaborador (início, cursos, aulas/player, trilhas, certificados, perfil)
  admin/                     Views do painel administrativo personalizado
  components/aluno-layout.blade.php   Layout base (header, sidebar, notificações) das telas do colaborador
  pdf/certificado.blade.php  Template do PDF do certificado

routes/
  web.php                    Todas as rotas web (aluno, admin, progresso)
  console.php                Comandos artisan agendados
```