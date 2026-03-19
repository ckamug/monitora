START TRANSACTION;

CREATE TABLE IF NOT EXISTS rec_prontuario_sobre_uso (
    prontuario_sobre_uso_id INT NOT NULL AUTO_INCREMENT,
    acolhido_entrada_id INT NULL,
    dados_json LONGTEXT NULL,
    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,
    PRIMARY KEY (prontuario_sobre_uso_id),
    UNIQUE KEY uq_psu_acolhido_entrada (acolhido_entrada_id),

    CONSTRAINT fk_psu_acolhido_entrada
        FOREIGN KEY (acolhido_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),

    CONSTRAINT fk_psu_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
