<?php
//include_once("../../config_lib.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

function getModeloPublicacaoPreenchido($registrobanco){
	/*
	 MODELO PUBLICACAO
	 5К T.A. AO C-SAFI NК 017/17. Prorrogaчуo do prazo de vigъncia do contrato mater.
	 Contratada: ORGANIZAЧеES CERCRED LTDA EPP, CNPJ: 11.222.008/0001-19. Vigъncia: 01.01.2020 a 31.12.2020.
	 */
	$termo = new vocontrato();
	$termo->getDadosBanco($registrobanco);
	$empresa = $registrobanco[vocontrato::$nmAtrContratadaContrato];
	
	$tpdoc = dominioTpDOCPessoa::getDescricao(documentoPessoa::getTpDocSemMascara($termo->docContratada));
	$doc = documentoPessoa::getNumeroDocFormatado($termo->docContratada);
	$datainicio = $termo->dtVigenciaInicial;
	$datafim = $termo->dtVigenciaFinal;
	
	$exibirEspecie = !in_array($termo->cdEspecie, dominioEspeciesContrato::getColecaoTermosNaoNumeradosPublicacao());
	
	$contrato = getTextoHTMLNegrito(getTextoGridContrato($termo, null, false, true, $exibirEspecie));
	//echo "ano contrato: " . $termo->anoContrato;
	
	$publicacao = "$contrato-$empresa.$tpdoc:$doc."; 
	//$publicacao	.= getTextoHTMLDestacado("XXX-INCLUIR OBJETO RESUMIDO(MAX 30 LETRAS)-XXX", "blue");
	$publicacao	.= getTextoHTMLDestacado("XXX[RESUMIR OBJETO AO MСXIMO]-". $termo->objeto . "-XXX[RESUMIR]", "blue");
	if($datainicio == $datafim){
		$publicacao .= ".Vigъncia:$datainicio.";
	}else{
		$publicacao .= ".Vigъncia:$datainicio a $datafim.";
	}
	
	$arrayValidacao = array(
			$empresa,
			$tpdoc,
			$doc,
			$datainicio,
			$datafim,
			$contrato,			
	);
	
	if(!isAtributoValido($arrayValidacao)){
		throw new excecaoAtributoInvalido($publicacao);
	}
	
	return $publicacao;
}

/**
 * retorna a publicacao das chaves selecionadas
 * @param unknown $chave
 * @param unknown $voentidade
 * @return string
 */
function getDadosPublicacaoChaves($chave, $voentidade = null) {	
	
	$array = explode ( constantes::$CD_CAMPO_SUBSTITUICAO, $chave );
	$indice = 0;
	
	foreach ($array as $chave){
		$indice++;
		if($chave != ""){
			$retorno .= getDadosPublicacaoContrato($chave, $indice);
		}
	}
	
	return $retorno;
}

/**
 * retorna publicacao de uma chave somente
 * @param unknown $chaveContrato
 * @throws excecaoChaveRegistroInexistente
 * @throws excecaoMaisDeUmRegistroRetornado
 * @return string
 */
function getDadosPublicacaoContrato($chaveContrato, $indice) {
	$vocontrato = new vocontrato();
	$vocontrato->getChavePrimariaVOExplodeParam($chaveContrato);

	$dbcontrato = new dbcontrato();

	try{
		$colecao = $dbcontrato->consultarContratoComLicon($vocontrato, false, false);
		$tamanho = sizeof ( $colecao );
		//echo "tamanho $tamanho!"; 
		$registrobanco = $colecao[0];
		
		if ($colecao == ""){
			throw new excecaoChaveRegistroInexistente ("DbProcesso. Consulta Chave Primсria");
		}else{
			$publicacao = getModeloPublicacaoPreenchido($registrobanco);
		}
		
		if ($tamanho > 1 
				|| isDataValidaNaoVazia($registrobanco[vocontrato::$nmAtrDtPublicacaoContrato]) 
				|| $registrobanco[voContratoLicon::$nmAtrAnoDemanda] != null){
			//caso tenha demanda LICON preenchida, significa que ja houve pelo menos uma tentativa de publicacao
			//se ja houve publicacao anterior, deve ser alertado
			//o alerta eh igual aquele levantado em caso de mais de um registro. Dai irem para a mesma opcao
			//levantando a mesma excecao
			throw new excecaoMaisDeUmRegistroRetornado ();
		}		

	}catch (excecaoChaveRegistroInexistente $ex){
		$publicacao = getTextoHTMLDestacado("VERIFIQUE O ". ($indice) ."К REGISTRO. CONSTA COMO INEXISTENTE.");
	}catch (excecaoMaisDeUmRegistroRetornado $ex){
		//informa a existencia de publicacao anterior e deixa pro usuario pensar o que fazer.
		$publicacao = getTextoHTMLDestacado("VERIFIQUE O ". ($indice) ."К REGISTRO. Щ POSSЭVEL QUE JС TENHA SIDO PUBLICADO. APAGUE PARA DESCONSIDERAR.")
		. $publicacao;
		//$publicacao .= getModeloPublicacaoPreenchido($registrobanco);
	}catch (excecaoAtributoInvalido $ex){
		//informa a existencia de publicacao anterior e deixa pro usuario pensar o que fazer.
		$publicacao = $ex->getMsgEconti() . "." . getTextoHTMLDestacado("VERIFIQUE O ". ($indice) ."К REGISTRO. HС DADOS NУO PREENCHIDOS NA PLANILHA.");			
	}		

	return $publicacao;
}

?>