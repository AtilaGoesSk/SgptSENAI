CREATE DATABASE sgpt_db;
USE sgpt_db;

ALTER TABLE system_users
ADD COLUMN tp_cargo INT NOT NULL DEFAULT 1;
-- Criação da tabela de Projetos
CREATE TABLE projeto_teste (
    id_projeto SERIAL PRIMARY KEY,
    nm_projeto VARCHAR(150) NOT NULL,
    ds_projeto TEXT NOT NULL,
    nu_versao VARCHAR(20) NOT NULL,
    id_usuario INTEGER NOT NULL,
    dt_criacao DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES system_users(id)
);

-- Criação da tabela de Planos de Teste
CREATE TABLE plano_teste (
    id_plano_teste SERIAL PRIMARY KEY,
    id_projeto INTEGER NOT NULL,
    nm_plano VARCHAR(150) NOT NULL,
    ds_plano TEXT NOT NULL,
    dt_inicio DATE NOT NULL,
    dt_final DATE NOT NULL,
    dt_criacao DATE NOT NULL,
    FOREIGN KEY (id_projeto) REFERENCES projeto_teste(id_projeto)
);

-- Criação da tabela de Casos de Teste
CREATE TABLE caso_teste (
    id_caso_teste SERIAL PRIMARY KEY,
    id_plano_teste INTEGER NOT NULL,
    nm_caso_teste VARCHAR(150) NOT NULL,
    ds_caso_teste TEXT NOT NULL,
    tp_categoria INTEGER NOT NULL, -- Ex.: 1=Funcionalidade, 2=Interface, 3=Segurança
    ds_resultado_esperado TEXT NOT NULL,
    tp_status INTEGER NOT NULL,    -- Ex.: 1=Pendente, 2=Aprovado, 3=Reprovado
    dt_criacao DATE NOT NULL,
    FOREIGN KEY (id_plano_teste) REFERENCES plano_teste(id_plano_teste)
);
