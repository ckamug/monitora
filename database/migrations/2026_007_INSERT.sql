INSERT INTO rec_tipos_atendimentos (tipo_atendimento_descricao)
VALUES
    ('Atendimento Individual'),
    ('Atendimento em Grupo');
    
    
CREATE TABLE IF NOT EXISTS `rec_prontuario_atividades` (
  `prontuario_atividades_id` int(11) NOT NULL AUTO_INCREMENT,
  `prontuario_entrada_id` int(11) NOT NULL DEFAULT '0',
  `usuario_id` int(11) NOT NULL DEFAULT '0',
  `tipo_atendimento_id` int(11) NOT NULL DEFAULT '0',
  `subtipo_atendimento_id` int(11) NOT NULL DEFAULT '0',
  `descricao_outro_tipo_atendimento` varchar(250) NOT NULL,
  `descricao_acao` longtext NOT NULL,
  `data_cadastro` datetime NOT NULL,
  PRIMARY KEY (`prontuario_atividades_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC
COMMENT='Tabela para registro de atividades nos prontuários dos acolhidos.';

INSERT INTO rec_tipos_atendimentos (tipo_atendimento_descricao)
VALUES
    ('Atividade Externa');
    
    
    
   START TRANSACTION;

-- 1) Menu ATIVIDADES > Atendimento em Grupo (tipo_atendimento_id = 2)
-- Acrescentar "Elevação de Escolaridade"
INSERT INTO rec_subtipos_atendimentos (tipo_atendimento_id, subtipo_descricao)
SELECT 2, 'Elevação de Escolaridade'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM rec_subtipos_atendimentos
    WHERE tipo_atendimento_id = 2
      AND subtipo_descricao = 'Elevação de Escolaridade'
);

-- Acrescentar "Práticas Integrativas e Complementares"
INSERT INTO rec_subtipos_atendimentos (tipo_atendimento_id, subtipo_descricao)
SELECT 2, 'Práticas Integrativas e Complementares'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM rec_subtipos_atendimentos
    WHERE tipo_atendimento_id = 2
      AND subtipo_descricao = 'Práticas Integrativas e Complementares'
);

-- 2) Menu ATIVIDADES > Atividade Externa (tipo_atendimento_id = 4)
-- Trocar "Fortalecimento de Vínculos Familiares" por "Visita a Familiar(es)"
UPDATE rec_subtipos_atendimentos
SET subtipo_descricao = 'Visita a Familiar(es)'
WHERE tipo_atendimento_id = 4
  AND subtipo_descricao = 'Fortalecimento de Vínculos Familiares';

COMMIT;

-- Validação rápida
SELECT subtipo_atendimento_id, tipo_atendimento_id, subtipo_descricao
FROM rec_subtipos_atendimentos
WHERE (tipo_atendimento_id = 2 AND subtipo_descricao IN (
        'Elevação de Escolaridade',
        'Práticas Integrativas e Complementares'
      ))
   OR (tipo_atendimento_id = 4 AND subtipo_descricao = 'Visita a Familiar(es)')
ORDER BY tipo_atendimento_id, subtipo_atendimento_id;
