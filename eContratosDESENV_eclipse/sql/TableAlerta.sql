ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table mensageria;
drop table msg_registro;
CREATE TABLE mensageria (
	msg_sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    
    msg_dt_inicio DATE NOT NULL,
    msg_dt_fim DATE NULL,
    msg_in_habilitado CHAR(1) NOT NULL DEFAULT 'S',
    msg_num_dias_frequencia INT,
    msg_obs MEDIUMTEXT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (msg_sq)
);

drop table msg_registro;
CREATE TABLE msg_registro (	
    sq INT NOT NULL,    
    msg_sq INT NOT NULL,
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (sq, msg_sq),
   	CONSTRAINT fk_mensageria FOREIGN KEY (msg_sq) 
	REFERENCES mensageria (msg_sq) 
		ON DELETE RESTRICT
		ON UPDATE RESTRICT
);

select * from mensageria
where msg_in_habilitado = 'S'

update mensageria
set msg_in_habilitado = 'N', msg_dt_fim = DATE(NOW())
where msg_in_habilitado = 'S'

select * from msg_registro
