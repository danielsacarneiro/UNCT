<?php
//include_once("../../config_lib.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

function getModeloPublicacaoPreenchido($registrobanco){
	/*
	 MODELO PUBLICACAO
	 5� T.A. AO C-SAFI N� 017/17. Prorroga��o do prazo de vig�ncia do contrato mater.
	 Contratada: ORGANIZA��ES CERCRED LTDA EPP, CNPJ: 11.222.008/0001-19. Vig�ncia: 01.01.2020 a 31.12.2020.
	 */
	$termo = new vocontrato();
	$termo->getDadosBanco($registrobanco);
	$empresa = $registrobanco[vocontrato::$nmAtrContratadaContrato];
	
	$tpdoc = dominioTpDOCPessoa::getDescricao(documentoPessoa::getTpDocSemMascara($termo->docContratada));
	$doc = documentoPessoa::getNumeroDocFormatado($termo->docContratada);
	$datainicio = $termo->dtVigenciaInicial;
	$datafim = $termo->dtVigenciaFinal;
	
	$contrato = getTextoHTMLNegrito(getTextoGridContrato($termo, null, false, true));
	//echo "ano contrato: " . $termo->anoContrato;
	
	$publicacao = "$contrato-$empresa.$tpdoc:$doc." . getTextoHTMLDestacado("XXX-INCLUIR OBJETO RESUMIDO(MAX 30 LETRAS)-XXX", "blue");
	$publicacao .= ".Vig�ncia:$datainicio a $datafim.";
	
	
	
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

		if ($colecao == ""){
			throw new excecaoChaveRegistroInexistente ("DbProcesso. Consulta Chave Prim�ria");
		}
				
		if ($tamanho > 1){
				throw new excecaoMaisDeUmRegistroRetornado ();
		}		
		
		$registrobanco = $colecao[0];
		$publicacao = getModeloPublicacaoPreenchido($registrobanco);

	}catch (excecaoChaveRegistroInexistente $ex){
		$publicacao = getTextoHTMLDestacado("VERIFIQUE O ". ($indice) ."� TERMO. CONSTA COMO INEXISTENTE.");
	}catch (excecaoMaisDeUmRegistroRetornado $ex){
		//informa a existencia de publicacao anterior e deixa pro usuario pensar o que fazer.
		$publicacao = getTextoHTMLDestacado("VERIFIQUE O ". ($indice) ."� TERMO. PARECE J� TER SIDO ENVIADO PARA PUBLICA��O. APAGUE PARA DESCONSIDERAR.");
		$publicacao .= getModeloPublicacaoPreenchido($registrobanco);
	}

	return $publicacao;
}

?>