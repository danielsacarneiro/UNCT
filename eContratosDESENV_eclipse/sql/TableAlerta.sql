ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table mensageria;
drop table msg_registro;
CREATE TABLE mensageria (
	msg_sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    
    msg_dt_referencia DATE NOT NULL,
    msg_in_habilitado CHAR(1) NOT NULL DEFAULT 'S',
    msg_num_dias_frequencia INT,
    msg_obs MEDIUMTEXT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (msg_sq)
);

CREATE TABLE msg_registro (	
	msgr_exercicio INT NOT NULL,
    sq INT NOT NULL,
    
    msg_sq INT NOT NULL,
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (msgr_exercicio, sq)
);

select * from mensageria
