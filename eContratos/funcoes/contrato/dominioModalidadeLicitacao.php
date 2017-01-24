<?php
include_once(caminho_util."dominio.class.php");

  Class dominioModalidadeLicitacao extends dominio{

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				   "C" => "Convite",
				   "T" => "Tomada de Preços",
				   "O" => "Concorrência",
				   "L" => "Leilão"
				   );
	}
	
}
?>