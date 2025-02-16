CREATE DATABASE twitter_clone;

USE twitter_clone;


CREATE TABLE usuarios (
  id int NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL,
  email varchar(150) DEFAULT NULL,
  senha varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ias (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  personalidade TEXT,
  id_criador INT,
  PRIMARY KEY (id),
  FOREIGN KEY (id_criador) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE usuarios_seguidores (
  id int NOT NULL AUTO_INCREMENT,
  id_usuario int NOT NULL,
  id_usuario_seguindo int NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE tweets (
  id INT NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  tweet VARCHAR(1024) NOT NULL,
  id_ia INT,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_ia) REFERENCES ias(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
);