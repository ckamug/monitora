START TRANSACTION;

CREATE TABLE IF NOT EXISTS rec_doencas (
    doenca_id INT NOT NULL AUTO_INCREMENT,
    doenca_descricao VARCHAR(150) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    ordem INT NOT NULL DEFAULT 0,
    PRIMARY KEY (doenca_id),
    UNIQUE KEY uq_doenca_desc (doenca_descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_prontuario_saude_geral (
    prontuario_saude_geral_id INT NOT NULL AUTO_INCREMENT,
    acolhido_entrada_id INT NULL,

    realiza_tratamento_medico_ambulatorial TINYINT(1) NULL,
    onde_tratamento_medico_ambulatorial VARCHAR(255) NULL,
    outra_doenca_descricao VARCHAR(255) NULL,

    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,

    PRIMARY KEY (prontuario_saude_geral_id),
    UNIQUE KEY uq_psg_acolhido_entrada (acolhido_entrada_id),

    CONSTRAINT fk_psg_acolhido_entrada
        FOREIGN KEY (acolhido_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),

    CONSTRAINT fk_psg_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS rec_prontuario_sg_doencas (
    prontuario_sg_doenca_id INT NOT NULL AUTO_INCREMENT,
    prontuario_saude_geral_id INT NOT NULL,
    doenca_id INT NOT NULL,

    PRIMARY KEY (prontuario_sg_doenca_id),
    UNIQUE KEY uq_psg_doenca (prontuario_saude_geral_id, doenca_id),

    CONSTRAINT fk_psg_doenca_prontuario
        FOREIGN KEY (prontuario_saude_geral_id)
        REFERENCES rec_prontuario_saude_geral (prontuario_saude_geral_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_psg_doenca_opcao
        FOREIGN KEY (doenca_id)
        REFERENCES rec_doencas (doenca_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO rec_doencas (doenca_id, doenca_descricao, status, ordem) VALUES
(1,  'Pressão Alta', 1, 1),
(2,  'Colesterol', 1, 2),
(3,  'Tuberculose', 1, 3),
(4,  'Sífilis', 1, 4),
(5,  'Doenças cardiovasculares', 1, 5),
(6,  'Epilepsia', 1, 6),
(7,  'Diabetes', 1, 7),
(8,  'Hepatite (B/C)', 1, 8),
(9,  'HIV', 1, 9),
(10, 'Cirrose', 1, 10),
(11, 'Outra', 1, 11),
(12, 'Não tenho', 1, 12)
ON DUPLICATE KEY UPDATE
    doenca_descricao = VALUES(doenca_descricao),
    status = VALUES(status),
    ordem = VALUES(ordem);

COMMIT;
