ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table documento;
CREATE TABLE documento (
	sq INT NOT NULL,
    ofic_cd_setor INT NOT NULL, 
    ofic_ex INT NOT NULL,
    ofic_tp_doc CHAR(2) NOT NULL,
    ofic_link_doc TEXT NULL,
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ofic_ex, ofic_cd_setor, sq, ofic_tp_doc)
);

