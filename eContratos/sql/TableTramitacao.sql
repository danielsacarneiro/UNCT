ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table tramitacao;
CREATE TABLE tramitacao (
	sq_tram BIGINT NOT NULL,
    
    tr_observacao MEDIUMTEXT,
    tr_dtreferencia DATE NOT NULL,    
	doc_sq INT, -- documento por enquanto eh opcional
    doc_cd_setor INT, 
    doc_ex INT,
    doc_tp CHAR(2),    
    
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (sq_tram)
);
