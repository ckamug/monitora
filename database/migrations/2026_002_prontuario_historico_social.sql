START TRANSACTION;

SET @db := DATABASE();

-- Adiciona status em rec_grau_escolaridade apenas se não existir
SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db
        AND TABLE_NAME = 'rec_grau_escolaridade'
        AND COLUMN_NAME = 'status'
    ),
    'SELECT 1',
    'ALTER TABLE rec_grau_escolaridade ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER grau_escolaridade_descricao'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- (Opcional, só se quiser já normalizar ordem também)
SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db
        AND TABLE_NAME = 'rec_grau_escolaridade'
        AND COLUMN_NAME = 'ordem'
    ),
    'SELECT 1',
    'ALTER TABLE rec_grau_escolaridade ADD COLUMN `ordem` INT NOT NULL DEFAULT 0 AFTER status'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

COMMIT;


START TRANSACTION;

-- Mantém somente tabelas novas de apoio que ainda não existem
CREATE TABLE IF NOT EXISTS rec_anos_series (
    ano_serie_id INT NOT NULL AUTO_INCREMENT,
    ano_serie_descricao VARCHAR(120) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ano_serie_id),
    UNIQUE KEY uq_ano_serie_desc (ano_serie_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_onde_costuma_dormir (
    onde_costuma_dormir_id INT NOT NULL AUTO_INCREMENT,
    onde_costuma_dormir_descricao VARCHAR(120) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (onde_costuma_dormir_id),
    UNIQUE KEY uq_onde_costuma_dormir_desc (onde_costuma_dormir_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_faixas_tempo (
    faixa_tempo_id INT NOT NULL AUTO_INCREMENT,
    faixa_tempo_descricao VARCHAR(120) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (faixa_tempo_id),
    UNIQUE KEY uq_faixa_tempo_desc (faixa_tempo_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_rotina_diurna (
    rotina_diurna_id INT NOT NULL AUTO_INCREMENT,
    rotina_diurna_descricao VARCHAR(180) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (rotina_diurna_id),
    UNIQUE KEY uq_rotina_diurna_desc (rotina_diurna_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_trabalho_principal (
    trabalho_principal_id INT NOT NULL AUTO_INCREMENT,
    trabalho_principal_descricao VARCHAR(255) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (trabalho_principal_id),
    UNIQUE KEY uq_trabalho_principal_desc (trabalho_principal_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_motivos_rua (
    motivo_rua_id INT NOT NULL AUTO_INCREMENT,
    motivo_rua_descricao VARCHAR(255) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (motivo_rua_id),
    UNIQUE KEY uq_motivo_rua_desc (motivo_rua_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_referenciada (
    referenciada_id INT NOT NULL AUTO_INCREMENT,
    referenciada_descricao VARCHAR(120) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (referenciada_id),
    UNIQUE KEY uq_referenciada_desc (referenciada_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_prontuario_historico_social (
    prontuario_historico_social_id INT NOT NULL AUTO_INCREMENT,
    acolhido_entrada_id INT NULL,

    sabe_ler_escrever TINYINT(1) NULL,
    frequentou_escola TINYINT(1) NULL,
    grau_escolaridade_id INT NULL,
    ano_serie_id INT NULL,
    nome_escola VARCHAR(255) NULL,
    uf_escola_id INT NULL,
    municipio_escola_id INT NULL,

    onde_costuma_dormir_id INT NULL,
    tempo_moradia_id INT NULL,
    rotina_diurna_id INT NULL,

    tempo_situacao_rua_id INT NULL,
    situacao_rua_origem VARCHAR(255) NULL,

    atividade_remunerada TINYINT(1) NULL,
    trabalho_principal_id INT NULL,
    outro_trabalho_principal VARCHAR(255) NULL,

    valor_ajuda_doacao DECIMAL(12,2) NULL,
    valor_aposentadoria DECIMAL(12,2) NULL,
    valor_seguro_desemprego DECIMAL(12,2) NULL,
    valor_pensao_alimenticia DECIMAL(12,2) NULL,
    valor_outras_fontes DECIMAL(12,2) NULL,

    precisa_qualificacao TINYINT(1) NULL,
    qualificacao_descricao VARCHAR(255) NULL,

    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,

    PRIMARY KEY (prontuario_historico_social_id),
    UNIQUE KEY uq_phs_acolhido_entrada (acolhido_entrada_id),

    CONSTRAINT fk_phs_acolhido_entrada
        FOREIGN KEY (acolhido_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),

    -- REAPROVEITA TABELA EXISTENTE
    CONSTRAINT fk_phs_grau_escolaridade
        FOREIGN KEY (grau_escolaridade_id) REFERENCES rec_grau_escolaridade (grau_escolaridade_id),

    CONSTRAINT fk_phs_ano_serie
        FOREIGN KEY (ano_serie_id) REFERENCES rec_anos_series (ano_serie_id),

    -- REAPROVEITA TABELAS EXISTENTES
    CONSTRAINT fk_phs_uf_escola
        FOREIGN KEY (uf_escola_id) REFERENCES tbl_estados (estado_id),

    CONSTRAINT fk_phs_municipio_escola
        FOREIGN KEY (municipio_escola_id) REFERENCES tbl_cidades (cidade_id),

    CONSTRAINT fk_phs_onde_dormir
        FOREIGN KEY (onde_costuma_dormir_id) REFERENCES rec_onde_costuma_dormir (onde_costuma_dormir_id),

    CONSTRAINT fk_phs_tempo_moradia
        FOREIGN KEY (tempo_moradia_id) REFERENCES rec_faixas_tempo (faixa_tempo_id),

    CONSTRAINT fk_phs_rotina_diurna
        FOREIGN KEY (rotina_diurna_id) REFERENCES rec_rotina_diurna (rotina_diurna_id),

    CONSTRAINT fk_phs_tempo_situacao_rua
        FOREIGN KEY (tempo_situacao_rua_id) REFERENCES rec_faixas_tempo (faixa_tempo_id),

    CONSTRAINT fk_phs_trabalho_principal
        FOREIGN KEY (trabalho_principal_id) REFERENCES rec_trabalho_principal (trabalho_principal_id),

    CONSTRAINT fk_phs_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_prontuario_hs_motivos_rua (
    prontuario_hs_motivo_rua_id INT NOT NULL AUTO_INCREMENT,
    prontuario_historico_social_id INT NOT NULL,
    motivo_rua_id INT NOT NULL,
    PRIMARY KEY (prontuario_hs_motivo_rua_id),
    UNIQUE KEY uq_phs_motivo (prontuario_historico_social_id, motivo_rua_id),
    CONSTRAINT fk_phs_motivo_principal
        FOREIGN KEY (prontuario_historico_social_id) REFERENCES rec_prontuario_historico_social (prontuario_historico_social_id) ON DELETE CASCADE,
    CONSTRAINT fk_phs_motivo_opcao
        FOREIGN KEY (motivo_rua_id) REFERENCES rec_motivos_rua (motivo_rua_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_prontuario_hs_referenciada (
    prontuario_hs_referenciada_id INT NOT NULL AUTO_INCREMENT,
    prontuario_historico_social_id INT NOT NULL,
    referenciada_id INT NOT NULL,
    PRIMARY KEY (prontuario_hs_referenciada_id),
    UNIQUE KEY uq_phs_referenciada (prontuario_historico_social_id, referenciada_id),
    CONSTRAINT fk_phs_referenciada_principal
        FOREIGN KEY (prontuario_historico_social_id) REFERENCES rec_prontuario_historico_social (prontuario_historico_social_id) ON DELETE CASCADE,
    CONSTRAINT fk_phs_referenciada_opcao
        FOREIGN KEY (referenciada_id) REFERENCES rec_referenciada (referenciada_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;

START TRANSACTION;

-- =========================================================
-- rec_onde_costuma_dormir (hardcoded de "Onde costuma dormir?")
-- =========================================================
INSERT INTO rec_onde_costuma_dormir (onde_costuma_dormir_id, onde_costuma_dormir_descricao, status, ordem) VALUES
(1, 'Rua', 1, 1),
(2, 'Domicílio Particular', 1, 2),
(3, 'Casa própria/alugada', 1, 3),
(4, 'Outros', 1, 4),
(5, 'Albergue', 1, 5)
ON DUPLICATE KEY UPDATE
onde_costuma_dormir_descricao = VALUES(onde_costuma_dormir_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_faixas_tempo (hardcoded de tempo de moradia/situação de rua)
-- =========================================================
INSERT INTO rec_faixas_tempo (faixa_tempo_id, faixa_tempo_descricao, status, ordem) VALUES
(1, 'Até seis meses', 1, 1),
(2, 'Entre seis meses e um ano', 1, 2),
(3, 'Entre um e dois anos', 1, 3),
(4, 'Entre dois e cinco anos', 1, 4),
(5, 'Entre cinco e dez anos', 1, 5),
(6, 'Mais de dez anos', 1, 6)
ON DUPLICATE KEY UPDATE
faixa_tempo_descricao = VALUES(faixa_tempo_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_rotina_diurna (hardcoded de "Durante o dia, qual era a sua rotina?")
-- =========================================================
INSERT INTO rec_rotina_diurna (rotina_diurna_id, rotina_diurna_descricao, status, ordem) VALUES
(1, 'Trabalho formal', 1, 1),
(2, 'Trabalho informal', 1, 2),
(3, 'Ficava em cenas abertas de uso de drogas', 1, 3),
(4, 'Transitava pelos logradouros', 1, 4)
ON DUPLICATE KEY UPDATE
rotina_diurna_descricao = VALUES(rotina_diurna_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_trabalho_principal (hardcoded de "Nesse trabalho principal era")
-- =========================================================
INSERT INTO rec_trabalho_principal (trabalho_principal_id, trabalho_principal_descricao, status, ordem) VALUES
(1, 'Trabalhador por conta própria (bico, autônomo)', 1, 1),
(2, 'Trabalhador temporário em área rural', 1, 2),
(3, 'Empregado sem carteira de trabalho assinada', 1, 3),
(4, 'Empregado com carteira de trabalho assinada', 1, 4),
(5, 'Trabalhador doméstico sem carteira de trabalho assinada', 1, 5),
(6, 'Trabalhador doméstico com carteira de trabalho assinada', 1, 6),
(7, 'Trabalhador não-remunerado', 1, 7),
(8, 'Militar ou servidor público', 1, 8),
(9, 'Empregador', 1, 9),
(10, 'Estagiário', 1, 10),
(11, 'Aprendiz', 1, 11),
(12, 'Outro', 1, 12)
ON DUPLICATE KEY UPDATE
trabalho_principal_descricao = VALUES(trabalho_principal_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_motivos_rua (hardcoded de "Quais os principais motivos...")
-- =========================================================
INSERT INTO rec_motivos_rua (motivo_rua_id, motivo_rua_descricao, status, ordem) VALUES
(1, 'Perda de moradia', 1, 1),
(2, 'Desemprego/renda insuficiente para manutenção da moradia', 1, 2),
(3, 'Ameaça/Violência do território ou terceiros', 1, 3),
(4, 'Violência familiar', 1, 4),
(5, 'Separação/Divórcio', 1, 5),
(6, 'Tratamento de saúde', 1, 6),
(7, 'Alcoolismo/Drogas', 1, 7),
(8, 'Preferência/opção própria', 1, 8),
(9, 'Morte de membro da família/Pessoa próxima', 1, 9)
ON DUPLICATE KEY UPDATE
motivo_rua_descricao = VALUES(motivo_rua_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_referenciada (hardcoded de "É referenciada ou já foi atendida no:")
-- OBS: usei o texto do label (CRAS/CREAS/Centro Pop), não o value quebrado do HTML.
-- =========================================================
INSERT INTO rec_referenciada (referenciada_id, referenciada_descricao, status, ordem) VALUES
(1, 'CRAS', 1, 1),
(2, 'CREAS', 1, 2),
(3, 'Centro Pop', 1, 3)
ON DUPLICATE KEY UPDATE
referenciada_descricao = VALUES(referenciada_descricao),
status = VALUES(status),
ordem = VALUES(ordem);

-- =========================================================
-- rec_grau_escolaridade (existente): seed baseado no cadastro-acolhido
-- Ajuste nomes de coluna se seu schema usar nomes diferentes.
-- =========================================================
INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Sem escolaridade', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Sem escolaridade');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Fundamental Completo', 1, 2
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Fundamental Completo');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Fundamental Incompleto', 1, 3
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Fundamental Incompleto');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Médio Completo', 1, 4
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Médio Completo');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Médio Incompleto', 1, 5
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Médio Incompleto');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Técnico Completo', 1, 6
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Técnico Completo');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Técnico Incompleto', 1, 7
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Técnico Incompleto');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Superior Completo', 1, 8
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Superior Completo');

INSERT INTO rec_grau_escolaridade (grau_escolaridade_descricao, status, ordem)
SELECT 'Ensino Superior Incompleto', 1, 9
WHERE NOT EXISTS (SELECT 1 FROM rec_grau_escolaridade WHERE grau_escolaridade_descricao = 'Ensino Superior Incompleto');

COMMIT;