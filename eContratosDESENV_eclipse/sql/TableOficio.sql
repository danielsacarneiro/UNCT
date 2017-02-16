ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table oficio;
CREATE TABLE oficio (
	sq INT NOT NULL,
    ofic_cd_setor INT NOT NULL, 
    ofic_ex INT NOT NULL, 
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ofic_ex, ofic_cd_setor, sq)
);

