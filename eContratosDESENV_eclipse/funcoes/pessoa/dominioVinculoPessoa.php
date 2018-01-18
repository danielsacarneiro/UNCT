<?php
include_once(caminho_util."dominio.class.php");

  Class dominioVinculoPessoa extends dominio{
  	static $CD_VINCULO_RESPONSAVEL = 1;
  	static $CD_VINCULO_CONTRATADO = 2;
  	static $CD_VINCULO_USUARIO= 3;
  	static $CD_VINCULO_SERVIDOR= 4;
  	static $CD_VINCULO_CONTATO= 5;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_VINCULO_RESPONSAVEL => "Responsável",
				self::$CD_VINCULO_CONTRATADO => "Contratado",
				self::$CD_VINCULO_USUARIO => "Usuário",
				self::$CD_VINCULO_SERVIDOR => "Servidor",
				self::$CD_VINCULO_CONTATO => "Contato",
				);
	}
	
}
?>