<?php
include_once ("select.php");
// .................................................................................................................
// Classe select
// cria um combo select html

Class selectOR_AND extends select{
	 
	// ...............................................................
	// Construtor
	//recebe uma colecao Cd x Descricao
	function __construct () {
		$arrayOuE = array("OR"=>"OU","AND"=> "E");
		parent::__construct($arrayOuE);
	}

}
?>