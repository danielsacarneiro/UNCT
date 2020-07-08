<?php

function getUsuarioATJA(){
	$dbusuario = new dbUsuarioInfo();
	$filtro = new filtroManterUsuario();
	$filtro->cdSetor = dominioSetor::$CD_SETOR_ATJA;
	$filtro->isValidarConsulta = false;
	
	$retorno = $dbusuario->consultarTelaConsulta(new voUsuarioInfo(), $filtro);
	//var_dump($retorno);
	return $retorno;
}

?>