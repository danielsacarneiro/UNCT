ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table mensageria;
drop table msg_registro;
CREATE TABLE mensageria (
	msg_sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    
    msg_tipo CHAR(1) NOT NULL DEFAULT 'P',
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
ALTER TABLE mensageria ADD COLUMN msg_tipo CHAR(1) NOT NULL DEFAULT 'P' AFTER ct_tipo;

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
where msg_dt_fim is null

update mensageria
set msg_in_habilitado = 'N', msg_dt_fim = DATE(NOW())
where msg_in_habilitado = 'S'

--
delete from mensageria
where msg_sq >= 74 and msg_sq <= 85

delete from msg_registro
where msg_sq >= 74 and msg_sq <= 85

delete from mensageria
where date(dh_inclusao) >= '2020-11-17'
and msg_in_habilitado = 'S'

delete from msg_registro
where msg_sq in
(
select (msg_sq) from mensageria
where date(dh_inclusao) >= '2020-11-17'
and msg_in_habilitado = 'S'
)

update mensageria
set msg_dt_fim = '2020-11-17'
where msg_dt_fim is null

