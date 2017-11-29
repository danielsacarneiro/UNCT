<?php
include_once("../../config_lib.php");
include_once("dominioVinculoPessoa.php");
include_once(caminho_vos . "dbpessoa.php");
include_once(caminho_vos . "vogestor.php");
include_once(caminho_filtros. "filtroManterPessoa.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

function getHTMLConsultaPorDemanda($chave){
	$vo = new voDemanda ();
	$vo->getChavePrimariaVOExplodeParam ( $chave );
	$colecaoContrato = consultarContratosDemanda ( $vo );
		
	$vo->getDadosBanco($colecaoContrato[0]);
	
	$colecaoContrato = converteRecordSetEmColecaoVOsContrato ( $colecaoContrato );
	
	$retorno = "<TR><TD>Título: <INPUT type='text' value='" . $vo->texto . "'  class='camporeadonly' size='70' readonly></TD></TR>";
	// vai na bibliotacontrato
	$retorno = $retorno . getColecaoContratoDet ( $colecaoContrato );
	return $retorno; 
}
function getHTMLConsultaPorPAAP($chave){
	$vo = new voPA();
	$vo->getChavePrimariaVOExplodeParam ( $chave );
	$colecaoContrato = consultarContratosPAAP ( $vo );
	if(!isColecaoVazia($colecaoContrato)){
		$vo->getDadosBanco($colecaoContrato[0]);	
		$colecaoContrato = converteRecordSetEmColecaoVOsContrato ( $colecaoContrato );
	}
	// vai na bibliotacontrato
	$retorno = $retorno . getColecaoContratoDet ( $colecaoContrato );
	return $retorno;	
}
function getDadosContratada($chave, $voentidade = null) {
	$isConsultaPessoaPorDemanda = $voentidade == "vodemanda";
	$isConsultaPessoaPorPAAP = $voentidade == "voPA";
	$isConsultaPorContrato = !($isConsultaPessoaPorDemanda || $isConsultaPessoaPorPAAP);

	//echo $chave;
	if ($chave != null && $chave != "") {
		if ($isConsultaPorContrato) {				
			$vo = new vocontrato ();
			$vo->getChavePrimariaVOExplodeParam ( $chave );
			$recordSet = consultarPessoasContrato ( $vo );
				
			$retorno = getCampoContratada ( "", "", $chave );
			if ($recordSet != "") {
				// $colecaoColunasAgrupar = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
				// $recordSet = getRecordSetGroupBy($recordSet, $colecaoColunasAgrupar);
				$tam = count ( $recordSet );

				$retorno = "";
				for($i = 0; $i < $tam; $i ++) {
					$registro = $recordSet [$i];
						
					$retorno .= getCampoContratada ( $registro [vopessoa::$nmAtrNome], $registro [vopessoa::$nmAtrDoc], $chave ) . "<br>";
						
					// guarda para usar na pagina que chamou o metodo
					$arrayCdAutorizacao [] = $registro [vocontrato::$nmAtrCdAutorizacaoContrato];
				}

				putObjetoSessao ( "teste", $arrayCdAutorizacao );
			}
		} else {
			if($isConsultaPessoaPorDemanda){
			/*$vo = new voDemanda ();
			$vo->getChavePrimariaVOExplodeParam ( $chave );
			$colecaoContrato = consultarContratosDemanda ( $vo );
			
			$vo->getDadosBanco($colecaoContrato[0]);
				
			$colecaoContrato = converteRecordSetEmColecaoVOsContrato ( $colecaoContrato );
				
			$retorno = "<TR><TD>Título: <INPUT type='text' value='" . $vo->texto . "'  class='camporeadonly' size='70' readonly></TD></TR>"; 
			// vai na bibliotacontrato
			$retorno = $retorno . getColecaoContratoDet ( $colecaoContrato );*/
				$retorno =	getHTMLConsultaPorDemanda($chave);
			}else{
				$retorno =	getHTMLConsultaPorPAAP($chave);
			}
		}
	}

	return $retorno;
}
function converteRecordSetEmColecaoVOsContrato($colecao) {
	$retorno = "";
	if (! isColecaoVazia ( $colecao )) {
		foreach ( $colecao as $registrobanco ) {
			$vocontrato = new vocontrato ();
			$vocontrato->getDadosBanco ( $registrobanco );
				
			$retorno [] = $vocontrato;
		}
	}

	return $retorno;
}

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

function consultarPessoasContrato($voContrato){
	//$voContrato = new vocontrato();
	$filtro = new filtroManterPessoa(false);
	$filtro->anoContrato = $voContrato->anoContrato;
	$filtro->cdContrato = $voContrato->cdContrato;
	$filtro->tpContrato = $voContrato->tipo;
	$filtro->setaFiltroConsultaSemLimiteRegistro();
	//seta clausula group by
	$filtro->groupby = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
	$filtro->cdAtrOrdenacao = vocontrato::$nmAtrSqContrato;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
	
	$db = new dbpessoa();
	$colecao = $db->consultarPessoaContratoFiltro($filtro);
	
	return $colecao;
}

function getCampoContratada($pNmContratada, $pDocContratada, $pChaveContrato){

	$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";
	if($pDocContratada != ""){
		
		$doc = new documentoPessoa($pDocContratada);
		$docComMascara = $doc->formata();
		$sizeDoc = strlen($docComMascara);
		if($doc->valida()){
			$sizeDoc = 18;
		}
		
		$javaScript = "onLoad=''";
		$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=40 readonly value='".$pNmContratada."' ".$javaScript.">\n";
		$retorno .= "&nbsp;CNPJ/CNPF: <INPUT type='text' class='camporeadonlyalinhadodireita' size=".$sizeDoc. " readonly value='". $docComMascara."' ".$javaScript.">\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_NOME_DADOS_CONTRATADA . "' name='".vopessoa::$ID_NOME_DADOS_CONTRATADA."' value='".$pNmContratada."' >\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_DOC_DADOS_CONTRATADA. "' name='".vopessoa::$ID_DOC_DADOS_CONTRATADA."' value='".$pDocContratada."' >\n";
	}
	
	//$idContrato = vopessoa::$ID_CONTRATO. "[]";
	$idContrato = vopessoa::$ID_CONTRATO . $pChaveContrato;
	
	//vai em colchete porque podem ser retornados mais de um contrato
	$retorno .= "<INPUT type='hidden' id='" . $idContrato. "' name='".vopessoa::getID_REQ_ColecaoContrato()."' value='".$pChaveContrato."' onLoad='alert(this.name);'>\n";

	return $retorno;
}


?>