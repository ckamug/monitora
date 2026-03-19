-- NOVA MIGRATION CORRETA

SET @db := DATABASE();

-- 1) garantir status em rec_etnias
SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db
        AND TABLE_NAME = 'rec_etnias'
        AND COLUMN_NAME = 'status'
    ),
    'SELECT 1',
    'ALTER TABLE rec_etnias ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER etnia_descricao'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2) garantir status em rec_tipos_certidao
SET @sql := (
  SELECT IF(
    EXISTS (
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db
        AND TABLE_NAME = 'rec_tipos_certidao'
        AND COLUMN_NAME = 'status'
    ),
    'SELECT 1',
    'ALTER TABLE rec_tipos_certidao ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER tipo_certidao_descricao'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3) popular rec_tipos_certidao (se faltar)
INSERT INTO rec_tipos_certidao (tipo_certidao_descricao, status)
SELECT 'Nascimento', 1
WHERE NOT EXISTS (
  SELECT 1 FROM rec_tipos_certidao WHERE tipo_certidao_descricao = 'Nascimento'
);

INSERT INTO rec_tipos_certidao (tipo_certidao_descricao, status)
SELECT 'Casamento', 1
WHERE NOT EXISTS (
  SELECT 1 FROM rec_tipos_certidao WHERE tipo_certidao_descricao = 'Casamento'
);

INSERT INTO rec_tipos_certidao (tipo_certidao_descricao, status)
SELECT 'Certidão Administrativa de Nascimento do Indígena (RANI)', 1
WHERE NOT EXISTS (
  SELECT 1
  FROM rec_tipos_certidao
  WHERE tipo_certidao_descricao = 'Certidão Administrativa de Nascimento do Indígena (RANI)'
);

-- 4) tabela normalizada para a pergunta 1.1
CREATE TABLE IF NOT EXISTS rec_opcoes_registro_cartorio (
    registro_cartorio_opcao_id INT NOT NULL AUTO_INCREMENT,
    registro_cartorio_opcao_descricao VARCHAR(255) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (registro_cartorio_opcao_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO rec_opcoes_registro_cartorio (registro_cartorio_opcao_id, registro_cartorio_opcao_descricao, status)
VALUES
(1, 'Sim e tem Certidão de Nascimento e/ou de Casamento', 1),
(2, 'Sim, mas não tem Certidão de Nascimento nem de Casamento', 1),
(3, 'Não (Se tem RANI, passe ao 1.3.2, opção C)', 1),
(4, 'Não sabe', 1)
ON DUPLICATE KEY UPDATE
registro_cartorio_opcao_descricao = VALUES(registro_cartorio_opcao_descricao),
status = VALUES(status);

-- 5) tabela principal da aba Identificação
CREATE TABLE IF NOT EXISTS rec_prontuario_identificacao (
    prontuario_identificacao_id INT NOT NULL AUTO_INCREMENT,
    prontuario_entrada_id INT NULL,

    nacionalidade VARCHAR(120) NULL,
    naturalidade VARCHAR(120) NULL,
    etnia_id INT NULL,

    registro_cartorio_opcao_id INT NULL,

    tipo_certidao_id INT NULL,
    nome_cartorio VARCHAR(255) NULL,
    data_registro DATE NULL,
    numero_livro VARCHAR(30) NULL,
    numero_folha VARCHAR(30) NULL,
    numero_termo_rani VARCHAR(30) NULL,
    matricula VARCHAR(50) NULL,

    estado_registro_id INT NULL,
    cidade_registro_id INT NULL,

    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,

    PRIMARY KEY (prontuario_identificacao_id),
    UNIQUE KEY uq_prontuario_identificacao_entrada (prontuario_entrada_id),

    KEY idx_prontuario_identificacao_etnia (etnia_id),
    KEY idx_prontuario_identificacao_tipo_certidao (tipo_certidao_id),
    KEY idx_prontuario_identificacao_registro_cartorio (registro_cartorio_opcao_id),
    KEY idx_prontuario_identificacao_estado (estado_registro_id),
    KEY idx_prontuario_identificacao_cidade (cidade_registro_id),
    KEY idx_prontuario_identificacao_usuario (usuario_id),

    CONSTRAINT fk_prontuario_identificacao_entrada
        FOREIGN KEY (prontuario_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),

    CONSTRAINT fk_prontuario_identificacao_etnia
        FOREIGN KEY (etnia_id) REFERENCES rec_etnias (etnia_id),

    CONSTRAINT fk_prontuario_identificacao_tipo_certidao
        FOREIGN KEY (tipo_certidao_id) REFERENCES rec_tipos_certidao (tipo_certidao_id),

    CONSTRAINT fk_prontuario_identificacao_registro_cartorio
        FOREIGN KEY (registro_cartorio_opcao_id) REFERENCES rec_opcoes_registro_cartorio (registro_cartorio_opcao_id),

    CONSTRAINT fk_prontuario_identificacao_estado
        FOREIGN KEY (estado_registro_id) REFERENCES tbl_estados (estado_id),

    CONSTRAINT fk_prontuario_identificacao_cidade
        FOREIGN KEY (cidade_registro_id) REFERENCES tbl_cidades (cidade_id),

    CONSTRAINT fk_prontuario_identificacao_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
