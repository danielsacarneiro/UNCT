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
	function __construct0() {
		$colecaoExer = array();
		for ($i=getAnoHoje()+1;$i>1998;$i--){
			$colecaoExer[$i]=$i;
		}		
		//echo "esse0";
		parent::__construct1($colecaoExer);
	}
	
	function __construct1($anoInicial) {
		//echo "esse1";
		$colecaoExer = array();
		for ($i=getAnoHoje()+1;$i>$anoInicial;$i--){
			$colecaoExer[$i]=$i;
		}
	
		parent::__construct1($colecaoExer);
	}
	
	/**
	 * metodo singleton de criacao de exercicio considerando o ano inicial em constantes
	 * @return selectExercicio
	 */
	static function getSelectColecaoAnoInicio(){	
		$retorno = new selectExercicio(constantes::$ANO_INICIO);
		return $retorno;		
	} 
}
?>