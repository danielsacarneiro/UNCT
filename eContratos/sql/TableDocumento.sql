ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table documento;
CREATE TABLE documento (
	sq INT NOT NULL,
    doc_cd_setor INT NOT NULL, 
    doc_ex INT NOT NULL,
    doc_tp CHAR(2) NOT NULL,
    doc_link TEXT NULL,
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (doc_ex, doc_cd_setor, doc_tp, sq)
);

