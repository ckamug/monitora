START TRANSACTION;

CREATE TABLE IF NOT EXISTS rec_prontuario_medicacoes (
    prontuario_medicacao_id INT NOT NULL AUTO_INCREMENT,
    acolhido_entrada_id INT NULL,
    data_medicacao DATE NULL,
    nome_medicacao VARCHAR(255) NULL,
    dosagem VARCHAR(255) NULL,
    prescricao TEXT NULL,
    tempo_uso VARCHAR(120) NULL,
    unidade_saude_prescreveu VARCHAR(255) NULL,
    observacoes TEXT NULL,
    usuario_id INT NULL,
    data_cadastro DATETIME NULL,
    data_atualizacao DATETIME NULL,
    PRIMARY KEY (prontuario_medicacao_id),
    KEY idx_pm_acolhido_entrada (acolhido_entrada_id),

    CONSTRAINT fk_pm_acolhido_entrada
        FOREIGN KEY (acolhido_entrada_id) REFERENCES rec_acolhidos_entradas (acolhido_entrada_id),

    CONSTRAINT fk_pm_usuario
        FOREIGN KEY (usuario_id) REFERENCES rec_usuarios (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
