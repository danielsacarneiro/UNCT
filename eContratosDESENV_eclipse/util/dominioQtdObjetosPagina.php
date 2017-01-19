<?php
include_once("dominio.class.php");

  Class dominioQtdObjetosPagina extends dominio{

// ...............................................................
// Construtor
	function __construct() {
		$fator = 30;
		$opcoes  = 6;
		
		$proxvalor = 10;
		$keys = array("5", "$proxvalor");
		$descricao = array("5", "$proxvalor");
		
		for($i=count($keys); $i < $opcoes; $i++){						
			$proxvalor = $proxvalor+$fator;
			$keys[] = "$proxvalor";
			$descricao[] = "$proxvalor";
		}
		
		$this->colecao = array_combine($keys, $descricao);
	}
	
}
?>