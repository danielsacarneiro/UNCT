<?php
include_once(caminho_util."dominio.class.php");

  Class dominioVinculoPessoa extends dominio{
  	static $CD_VINCULO_RESPONSAVEL = 1;
  	static $CD_VINCULO_CONTRATADO = 2;
  	static $CD_VINCULO_USUARIO= 3;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				1 => "Responsável",
				2 => "Contratado",
				3 => "Usuário"
				);
	}
	
}
?>