CREATE DATABASE sgpt_db;
--drop database sgpt_db;

-- Tabela de Projetos
CREATE TABLE projeto_teste (
    id_projeto SERIAL PRIMARY KEY,
    nm_projeto VARCHAR(150) NOT NULL,
    ds_projeto TEXT,
    nu_versao VARCHAR(20), -- Ex.: '1.0', '2.5'
    id_usuario INTEGER REFERENCES system_users(id) ON DELETE SET NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Planos de Teste
CREATE TABLE plano_teste (
    id_plano_teste SERIAL PRIMARY KEY,
    id_projeto INTEGER REFERENCES projeto_teste(id_projeto) ON DELETE CASCADE,
    nm_plano VARCHAR(150) NOT NULL,
    ds_plano TEXT,
    dt_inicio DATE,
    dt_final DATE,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Casos de Teste
CREATE TABLE caso_teste (
    id_caso_teste SERIAL PRIMARY KEY,
    id_plano_teste INTEGER REFERENCES plano_teste(id_plano_teste) ON DELETE CASCADE,
    nm_caso_teste VARCHAR(150) NOT NULL,
    ds_caso_teste TEXT,
    tp_categoria VARCHAR(50), -- Ex.: 'Funcional', 'Interface', 'Segurança', etc.
    ds_resultado_esperado TEXT,
    tp_status VARCHAR(20) DEFAULT 'Pendente' CHECK (tp_status IN ('Pendente', 'Sucesso', 'Falha', 'Bloqueado')),
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
