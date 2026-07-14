# GearUp: Planejamento Geral & Modelagem de Arquitetura

Este documento serve como a especificação técnica oficial e o plano de desenvolvimento passo a passo para o desenvolvimento do **GearUp**, uma plataforma **SaaS B2B de Treinamento Corporativo (Corporate LMS — Learning Management System)**. O objetivo do projeto é aplicar conceitos avançados de desenvolvimento web moderno e infraestrutura em contêineres, garantindo práticas rigorosas de segurança, automação, resiliência e arquitetura moderna.

> **Nota de origem:** ainda precisa de alterações.

---

## 1. Escopo Funcional do Sistema

O sistema é dividido em **4** grandes pilares principais, separando claramente o funil de aquisição, as regras de acesso e o núcleo (core business) da aplicação.

### 1.1. Funil de Aquisição & Monetização
* **Apresentação e Vendas:** Landing page institucional apresentando o GearUp para empresas-cliente (proposta de valor, funcionalidades de trilhas/certificação, planos de precificação). *(não observado nas telas capturadas — presumido pelo modelo B2B SaaS)*.
* **Modelos de Cobrança:**
    * *Assinatura (SaaS):* Planos recorrentes por empresa-cliente (tenant), provavelmente com cobrança por número de colaboradores ativos (per-seat). Inclui regras de *Grace Period* (tolerância para falhas de pagamento) e *Grandfathering* (manutenção de preço antigo para clientes legados).
    * *Venda Avulsa (One-time):* Não identificado nas telas — a plataforma opera no modelo de acesso corporativo contínuo (todos os cursos liberados por assinatura da empresa).
* **Integração Financeira (Webhooks):** Integração com gateway de pagamento **[a definir — ex: Stripe]**. Fluxo de liberação de acesso deve ser assíncrono, baseado em eventos de webhook para ativação, renovação e cancelamento automáticos da assinatura da empresa.

### 1.2. Gestão de Acessos e Onboarding
* **Modelo de Entrada:** Acesso via convite/cadastro corporativo — o colaborador é adicionado pelo Administrador da empresa (visto na tela "Gerenciar Colaboradores"), e não via self-signup público.
* **Gatilhos de Bloqueio (Paywall):** Ao atingir limites do plano contratado (ex: número de colaboradores cadastrados), o sistema deve bloquear novos cadastros e direcionar o Administrador para upgrade de plano.
* **Roles & Permissions (RBAC):** Dois perfis confirmados nas telas:
    * **Colaborador** — acessa Início, Cursos, Trilhas, Certificados e Perfil (consumo de conteúdo e progresso próprio).
    * **Administrador** — acessa Dashboard, Cursos (CRUD) e Colaboradores (gestão da equipe), além de tudo que o Colaborador vê.

### 1.3. Core Business (O Núcleo da Aplicação)
* **Catálogo de Cursos:** Listagem de cursos por área/categoria (DevOps, Cloud Computing, Segurança da Informação, Desenvolvimento de Software, Infraestrutura, Banco de Dados, Suporte Técnico), com carga horária, instrutor e módulos.
* **Trilhas de Aprendizagem:** Agrupamento de cursos obrigatórios/opcionais por área de atuação, com cálculo de progresso agregado da trilha.
* **Acompanhamento de Progresso e Matrícula:** Tela "Início" com progresso geral do colaborador, cursos em andamento, atividades pendentes (provas/prazos) e recomendações.
* **Emissão de Certificados:** Geração de certificado (PDF) por curso concluído, com visualização e download.
* **Dashboard Administrativo:** KPIs agregados (colaboradores ativos, cursos ativos, taxa de conclusão, treinamentos pendentes), gráficos de atividade mensal e distribuição de status, ranking de cursos mais acessados e lista de colaboradores com pendências.
* **Processamento de Arquivos/Mídia:** Upload e armazenamento de conteúdo de curso (vídeos, apostilas) e de certificados gerados em PDF — regras de sanitização e storage a definir.

### 1.4. Comunicação e Notificações
* **Avisos da empresa:** Mural de comunicados visto na tela "Início" (ex: "Nova trilha DevOps disponível", "Prazo de certificações Q3").
* **Notificações In-App:** Ícone de sino com contador (visto no topo de todas as telas) — indica notificações não lidas.
* **E-mails transacionais:** Boas-vindas ao colaborador, lembrete de prazo de atividade pendente, aviso de certificado emitido. *(fluxo não observado diretamente, inferido do domínio)*.

---

## 2. Stack Tecnológica Sugerida

*(Nenhuma stack foi confirmada nas telas analisadas — as opções abaixo são sugestões compatíveis com o porte do projeto, a validar com o time técnico.)*

* **Linguagem & Interpretador:** TypeScript (Node.js 20+)
* **Framework Principal (Backend):** NestJS
* **Interface Frontend:** React + TailwindCSS (aderente ao visual observado: sidebar fixa escura, cards brancos, badges de status coloridos)
* **Banco de Dados Relacional:** PostgreSQL 16
* **Cache e Filas:** Redis 7.2
* **Armazenamento (Storage):** AWS S3 ou MinIO (para PDFs de certificado e mídias de curso)
* **Comunicação em Tempo Real (Opcional):** WebSockets (para o contador de notificações em tempo real)
* **Ambiente de Desenvolvimento & Infraestrutura:** Docker e Docker Compose com limites estritos de recursos (CPU/RAM) para simular restrições de produção

---

## 3. Arquitetura de Segurança Comercial (Anti-Fraude e Resiliência)

*(Seção padrão de boas práticas — a validar quanto à aplicabilidade ao modelo de negócio real do GearUp.)*

### 3.1. Proteção Transacional e Idempotência
1. **Verificação de Preços Fechada (Server-Side Pricing):** valores de planos por assinatura de empresa nunca devem ser confiados ao payload do Frontend; o backend busca o preço vigente direto do banco antes de gerar a sessão de checkout.
2. **Idempotência de Webhooks:** todo evento do gateway de pagamento deve ser registrado por ID único (tabela `webhook_logs`); duplicidades (*Replay Attacks*) são ignoradas com retorno HTTP 200.

### 3.2. Proteção de Infraestrutura e Uploads
1. **Storage Privado e Presigned URLs:** upload de vídeos/apostilas de curso e emissão de certificados em PDF devem usar URLs assinadas, evitando sobrecarga de I/O no backend.
2. **Sanitização Rigorosa:** conteúdo rico de curso (descrições, materiais) deve passar por sanitizadores no servidor para evitar XSS.

### 3.3. Tolerância a Falhas (Graceful Fallback) e Concorrência
* **Filas Assíncronas:** geração de PDF de certificado, cálculo de progresso agregado de trilha e disparo de e-mails/avisos em massa devem rodar em *workers* de background.
* **Travas de Concorrência (Locks):** atualização de `percentual_concluido` em MATRICULA deve usar *pessimistic locking* para evitar condições de corrida em cliques simultâneos de "Continuar".

---

## 4. Modelagem e Arquitetura de Dados (Dicionário Macro)

> # Levantamento de Modelagem de Dados — Plataforma GearUp (LMS Corporativo)

## 1. Contexto

O GearUp é uma plataforma de treinamento corporativo (LMS) com dois perfis de acesso — **Colaborador** e **Administrador** — usada para gerenciar cursos, trilhas de aprendizagem, matrículas, progresso e certificação dos funcionários da TechCorp. Este levantamento foi feito a partir da inspeção visual das telas do sistema (Início, Cursos, Trilhas, Certificados, Dashboard Administrativo, Gerenciar Cursos, Gerenciar Colaboradores).

---

## 2. Entidades identificadas

### 2.1 AREA (Área de atuação / categoria)
Categoriza usuários, cursos e trilhas (ex: DevOps, Cloud Computing, Segurança da Informação, Desenvolvimento de Software, Infraestrutura, Banco de Dados, Suporte Técnico).

| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| nome | string | Único |

### 2.2 USUARIO (Colaborador / Instrutor / Administrador)
Um único cadastro de pessoa, diferenciado por `perfil_acesso` e, opcionalmente, atuando como instrutor de cursos.

| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| nome | string | |
| email | string | Único, formato `nome@techcorp.com.br` |
| cargo | string | Ex: "Desenvolvedor Sênior", "DevOps Engineer" |
| area_id | FK → AREA | Área de atuação do usuário |
| perfil_acesso | enum | `colaborador` \| `administrador` |
| avatar_iniciais | string | Derivado do nome (ex: "LA") |

### 2.3 CURSO
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| nome | string | |
| area_id | FK → AREA | Categoria do curso |
| instrutor_id | FK → USUARIO | Responsável pelo conteúdo |
| carga_horaria | int (horas) | Ex: 18h, 32h |
| numero_modulos | int | Derivável de MODULO, mas exibido como atributo direto no admin |
| status | enum | `não iniciado` \| `em andamento` \| `concluído` (status agregado, calculado ou geral do curso) |

### 2.4 MODULO
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| curso_id | FK → CURSO | |
| titulo | string | |
| ordem | int | Sequência dentro do curso |

### 2.5 TRILHA (Learning Path)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| nome | string | Ex: "Banco de Dados" |
| area_id | FK → AREA | |
| percentual_conclusao | decimal | Calculado a partir das matrículas dos cursos vinculados |

### 2.6 TRILHA_CURSO (associativa N:N)
Vincula cursos a trilhas, definindo se são obrigatórios ou opcionais.

| Atributo | Tipo | Observação |
|---|---|---|
| trilha_id | FK → TRILHA | PK composta |
| curso_id | FK → CURSO | PK composta |
| tipo | enum | `obrigatório` \| `opcional` |

### 2.7 MATRICULA (Progresso do usuário no curso)
Entidade associativa entre USUARIO e CURSO que registra o progresso individual.

| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| percentual_concluido | int (0-100) | |
| status | enum | `não iniciado` \| `em andamento` \| `concluído` |
| data_inicio | date | |
| data_conclusao | date, nullable | Preenchida quando status = concluído |

> Regra: `UNIQUE(usuario_id, curso_id)` — um usuário tem no máximo uma matrícula ativa por curso.

### 2.8 CERTIFICADO
Emitido quando uma MATRICULA atinge 100% / status concluído.

| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| data_emissao | date | |
| carga_horaria | int | Copiado do curso no momento da emissão (histórico) |
| url_pdf | string | Link para download |

### 2.9 ATIVIDADE_PENDENTE
Prazos e tarefas associadas a um curso em andamento (provas, módulos a assistir).

| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| tipo | enum | `prova` \| `módulo` \| `outro` |
| descricao | string | Ex: "Prova final — Docker e Kubernetes" |
| prazo | date | |
| status | enum | `pendente` \| `concluída` |

### 2.10 AVISO (Comunicado da empresa)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, int | |
| titulo | string | |
| descricao | string | |
| data_publicacao | date | |
| tipo | enum | Ex: `nova trilha`, `prazo de certificação` |

---

## 3. Relacionamentos e cardinalidade

| Relacionamento | Cardinalidade | Descrição |
|---|---|---|
| AREA → USUARIO | 1:N | Uma área agrupa vários colaboradores |
| AREA → CURSO | 1:N | Uma área agrupa vários cursos |
| AREA → TRILHA | 1:N | Uma área agrupa uma trilha (ou mais) |
| USUARIO (instrutor) → CURSO | 1:N | Um instrutor leciona vários cursos |
| CURSO → MODULO | 1:N | Um curso possui vários módulos |
| TRILHA ↔ CURSO | N:N (via TRILHA_CURSO) | Um curso pode pertencer a mais de uma trilha; uma trilha tem vários cursos |
| USUARIO ↔ CURSO | N:N (via MATRICULA) | Progresso individual por curso |
| USUARIO → CERTIFICADO | 1:N | Um usuário acumula vários certificados |
| CURSO → CERTIFICADO | 1:N | Um curso gera certificado para cada concluinte |
| USUARIO → ATIVIDADE_PENDENTE | 1:N | |
| CURSO → ATIVIDADE_PENDENTE | 1:N | |

---

## 4. Regras de negócio observadas nas telas

1. Um usuário só pode ter `perfil_acesso = administrador` para acessar Dashboard, Gerenciar Cursos e Gerenciar Colaboradores.
2. O **progresso geral** do usuário (ex: 47%) é uma média calculada sobre todas as suas MATRICULAS.
3. Uma **trilha** só é considerada concluída (100%) quando todos os cursos marcados como `obrigatório` em TRILHA_CURSO estão com status `concluído` na MATRICULA do usuário.
4. **Certificado** é gerado automaticamente (ou emitido) somente quando `MATRICULA.percentual_concluido = 100`.
5. O card "Próximo certificado" indica que o sistema calcula, por curso não concluído, quanto falta para desbloquear — sugere uma trava de negócio "1 certificado por curso concluído".
6. **Atividades pendentes** têm prazo e ficam associadas ao curso e ao usuário — possivelmente geradas a partir dos módulos/avaliações do curso.
7. O **Dashboard Administrativo** consome dados agregados de MATRICULA (para os gráficos de "Atividade mensal" e "Distribuição") e não é uma entidade própria — é uma camada de relatório/agregação.
8. **Gerenciar Cursos** permite operações CRUD completas sobre CURSO (novo, editar, excluir), reforçando que é a entidade mestre administrada centralmente.

---

## 5. Observações e pontos em aberto (não visíveis nas telas)

Como a análise foi feita apenas a partir dos frames visuais (sem áudio/documentação), os seguintes pontos precisam de validação com o time ou com a narração do vídeo:
- Se **instrutor** é sempre um USUARIO interno (com login) ou pode ser um cadastro simples de nome (sem conta de acesso).
- Se existe um **histórico de tentativas de prova** (notas, tentativas) ou apenas o status pendente/concluída.
- Se as **notificações** (sino no topo, "3") são uma entidade própria (NOTIFICACAO) separada de AVISO, com campo `lida`.
- Regras de expiração/revalidação de certificado (não observado nas telas).
- Se módulos possuem conteúdo tipado (vídeo, texto, quiz) — não visível nos frames capturados.

---

## 6. Diagrama Entidade-Relacionamento

O diagrama ER foi gerado na conversa (visualização interativa). Resumo estrutural:

```
AREA 1─N USUARIO
AREA 1─N CURSO
AREA 1─N TRILHA
USUARIO(instrutor) 1─N CURSO
CURSO 1─N MODULO
TRILHA N─N CURSO (via TRILHA_CURSO)
USUARIO N─N CURSO (via MATRICULA)
USUARIO 1─N CERTIFICADO N─1 CURSO
USUARIO 1─N ATIVIDADE_PENDENTE N─1 CURSO
```


#### Entidades Base e Acesso
* **`users`:** `id`, `name`, `email`, `password`, `cargo`, `area_id` (FK), `role` (colaborador/administrador), `status`, `last_login_at`, `timestamps`.
* **`tenants` (empresas-cliente, multi-tenant):** `id`, `name`, `slug`, `owner_id`, `timestamps`. *(inferido pelo modelo B2B — TechCorp seria um exemplo de tenant)*.
* **`areas`:** `id`, `nome` (DevOps, Cloud Computing, Banco de Dados etc.).

#### Entidades Financeiras
* **`plans`:** `id`, `name`, `gateway_plan_id`, `price`, `interval_days`, `is_active`.
* **`subscriptions`:** `id`, `tenant_id`, `plan_id`, `status` (active, past_due, canceled), `expires_at`.
* **`orders` / `transactions`:** `id`, `tenant_id`, `amount`, `status`, `gateway_transaction_id`.
* **`webhook_logs`:** `id`, `event_id` (Unique/Index), `processed_at`.

#### Entidades do Core Business
* **`courses` (cursos):** `id`, `name`, `area_id` (FK), `instructor_id` (FK → users), `workload_hours`, `status`, `timestamps`.
* **`modules` (módulos):** `id`, `course_id` (FK), `title`, `order`.
* **`learning_paths` (trilhas):** `id`, `name`, `area_id` (FK).
* **`learning_path_courses` (associativa):** `learning_path_id` (FK), `course_id` (FK), `type` (obrigatório/opcional).
* **`enrollments` (matrículas/progresso):** `id`, `user_id` (FK), `course_id` (FK), `percentage`, `status`, `started_at`, `completed_at`.
* **`certificates`:** `id`, `user_id` (FK), `course_id` (FK), `issued_at`, `workload_hours`, `pdf_url`.
* **`pending_activities` (atividades pendentes):** `id`, `user_id` (FK), `course_id` (FK), `type`, `description`, `due_date`, `status`.
* **`announcements` (avisos):** `id`, `title`, `description`, `published_at`, `type`.

---

## 5. Guia de Desenvolvimento Interativo (Checklist de Etapas)

### [ ] ETAPA 1: Setup do Ecossistema e Infraestrutura
- [ ] Inicializar o repositório e configurar o framework base (NestJS + React).
- [ ] Configurar o arquivo `docker-compose.yml` (App, PostgreSQL, Redis, Storage).
- [ ] Configurar variáveis de ambiente (`.env`) e padronização de código (Linters/Formatters).

### [ ] ETAPA 2: Camada de Dados, Autenticação e Permissões
- [ ] Criar Migrations estruturadas (UUIDs para chaves primárias expostas) para `users`, `tenants`, `areas`, `courses`, `modules`, `learning_paths`, `enrollments`, `certificates`.
- [ ] Implementar os Models, relacionamentos (1:N e N:N via tabelas associativas) e lógicas de `Casts`.
- [ ] Configurar Autenticação e Autorização (Middlewares de Roles: colaborador / administrador).
- [ ] Construir Database Seeders para popular colaboradores, cursos e trilhas de teste (ex: cenário TechCorp usado na demo).

### [ ] ETAPA 3: Núcleo de Segurança e Proteções
- [ ] Configurar cabeçalhos de segurança (Security Headers, CORS, CSP).
- [ ] Implementar Rate Limiting para APIs públicas e rota de login.
- [ ] Configurar Uploads diretos (Presigned URLs) para materiais de curso e sanitização de inputs ricos.

### [ ] ETAPA 4: Integração Financeira e Assinaturas
- [ ] Desenvolver integração com a API do gateway de pagamento escolhido.
- [ ] Criar Controller e Jobs para recebimento e processamento de Webhooks.
- [ ] Validar sistema de Idempotência (bloqueio de webhooks duplicados).
- [ ] Implementar Middleware de bloqueio de acesso (Paywall por limite de colaboradores/inadimplência do tenant).

### [ ] ETAPA 5: Desenvolvimento do Core Business
- [ ] Desenvolver Catálogo de Cursos com filtros por área.
- [ ] Desenvolver Trilhas de Aprendizagem (obrigatórios x opcionais, cálculo de % agregado).
- [ ] Desenvolver Matrícula/Progresso (tela "Início" com cursos em andamento e % de conclusão).
- [ ] Desenvolver emissão e download de Certificados em PDF.
- [ ] Desenvolver Dashboard Administrativo (KPIs, gráficos de atividade mensal e distribuição).
- [ ] Desenvolver CRUD de Gerenciar Cursos e Gerenciar Colaboradores (visão Admin).
- [ ] Implementar validações rigorosas de regras de negócio (ex: certificado só emitido a 100% de conclusão).
- [ ] Criar testes unitários e de integração (Feature Tests) para as lógicas críticas de progresso e certificação.

### [ ] ETAPA 6: Filas, Notificações e Tarefas Agendadas (CRON)
- [ ] Configurar sistema de filas (Redis) para geração assíncrona de PDF de certificado.
- [ ] Implementar rotinas de disparo de avisos/e-mails (novo curso, prazo de atividade pendente).
- [ ] Criar comandos agendados (ex: varredura de atividades pendentes vencendo, recalcular progresso de trilhas, limpeza de logs antigos).

### [ ] ETAPA 7: Interfaces Visuais e Frontend
- [ ] Desenvolver a Landing Page e vitrine de planos (venda B2B para novas empresas-cliente).
- [ ] Construir o painel Administrador (Dashboard, Cursos, Colaboradores) replicando o padrão visual observado (sidebar escura, cards brancos, badges de status).
- [ ] Construir a interface do Colaborador (Início, Cursos, Trilhas, Certificados, Perfil) garantindo responsividade.

### [ ] ETAPA 8: Monitoramento, Telemetria e Deploy
- [ ] Configurar tratamento global de exceções (ocultar Stack Traces em Produção).
- [ ] Integrar ferramentas de APM e rastreamento de erros (ex: Sentry).
- [ ] Configurar pipeline de CI/CD (Testes automatizados → Build → Deploy).
- [ ] Realizar testes de carga básicos e auditoria de segurança pré-lançamento.
