<?php
include_once(caminho_util."dominio.class.php");

  Class dominioModalidadeLicitacao extends dominio{

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				   "C" => "Convite",
				   "T" => "Tomada de Pre�os",
				   "O" => "Concorr�ncia",
				   "L" => "Leil�o"
				   );
	}
	
}
?>