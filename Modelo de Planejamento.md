## 8. Guia de Desenvolvimento (Checklist de Etapas)

### [ ] ETAPA 1: Setup do Ambiente
- [X] Projeto Laravel 11 configurado com Filament 3 instalado.
- [X] Configuração do ambiente local (XAMPP) e `.env`.
- [X] Repositório Git/GitHub configurado.

### [ ] ETAPA 2: Camada de Dados, Autenticação e Permissões
- [X] Migrations para `users`, `areas`, `courses`, `modules`, `learning_paths`, `learning_path_courses`, `enrollments`, `certificates`, `pending_activities`, `announcements`.
- [X] Models com relacionamentos (1:N e N:N via tabelas associativas).
- [X] Autenticação via Sanctum + Roles (colaborador / administrador) via policies/middleware. <- middleware feito
- [X] Seeders para popular colaboradores, cursos e trilhas de teste (cenário TechCorp).

### [ ] ETAPA 3: Núcleo de Segurança
- [X] Validação de uploads (tipo/tamanho) e sanitização de inputs ricos.
- [X] Proteção de rotas de API via Sanctum.
- [X] Rate limiting em rotas sensíveis (login, uploads).

### [ ] ETAPA 4: Core Business
- [ ] Catálogo de Cursos com filtros por área.
- [ ] Trilhas de Aprendizagem (obrigatórios x opcionais, cálculo de % agregado).
- [ ] Matrícula/Progresso (tela "Início" com cursos em andamento e % de conclusão).
- [ ] Emissão e download de Certificados em PDF (dompdf).
- [ ] Dashboard Administrativo (Filament Widgets: KPIs, gráficos de atividade mensal e distribuição).
- [ ] Filament Resources de Gerenciar Cursos e Gerenciar Colaboradores (visão Admin).
- [ ] Validações de regras de negócio (ex: certificado só emitido a 100% de conclusão).
- [ ] Testes unitários e de integração (Feature Tests) para progresso e certificação.

### [ ] ETAPA 5: Filas e Notificações
- [ ] Configurar Laravel Queues para geração assíncrona de PDF de certificado.
- [ ] Rotinas de disparo de avisos/e-mails (novo curso, prazo de atividade pendente) via Notifications.
- [ ] Comandos agendados (Scheduler): varredura de atividades pendentes vencendo, recálculo de progresso de trilhas.

### [ ] ETAPA 6: Interfaces Visuais e Frontend
- [ ] Painel Administrador via Filament (Dashboard, Cursos, Colaboradores), seguindo o padrão visual (sidebar escura, cards brancos, badges de status).
- [ ] Interface do Colaborador (Início, Cursos, Trilhas, Certificados, Perfil) com Blade + CSS/JS, responsiva.

# GearUp: Planejamento Geral & Modelagem de Arquitetura

Este documento serve como a especificação técnica oficial e o plano de desenvolvimento passo a passo para o **GearUp**, uma plataforma de **Treinamento Corporativo (LMS — Learning Management System)** voltada ao setor automotivo. O escopo atual foca em um único cliente/empresa (cenário acadêmico TechCorp), com arquitetura pensada para evoluir depois para multi-tenant/SaaS.

---

## 1. Escopo Funcional do Sistema

O sistema é dividido em **3** pilares principais no escopo atual (o funil de monetização SaaS fica no roadmap futuro, seção 7).

### 1.1. Gestão de Acessos e Onboarding
* **Modelo de Entrada:** Acesso via convite/cadastro corporativo — o colaborador é adicionado pelo Administrador (tela "Gerenciar Colaboradores"), sem self-signup público.
* **Roles & Permissions (RBAC):** Dois perfis:
    * **Colaborador** — acessa Início, Cursos, Trilhas, Certificados e Perfil (consumo de conteúdo e progresso próprio).
    * **Administrador** — acessa Dashboard, Cursos (CRUD) e Colaboradores (gestão da equipe), além de tudo que o Colaborador vê. Gerenciado via **painel Filament**.

### 1.2. Core Business (O Núcleo da Aplicação)
* **Catálogo de Cursos:** Listagem por área/categoria (DevOps, Cloud Computing, Segurança da Informação, Desenvolvimento de Software, Infraestrutura, Banco de Dados, Suporte Técnico), com carga horária, instrutor e módulos.
* **Trilhas de Aprendizagem:** Agrupamento de cursos obrigatórios/opcionais por área, com cálculo de progresso agregado.
* **Acompanhamento de Progresso e Matrícula:** Tela "Início" com progresso geral do colaborador, cursos em andamento, atividades pendentes (provas/prazos) e recomendações.
* **Emissão de Certificados:** Geração de certificado em PDF por curso concluído, via `barryvdh/laravel-dompdf`, com visualização e download.
* **Dashboard Administrativo:** KPIs agregados (colaboradores ativos, cursos ativos, taxa de conclusão, treinamentos pendentes), gráficos de atividade mensal e distribuição de status, ranking de cursos mais acessados, lista de colaboradores com pendências.
* **Processamento de Arquivos/Mídia:** Upload e armazenamento de conteúdo de curso (vídeos, apostilas) e de certificados PDF gerados.

### 1.3. Comunicação e Notificações
* **Avisos da empresa:** Mural de comunicados na tela "Início" (ex: "Nova trilha DevOps disponível", "Prazo de certificações Q3").
* **Notificações In-App:** Ícone de sino com contador (topo de todas as telas) — indica notificações não lidas.
* **E-mails transacionais:** Boas-vindas ao colaborador, lembrete de prazo de atividade pendente, aviso de certificado emitido *(via Laravel Mail/Notifications)*.

---

## 2. Stack Tecnológica

* **Linguagem & Framework Principal (Backend):** PHP 8+ com **Laravel 11**
* **Painel Administrativo:** **Filament 3** (Resources para Cursos, Colaboradores, Trilhas etc.)
* **Autenticação/API:** **Laravel Sanctum**
* **Banco de Dados Relacional:** **MySQL**
* **Geração de PDF:** `barryvdh/laravel-dompdf` (certificados)
* **Frontend:** HTML, CSS, JavaScript (Blade + Filament, seguindo o padrão visual: sidebar fixa escura, cards brancos, badges de status coloridos)
* **Ambiente de Desenvolvimento:** XAMPP (Windows, local) — Docker fica como opção futura para padronizar ambiente/deploy
* **Controle de Versão:** Git/GitHub

---

## 3. Segurança e Boas Práticas (Escopo Atual)

### 3.1. Proteção de Infraestrutura e Uploads
1. **Storage e Sanitização:** upload de vídeos/apostilas de curso deve ser validado (tipo, tamanho) no backend; conteúdo rico de curso (descrições, materiais) deve ser sanitizado para evitar XSS.
2. **Autenticação de API:** rotas protegidas via Sanctum, com tokens por usuário.

### 3.2. Tolerância a Falhas e Concorrência
* **Filas Assíncronas (Laravel Queues):** geração de PDF de certificado, cálculo de progresso agregado de trilha e disparo de e-mails/avisos em massa devem rodar em jobs de fila, não de forma síncrona no request.
* **Travas de Concorrência:** atualização de `percentual_concluido` em MATRICULA deve evitar condições de corrida em cliques simultâneos de "Continuar" (ex: transação de banco + lock otimista/pessimista quando necessário).

---

## 4. Modelagem e Arquitetura de Dados (Dicionário Macro)

### 4.1. Contexto

O GearUp é uma plataforma de treinamento corporativo (LMS) com dois perfis de acesso — **Colaborador** e **Administrador** — usada para gerenciar cursos, trilhas de aprendizagem, matrículas, progresso e certificação dos funcionários (cenário TechCorp).

### 4.2. Entidades identificadas

#### AREA (Área de atuação / categoria)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| nome | string | Único |

#### USUARIO (Colaborador / Instrutor / Administrador)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| nome | string | |
| email | string | Único, formato `nome@techcorp.com.br` |
| cargo | string | Ex: "Desenvolvedor Sênior", "DevOps Engineer" |
| area_id | FK → AREA | Área de atuação do usuário |
| perfil_acesso | enum | `colaborador` \| `administrador` |
| avatar_iniciais | string | Derivado do nome (ex: "LA") |

#### CURSO
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| nome | string | |
| area_id | FK → AREA | Categoria do curso |
| instrutor_id | FK → USUARIO | Responsável pelo conteúdo |
| carga_horaria | int (horas) | Ex: 18h, 32h |
| numero_modulos | int | Derivável de MODULO, exibido como atributo no admin |
| status | enum | `não iniciado` \| `em andamento` \| `concluído` |

#### MODULO
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| curso_id | FK → CURSO | |
| titulo | string | |
| ordem | int | Sequência dentro do curso |

#### TRILHA (Learning Path)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| nome | string | Ex: "Banco de Dados" |
| area_id | FK → AREA | |
| percentual_conclusao | decimal | Calculado a partir das matrículas dos cursos vinculados |

#### TRILHA_CURSO (associativa N:N)
| Atributo | Tipo | Observação |
|---|---|---|
| trilha_id | FK → TRILHA | PK composta |
| curso_id | FK → CURSO | PK composta |
| tipo | enum | `obrigatório` \| `opcional` |

#### MATRICULA (Progresso do usuário no curso)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| percentual_concluido | int (0-100) | |
| status | enum | `não iniciado` \| `em andamento` \| `concluído` |
| data_inicio | date | |
| data_conclusao | date, nullable | Preenchida quando status = concluído |

> Regra: `UNIQUE(usuario_id, curso_id)` — um usuário tem no máximo uma matrícula ativa por curso.

#### CERTIFICADO
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| data_emissao | date | |
| carga_horaria | int | Copiado do curso no momento da emissão (histórico) |
| url_pdf | string | Caminho/link para download do PDF gerado via dompdf |

#### ATIVIDADE_PENDENTE
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| usuario_id | FK → USUARIO | |
| curso_id | FK → CURSO | |
| tipo | enum | `prova` \| `módulo` \| `outro` |
| descricao | string | Ex: "Prova final — Docker e Kubernetes" |
| prazo | date | |
| status | enum | `pendente` \| `concluída` |

#### AVISO (Comunicado da empresa)
| Atributo | Tipo | Observação |
|---|---|---|
| id | PK, bigint | |
| titulo | string | |
| descricao | string | |
| data_publicacao | date | |
| tipo | enum | Ex: `nova trilha`, `prazo de certificação` |

### 4.3. Relacionamentos e cardinalidade

| Relacionamento | Cardinalidade | Descrição |
|---|---|---|
| AREA → USUARIO | 1:N | Uma área agrupa vários colaboradores |
| AREA → CURSO | 1:N | Uma área agrupa vários cursos |
| AREA → TRILHA | 1:N | Uma área agrupa uma ou mais trilhas |
| USUARIO (instrutor) → CURSO | 1:N | Um instrutor leciona vários cursos |
| CURSO → MODULO | 1:N | Um curso possui vários módulos |
| TRILHA ↔ CURSO | N:N (via TRILHA_CURSO) | Um curso pode pertencer a mais de uma trilha; uma trilha tem vários cursos |
| USUARIO ↔ CURSO | N:N (via MATRICULA) | Progresso individual por curso |
| USUARIO → CERTIFICADO | 1:N | Um usuário acumula vários certificados |
| CURSO → CERTIFICADO | 1:N | Um curso gera certificado para cada concluinte |
| USUARIO → ATIVIDADE_PENDENTE | 1:N | |
| CURSO → ATIVIDADE_PENDENTE | 1:N | |

### 4.4. Tabelas Laravel (migrations)

* **`users`:** `id`, `name`, `email`, `password`, `cargo`, `area_id` (FK), `role` (colaborador/administrador), `status`, `last_login_at`, `timestamps`.
* **`areas`:** `id`, `nome` (DevOps, Cloud Computing, Banco de Dados etc.).
* **`courses` (cursos):** `id`, `name`, `area_id` (FK), `instructor_id` (FK → users), `workload_hours`, `status`, `timestamps`.
* **`modules` (módulos):** `id`, `course_id` (FK), `title`, `order`.
* **`learning_paths` (trilhas):** `id`, `name`, `area_id` (FK).
* **`learning_path_courses` (associativa):** `learning_path_id` (FK), `course_id` (FK), `type` (obrigatório/opcional).
* **`enrollments` (matrículas/progresso):** `id`, `user_id` (FK), `course_id` (FK), `percentage`, `status`, `started_at`, `completed_at`.
* **`certificates`:** `id`, `user_id` (FK), `course_id` (FK), `issued_at`, `workload_hours`, `pdf_url`.
* **`pending_activities` (atividades pendentes):** `id`, `user_id` (FK), `course_id` (FK), `type`, `description`, `due_date`, `status`.
* **`announcements` (avisos):** `id`, `title`, `description`, `published_at`, `type`.

---

## 5. Regras de negócio

1. Um usuário só pode ter `perfil_acesso = administrador` para acessar Dashboard, Gerenciar Cursos e Gerenciar Colaboradores (via middleware/policy + Filament).
2. O **progresso geral** do usuário (ex: 47%) é uma média calculada sobre todas as suas MATRICULAS.
3. Uma **trilha** só é considerada concluída (100%) quando todos os cursos marcados como `obrigatório` em TRILHA_CURSO estão com status `concluído` na MATRICULA do usuário.
4. **Certificado** é gerado automaticamente somente quando `MATRICULA.percentual_concluido = 100`.
5. O card "Próximo certificado" indica quanto falta, por curso não concluído, para desbloquear — trava de negócio de "1 certificado por curso concluído".
6. **Atividades pendentes** têm prazo e ficam associadas ao curso e ao usuário.
7. O **Dashboard Administrativo** consome dados agregados de MATRICULA (gráficos de "Atividade mensal" e "Distribuição") — não é entidade própria, é camada de relatório/agregação (Filament Widgets).
8. **Gerenciar Cursos** permite CRUD completo sobre CURSO via Filament Resource.

---

## 6. Pontos em aberto (a validar)

- Se **instrutor** é sempre um USUARIO interno (com login) ou pode ser um cadastro simples de nome (sem conta de acesso).
- Se existe **histórico de tentativas de prova** (notas, tentativas) ou apenas status pendente/concluída.
- Se as **notificações** (sino no topo) são entidade própria (NOTIFICACAO) separada de AVISO, com campo `lida`.
- Regras de expiração/revalidação de certificado.
- Se módulos possuem conteúdo tipado (vídeo, texto, quiz).

---

## 7. Roadmap Futuro (fora do escopo atual)

> Estas seções descrevem uma evolução possível do GearUp para modelo B2B SaaS multi-tenant. **Não fazem parte da implementação atual** — ficam registradas aqui como visão de produto para eventual pitch ou expansão pós-MVP.

* **Multi-tenant real:** tabela `tenants` (empresas-cliente), cada empresa com seus próprios colaboradores/cursos isolados.
* **Monetização SaaS:** planos de assinatura recorrente (per-seat), integração com gateway de pagamento (ex: Stripe), webhooks com idempotência (`webhook_logs`), Grace Period e Grandfathering.
* **Paywall:** bloqueio de novos cadastros ao atingir limite de colaboradores do plano contratado, com direcionamento para upgrade.
* **Landing page comercial:** vitrine institucional com planos de precificação para novas empresas-cliente.
* **Infraestrutura em contêineres:** Docker/Docker Compose com limites de recursos, Redis para cache/filas, storage via S3/MinIO com presigned URLs.

---



### [ ] ETAPA 7: Monitoramento e Deploy
- [ ] Tratamento global de exceções (ocultar stack traces em produção).
- [ ] Logs estruturados.
- [ ] Deploy do projeto (ambiente de produção a definir).
