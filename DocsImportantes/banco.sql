CREATE DATABASE sgpt_db;
USE sgpt_db;

ALTER TABLE system_users
ADD COLUMN tp_cargo INT NOT NULL DEFAULT 1;

CREATE TABLE projeto_teste (
    id_projeto INT AUTO_INCREMENT PRIMARY KEY,
    nm_projeto VARCHAR(150) NOT NULL,
    ds_projeto TEXT NOT NULL,
    nu_versao VARCHAR(20) NOT NULL,
    id_usuario INT NOT NULL,
    dt_criacao DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES system_users(id)
);

CREATE TABLE plano_teste (
    id_plano_teste INT AUTO_INCREMENT PRIMARY KEY,
    id_projeto INT NOT NULL,
    nm_plano VARCHAR(150) NOT NULL,
    ds_plano TEXT NOT NULL,
    dt_inicio DATE NOT NULL,
    dt_final DATE NOT NULL,
    dt_criacao DATE NOT NULL,
    FOREIGN KEY (id_projeto) REFERENCES projeto_teste(id_projeto)
);

CREATE TABLE caso_teste (
    id_caso_teste SERIAL PRIMARY KEY,
    id_plano_teste INTEGER REFERENCES plano_teste(id_plano_teste) NOT NULL,
    nm_caso_teste VARCHAR(150) NOT NULL,
    ds_caso_teste TEXT NOT NULL,
    tp_categoria INTEGER NOT NULL,
    ds_resultado_esperado TEXT NOT NULL,
    tp_status INTEGER NOT NULL,    
    dt_criacao DATE NOT NULL
);
