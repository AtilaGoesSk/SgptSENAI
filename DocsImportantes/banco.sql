CREATE DATABASE IF NOT EXISTS SGPT_DB;


-- Tabela usuario
CREATE TABLE usuario (
    id_usuario SERIAL CONSTRAINT pk_usuario PRIMARY KEY,
    nm_usuario VARCHAR(100) NOT NULL,
    ds_email VARCHAR(100) UNIQUE NOT NULL,
    ds_senha TEXT NOT NULL,
    tp_usuario int(10) NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela equipe
CREATE TABLE equipe (
    id_equipe SERIAL CONSTRAINT pk_equipe PRIMARY KEY,
    nm_equipe VARCHAR(100) NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela usuario_equipe
CREATE TABLE usuario_equipe (
    id_usuario_equipe SERIAL CONSTRAINT pk_usuario_equipe PRIMARY KEY,
    id_usuario INT NOT NULL REFERENCES usuario(id_usuario) CONSTRAINT fk_usuario_equipe_usuario,
    id_equipe INT NOT NULL REFERENCES equipe(id_equipe) CONSTRAINT fk_usuario_equipe_equipe,
    UNIQUE (id_usuario, id_equipe)
);

-- Tabela plano_teste
CREATE TABLE plano_teste (
    id_plano_teste SERIAL CONSTRAINT pk_plano_teste PRIMARY KEY,
    nm_titulo VARCHAR(150) NOT NULL,
    ds_plano TEXT,
    id_usuario INT REFERENCES usuario(id_usuario) CONSTRAINT fk_plano_teste_usuario,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela caso_teste
CREATE TABLE caso_teste (
    id_caso_teste SERIAL CONSTRAINT pk_caso_teste PRIMARY KEY,
    id_plano_teste INT REFERENCES plano_teste(id_plano_teste) CONSTRAINT fk_caso_teste_plano_teste,
    nm_titulo VARCHAR(150) NOT NULL,
    ds_caso TEXT,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela execucao_teste
CREATE TABLE execucao_teste (
    id_execucao_teste SERIAL CONSTRAINT pk_execucao_teste PRIMARY KEY,
    id_caso_teste INT REFERENCES caso_teste(id_caso_teste) CONSTRAINT fk_execucao_teste_caso_teste,
    id_usuario INT REFERENCES usuario(id_usuario) CONSTRAINT fk_execucao_teste_usuario,
    tp_execucao int(10),
    dt_execucao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela registro_teste
CREATE TABLE registro_teste (
    id_registro_teste SERIAL CONSTRAINT pk_registro_teste PRIMARY KEY,
    id_execucao_teste INT REFERENCES execucao_teste(id_execucao_teste) CONSTRAINT fk_registro_teste_execucao_teste,
    ds_resultado TEXT,
    dt_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela analise_registro
CREATE TABLE analise_registro (
    id_analise_registro SERIAL CONSTRAINT pk_analise_registro PRIMARY KEY,
    id_registro_teste INT REFERENCES registro_teste(id_registro_teste) CONSTRAINT fk_analise_registro_registro_teste,
    id_usuario INT REFERENCES usuario(id_usuario) CONSTRAINT fk_analise_registro_usuario,
    ds_comentario TEXT,
    dt_analise TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela status_teste
CREATE TABLE status_teste (
    id_status_teste SERIAL CONSTRAINT pk_status_teste PRIMARY KEY,
    id_analise_registro INT REFERENCES analise_registro(id_analise_registro) CONSTRAINT fk_status_teste_analise_registro,
    tp_status int(10),
    dt_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela escopo
CREATE TABLE escopo (
    id_escopo SERIAL CONSTRAINT pk_escopo PRIMARY KEY,
    ds_escopo TEXT NOT NULL,
    id_usuario INT REFERENCES usuario(id_usuario) CONSTRAINT fk_escopo_usuario,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela feedback
CREATE TABLE feedback (
    id_feedback SERIAL CONSTRAINT pk_feedback PRIMARY KEY,
    id_usuario_remetente INT REFERENCES usuario(id_usuario) CONSTRAINT fk_feedback_usuario_remetente,
    id_usuario_destinatario INT REFERENCES usuario(id_usuario) CONSTRAINT fk_feedback_usuario_destinatario,
    ds_mensagem TEXT NOT NULL,
    dt_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);