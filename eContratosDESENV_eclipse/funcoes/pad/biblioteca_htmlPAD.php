<?php
include_once("../../config_lib.php");
include_once(caminho_vos . "vocontrato.php");
include_once("dominioSituacaoPAD.php");

function getComboSituacaoPAD($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$dominio = new dominioSituacaoPAD();
	$select = new select($dominio->colecao);

	$retorno = $select->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);

	return $retorno;
}

function getComboGestorPessoa($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada){
    return getComboGestorPessoaMais($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada, "camponaoobrigatorio", "");
}

function getComboGestorPessoaMais($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada, $classCampo, $tagHtml){
    if($db == null)
        $db = new dbgestorpessoa();

    if ($cdGestor == null)
        $cdGestor = @$_GET[vocontrato::$nmAtrGestorContrato];
                
    $recordSet = $db->consultarSelect($cdGestor);    
    $gestorSelect = new select(array());
    
    $retorno = "<select $tagHtml></select>";
    if($cdGestor != null){ 
        $gestorSelect->getRecordSetComoColecaoSelect(vogestorpessoa::$nmAtrCd, vogestorpessoa::$nmAtrNome, $recordSet);    
        $retorno = $gestorSelect->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);    
    }	
        
    return $retorno;
}

?>