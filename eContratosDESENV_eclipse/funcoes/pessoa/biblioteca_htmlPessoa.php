<?php
include_once("../../config_lib.php");
include_once("dominioVinculoPessoa.php");
include_once(caminho_vos . "dbpessoa.php");
include_once(caminho_vos . "vogestor.php");
include_once(caminho_filtros. "filtroManterPessoa.php");

function getComboPessoaRespPA($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){	
	$dbprocesso = new dbpessoa();
	$filtro = new filtroManterPessoa(false);
	$filtro->cdvinculo = dominioVinculoPessoa::$CD_VINCULO_SERVIDOR;
	$filtro->cdAtrOrdenacao = null;
	$recordset = $dbprocesso->consultarPessoaManter($filtro, false);
		
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordset);
	
	return getComboColecaoGenerico($select->colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml);
	
}

function getComboPessoaVinculo($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$dominioVinculo = new dominioVinculoPessoa();
	return getComboColecaoGenerico($dominioVinculo->colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml);
}

function getComboGestorResponsavel($cdGestor){
	$pCdOpcaoSelecionada = null;
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
				
				$cd = $recordSet[$i][vogestor::$nmAtrCd];
				$checked = "";
				if($cd == $pCdOpcaoSelecionada)
					$checked = "checked";				
				
				$retorno .= "<TR>\n";
				$retorno .= "<TD class='tabeladados' width='1%'>" . getRadioButton(vogestor::$nmAtrCd, vogestor::$nmAtrCd, $cd ,$checked,"") . "</TD>\n";
				$retorno .= "<TD class='tabeladados' width='1%'>" . complementarCharAEsquerda($cd ,"0", TAMANHO_CODIGOS) . "</TD>\n";
				$retorno .= "<TD class='tabeladados' width='90%'>". $recordSet[$i][vogestor::$nmAtrDescricao] . "</TD>\n";
				$retorno .= "</TR>\n";
			}
			
			$retorno .= "</TABLE>\n";
		
	}

	return $retorno;
}

function getCampoContratada($pNmContratada, $pDocContratada, $pChaveContrato){

	$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";
	if($pDocContratada != ""){
		$javaScript = "onLoad=''";
		$retorno = $pDocContratada . " - ". $pNmContratada;
		$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=50 readonly value='".$retorno."' ".$javaScript.">\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$SQ_CONTRATO_DADOS_CONTRATADA . "' name='".vopessoa::$SQ_CONTRATO_DADOS_CONTRATADA."' value='".$pChaveContrato."' >\n";
	}

	return $retorno;
}


?>