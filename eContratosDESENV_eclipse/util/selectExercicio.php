<?php
include_once ("select.php");
require_once(caminho_util."bibliotecaDataHora.php");
// .................................................................................................................
// Classe select
// cria um combo select html

  Class selectExercicio extends select{
  	
// ...............................................................
// Construtor
//recebe uma colecao Cd x Descricao	
	function __construct () {
		$colecaoExer = array();
		for ($i=getAnoHoje();$i>2000;$i--){
			$colecaoExer[$i]=$i;
		}		
		
		parent::__construct($colecaoExer);
	}
		
}
?>