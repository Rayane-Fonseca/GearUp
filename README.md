# GearUp — Plataforma de Treinamento Corporativo (LMS)

Projeto acadêmico (SENAI/SAEP) construído em **Laravel 11 + Filament 3 + MySQL + Tailwind + Alpine.js**.

Este pacote contém a reconstrução do projeto original a partir do vídeo de demonstração: o código foi corrigido, o banco de dados populado com os mesmos dados exibidos no vídeo, e as telas de Colaborador e Administrador foram conectadas a dados reais (nada mais é HTML estático).

---

## O que foi corrigido/reconstruído nesta versão

- **Banco de dados consistente**: migrations reescritas do zero (usuarios, cursos, módulos, aulas, trilhas, trilha_curso, progressos, certificados, notificações, logs), eliminando as migrations de "patch" conflitantes que existiam antes.
- **Um único modelo de usuário** (`Usuario` / tabela `usuarios`) para Colaborador e Administrador, com o campo `perfil` definindo o papel. O guard de autenticação (`config/auth.php`) e o Filament apontam para este mesmo model.
- **Seeder novo** (`TechCorpSeeder`) com os dados reais vistos no vídeo: 8 colaboradores (Lucas Andrade, Ana Costa, Rafael Torres, Fernanda Lima, Carlos Mendes, Marcos Vieira, Juliana Souza, Paulo Ramos) + 1 administrador, 9 cursos, 7 trilhas de aprendizagem, progresso, certificados e notificações do usuário de demonstração (Lucas Andrade).
- **`AlunoController` completo**: os métodos que faltavam (`trilhas`, `trilhaDetalhe`, `certificados`, `perfil`) foram criados — antes só existiam `inicio()` e `cursos()`, o que quebrava a navegação.
- **Views do Colaborador conectadas ao banco**: início, cursos (com filtro por categoria), trilhas, detalhe da trilha, certificados e perfil agora usam dados reais via Eloquent, com layout/header/sidebar iguais ao vídeo (busca, sino de notificações, avatar, badge "COLABORADOR").
- **Login corrigido**: agora tem os dois botões do vídeo ("Entrar como Colaborador" / "Entrar como Administrador"), textos em PT-BR (antes estava em PT-PT), e valida se a conta realmente tem aquele perfil antes de deixar entrar.
- **Painel do Administrador**: dashboard com os cards, gráfico de atividade mensal (barras) e distribuição (rosca) via Chart.js, mais as telas **Gerenciar Cursos** (CRUD completo com modal) e **Gerenciar Colaboradores** (busca + filtro por área), no mesmo estilo visual (sidebar escura) do vídeo.
- **Painel Filament** movido para `/gestao` (antes ficava em `/admin`, que agora é o painel personalizado acima) — continua disponível para gerenciar Módulos, Aulas e Certificados com mais detalhe, restrito a usuários com perfil Administrador.
- **Certificados**: fluxo de emissão (`Certificado::validarEmissao` + `GerarPdfCertificadoJob`) corrigido para usar o novo schema.

Todas as rotas foram testadas rodando o projeto de verdade (migrations, seeder, servidor local e requisições HTTP reais), incluindo o bloqueio de acesso cruzado entre as áreas (um Colaborador não consegue abrir `/admin`, e vice-versa).

---

## Como rodar o projeto

```bash
composer install
npm install
cp .env.example .env   # se não existir um .env ainda
php artisan key:generate
```

Configure o banco de dados no `.env` (já vem pré-configurado para MySQL local: banco `gearup`, usuário `root`). Crie o banco `gearup` no seu MySQL/XAMPP antes de migrar.

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Acesse `http://localhost:8000`.

### Contas de teste (senha para todas: `senha123`)

| Perfil | E-mail | Observação |
|---|---|---|
| Colaborador (demo) | `lucas@techcorp.com.br` | Usuário principal do vídeo, com progresso, trilhas e certificados |
| Administrador | `admin@techcorp.com.br` | Acessa `/admin` (painel personalizado) e `/gestao` (Filament) |
| Colaboradores extras | `ana@`, `rafael@`, `fernanda@`, `carlos@`, `marcos@`, `juliana@`, `paulo@techcorp.com.br` | Aparecem em Gerenciar Colaboradores |

---

## Observação sobre fidelidade ao vídeo

O vídeo mostra uma demonstração estática (mockup) — alguns números (ex: contagem de cursos, percentuais de trilhas) eram "soltos", sem uma base de dados real por trás. Nesta reconstrução, todos os números vêm de consultas reais ao banco (progresso médio, contagem de cursos, horas somadas etc.), então alguns valores podem variar ligeiramente em relação ao vídeo — a estrutura, o fluxo e o visual foram mantidos o mais fiéis possível, mas agora é um sistema funcional de verdade, não uma tela estática.
