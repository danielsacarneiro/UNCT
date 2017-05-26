ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table usuario_info;
CREATE TABLE usuario_info (
    ID INT NOT NULL, 

    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ID)
);

drop table usuario_setor;
CREATE TABLE usuario_setor (
    ID INT NOT NULL,
    usu_cd_setor INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    CONSTRAINT pk PRIMARY KEY (ID,usu_cd_setor)
);
