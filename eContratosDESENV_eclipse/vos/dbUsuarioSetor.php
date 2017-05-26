<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbUsuarioSetor extends dbprocesso{
		
	function getSQLValuesInsert($vo){		
		
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->id) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdSetor);
				
		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>