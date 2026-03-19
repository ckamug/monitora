START TRANSACTION;

SET @db := DATABASE();

-- =========================================================
-- 1) TABELAS DE DOMÍNIO
-- =========================================================
CREATE TABLE IF NOT EXISTS rec_especificidades (
    especificidade_id INT NOT NULL AUTO_INCREMENT,
    especificidade_descricao VARCHAR(150) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (especificidade_id),
    UNIQUE KEY uq_especificidade_desc (especificidade_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_tipo_acompanhamento (
    tipo_acompanhamento_id INT NOT NULL AUTO_INCREMENT,
    tipo_acompanhamento_descricao VARCHAR(180) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (tipo_acompanhamento_id),
    UNIQUE KEY uq_tipo_acompanhamento_desc (tipo_acompanhamento_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Caso as tabelas já existam sem status/ordem
SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA=@db AND TABLE_NAME='rec_especificidades' AND COLUMN_NAME='status'
    ),
    'SELECT 1',
    'ALTER TABLE rec_especificidades ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 1 AFTER especificidade_descricao'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA=@db AND TABLE_NAME='rec_especificidades' AND COLUMN_NAME='ordem'
    ),
    'SELECT 1',
    'ALTER TABLE rec_especificidades ADD COLUMN ordem INT NOT NULL DEFAULT 0 AFTER status'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA=@db AND TABLE_NAME='rec_tipo_acompanhamento' AND COLUMN_NAME='status'
    ),
    'SELECT 1',
    'ALTER TABLE rec_tipo_acompanhamento ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 1 AFTER tipo_acompanhamento_descricao'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA=@db AND TABLE_NAME='rec_tipo_acompanhamento' AND COLUMN_NAME='ordem'
    ),
    'SELECT 1',
    'ALTER TABLE rec_tipo_acompanhamento ADD COLUMN ordem INT NOT NULL DEFAULT 0 AFTER status'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =========================================================
-- 2) TABELA PRINCIPAL DA ABA 4
-- =========================================================
CREATE TABLE IF NOT EXISTS rec_prontuario_avaliacao_psicossocial (
    prontuario_avaliacao_psicossocial_id INT NOT NULL AUTO_INCREMENT,
    acolhido_entrada_id INT NULL,
    tipo_acompanhamento_id INT NULL,
    onde_acompanhamento VARCHAR(255) NULL,
    outro_transtorno VARCHAR(255) NULL,
    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,
    PRIMARY KEY (prontuario_avaliacao_psicossocial_id),
    UNIQUE KEY uq_pap_acolhido_entrada (acolhido_entrada_id),
    CONSTRAINT fk_pap_acolhido_entrada
        FOREIGN KEY (acolhido_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),
    CONSTRAINT fk_pap_tipo_acompanhamento
        FOREIGN KEY (tipo_acompanhamento_id) REFERENCES rec_tipo_acompanhamento (tipo_acompanhamento_id),
    CONSTRAINT fk_pap_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =========================================================
-- 3) TABELA N:N (ESPECIFICIDADES MARCADAS)
-- =========================================================
CREATE TABLE IF NOT EXISTS rec_prontuario_ap_especificidades (
    prontuario_ap_especificidade_id INT NOT NULL AUTO_INCREMENT,
    prontuario_avaliacao_psicossocial_id INT NOT NULL,
    especificidade_id INT NOT NULL,
    PRIMARY KEY (prontuario_ap_especificidade_id),
    UNIQUE KEY uq_pap_especificidade (prontuario_avaliacao_psicossocial_id, especificidade_id),
    CONSTRAINT fk_pap_espec_principal
        FOREIGN KEY (prontuario_avaliacao_psicossocial_id)
        REFERENCES rec_prontuario_avaliacao_psicossocial (prontuario_avaliacao_psicossocial_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_pap_espec_opcao
        FOREIGN KEY (especificidade_id)
        REFERENCES rec_especificidades (especificidade_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =========================================================
-- 4) SEEDS
-- =========================================================
INSERT INTO rec_especificidades (especificidade_id, especificidade_descricao, status, ordem) VALUES
(1,  'Ansiedade', 1, 1),
(2,  'Depressão', 1, 2),
(3,  'Esquizofrenia', 1, 3),
(4,  'Transtorno Bipolar', 1, 4),
(5,  'Síndrome do Pânico', 1, 5),
(6,  'Transtorno de Personalidade', 1, 6),
(7,  'Transtorno do Espectro Autista (TEA)', 1, 7),
(8,  'Transtorno Obsessivo Compulsivo (TOC)', 1, 8),
(9,  'Anorexia', 1, 9),
(10, 'Bulimia', 1, 10),
(11, 'Distúrbios de sono', 1, 11),
(12, 'Déficit de Atenção (TDAH)', 1, 12),
(13, 'Outra', 1, 13),
(14, 'Não se aplica', 1, 14),
(15, 'Tentativas de Suicídio', 1, 15)
ON DUPLICATE KEY UPDATE
    especificidade_descricao = VALUES(especificidade_descricao),
    status = VALUES(status),
    ordem = VALUES(ordem);

INSERT INTO rec_tipo_acompanhamento (tipo_acompanhamento_id, tipo_acompanhamento_descricao, status, ordem) VALUES
(1, 'Não faz acompanhamento', 1, 1),
(2, 'Continua em acompanhamento', 1, 2),
(3, 'Atualmente não, mas já fez acompanhamento', 1, 3)
ON DUPLICATE KEY UPDATE
    tipo_acompanhamento_descricao = VALUES(tipo_acompanhamento_descricao),
    status = VALUES(status),
    ordem = VALUES(ordem);

COMMIT;
