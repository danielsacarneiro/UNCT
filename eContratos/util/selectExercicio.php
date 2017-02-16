<?php
include_once ("select.php");
// .................................................................................................................
// Classe select
// cria um combo select html

  Class selectExercicio extends select{
  	
// ...............................................................
// Construtor
//recebe uma colecao Cd x Descricao	
	function __construct () {
		$colecaoExer = array();
		for ($i=anoDefault;$i>2000;$i--){
			$colecaoExer[$i]=$i;
		}		
		
		parent::__construct($colecaoExer);
	}
		
}
?>