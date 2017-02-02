<?php
include_once("../../config_lib.php");
include_once("dominioVinculoPessoa.php");
include_once(caminho_vos . "dbpessoa.php");
include_once(caminho_vos . "vogestor.php");

function getComboPessoaVinculo($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){	
	$dominioVinculo = new dominioVinculoPessoa();
	$select = new select($dominioVinculo->colecao);
		
	$retorno = $select->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);	

	return $retorno;
}

/*function getComboGestorResponsavel($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada, $classCampo, $tagHtml){
    if($db == null)
        $db = new dbpessoa();

    if ($cdGestor == null)
        $cdGestor = @$_GET[vocontrato::$nmAtrGestorContrato];
                
    $recordSet = $db->consultarSelect($cdGestor);    
    $gestorSelect = new select(array());
    
    $retorno = "<select $tagHtml></select>";
    if($cdGestor != null){ 
        $gestorSelect->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordSet);    
        $retorno = $gestorSelect->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);    
    }	
        
    return $retorno;
}*/

function getComboGestorResponsavel($cdGestor){	
	$db = new dbpessoa();
	$recordSet = "";
		
		$retorno = "";		
		
		if($cdGestor != "")
			$recordSet = $db->consultarGestorPorParam($cdGestor);
		//var_dump($recordSet);	
		$tam = count($recordSet);
		//echo $tam;	
		
		if($recordSet != "" && $tam > 0){
			
			$retorno .= "<TABLE class='tabeladados' cellpadding='0' cellspacing='0'>\n";
			$retorno .= "<TR>\n";
			$retorno .= "<TH class='headertabeladados' width='1%'>X</TH>\n";
			$retorno .= "<TH class='headertabeladados' width='1%'>Código</TH>\n";
			$retorno .= "<TH class='headertabeladados' width='90%'>Descrição</TH>\n";
			$retorno .= "</TR>\n";
			
			for($i=0;$i<$tam;$i++){
				$retorno .= "<TR>\n";
				$retorno .= "<TD class='tabeladados' width='1%'>" . getRadioButton(vogestor::$nmAtrCd, vogestor::$nmAtrCd, $recordSet[$i][vogestor::$nmAtrCd],"","") . "</TD>\n";
				$retorno .= "<TD class='tabeladados' width='1%'>" . complementarCharAEsquerda($recordSet[$i][vogestor::$nmAtrCd],"0", TAMANHO_CODIGOS) . "</TD>\n";
				$retorno .= "<TD class='tabeladados' width='90%'>". $recordSet[$i][vogestor::$nmAtrDescricao] . "</TD>\n";
				$retorno .= "</TR>\n";
			}
			
			$retorno .= "</TABLE>\n";
		
	}

	return $retorno;
}

?>