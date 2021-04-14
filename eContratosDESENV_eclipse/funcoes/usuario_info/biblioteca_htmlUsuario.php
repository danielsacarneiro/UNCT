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

function getComboUsuarioATJA($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){

	$recordset = getUsuarioATJA();
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName, $recordset);

	$arrayCombo = array($idCampo, $nmCampo, $cdOpcaoSelecionada, true, false, $classCampo, false, $tagHtml);
	
	return $select->getHtmlComboArray ($arrayCombo);
}

function isUsuarioChefia() {
	$vousu = new voUsuarioInfo();
	return existeStr1NaStr2(dominioUsuarioCaracteristicas::$CD_CHEFE, getCaracteristicasUsuarioLogado())
	|| existeStr1NaStr2(dominioUsuarioCaracteristicas::$CD_ATJA, getCaracteristicasUsuarioLogado());
}

?>