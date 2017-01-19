<?php
include_once("lib/dbprocesso.obj.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbusuario extends dbprocesso{
    
	function consultarUsuario($tipo){
			
		/*$voContrato = new vocontrato();
		
		$query = " INSERT INTO " . vocontrato::$nmEntidade . " \n";
		$query .= " (";
		$query .= $voContrato->getListaAtributos();
		$query .=") ";
		
		$query .= " \nVALUES(";
		$query .= $voContrato->getAtributosPlanilhaLinha($tipo, $linha);
		$query .=")";
		
		//echo $query;		
		$retorno = $this->cDb->atualizarImportacao($query);*/
					
	    return $retorno;
		
	}	
	
}
?>