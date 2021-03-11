<?php
//include_once("../../config_lib.php");
include_once(caminho_vos . "dbpessoa.php");
include_once(caminho_vos . "vogestor.php");
include_once(caminho_filtros. "filtroManterPessoa.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

function getHTMLInputNumDocumentoPessoa($ID, $Name, $value="", $class = "camponaoobrigatorio"){
	return getInputText($ID
						, $Name
						, $value
						, $class,
						dominioTpDOCPessoa::$TAMANHO_HTML,
						dominioTpDOCPessoa::$NUM_MAX_CARACTER_HTML,
						" onkeyup='formatarCampoCNPFouCNPJ(this, event);' ");
}

function getHTMLConsultaPorDemanda($chave, $isFuncaoInserirDados=false){
	$vo = new voDemanda ();
	$vo->getChavePrimariaVOExplodeParam ( $chave );
	$colecao= consultarDadosHTMLDemanda ( $vo );
	
	if(!isColecaoVazia($colecao)){
		$vo->getDadosBanco($colecao[0]);
		$colecaoContrato = converteRecordSetEmColecaoVOsContrato ( $colecao );
		
		$retorno = "<TR><TD>Título: <INPUT type='text' value='" . $vo->texto . "'  class='camporeadonly' size='70' readonly></TD></TR>";
		// vai na bibliotacontrato
		//var_dump($colecaoContrato);
		if(!isColecaoVazia($colecaoContrato)){
			$retorno = $retorno . getColecaoContratoDet ( $colecaoContrato );
		}else{
			if($isFuncaoInserirDados){
				$retorno = $retorno . "<br>CNPJ/CPF imputada:" . getHTMLInputNumDocumentoPessoa(voPA::$nmAtrNumDocImputada,
						voPA::$nmAtrNumDocImputada, "", constantes::$CD_CLASS_CAMPO_OBRIGATORIO);
			}
		}
	}else{
		$stringTexto = getHTMLTextoObjetoNaoEncontrato();
		$retorno = $stringTexto;
	}
	
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
function getDadosContratada($chave, $voentidade = null, $funcao = null) {
	$isConsultaPessoaPorDemanda = $voentidade == "vodemanda";
	$isConsultaPessoaPorPAAP = $voentidade == "voPA";
	$isConsultaPorContrato = !($isConsultaPessoaPorDemanda || $isConsultaPessoaPorPAAP);
	
	//echo $chave;
	if ($chave != null && $chave != "") {
		if ($isConsultaPorContrato) {				
			$vo = new vocontrato ();
			$vo->getChavePrimariaVOExplodeParam ( $chave );
			//echo $chave;
			$recordSet = consultarPessoasContrato ( $vo, $vo->sqEspecie != null);
				
			$retorno = getCampoContratada ( "", "", $chave );
			if ($recordSet != "") {
				// $colecaoColunasAgrupar = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
				// $recordSet = getRecordSetGroupBy($recordSet, $colecaoColunasAgrupar);
				$tam = count ( $recordSet );

				//$retorno = "";
				
				//bota a lupa sempre pro mater
				$retorno = "";
				$lupaTemp = getLupaContratoMaterPorChaveHTML($chave);
				
				for($i = 0; $i < $tam; $i ++) {
					$registro = $recordSet [$i];
						
					$retorno .= getCampoContratada ( $registro [vopessoa::$nmAtrNome], $registro [vopessoa::$nmAtrDoc], $chave ) . "<br>";
						
					// guarda para usar na pagina que chamou o metodo
					$arrayCdAutorizacao [] = $registro [vocontrato::$nmAtrCdAutorizacaoContrato];
				}
				
				$retorno = $lupaTemp. $retorno;

				//putObjetoSessao ( "teste", $arrayCdAutorizacao );
			}
		} else {
			if($isConsultaPessoaPorDemanda){
				$retorno =	getHTMLConsultaPorDemanda($chave, $funcao == constantes::$CD_FUNCAO_INCLUIR);
			}else{
				$retorno =	getHTMLConsultaPorPAAP($chave);
			}
		}
	}
	
	return $retorno;
}
function getLupaContratoMaterPorChaveHTML($chave){
	//bota a lupa sempre pro mater
	$voContTemp = new vocontrato();
	$voContTemp->getChavePrimariaVOExplodeParam($chave);
	
	if($voContTemp->cdEspecie == null){
		$voContTemp->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
	}
	if($voContTemp->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
		$voContTemp->sqEspecie = 1;
	}	
	//echo $voContTemp->sqEspecie;
	/*$voContTemp->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
	$voContTemp->sqEspecie = 1;*/
	$chaveTemp = $voContTemp->getValorChaveHTML();
	//echo $chaveTemp;
	return getLinkPesquisa ( "../contrato/detalharContrato.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chaveTemp );	
}
function converteRecordSetEmColecaoVOsContrato($colecao) {
	$retorno = "";
	if (! isColecaoVazia ( $colecao )) {
		foreach ( $colecao as $registrobanco ) {
			$vocontrato = new vocontrato ();
			$vocontrato->getDadosBanco ( $registrobanco );
			
			if(isContratoValido($vocontrato)){
				$retorno [] = $vocontrato;
			}
		}
	}

	return $retorno;
}

/**
 * metodo criado para exibir os servidores que instruiram PAAP, mesmo para aqueles que ja perderam essa funcao
 * eh uma marreta para o nao funcionamento correto de vigencia
 * @param unknown $idCampo
 * @param unknown $nmCampo
 * @param unknown $cdOpcaoSelecionada
 * @param unknown $classCampo
 * @param unknown $tagHtml
 * @return string
 */
function getComboPessoaRespPAConsulta($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml, $comCampoHiddenACompararBanco = false){
	$dbprocesso = new dbpessoa();
	$recordset = $dbprocesso->consultarPessoaManterConsultaPAAP();
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordset);
	
	$arrayCombo = array($idCampo, $nmCampo, $cdOpcaoSelecionada, true, false, $classCampo, false, $tagHtml);
	$arrayCombo[9] = $comCampoHiddenACompararBanco;
	
	//return $select->getHtmlCombo ( $idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, false, $tagHtml );
	return $select->getHtmlComboArray ($arrayCombo);

}

function getComboPessoaRespATJAConsulta($arrayParam){
	$dbprocesso = new dbpessoa();
	$recordset = $dbprocesso->consultarPessoaManterConsultaPAAP();
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordset);
			
	return $select->getHtmlComboArray ( $arrayParam );
}

function getComboUsuarioPorSetor($pArray, $cdSetor){
	
	$dbprocesso = new dbUsuarioInfo();
	$filtro = new filtroManterUsuario(false);
	$filtro->cdSetor = $cdSetor;
	$colecao = $dbprocesso->consultarSetorUsuario($filtro);	
	
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName, $colecao);
	
	return $select->getHtmlComboArray ( $pArray );
}

function getComboPessoaRespPA($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){	
	$dbprocesso = new dbpessoa();
	$filtro = new filtroManterPessoa(false);
	$filtro->cdvinculo = dominioVinculoPessoa::$CD_VINCULO_SERVIDOR;
	$filtro->inAtribuicaoPAAP = constantes::$CD_SIM;
	$filtro->cdAtrOrdenacao = null;
	$recordset = $dbprocesso->consultarPessoaManter($filtro, false);
		
	$select = new select(array());
	$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordset);
	
	return getComboColecaoGenerico($select->colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml);
	
}

function getComboPessoaPregoeiro($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$dbprocesso = new dbpessoa();
	$filtro = new filtroManterPessoa(false);
	$filtro->cdvinculo = dominioVinculoPessoa::$CD_VINCULO_SERVIDOR;
	$filtro->inAtribuicaoPregoeiro = constantes::$CD_SIM;
	$filtro->cdAtrOrdenacao = null;
	$recordset = $dbprocesso->consultarPessoaManter($filtro, false);

	$select = new select(array());
	//$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, acrescentarCdCPLNomePregoeiro($recordset));
	$select->getRecordSetComoColecaoSelect(vopessoa::$nmAtrCd, vopessoa::$nmAtrNome, $recordset);

	return getComboColecaoGenerico($select->colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml);
}

/*function acrescentarCdCPLNomePregoeiro($recordset){
	for($i =0 ; $i<sizeof($recordset);$i++){
		$registro = $recordset[$i];
		$nmpessoa = $registro[vopessoa::$nmAtrNome];
		$registro[vopessoa::$nmAtrNome] = $nmpessoa . "-" . dominioComissaoProcLicitatorio::getCPLPorPregoeiro($nmpessoa, true);
		$recordset[$i] = $registro;
	}	
	
	return $recordset;
}*/


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

function consultarPessoasContrato($voContrato, $pIsChaveCompleta=false){
	//$voContrato = new vocontrato();
	$filtro = new filtroManterPessoa(false);
	//$filtro->voPrincipal = new vopessoa();
	
	$filtro->anoContrato = $voContrato->anoContrato;
	$filtro->cdContrato = $voContrato->cdContrato;
	$filtro->tpContrato = $voContrato->tipo;
	if($pIsChaveCompleta){
		$filtro->cdEspecieContrato = $voContrato->cdEspecie;
		$filtro->sqEspecieContrato = $voContrato->sqEspecie;
		//echo $filtro->cdEspecieContrato ;
	}

	$filtro->setaFiltroConsultaSemLimiteRegistro();
	//seta clausula group by
	$filtro->groupby = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
	$filtro->cdAtrOrdenacao = vocontrato::$nmAtrSqContrato;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;

	$db = new dbpessoa();
	$colecao = $db->consultarPessoaContratoFiltro($filtro);

	return $colecao;
}

function consultarPessoaDocumento($numDoc, $pDataVigencia = null){
	$filtro = new filtroManterPessoa(false);
	$numDoc = documentoPessoa::getNumeroDocSemMascara($numDoc);
	$filtro->doc = $numDoc;
	$filtro->dtVigencia = $pDataVigencia;
	//echo $numDoc;
	//seta clausula group by
	$filtro->groupby = array(vopessoa::$nmAtrCd);
	$filtro->cdAtrOrdenacao = vopessoa::$nmAtrCd;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
	
	$db = new dbpessoa();
	//$colecao = $db->consultarPessoaContratoFiltro($filtro);
	$colecao = $db->consultarPessoaManter($filtro, false);
	
	//ECHO "AQUI";
	
	if(isColecaoVazia($colecao)){
		throw new excecaoConsultaVazia("Não existe pessoa com o documento em questão.");
	}
	
	if(sizeof($colecao)>1){
		throw new excecaoConsultaVazia("Verifique o cadastro de pessoas. Existe mais de uma pessoa associada ao documento.");
	}
	
	return $colecao[0];
}

function getCampoContratada($pNmContratada, $pDocContratada, $pChaveContrato, $complemento=null){

	$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";
	if($pNmContratada != ""){
		
		$javaScript = "onLoad=''";
		$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=40 readonly value='".$pNmContratada."' ".$javaScript.">\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_NOME_DADOS_CONTRATADA . "' name='".vopessoa::$ID_NOME_DADOS_CONTRATADA."' value='".$pNmContratada."' >\n";
		
		if($pDocContratada != null){
			$doc = new documentoPessoa($pDocContratada);
			$docComMascara = $doc->formata();
			$sizeDoc = strlen($docComMascara);
			if($doc->valida()){
				$sizeDoc = 18;
			}
			
			$retorno .= "&nbsp;CNPJ/CNPF: <INPUT type='text' class='camporeadonlyalinhadodireita' size=".$sizeDoc. " readonly value='". $docComMascara."' ".$javaScript.">\n";
			$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_DOC_DADOS_CONTRATADA. "' name='".vopessoa::$ID_DOC_DADOS_CONTRATADA."' value='".$pDocContratada."' >\n";
		}
		
		if($complemento != null && $complemento != ""){
			$retorno .= "<br>". getTextoHTMLNegrito(getTextoHTMLFonteParametros($complemento,1))."\n";
		}
		
	}
	
	//$idContrato = vopessoa::$ID_CONTRATO. "[]";
	$idContrato = vopessoa::$ID_CONTRATO . $pChaveContrato;
	
	//vai em colchete porque podem ser retornados mais de um contrato
	$retorno .= "<INPUT type='hidden' id='" . $idContrato. "' name='".vopessoa::getID_REQ_ColecaoContrato()."' value='".$pChaveContrato."' onLoad='alert(this.name);'>\n";

	return $retorno;
}


?>