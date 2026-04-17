-- Portal de Adoção de Animais — Schema PostgreSQL
-- Padrão: Class Table Inheritance (espelha a hierarquia PHP)

-- Tabela base compartilhada por todas as espécies
CREATE TABLE animais (
    id            SERIAL PRIMARY KEY,
    especie       VARCHAR(20) NOT NULL CHECK (especie IN ('Cachorro','Gato','Papagaio','Generico')),
    nome          VARCHAR(80) NOT NULL,
    idade_meses   SMALLINT NOT NULL CHECK (idade_meses >= 0),
    descricao     TEXT,
    foto_path     VARCHAR(255),
    status        VARCHAR(20) NOT NULL DEFAULT 'disponivel' CHECK (status IN ('disponivel','adotado')),
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Extensão: Cachorro
CREATE TABLE cachorros (
    animal_id INT PRIMARY KEY REFERENCES animais(id) ON DELETE CASCADE,
    raca      VARCHAR(60),
    porte     VARCHAR(20) NOT NULL DEFAULT 'medio' CHECK (porte IN ('pequeno','medio','grande')),
    vacinado  BOOLEAN NOT NULL DEFAULT FALSE
);

-- Extensão: Gato
CREATE TABLE gatos (
    animal_id INT PRIMARY KEY REFERENCES animais(id) ON DELETE CASCADE,
    raca      VARCHAR(60),
    pelagem   VARCHAR(20) NOT NULL DEFAULT 'curto' CHECK (pelagem IN ('curto','longo','sem_pelo')),
    castrado  BOOLEAN NOT NULL DEFAULT FALSE
);

-- Extensão: Papagaio
CREATE TABLE papagaios (
    animal_id    INT PRIMARY KEY REFERENCES animais(id) ON DELETE CASCADE,
    especie_ave  VARCHAR(100),
    fala_humana  BOOLEAN NOT NULL DEFAULT FALSE,
    cor_plumagem VARCHAR(60)
);

-- Extensão: Animal Genérico (outras espécies)
CREATE TABLE animais_genericos (
    animal_id         INT PRIMARY KEY REFERENCES animais(id) ON DELETE CASCADE,
    especie_descricao VARCHAR(100) NOT NULL
);

-- Histórico de saúde (1 animal → N registros)
CREATE TABLE historico_saude (
    id          SERIAL PRIMARY KEY,
    animal_id   INT NOT NULL REFERENCES animais(id) ON DELETE CASCADE,
    data_evento DATE NOT NULL,
    tipo        VARCHAR(20) NOT NULL DEFAULT 'outro' CHECK (tipo IN ('vacina','consulta','cirurgia','exame','outro')),
    descricao   TEXT NOT NULL,
    veterinario VARCHAR(80),
    criado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Comentários (1 animal → N comentários)
CREATE TABLE comentarios (
    id        SERIAL PRIMARY KEY,
    animal_id INT NOT NULL REFERENCES animais(id) ON DELETE CASCADE,
    autor     VARCHAR(60) NOT NULL,
    texto     TEXT NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_animais_especie    ON animais(especie);
CREATE INDEX idx_animais_status     ON animais(status);
CREATE INDEX idx_historico_animal   ON historico_saude(animal_id);
CREATE INDEX idx_comentarios_animal ON comentarios(animal_id);
