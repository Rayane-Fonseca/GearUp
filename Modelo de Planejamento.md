# GearUp: Planejamento Geral & Modelagem de Arquitetura

Este documento serve como a especificação técnica oficial e o plano de desenvolvimento passo a passo para o desenvolvimento do **GearUp**, uma plataforma **SaaS B2B de Treinamento Corporativo (Corporate LMS — Learning Management System)**. O objetivo do projeto é aplicar conceitos avançados de desenvolvimento web moderno e infraestrutura em contêineres, garantindo práticas rigorosas de segurança, automação, resiliência e arquitetura moderna.

> **Nota de origem:** as seções 1 e 4 foram preenchidas a partir da inspeção das telas reais do produto (vídeo de demonstração). As seções 2 e 3 (stack tecnológica e arquitetura de segurança) contêm **sugestões técnicas a validar com o time**, pois não foram observadas diretamente no material de origem — estão sinalizadas como tal.

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

> Modelagem detalhada por entidade, com diagrama ER completo, disponível no documento `levantamento_modelagem_gearup.md` gerado anteriormente. Resumo macro abaixo, no formato do template:

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
