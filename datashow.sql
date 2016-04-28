-- --------------------------------------------------------
-- Sistema de aluguel de datashow
-- --------------------------------------------------------

DROP DATABASE IF EXISTS mydatashow;
CREATE DATABASE mydatashow DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE mydatashow;

-- --------------------------------------------------------
-- Estrutura de Usuarios Administradores
-- --------------------------------------------------------
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  id int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nome varchar(250) NOT NULL,
  email varchar(250) NOT NULL,
  senha varchar(250) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO admin (nome, email, senha) VALUES
('Rodrigo','rodrigo54mix@gmail.com','123');

-- --------------------------------------------------------
-- Estrutura de Professores
-- --------------------------------------------------------
DROP TABLE IF EXISTS professores;
CREATE TABLE professores (
  id int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nome varchar(250) NOT NULL,
  email varchar(250) NOT NULL,
  cpf varchar(11) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- Estrutura de Datashows
-- --------------------------------------------------------
DROP TABLE IF EXISTS datashows;
CREATE TABLE datashows (
  id int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  patrimonio varchar(250) NOT NULL,
  descricao varchar(250) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------
-- Estrutura de Solicitações
-- --------------------------------------------------------
DROP TABLE IF EXISTS solicitacao;
CREATE TABLE solicitacao (
  id int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  horario varchar(100) NOT NULL,
  turma varchar(100) NOT NULL,
  status varchar(10) NOT NULL,
  id_patrimonio int(10) UNSIGNED NOT NULL,
  id_professor int(10) UNSIGNED NOT NULL,
  FOREIGN KEY (id_patrimonio) REFERENCES professores(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_professor) REFERENCES datashows(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
);

-- When sess_match_ip = TRUE
-- ALTER TABLE ci_sessions ADD PRIMARY KEY (id, ip_address);

-- When sess_match_ip = FALSE
ALTER TABLE ci_sessions ADD PRIMARY KEY (id);

-- To drop a previously created primary key (use when changing the setting)
-- ALTER TABLE ci_sessions DROP PRIMARY KEY;

-- SELECT Concat('ALTER TABLE ', TABLE_NAME, ' RENAME TO dash_', TABLE_NAME, ';')
-- FROM information_schema.tables WHERE table_schema = 'mydatashow'

ALTER TABLE ci_sessions RENAME TO dash_ci_sessions;
ALTER TABLE datashows RENAME TO dash_datashows;
ALTER TABLE professores RENAME TO dash_professores;
ALTER TABLE solicitacao RENAME TO dash_solicitacao;
ALTER TABLE admin RENAME TO dash_admin;