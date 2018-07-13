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
    ID BIGINT(20) NOT NULL,
    usu_cd_setor INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    CONSTRAINT pk PRIMARY KEY (ID,usu_cd_setor),
    CONSTRAINT fk_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID) ON DELETE RESTRICT ON UPDATE RESTRICT
);

ALTER TABLE usuario_setor CHANGE COLUMN ID ID BIGINT(20);

ALTER TABLE usuario_setor ADD CONSTRAINT fk_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID) ON DELETE RESTRICT ON UPDATE RESTRICT;

select * from usuario_setor
where ID not in (
select ID from wp_users
)

select * from wp_users
where id = 5
