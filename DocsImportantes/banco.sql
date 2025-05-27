-- Criação do banco de dados
-- CREATE DATABASE sgpt_db;

-- Criação das tabelas
CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    nm_usuario VARCHAR(100) NOT NULL,
    ds_email VARCHAR(100) UNIQUE NOT NULL,
    ds_senha TEXT NOT NULL,
    tp_usuario INTEGER NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipe (
    id_equipe SERIAL PRIMARY KEY,
    nm_equipe VARCHAR(100) NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuario_equipe (
    id_usuario_equipe SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuario(id_usuario),
    id_equipe INTEGER NOT NULL REFERENCES equipe(id_equipe),
    UNIQUE (id_usuario, id_equipe)
);

CREATE TABLE plano_teste (
    id_plano_teste SERIAL PRIMARY KEY,
    nm_titulo VARCHAR(150) NOT NULL,
    ds_plano TEXT,
    id_usuario INTEGER REFERENCES usuario(id_usuario),
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE caso_teste (
    id_caso_teste SERIAL PRIMARY KEY,
    id_plano_teste INTEGER REFERENCES plano_teste(id_plano_teste),
    nm_titulo VARCHAR(150) NOT NULL,
    ds_caso TEXT,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE execucao_teste (
    id_execucao_teste SERIAL PRIMARY KEY,
    id_caso_teste INTEGER REFERENCES caso_teste(id_caso_teste),
    id_usuario INTEGER REFERENCES usuario(id_usuario),
    tp_execucao INTEGER,
    dt_execucao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registro_teste (
    id_registro_teste SERIAL PRIMARY KEY,
    id_execucao_teste INTEGER REFERENCES execucao_teste(id_execucao_teste),
    ds_resultado TEXT,
    dt_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE analise_registro (
    id_analise_registro SERIAL PRIMARY KEY,
    id_registro_teste INTEGER REFERENCES registro_teste(id_registro_teste),
    id_usuario INTEGER REFERENCES usuario(id_usuario),
    ds_comentario TEXT,
    dt_analise TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE status_teste (
    id_status_teste SERIAL PRIMARY KEY,
    id_analise_registro INTEGER REFERENCES analise_registro(id_analise_registro),
    tp_status INTEGER,
    dt_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE escopo (
    id_escopo SERIAL PRIMARY KEY,
    ds_escopo TEXT NOT NULL,
    id_usuario INTEGER REFERENCES usuario(id_usuario),
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback (
    id_feedback SERIAL PRIMARY KEY,
    id_usuario_remetente INTEGER REFERENCES usuario(id_usuario),
    id_usuario_destinatario INTEGER REFERENCES usuario(id_usuario),
    ds_mensagem TEXT NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
