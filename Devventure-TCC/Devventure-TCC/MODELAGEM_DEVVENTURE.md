### 1. Análise Técnica

#### 1.1. Regras de negócio
- Autenticação e perfis: o sistema mantém perfis distintos de professor, aluno e administrador, cada qual com seus próprios cadastros, autenticação, verificação de e-mail, recuperação/2FA e status de aprovação. Professores vinculam-se a turmas e gerenciam conteúdos; alunos acumulam pontos, participam de turmas e enviam respostas; administradores têm cadastro simples para gestão geral.
- Estrutura acadêmica: turmas pertencem a um professor e agregam aulas, exercícios e provas. Convites controlam a entrada de alunos em turmas, com estados pendente/aceito/recusado. A vinculação efetiva de alunos ocorre via tabela de associação.
- Conteúdos e avaliações: aulas possuem pontuação, vídeo e duração. Exercícios têm janela de publicação/fechamento, valem pontos e podem conter arquivos/imagens de apoio. Provas têm janela de abertura/fechamento, duração e múltiplas questões (texto ou múltipla escolha) com alternativas. Alunos registram tentativas de provas e respostas por questão (com avaliação de correção quando aplicável).
- Formularios/quiz por aula: cada aula pode ter formulário com perguntas, opções e respostas de alunos. Há também registro separado de respostas de exercícios, incluindo nota, conceito e feedback, mais arquivos entregues e seus nomes originais.
- Comunicação e engajamento: professores publicam avisos associados a turmas via tabela relacional. Depoimentos são coletados e moderados via flag de aprovação.
- Infraestrutura Laravel: inclui tabelas padrão para usuários genéricos, redefinição de senha, jobs falhos e tokens pessoais.

#### 1.2. Entidades e atributos
(Ver seção 2 para resumo estruturado.) Destaques por tabela:
- **professor**: identificação, CPF único, área de ensino, formação, contato, credenciais, campos de 2FA e redefinição de senha, status default pendente, timestamps.【F:database/migrations/2025_09_09_203628_table_professor.php†L16-L39】
- **aluno**: dados pessoais, RA único, semestre, e-mail único, total de pontos com default 0, contato, credenciais, 2FA, redefinição de senha, status pendente.【F:database/migrations/2025_09_10_210126_create_aluno_table.php†L18-L41】
- **admin**: nome, e-mail único, senha, timestamps.【F:database/migrations/2025_09_10_213059_create_admin_table.php†L16-L22】
- **turmas**: nome, turno, ano, datas de início/fim, professor dono (FK cascade).【F:database/migrations/2025_09_18_184450_create_turmas_table.php†L16-L26】
- **exercicios**: nome, descrição opcional, pontos (default 10), datas de publicação/fechamento, vínculos a turma e professor (cascade).【F:database/migrations/2025_09_18_202555_create_exercicios_table.php†L21-L31】
- **aluno_turma**: PK composta aluno/turma, ambas FKs cascade.【F:database/migrations/2025_09_20_030128_create_aluno_turma_table.php†L16-L24】
- **convites**: FK para turma, aluno e professor, status com default pendente, timestamps.【F:database/migrations/2025_09_20_213047_create_convites_table.php†L16-L23】
- **aulas**: FK turma (cascade), título, pontos default 5, URL de vídeo, duração em segundos, timestamps.【F:database/migrations/2025_09_26_132545_create_aulas_table.php†L16-L24】
- **aula_aluno**: participação do aluno na aula com progresso (segundos assistidos), status (enum), conclusão, unicidade aula/aluno, timestamps, FKs cascata.【F:database/migrations/2025_09_26_134416_create_aluno_aula_table.php†L17-L31】
- **formularios**: vínculo 1:1 com aula, título, timestamps.【F:database/migrations/2025_09_28_180531_create_formularios_table.php†L16-L22】
- **perguntas**: FK formulário, texto, tipo com default texto_curto, timestamps.【F:database/migrations/2025_09_28_180624_create_perguntas_table.php†L17-L24】
- **opcoes**: FK pergunta, texto, flag is_correct default false, timestamps.【F:database/migrations/2025_10_05_111829_create_opcaos_table.php†L16-L22】
- **respostas**: FK aluno, pergunta e opcao (todas cascata), timestamps.【F:database/migrations/2025_10_05_192331_create_respostas_table.php†L14-L21】
- **respostas_exercicios**: FK exercicio e aluno, data_envio, timestamps; campos de nota, conceito (2 chars) e feedback adicionados depois.【F:database/migrations/2025_10_13_193945_create_respostas_exercicios_table.php†L16-L21】【F:database/migrations/2025_10_16_163915_add_grading_to_respostas_exercicios_table.php†L16-L20】
- **arquivos_resposta**: FK resposta_exercicio, caminho de arquivo, nome_original, timestamps.【F:database/migrations/2025_10_13_201337_create_arquivos_resposta_table.php†L16-L21】【F:database/migrations/2025_10_16_165944_add_nome_original_to_arquivos_resposta_table.php†L16-L25】
- **exercicio_arquivos_apoio** e **exercicio_imagens_apoio**: arquivos/imagens auxiliares por exercício, com caminho, nome original (para arquivos) e timestamps.【F:database/migrations/2025_10_13_234303_create_exercicio_arquivos_apoio_table.php†L16-L22】【F:database/migrations/2025_10_13_234330_create_exercicio_imagens_apoio_table.php†L16-L20】
- **avisos** e **aviso_turma**: avisos do professor e associação às turmas (many-to-many), ambos com timestamps.【F:database/migrations/2025_10_15_133918_create_avisos_table.php†L16-L21】【F:database/migrations/2025_10_15_134008_create_aviso_turma_table.php†L16-L20】
- **provas**: FK turma, título, instruções, janela de abertura/fechamento, duração em minutos, timestamps.【F:database/migrations/2025_10_29_202447_create_provas_tables.php†L21-L29】
- **prova_questoes**: FK prova, enunciado, tipo (enum texto ou múltipla escolha), pontuação decimal, timestamps.【F:database/migrations/2025_10_29_205305_create_prova_questoes_table.php†L16-L22】
- **prova_alternativas**: FK questão, texto da alternativa, flag de correção, timestamps.【F:database/migrations/2025_10_29_205340_create_prova_alternativas_table.php†L16-L21】
- **aluno_prova_tentativas**: FK prova e aluno, horários início/fim, pontuação final, timestamps.【F:database/migrations/2025_10_29_205415_create_aluno_prova_tentativas_table.php†L16-L23】
- **aluno_respostas_provas**: FK tentativa e questão, FK opcional para alternativa escolhida, resposta em texto e flag de correção, timestamps.【F:database/migrations/2025_10_29_205440_create_aluno_respostas_provas_table.php†L16-L23】
- **depoimentos**: autor, texto, aprovado (default false), timestamps.【F:database/migrations/2025_11_02_225230_create_depoimentos_table.php†L11-L17】
- **tabelas padrão Laravel**: users, password_resets, failed_jobs, personal_access_tokens com estrutura tradicional de autenticação/token/filas.【F:database/migrations/2014_10_12_000000_create_users_table.php†L16-L24】【F:database/migrations/2014_10_12_100000_create_password_resets_table.php†L15-L18】【F:database/migrations/2019_08_19_000000_create_failed_jobs_table.php†L14-L21】【F:database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php†L15-L23】

#### 1.3. Relacionamentos
- Professor 1:N Turma; Turma guarda FK professor_id com cascade em exclusão.【F:database/migrations/2025_09_18_184450_create_turmas_table.php†L16-L26】
- Turma 1:N Exercicio e Exercicio 1:N Arquivos/Imagens de apoio; FKs com cascade.【F:database/migrations/2025_09_18_202555_create_exercicios_table.php†L21-L31】【F:database/migrations/2025_10_13_234303_create_exercicio_arquivos_apoio_table.php†L16-L22】【F:database/migrations/2025_10_13_234330_create_exercicio_imagens_apoio_table.php†L16-L20】
- Turma N:N Aluno via aluno_turma (PK composta), com cascata para ambos os lados.【F:database/migrations/2025_09_20_030128_create_aluno_turma_table.php†L16-L24】
- Professor/Aluno/Turma 1:N Convites; status controla aceite.【F:database/migrations/2025_09_20_213047_create_convites_table.php†L16-L23】
- Turma 1:N Aula; Aula N:N Aluno via aula_aluno com progresso e unicidade por par.【F:database/migrations/2025_09_26_132545_create_aulas_table.php†L16-L23】【F:database/migrations/2025_09_26_134416_create_aluno_aula_table.php†L17-L31】
- Aula 1:1 Formulario; Formulario 1:N Perguntas; Pergunta 1:N Opcoes; Aluno 1:N Respostas ligadas a Pergunta/Opção.【F:database/migrations/2025_09_28_180531_create_formularios_table.php†L16-L22】【F:database/migrations/2025_09_28_180624_create_perguntas_table.php†L17-L24】【F:database/migrations/2025_10_05_111829_create_opcaos_table.php†L16-L22】【F:database/migrations/2025_10_05_192331_create_respostas_table.php†L14-L21】
- Exercicio 1:N RespostasExercicios; cada resposta pertence a aluno e pode ter avaliação (nota/conceito) e vários arquivos enviados.【F:database/migrations/2025_10_13_193945_create_respostas_exercicios_table.php†L16-L21】【F:database/migrations/2025_10_16_163915_add_grading_to_respostas_exercicios_table.php†L16-L20】【F:database/migrations/2025_10_13_201337_create_arquivos_resposta_table.php†L16-L21】【F:database/migrations/2025_10_16_165944_add_nome_original_to_arquivos_resposta_table.php†L16-L25】
- Professor 1:N Avisos; Aviso N:N Turma via aviso_turma.【F:database/migrations/2025_10_15_133918_create_avisos_table.php†L16-L21】【F:database/migrations/2025_10_15_134008_create_aviso_turma_table.php†L16-L20】
- Turma 1:N Prova; Prova 1:N ProvaQuestao; Questao 1:N ProvaAlternativa; Aluno 1:N Tentativas; Tentativa 1:N RespostasProvas (ligadas a questões e alternativa opcional).【F:database/migrations/2025_10_29_202447_create_provas_tables.php†L21-L29】【F:database/migrations/2025_10_29_205305_create_prova_questoes_table.php†L16-L22】【F:database/migrations/2025_10_29_205340_create_prova_alternativas_table.php†L16-L21】【F:database/migrations/2025_10_29_205415_create_aluno_prova_tentativas_table.php†L16-L23】【F:database/migrations/2025_10_29_205440_create_aluno_respostas_provas_table.php†L16-L23】
- Depoimentos independentes sem FKs; aprovados via flag.【F:database/migrations/2025_11_02_225230_create_depoimentos_table.php†L11-L17】

### 2. Modelo Entidade-Relacionamento (MER)

#### 2.1. Entidades
- USERS (PK: id, name, email, email_verified_at, password, remember_token, created_at, updated_at)
- PASSWORD_RESETS (PK: email, token, created_at)
- FAILED_JOBS (PK: id, uuid, connection, queue, payload, exception, failed_at)
- PERSONAL_ACCESS_TOKENS (PK: id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, created_at, updated_at)
- PROFESSOR (PK: id, nome, cpf, areaEnsino, formacao, telefone, email, email_verified_at, avatar, password, two_factor_code, two_factor_expires_at, reset_password_code, reset_password_expires_at, remember_token, status, created_at, updated_at)
- ALUNO (PK: id, nome, ra, semestre, email, total_pontos, email_verified_at, avatar, telefone, password, two_factor_code, two_factor_expires_at, reset_password_code, reset_password_expires_at, remember_token, status, created_at, updated_at)
- ADMIN (PK: id, nome, email, password, created_at, updated_at)
- TURMAS (PK: id, nome_turma, turno, ano_turma, data_inicio, data_fim, professor_id, created_at, updated_at)
- EXERCICIOS (PK: id, nome, descricao, pontos, data_publicacao, data_fechamento, turma_id, professor_id, created_at, updated_at)
- ALUNO_TURMA (PK: (aluno_id, turma_id))
- CONVITES (PK: id, turma_id, aluno_id, professor_id, status, created_at, updated_at)
- AULAS (PK: id, turma_id, titulo, pontos, video_url, duracao_segundos, created_at, updated_at)
- AULA_ALUNO (PK: id, aluno_id, aula_id, segundos_assistidos, status, concluido_em, created_at, updated_at)
- FORMULARIOS (PK: id, aula_id, titulo, created_at, updated_at)
- PERGUNTAS (PK: id, formulario_id, texto_pergunta, tipo_pergunta, created_at, updated_at)
- OPCOES (PK: id, pergunta_id, texto_opcao, is_correct, created_at, updated_at)
- RESPOSTAS (PK: id, aluno_id, pergunta_id, opcao_id, created_at, updated_at)
- RESPOSTAS_EXERCICIOS (PK: id, exercicio_id, aluno_id, data_envio, nota, conceito, feedback, created_at, updated_at)
- ARQUIVOS_RESPOSTA (PK: id, resposta_exercicio_id, arquivo_path, nome_original, created_at, updated_at)
- EXERCICIO_ARQUIVOS_APOIO (PK: id, exercicio_id, arquivo_path, nome_original, created_at, updated_at)
- EXERCICIO_IMAGENS_APOIO (PK: id, exercicio_id, imagem_path, created_at, updated_at)
- AVISOS (PK: id, professor_id, titulo, conteudo, created_at, updated_at)
- AVISO_TURMA (PK: id, aviso_id, turma_id, created_at, updated_at)
- PROVAS (PK: id, turma_id, titulo, instrucoes, data_abertura, data_fechamento, duracao_minutos, created_at, updated_at)
- PROVA_QUESTOES (PK: id, prova_id, enunciado, tipo_questao, pontuacao, created_at, updated_at)
- PROVA_ALTERNATIVAS (PK: id, prova_questao_id, texto_alternativa, correta, created_at, updated_at)
- ALUNO_PROVA_TENTATIVAS (PK: id, prova_id, aluno_id, hora_inicio, hora_fim, pontuacao_final, created_at, updated_at)
- ALUNO_RESPOSTAS_PROVAS (PK: id, aluno_prova_tentativa_id, prova_questao_id, prova_alternativa_id, resposta_texto, correta, created_at, updated_at)
- DEPOIMENTOS (PK: id, autor, texto, aprovado, created_at, updated_at)

#### 2.2. Relacionamentos
- PROFESSOR 1:N TURMAS — FK: TURMAS.professor_id
- TURMAS 1:N EXERCICIOS — FK: EXERCICIOS.turma_id
- PROFESSOR 1:N EXERCICIOS — FK: EXERCICIOS.professor_id
- EXERCICIOS 1:N EXERCICIO_ARQUIVOS_APOIO — FK: EXERCICIO_ARQUIVOS_APOIO.exercicio_id
- EXERCICIOS 1:N EXERCICIO_IMAGENS_APOIO — FK: EXERCICIO_IMAGENS_APOIO.exercicio_id
- ALUNO N:N TURMAS — tabela ALUNO_TURMA (PK composta aluno_id, turma_id)
- TURMAS 1:N AULAS — FK: AULAS.turma_id
- ALUNO N:N AULAS — tabela AULA_ALUNO (FK aluno_id, aula_id), com progresso e timestamps
- AULAS 1:1 FORMULARIOS — FK: FORMULARIOS.aula_id
- FORMULARIOS 1:N PERGUNTAS — FK: PERGUNTAS.formulario_id
- PERGUNTAS 1:N OPCOES — FK: OPCOES.pergunta_id
- ALUNO 1:N RESPOSTAS — FKs: RESPOSTAS.aluno_id, RESPOSTAS.pergunta_id, RESPOSTAS.opcao_id
- EXERCICIOS 1:N RESPOSTAS_EXERCICIOS — FK: RESPOSTAS_EXERCICIOS.exercicio_id
- ALUNO 1:N RESPOSTAS_EXERCICIOS — FK: RESPOSTAS_EXERCICIOS.aluno_id
- RESPOSTAS_EXERCICIOS 1:N ARQUIVOS_RESPOSTA — FK: ARQUIVOS_RESPOSTA.resposta_exercicio_id
- PROFESSOR 1:N AVISOS — FK: AVISOS.professor_id
- AVISOS N:N TURMAS — tabela AVISO_TURMA (FK aviso_id, turma_id)
- PROFESSOR 1:N CONVITES — FK: CONVITES.professor_id
- TURMAS 1:N CONVITES — FK: CONVITES.turma_id
- ALUNO 1:N CONVITES — FK: CONVITES.aluno_id
- TURMAS 1:N PROVAS — FK: PROVAS.turma_id
- PROVAS 1:N PROVA_QUESTOES — FK: PROVA_QUESTOES.prova_id
- PROVA_QUESTOES 1:N PROVA_ALTERNATIVAS — FK: PROVA_ALTERNATIVAS.prova_questao_id
- ALUNO 1:N ALUNO_PROVA_TENTATIVAS — FK: ALUNO_PROVA_TENTATIVAS.aluno_id
- PROVAS 1:N ALUNO_PROVA_TENTATIVAS — FK: ALUNO_PROVA_TENTATIVAS.prova_id
- ALUNO_PROVA_TENTATIVAS 1:N ALUNO_RESPOSTAS_PROVAS — FK: ALUNO_RESPOSTAS_PROVAS.aluno_prova_tentativa_id
- PROVA_QUESTOES 1:N ALUNO_RESPOSTAS_PROVAS — FK: ALUNO_RESPOSTAS_PROVAS.prova_questao_id
- PROVA_ALTERNATIVAS 1:N ALUNO_RESPOSTAS_PROVAS (opcional) — FK: ALUNO_RESPOSTAS_PROVAS.prova_alternativa_id

### 3. Script SQL (DDL) – Microsoft SQL Server

**Decisões de modelagem**
- Uso de `BIGINT IDENTITY` para chaves primárias automáticas equivalentes ao `$table->id()` do Laravel.
- Tabelas de associação seguem PK composta quando definido (ex.: `aluno_turma`) ou IDENTITY quando o modelo fornece `id`.
- Campos datetime mapeados para `DATETIME2`; flags booleanas em `BIT`; textos variáveis em `NVARCHAR` para suporte a caracteres acentuados.
- Cascades replicam regras de exclusão das migrações; onde ausência de regra, aplica-se `ON DELETE NO ACTION` padrão.
- Enums convertidos para `CHECK` em colunas `NVARCHAR` ou `VARCHAR` conforme o domínio.

```sql
CREATE DATABASE DevVentureDB;
GO
USE DevVentureDB;
GO

-- Tabelas padrão Laravel
CREATE TABLE users (
    id BIGINT IDENTITY PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    email_verified_at DATETIME2 NULL,
    password NVARCHAR(255) NOT NULL,
    remember_token NVARCHAR(100) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

CREATE TABLE password_resets (
    email NVARCHAR(255) NOT NULL,
    token NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    PRIMARY KEY (email)
);
CREATE INDEX idx_password_resets_email ON password_resets(email);

CREATE TABLE failed_jobs (
    id BIGINT IDENTITY PRIMARY KEY,
    uuid NVARCHAR(255) NOT NULL UNIQUE,
    connection NVARCHAR(MAX) NOT NULL,
    queue NVARCHAR(MAX) NOT NULL,
    payload NVARCHAR(MAX) NOT NULL,
    exception NVARCHAR(MAX) NOT NULL,
    failed_at DATETIME2 NOT NULL DEFAULT SYSDATETIME()
);

CREATE TABLE personal_access_tokens (
    id BIGINT IDENTITY PRIMARY KEY,
    tokenable_type NVARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name NVARCHAR(255) NOT NULL,
    token NVARCHAR(64) NOT NULL UNIQUE,
    abilities NVARCHAR(MAX) NULL,
    last_used_at DATETIME2 NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);
CREATE INDEX idx_pat_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);

-- Núcleo acadêmico
CREATE TABLE professor (
    id BIGINT IDENTITY PRIMARY KEY,
    nome NVARCHAR(255) NOT NULL,
    cpf NVARCHAR(255) NOT NULL UNIQUE,
    areaEnsino NVARCHAR(255) NOT NULL,
    formacao NVARCHAR(MAX) NOT NULL,
    telefone NVARCHAR(255) NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    email_verified_at DATETIME2 NULL,
    avatar NVARCHAR(255) NULL,
    password NVARCHAR(255) NOT NULL,
    two_factor_code NVARCHAR(255) NULL,
    two_factor_expires_at DATETIME2 NULL,
    reset_password_code NVARCHAR(255) NULL,
    reset_password_expires_at DATETIME2 NULL,
    remember_token NVARCHAR(100) NULL,
    status NVARCHAR(50) NOT NULL DEFAULT 'pendente',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

CREATE TABLE aluno (
    id BIGINT IDENTITY PRIMARY KEY,
    nome NVARCHAR(255) NOT NULL,
    ra NVARCHAR(255) NOT NULL UNIQUE,
    semestre NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    total_pontos INT NOT NULL DEFAULT 0,
    email_verified_at DATETIME2 NULL,
    avatar NVARCHAR(255) NULL,
    telefone NVARCHAR(255) NULL,
    password NVARCHAR(255) NOT NULL,
    two_factor_code NVARCHAR(255) NULL,
    two_factor_expires_at DATETIME2 NULL,
    reset_password_code NVARCHAR(255) NULL,
    reset_password_expires_at DATETIME2 NULL,
    remember_token NVARCHAR(100) NULL,
    status NVARCHAR(50) NOT NULL DEFAULT 'pendente',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

CREATE TABLE admin (
    id BIGINT IDENTITY PRIMARY KEY,
    nome NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);

CREATE TABLE turmas (
    id BIGINT IDENTITY PRIMARY KEY,
    nome_turma NVARCHAR(255) NOT NULL,
    turno NVARCHAR(255) NOT NULL,
    ano_turma NVARCHAR(255) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    professor_id BIGINT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_turmas_professor FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);
CREATE INDEX idx_turmas_professor ON turmas(professor_id);

CREATE TABLE exercicios (
    id BIGINT IDENTITY PRIMARY KEY,
    nome NVARCHAR(255) NOT NULL,
    descricao NVARCHAR(MAX) NULL,
    pontos INT NOT NULL DEFAULT 10,
    data_publicacao DATETIME2 NOT NULL,
    data_fechamento DATETIME2 NOT NULL,
    turma_id BIGINT NOT NULL,
    professor_id BIGINT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_exercicios_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    CONSTRAINT fk_exercicios_professor FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);
CREATE INDEX idx_exercicios_turma ON exercicios(turma_id);
CREATE INDEX idx_exercicios_professor ON exercicios(professor_id);

CREATE TABLE exercicio_arquivos_apoio (
    id BIGINT IDENTITY PRIMARY KEY,
    exercicio_id BIGINT NOT NULL,
    arquivo_path NVARCHAR(255) NOT NULL,
    nome_original NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_exercicio_arqs_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE
);
CREATE INDEX idx_exercicio_arqs_exercicio ON exercicio_arquivos_apoio(exercicio_id);

CREATE TABLE exercicio_imagens_apoio (
    id BIGINT IDENTITY PRIMARY KEY,
    exercicio_id BIGINT NOT NULL,
    imagem_path NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_exercicio_imgs_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE
);
CREATE INDEX idx_exercicio_imgs_exercicio ON exercicio_imagens_apoio(exercicio_id);

CREATE TABLE aluno_turma (
    aluno_id BIGINT NOT NULL,
    turma_id BIGINT NOT NULL,
    CONSTRAINT pk_aluno_turma PRIMARY KEY (aluno_id, turma_id),
    CONSTRAINT fk_aluno_turma_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE,
    CONSTRAINT fk_aluno_turma_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);
CREATE INDEX idx_aluno_turma_turma ON aluno_turma(turma_id);

CREATE TABLE convites (
    id BIGINT IDENTITY PRIMARY KEY,
    turma_id BIGINT NOT NULL,
    aluno_id BIGINT NOT NULL,
    professor_id BIGINT NOT NULL,
    status NVARCHAR(50) NOT NULL DEFAULT 'pendente',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_convites_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    CONSTRAINT fk_convites_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE,
    CONSTRAINT fk_convites_professor FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);
CREATE INDEX idx_convites_turma ON convites(turma_id);
CREATE INDEX idx_convites_aluno ON convites(aluno_id);
CREATE INDEX idx_convites_professor ON convites(professor_id);

CREATE TABLE aulas (
    id BIGINT IDENTITY PRIMARY KEY,
    turma_id BIGINT NOT NULL,
    titulo NVARCHAR(255) NOT NULL,
    pontos INT NOT NULL DEFAULT 5,
    video_url NVARCHAR(255) NOT NULL,
    duracao_segundos INT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_aulas_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);
CREATE INDEX idx_aulas_turma ON aulas(turma_id);

CREATE TABLE aula_aluno (
    id BIGINT IDENTITY PRIMARY KEY,
    aluno_id BIGINT NOT NULL,
    aula_id BIGINT NOT NULL,
    segundos_assistidos INT NOT NULL DEFAULT 0,
    status NVARCHAR(20) NOT NULL,
    concluido_em DATETIME2 NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT uq_aula_aluno UNIQUE (aula_id, aluno_id),
    CONSTRAINT fk_aula_aluno_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE,
    CONSTRAINT fk_aula_aluno_aula FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
    CONSTRAINT ck_aula_aluno_status CHECK (status IN ('nao_iniciado','concluido'))
);
CREATE INDEX idx_aula_aluno_aula ON aula_aluno(aula_id);
CREATE INDEX idx_aula_aluno_aluno ON aula_aluno(aluno_id);

CREATE TABLE formularios (
    id BIGINT IDENTITY PRIMARY KEY,
    aula_id BIGINT NOT NULL,
    titulo NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_formularios_aula FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);
CREATE INDEX idx_formularios_aula ON formularios(aula_id);

CREATE TABLE perguntas (
    id BIGINT IDENTITY PRIMARY KEY,
    formulario_id BIGINT NOT NULL,
    texto_pergunta NVARCHAR(MAX) NOT NULL,
    tipo_pergunta NVARCHAR(50) NOT NULL DEFAULT 'texto_curto',
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_perguntas_formulario FOREIGN KEY (formulario_id) REFERENCES formularios(id) ON DELETE CASCADE
);
CREATE INDEX idx_perguntas_formulario ON perguntas(formulario_id);

CREATE TABLE opcoes (
    id BIGINT IDENTITY PRIMARY KEY,
    pergunta_id BIGINT NOT NULL,
    texto_opcao NVARCHAR(255) NOT NULL,
    is_correct BIT NOT NULL DEFAULT 0,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_opcoes_pergunta FOREIGN KEY (pergunta_id) REFERENCES perguntas(id) ON DELETE CASCADE
);
CREATE INDEX idx_opcoes_pergunta ON opcoes(pergunta_id);

CREATE TABLE respostas (
    id BIGINT IDENTITY PRIMARY KEY,
    aluno_id BIGINT NOT NULL,
    pergunta_id BIGINT NOT NULL,
    opcao_id BIGINT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_respostas_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE,
    CONSTRAINT fk_respostas_pergunta FOREIGN KEY (pergunta_id) REFERENCES perguntas(id) ON DELETE CASCADE,
    CONSTRAINT fk_respostas_opcao FOREIGN KEY (opcao_id) REFERENCES opcoes(id) ON DELETE CASCADE
);
CREATE INDEX idx_respostas_aluno ON respostas(aluno_id);
CREATE INDEX idx_respostas_pergunta ON respostas(pergunta_id);

CREATE TABLE respostas_exercicios (
    id BIGINT IDENTITY PRIMARY KEY,
    exercicio_id BIGINT NOT NULL,
    aluno_id BIGINT NOT NULL,
    data_envio DATETIME2 NOT NULL,
    nota INT NULL,
    conceito NVARCHAR(2) NULL,
    feedback NVARCHAR(MAX) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_resp_ex_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE,
    CONSTRAINT fk_resp_ex_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE
);
CREATE INDEX idx_resp_ex_exercicio ON respostas_exercicios(exercicio_id);
CREATE INDEX idx_resp_ex_aluno ON respostas_exercicios(aluno_id);

CREATE TABLE arquivos_resposta (
    id BIGINT IDENTITY PRIMARY KEY,
    resposta_exercicio_id BIGINT NOT NULL,
    arquivo_path NVARCHAR(255) NOT NULL,
    nome_original NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_arq_resp_resposta FOREIGN KEY (resposta_exercicio_id) REFERENCES respostas_exercicios(id) ON DELETE CASCADE
);
CREATE INDEX idx_arq_resp_resposta ON arquivos_resposta(resposta_exercicio_id);

CREATE TABLE avisos (
    id BIGINT IDENTITY PRIMARY KEY,
    professor_id BIGINT NOT NULL,
    titulo NVARCHAR(255) NOT NULL,
    conteudo NVARCHAR(MAX) NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_avisos_professor FOREIGN KEY (professor_id) REFERENCES professor(id) ON DELETE CASCADE
);
CREATE INDEX idx_avisos_professor ON avisos(professor_id);

CREATE TABLE aviso_turma (
    id BIGINT IDENTITY PRIMARY KEY,
    aviso_id BIGINT NOT NULL,
    turma_id BIGINT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_aviso_turma_aviso FOREIGN KEY (aviso_id) REFERENCES avisos(id) ON DELETE CASCADE,
    CONSTRAINT fk_aviso_turma_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);
CREATE INDEX idx_aviso_turma_aviso ON aviso_turma(aviso_id);
CREATE INDEX idx_aviso_turma_turma ON aviso_turma(turma_id);

CREATE TABLE provas (
    id BIGINT IDENTITY PRIMARY KEY,
    turma_id BIGINT NOT NULL,
    titulo NVARCHAR(255) NOT NULL,
    instrucoes NVARCHAR(MAX) NULL,
    data_abertura DATETIME2 NOT NULL,
    data_fechamento DATETIME2 NOT NULL,
    duracao_minutos INT NOT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_provas_turma FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);
CREATE INDEX idx_provas_turma ON provas(turma_id);

CREATE TABLE prova_questoes (
    id BIGINT IDENTITY PRIMARY KEY,
    prova_id BIGINT NOT NULL,
    enunciado NVARCHAR(MAX) NOT NULL,
    tipo_questao NVARCHAR(20) NOT NULL,
    pontuacao DECIMAL(5,2) NOT NULL DEFAULT 1.0,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_prova_questoes_prova FOREIGN KEY (prova_id) REFERENCES provas(id) ON DELETE CASCADE,
    CONSTRAINT ck_prova_questao_tipo CHECK (tipo_questao IN ('multipla_escolha','texto'))
);
CREATE INDEX idx_prova_questoes_prova ON prova_questoes(prova_id);

CREATE TABLE prova_alternativas (
    id BIGINT IDENTITY PRIMARY KEY,
    prova_questao_id BIGINT NOT NULL,
    texto_alternativa NVARCHAR(MAX) NOT NULL,
    correta BIT NOT NULL DEFAULT 0,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_prova_alternativas_questao FOREIGN KEY (prova_questao_id) REFERENCES prova_questoes(id) ON DELETE CASCADE
);
CREATE INDEX idx_prova_alternativas_questao ON prova_alternativas(prova_questao_id);

CREATE TABLE aluno_prova_tentativas (
    id BIGINT IDENTITY PRIMARY KEY,
    prova_id BIGINT NOT NULL,
    aluno_id BIGINT NOT NULL,
    hora_inicio DATETIME2 NOT NULL,
    hora_fim DATETIME2 NULL,
    pontuacao_final DECIMAL(5,2) NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_tentativas_prova FOREIGN KEY (prova_id) REFERENCES provas(id) ON DELETE CASCADE,
    CONSTRAINT fk_tentativas_aluno FOREIGN KEY (aluno_id) REFERENCES aluno(id) ON DELETE CASCADE
);
CREATE INDEX idx_tentativas_prova ON aluno_prova_tentativas(prova_id);
CREATE INDEX idx_tentativas_aluno ON aluno_prova_tentativas(aluno_id);

CREATE TABLE aluno_respostas_provas (
    id BIGINT IDENTITY PRIMARY KEY,
    aluno_prova_tentativa_id BIGINT NOT NULL,
    prova_questao_id BIGINT NOT NULL,
    prova_alternativa_id BIGINT NULL,
    resposta_texto NVARCHAR(MAX) NULL,
    correta BIT NULL,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL,
    CONSTRAINT fk_resp_prova_tentativa FOREIGN KEY (aluno_prova_tentativa_id) REFERENCES aluno_prova_tentativas(id) ON DELETE CASCADE,
    CONSTRAINT fk_resp_prova_questao FOREIGN KEY (prova_questao_id) REFERENCES prova_questoes(id) ON DELETE CASCADE,
    CONSTRAINT fk_resp_prova_alternativa FOREIGN KEY (prova_alternativa_id) REFERENCES prova_alternativas(id)
);
CREATE INDEX idx_resp_prova_tentativa ON aluno_respostas_provas(aluno_prova_tentativa_id);
CREATE INDEX idx_resp_prova_questao ON aluno_respostas_provas(prova_questao_id);

CREATE TABLE depoimentos (
    id BIGINT IDENTITY PRIMARY KEY,
    autor NVARCHAR(255) NOT NULL,
    texto NVARCHAR(MAX) NOT NULL,
    aprovado BIT NOT NULL DEFAULT 0,
    created_at DATETIME2 NULL,
    updated_at DATETIME2 NULL
);
```
