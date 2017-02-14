<?php
include_once(caminho_util."dominio.class.php");

  Class dominioSituacaoPAD extends dominio{
  	static $CD_SITUACAO_PAD_INSTAURADO = 1;
  	static $CD_SITUACAO_PAD_EM_ANDAMENTO = 2;
  	static $CD_SITUACAO_PAD_CANCELADO = 3;
  	static $CD_SITUACAO_PAD_ENCERRADO = 4;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_SITUACAO_PAD_INSTAURADO => "Instaurado",
				self::$CD_SITUACAO_PAD_EM_ANDAMENTO => "Em Andamento",
				self::$CD_SITUACAO_PAD_CANCELADO => "Cancelado",
				self::$CD_SITUACAO_PAD_ENCERRADO => "Encerrado"
				);
	}
	
}
?>