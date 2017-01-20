<?php
include_once(caminho_vos . "dbgestor.php");

function getComboGestor($db, $idCampo, $nmCampo, $cdOpcaoSelecionada){
    return getComboGestorMais($db, $idCampo, $nmCampo, $cdOpcaoSelecionada, "camponaoobrigatorio", "");
}

function getComboGestorMais($dbgestor, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
    if($dbgestor == null)
        $dbgestor = new dbgestor();
        
    $recordSet = $dbgestor->consultarSelect();
    
    $gestorSelect = new select(array());
    $gestorSelect->getRecordSetComoColecaoSelect(vogestor::$nmAtrCd, vogestor::$nmAtrDescricao, $recordSet);    
        
    return $gestorSelect->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);
    
}
?>