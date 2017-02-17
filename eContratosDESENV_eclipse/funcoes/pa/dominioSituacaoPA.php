<?php
include_once(caminho_util."dominio.class.php");

  Class dominioSituacaoPA extends dominio{
  	static $CD_SITUACAO_PA_INSTAURADO = 1;
  	static $CD_SITUACAO_PA_EM_ANDAMENTO = 2;
  	static $CD_SITUACAO_PA_CANCELADO = 3;
  	static $CD_SITUACAO_PA_ENCERRADO = 4;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_SITUACAO_PA_INSTAURADO => "Instaurado",
				self::$CD_SITUACAO_PA_EM_ANDAMENTO => "Em Andamento",
				self::$CD_SITUACAO_PA_CANCELADO => "Cancelado",
				self::$CD_SITUACAO_PA_ENCERRADO => "Encerrado"
				);
	}
	
}
?>