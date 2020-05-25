<?php
require_once (caminho_util . "selectExercicio.php");
// require_once(caminho_util."constantes.class.php");
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioTipoContrato.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioEspeciesContrato.php");
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");
function isContratoValido($voContrato) {
	// so exibe contrato se tiver
	return $voContrato != null && $voContrato->cdContrato;
}
function getContratoDet($voContrato, $detalharContratoInfo = false, $isDetalharChaveCompleta=false) {
	$colecao = consultarPessoasContrato ( $voContrato );
	return getContratoDetalhamento ( $voContrato, $colecao, $detalharContratoInfo,$isDetalharChaveCompleta);
}
function getColecaoContratoDet($colecao,$isDetalharChaveCompleta=false) {
	$html = "";
	//var_dump($colecao);
	if(!isColecaoVazia($colecao)){
		foreach ( $colecao as $voContrato ) {
			$html .= getContratoDet ( $voContrato,false,$isDetalharChaveCompleta );
		}
	}else{
		$html = "NAO ENCONTRADO";
	}
	return $html;
}
function getContratoDetalhamentoAvulso($voContrato, $apenasComplemento=false){
	if (isContratoValido ( $voContrato )) {
		//$contrato = getContratoDescricaoEspecie($voContrato);
		$contrato = getDsEspecie($voContrato);		
		
		if(!$apenasComplemento){
			$contrato = formatarCodigoAnoComplemento ( $voContrato->cdContrato, $voContrato->anoContrato, dominioTipoContrato::getDescricaoStatic($voContrato->tipo ) ) . $contrato;
		}		
	}	
	return $contrato;	
}

function getContratoDetalhamento($voContrato, $colecao,  $detalharContratoInfo = false, $isDetalharChaveCompleta=false) {
	$arrayParametro[0] = $voContrato;
	$arrayParametro[1] = $colecao;
	$arrayParametro[2] = $detalharContratoInfo;
	$arrayParametro[3] = $isDetalharChaveCompleta;
	
	getContratoDetalhamentoParam($arrayParametro);
}
function getContratoDetalhamentoParam($arrayParametro) {
	$voContrato = $arrayParametro[0];
	$colecao = $arrayParametro[1];
	$detalharContratoInfo = $arrayParametro[2];
	$isDetalharChaveCompleta=$arrayParametro[3];
	$complementoDet=$arrayParametro[4];
	
	if($colecao == null){
		$colecao = consultarPessoasContrato ( $voContrato );
	}
	
	$vo = new vocontrato ();

	// so exibe contrato se tiver
	if (isContratoValido ( $voContrato )) {

		$dominioTipoContrato = new dominioTipoContrato ();
		$contrato = formatarCodigoAnoComplemento ( $voContrato->cdContrato, $voContrato->anoContrato, $dominioTipoContrato->getDescricao ( $voContrato->tipo ) );

		// $voContrato = new vocontrato();
		$chaveContrato = $voContrato->getValorChaveHTML ();
		$campoContratado = getCampoContratada ( "", "", $chaveContrato );
		$temLupa = false;
		if ($colecao != "") {
			$temLupa = true;
			//var_dump($colecao);
			if (! isArrayMultiDimensional ( $colecao )) {
				$nmpessoa = $colecao [vopessoa::$nmAtrNome];
				$docpessoa = $colecao [vopessoa::$nmAtrDoc];
				$complemento = $colecao [vopessoa::$nmAtrObservacao];
				$campoContratado = getCampoContratada ( $nmpessoa, $docpessoa, $voContrato->sq,$complemento);
			} else {
				$campoContratado = "";
				$tamanhoColecao = count ( $colecao );
				for($i = 0; $i < $tamanhoColecao; $i ++) {
					$nmpessoa = $colecao [$i] [vopessoa::$nmAtrNome];
					$docpessoa = $colecao [$i] [vopessoa::$nmAtrDoc];
					$complemento = $colecao [$i] [vopessoa::$nmAtrObservacao];
					$campoContratado .= getCampoContratada ( $nmpessoa, $docpessoa, $voContrato->sq,$complemento ) . "<br>";
				}
			}
		}

		?>
<TR>
	<INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>"
		name="<?=vocontrato::$nmAtrAnoContrato?>"
		value="<?=$voContrato->anoContrato?>">
	<INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>"
		name="<?=vocontrato::$nmAtrCdContrato?>"
		value="<?=$voContrato->cdContrato?>">
	<INPUT type="hidden" id="<?=vocontrato::$nmAtrTipoContrato?>"
		name="<?=vocontrato::$nmAtrTipoContrato?>"
		value="<?=$voContrato->tipo?>">
	<TH class="campoformulario" nowrap width=1%>Contrato:</TH>
	<TD class="campoformulario" colspan=3>Número:&nbsp;&nbsp;&nbsp;&nbsp; <INPUT
		type="text" value="<?php echo($contrato);?>"
		class="camporeadonlyalinhadodireita" size="<?=strlen($contrato)+1?>"
		readonly>	
	<?php				
		// $voContrato = new vocontrato();
		if ($voContrato->cdEspecie == null) {
			$voContrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		}
		if ($voContrato->sqEspecie == null) {
			$voContrato->sqEspecie = 1;
		}
		
		if($isDetalharChaveCompleta){
			$str = getContratoDescricaoEspecie($voContrato);
			echo getDetalhamentoHTML("", "", $str);
			echo getInputHidden(vocontrato::$nmAtrCdEspecieContrato, vocontrato::$nmAtrCdEspecieContrato, $voContrato->cdEspecie);
			echo getInputHidden(vocontrato::$nmAtrSqEspecieContrato, vocontrato::$nmAtrSqEspecieContrato, $voContrato->sqEspecie);
		}		
		
		if ($temLupa) {
			
			if($detalharContratoInfo){
				$voContratoInfo = new voContratoInfo();
//				$vocontratoasdsa = new vocontrato();
				$voContratoInfo->cdContrato = $voContrato->cdContrato;
				$voContratoInfo->anoContrato = $voContrato->anoContrato;
				$voContratoInfo->tipo = $voContrato->tipo;
				
				echo getLinkPesquisa ( "../".voContratoInfo::getNmTabela()."/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $voContratoInfo->getValorChaveHTML() );
			}else{
				
				echo getLinkPesquisa ( "../contrato/detalharContrato.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chaveContrato );
			}
			
		}
		
		$voContratoInfoPK = voContratoInfo::getVOContratoInfoDeUmVoContrato($voContrato);
		try{
			$colecao = $voContratoInfoPK->dbprocesso->consultarPorChaveTela($voContratoInfoPK, false);
			$voContratoInfoPK->getDadosBanco($colecao);
			echo getTextoHTMLNegrito("Autorização:" . dominioAutorizacao::getHtmlDetalhamento("", "", $voContratoInfoPK->cdAutorizacao, false));
		}catch (excecaoChaveRegistroInexistente $ex){
			;			
		}
		
		$nmPaginaChamada = $_SERVER['PHP_SELF'];
		$temExecucaoPraMostrar = existeContratoMod(clone($voContrato));
		if($temExecucaoPraMostrar){
			$vlPercentualAcrescimo = getValorNumPercentualAcrescimoContrato(clone $voContrato);
			$vlPercentualSupressao = getValorNumPercentualAcrescimoContrato(clone $voContrato, true);

			echo getTextoHTMLNegrito(" Acréscimo: " . getMoeda($vlPercentualAcrescimo, 2) . "%");
			if($vlPercentualAcrescimo > normativos::$LIMITE_ACRESCIMO){
				echo getTextoHTMLDestacado("(ATENÇÃO: LIMITE EXCEDIDO)", "red", true);
			}
			echo getTextoHTMLNegrito(" |Supressão: " . getMoeda(abs($vlPercentualSupressao), 2) . "%");
			
			if(!existeStr1NaStr2("execucao.php", $nmPaginaChamada)){
				//if(!existeStr1NaStr2("execucao.php", $nmPaginaChamada)){
				$chaveContratoExecucao = $voContrato->anoContrato
				. constantes::$CD_CAMPO_SEPARADOR
				. $voContrato->cdContrato
				. constantes::$CD_CAMPO_SEPARADOR
				. $voContrato->tipo
				. constantes::$CD_CAMPO_SEPARADOR
				. "1";
				echo getTextoLink("Execução", "../contrato/execucao.php?chave=$chaveContratoExecucao", null, true);
			}
		}
		
		
		?>							
		<div id=""><?=$campoContratado?></div>
		<?php 
		if ($complementoDet!= null){
			echo $complementoDet;
		}
		?>
		</TD>
</TR>
<?php
	}
}
function getContratoDescricaoEspecie($voContrato){
	//var_dump($voContrato);
	if($voContrato->cdEspecie != null){
		if($voContrato->cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
			$strSqEspecie = $voContrato->sqEspecie . "º ";
		}
		$str = $strSqEspecie . dominioEspeciesContrato::getDescricaoStatic($voContrato->cdEspecie);
	}
	return $str;	
}
function getContratoEntradaDeDados($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $comChaveCompletaSeNulo = true) {
	return getContratoEntradaDeDadosMais ( $tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, null, $comChaveCompletaSeNulo );
}
function getContratoEntradaDeDadosMais($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $comChaveCompletaSeNulo = true) {
	$vocontrato = new vocontrato ();
	$vocontrato->tipo = $tipoContrato;
	$vocontrato->anoContrato = $anoContrato;
	$vocontrato->cdContrato = $cdContrato;
	return getContratoEntradaDeDadosVO ( $vocontrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, false, $comChaveCompletaSeNulo );
}
function getContratoEntradaDeDadosVO($vocontrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $isExibirContratadaSePreenchido, $comChaveCompletaSeNulo = true, $pIsAlterarDemanda=false) {
	return 	getContratoEntradaDeDadosVOGenerico($vocontrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $isExibirContratadaSePreenchido, $comChaveCompletaSeNulo, $pIsAlterarDemanda);
}

function getContratoEntradaDeDadosVOSimples($vocontrato, $nmClass = "camponaoobrigatorio", $isExibirContratadaSePreenchido, $pcomChaveCompleta=false, $pIsAlterarDemanda=false, $pTemInformacoesComplementares=false) {	
	 $pArray = array($vocontrato,$nmClass,$isExibirContratadaSePreenchido,$pcomChaveCompleta,$pIsAlterarDemanda,$pTemInformacoesComplementares);	
	return getContratoEntradaArray($pArray);
}

function getContratoEntradaArrayGenerico($pArray) {
	$vocontrato = $pArray[0];	
	$nmClass = $pArray[1];
	$isExibirContratadaSePreenchido = $pArray[2];
	$pcomChaveCompleta = $pArray[3];
	$pTemInformacoesComplementares= $pArray[4];
	$complementoHTML = $pArray[5];
	$arrayNmCamposFormularioContrato = $pArray[6];
	
	$pIsAlterarDemanda= false;

	if($arrayNmCamposContrato == null){
		$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
		$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
		$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
		$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
		$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
		$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	}else{
		$pNmCampoCdContrato = $arrayNmCamposContrato[0];
		$pNmCampoAnoContrato = $arrayNmCamposContrato[1];
		$pNmCampoTipoContrato = $arrayNmCamposContrato[2];
		$pNmCampoCdEspecieContrato = $arrayNmCamposContrato[3];
		$pNmCampoSqEspecieContrato = $arrayNmCamposContrato[4];
		$nmCampoDivPessoaContratada = $arrayNmCamposContrato[5];
	}

	$chamadaFuncaoJS = "\"$complementoHTML\"";

	$required = "";

	if($nmClass == null){
		$nmClass = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	}
	//se nao for um array, cria o array de classes
	//se nao for o array, todos os componentes terao a mesma classe
	if (!is_array($nmClass)) {
		if ($nmClass == constantes::$CD_CLASS_CAMPO_OBRIGATORIO) {
			$required = "required";
		}		
		$arrayCssClass = array (
				$nmClass,
				$nmClass,
				$nmClass,
				$nmClass,
				$nmClass
		);		
	}else{
		$arrayCssClass = $nmClass;
	}
	
	//se nao for o array, todos os componentes terao o mesmo javascript
	if($complementoHTML != null){
		if (!is_array($complementoHTML)) {
			$arrayComplementoHTML = array (
					" $required onChange=$chamadaFuncaoJS ",
					" $required onBlur=$chamadaFuncaoJS ",
					" $required onChange=$chamadaFuncaoJS ",
					" $required onChange=$chamadaFuncaoJS ",
					" $required onBlur=$chamadaFuncaoJS ",
					);
		}else{
			$arrayComplementoHTML = $complementoHTML;
			for ($i=0; $i < count($arrayComplementoHTML);$i++){
				$htmlAtual = $arrayComplementoHTML[$i];
				$htmlAtual = "$htmlAtual $required ";
				$arrayComplementoHTML[$i] = $htmlAtual ;			
			}
		}
	}
	
	return 	getContratoEntradaDeDadosVOGenerico($vocontrato, $arrayCssClass, $arrayComplementoHTML, null, $isExibirContratadaSePreenchido, $pcomChaveCompleta, $pIsAlterarDemanda,$pcomChaveCompleta);
}

function getContratoEntradaArray($pArray) {
	$vocontrato = $pArray[0];
	$nmClass = $pArray[1];
	$isExibirContratadaSePreenchido = $pArray[2];
	$pcomChaveCompleta = $pArray[3];
	$pIsAlterarDemanda= $pArray[4];
	$pTemInformacoesComplementares= $pArray[5];
	$complementoHTML = $pArray[6];
		
	$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
	$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
	$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
	$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	
	$indiceJS = $indice;	
	$indiceJS = "''";
	
	$chamadaFuncaoJS = "\"carregaContratada($indiceJS, '$pNmCampoCdContrato', '$pNmCampoAnoContrato', '$pNmCampoTipoContrato', '$pNmCampoCdEspecieContrato', '$pNmCampoSqEspecieContrato', '$nmCampoDivPessoaContratada');$complementoHTML\"";
	
	$required = "";
	if ($nmClass == constantes::$CD_CLASS_CAMPO_OBRIGATORIO) {
		$required = "required";
	}
		
	$arrayCssClass = array (
				$nmClass,
				$nmClass,
				$nmClass,
				$nmClass,
				$nmClass
	);
		
	$arrayComplementoHTML = array (
				" $required onChange=$chamadaFuncaoJS ",
				" $required onBlur=$chamadaFuncaoJS ",
				" $required onChange=$chamadaFuncaoJS ",
				" $required onChange=$chamadaFuncaoJS ",
				" $required onBlur=$chamadaFuncaoJS ",
	);
	
	return 	getContratoEntradaDeDadosVOGenerico($vocontrato, $arrayCssClass, $arrayComplementoHTML, null, $isExibirContratadaSePreenchido, $pcomChaveCompleta, $pIsAlterarDemanda,$pcomChaveCompleta);
}
function getContratoEntradaDeDadosVOGenerico($vocontrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $isExibirContratadaSePreenchido, $comChaveCompletaSeNulo = true, $pIsAlterarDemanda=false, $pcomChaveCompleta=false) {	
	//$vocontrato = new vocontrato();
	if ($vocontrato != null) {
		$tipoContrato = $vocontrato->tipo;
		$cdContrato = $vocontrato->cdContrato;
		$anoContrato = $vocontrato->anoContrato;
		$cdEspecie = $vocontrato->cdEspecie;
		$sqEspecie = $vocontrato->sqEspecie;		
	}
				
	$isOpcaoMultiplos = $indiceContrato != null;
	
	$combo = new select ( dominioTipoContrato::getColecao () );
	$comboEspecie = new select ( dominioEspeciesContrato::getColecao () );
	$selectExercicio = new selectExercicio ();
	
	$cssTipoContrato = $arrayCssClass [0];
	$cssCdContrato = $arrayCssClass [1];
	$cssAnoContrato = $arrayCssClass [2];
	$cssCdEspecieContrato = $arrayCssClass [3];
	$cssSqEspecieContrato = $arrayCssClass [4];
	
	$htmlTipoContrato = $arrayComplementoHTML [0];
	$htmlCdContrato = $arrayComplementoHTML [1];
	$htmlAnoContrato = $arrayComplementoHTML [2];
	$htmlCdEspecieContrato = $arrayComplementoHTML [3];
	$htmlSqEspecieContrato = $arrayComplementoHTML [4];
	
	// parametros para a recuperacao de dados
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	if ($isOpcaoMultiplos) {
		$nmCampoDivNovoContrato = vocontrato::$ID_REQ_CAMPO_CONTRATO . $indiceContrato;
		$nmCampoDivContratoAnterior = vocontrato::$ID_REQ_CAMPO_CONTRATO . ($indiceContrato - 1);
		$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome . $indiceContrato;
	}
	
	$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
	$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
	$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
	$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
	
	$pIDCampoCdContrato = $pNmCampoCdContrato . $indiceContrato;
	$pIDCampoAnoContrato = $pNmCampoAnoContrato . $indiceContrato;
	$pIDCampoTipoContrato = $pNmCampoTipoContrato . $indiceContrato;
	$pIDCampoCdEspecieContrato = $pNmCampoCdEspecieContrato . $indiceContrato;
	$pIDCampoSqEspecieContrato = $pNmCampoSqEspecieContrato . $indiceContrato;
	
	$strCamposALimparSeparador = $pIDCampoCdContrato . "*" . $pIDCampoAnoContrato . "*" . $pIDCampoTipoContrato;
	
	/*
	 * $nmCampoDivPessoaContratada .= $indiceContrato;
	 * $nmCampoDivNovoContrato .= $indiceContrato;
	 * $pNmCampoCdContrato .= $indiceContrato;
	 * $pNmCampoAnoContrato .= $indiceContrato;
	 * $pNmCampoTipoContrato .= $indiceContrato;
	 * $pNmCampoCdEspecieContrato .= $indiceContrato;
	 * $pNmCampoSqEspecieContrato .= $indiceContrato;
	 */
	
	echo $combo->getHtmlCombo ( $pIDCampoTipoContrato, $pNmCampoTipoContrato, $tipoContrato, true, $cssTipoContrato, false, $htmlTipoContrato );
	?>
	Número:
<INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)"
	id="<?=$pIDCampoCdContrato?>" name="<?=$pNmCampoCdContrato?>"
	value="<?php echo(complementarCharAEsquerda($cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"
	class="<?=$cssCdContrato?>" size="4" maxlength="3" <?=$htmlCdContrato?>>
<?php
	echo "Ano: " . $selectExercicio->getHtmlCombo ( $pIDCampoAnoContrato, $pNmCampoAnoContrato, $anoContrato, true, $cssAnoContrato, false, $htmlAnoContrato );
	$nmCamposContrato = array(vocontrato::$nmAtrAnoContrato.$indiceContrato,
			vocontrato::$nmAtrCdContrato.$indiceContrato,
			vocontrato::$nmAtrTipoContrato.$indiceContrato,
			vocontrato::$nmAtrCdEspecieContrato.$indiceContrato,
			vocontrato::$nmAtrSqEspecieContrato.$indiceContrato,
	);
	$jsComplementarBorracha = "document.getElementById('$nmCampoDivPessoaContratada').innerHTML='';";
	echo getBorracha($nmCamposContrato, $jsComplementarBorracha);
	
	if($pIsAlterarDemanda)
		$paramAlterarDemanda = "true";
	else
		$paramAlterarDemanda = "false";
	
	if ($isOpcaoMultiplos) {
		echo "&nbsp;" . getImagemLink ( "javascript:carregaNovoCampoContrato('$nmCampoDivNovoContrato', $indiceContrato);\" ", "sinal_mais.gif" );
		echo "&nbsp;" . getImagemLink ( "javascript:limparCampoContrato('$nmCampoDivContratoAnterior', $indiceContrato, '$nmCampoDivPessoaContratada', '$strCamposALimparSeparador',$paramAlterarDemanda);\" ", "sinal_menos.gif" );
	}
		
	if($pcomChaveCompleta){
	?>
		<br>
		<INPUT type="text" id="<?=vocontrato::$nmAtrSqEspecieContrato?>" name="<?=vocontrato::$nmAtrSqEspecieContrato?>" value="<?=$sqEspecie;?>"  class="<?=$cssSqEspecieContrato?>" size="3" maxlength=2 <?=$htmlSqEspecieContrato?>> º
		<?php				
		//cria o combo
		$combo = new select(dominioEspeciesContrato::getColecao());
		echo $combo->getHtmlCombo(vocontrato::$nmAtrCdEspecieContrato, vocontrato::$nmAtrCdEspecieContrato, $cdEspecie, true, $cssCdEspecieContrato, false, $htmlCdEspecieContrato);
		
	}else if ($comChaveCompletaSeNulo) {
		
		/*$dbcontrato = new dbContratoInfo();
		$vocontratochave = new vocontrato();
		$vocontratochave = $dbcontrato->consultarPorChaveVO($vocontrato);*/		
		
	?>
		<INPUT type="hidden"
			id="<?=$pNmCampoCdEspecieContrato.$indiceContrato?>"
			name="<?=$pNmCampoCdEspecieContrato?>"
			value="<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>">
		<INPUT type="hidden"
			id="<?=$pNmCampoSqEspecieContrato.$indiceContrato?>"
			name="<?=$pNmCampoSqEspecieContrato?>" value="1">
	<?php
	}
	
?>		
<div id="<?=$nmCampoDivPessoaContratada?>">
<?php
	if ($isExibirContratadaSePreenchido && $vocontrato != null && $vocontrato->anoContrato != null) {
		
		if($vocontrato->cdEspecie == null){
			$vocontrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;			
		}
		if($vocontrato->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
			$vocontrato->sqEspecie = 1;
		}
		
		$chaveContrato = $vocontrato->getValorChaveHTML ();		
		echo getDadosContratada ( $chaveContrato );		
	}	
	//echo getLinkPesquisa ( "../contrato/detalharContrato.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chaveContrato );
	?>
</div>
<div id="<?=$nmCampoDivNovoContrato?>"></div>
<?php
}
function getCdAutorizacaoMaisRecenteContrato($voContrato) {
	$colecao = consultarPessoasContrato ( $voContrato );
	return getContratoDetalhamento ( $voContrato, $colecao );
}
function consultarDadosContratoCompilado($voContrato) {
	$retorno = "";
	
	if (isContratoValido ( $voContrato )) {
		$nmTabela = $voContrato->getNmTabelaEntidade ( false );
		
		$nmAtributosWhere = array (
				vocontrato::$nmAtrAnoContrato => $voContrato->anoContrato,
				vocontrato::$nmAtrCdContrato => $voContrato->cdContrato,
				vocontrato::$nmAtrTipoContrato => "'$voContrato->tipo'" 
		);
		
		$query = "SELECT * ";
		$query .= "\n FROM " . $nmTabela;
		$query .= "\n WHERE ";
		$query .= $voContrato->getValoresWhereSQL ( $voContrato, $nmAtributosWhere );
		$query .= "\n ORDER BY " . vocontrato::$nmAtrSqContrato;
		
		$db = new dbcontrato ();
		$retorno = $db->consultarEntidade ( $query, false );
		$retorno = $retorno [0];
	}
	
	// echo $query;
	return $retorno;
}
function getCampoDadosContratoSimples($nmClass = "camponaoobrigatorio", $complementoHTML=null,$comChaveCompletaSeNulo = true) {	
	return getCampoDadosContratoMultiplosPorIndice ( null, $nmClass,$complementoHTML,$comChaveCompletaSeNulo);
}
//function getCampoDadosContratoMultiplos($nmClass = "campoobrigatorio") {
function getCampoDadosContratoMultiplos($isCampoObrigatorio = true) {	
	//porque a funcao pode receber tanto um booleano quanto a string do nome da class
	if($isCampoObrigatorio || $isCampoObrigatorio == constantes::$CD_CLASS_CAMPO_OBRIGATORIO){
		$nmClass = constantes::$CD_CLASS_CAMPO_OBRIGATORIO;
	}else{
		$nmClass = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	}
	
	$indiceQtdContrato = 1;
	$html = getCampoDadosContratoMultiplosPorIndice ( $indiceQtdContrato, $nmClass );
	// $html .= "<INPUT type='hidden' id='". vocontrato::$ID_REQ_QTD_CONTRATOS . "' name='" . vocontrato::$ID_REQ_QTD_CONTRATOS . "' value='".$indiceQtdContrato."'>";
	return $html;
}
function getCampoDadosContratoMultiplosPorIndice($indice, $nmClass = "camponaoobrigatorio", $complementoHTML=null, $comChaveCompletaSeNulo = true) {
	return getCampoDadosVariosContrato ( "", "", "", $indice, $nmClass,$complementoHTML,$comChaveCompletaSeNulo);
}
function getCampoDadosColecaoContratos($colecaoContrato, $isExibirContratadaSePreenchido, $nmClass = "camponaoobrigatorio", $pIsAlterarDemanda=false) {	
	// var_dump($colecaoContrato);
	$i = 1;
	if (! isColecaoVazia ( $colecaoContrato )) {
		$html = "";		
		foreach ( $colecaoContrato as $vocontrato ) {
			
			$html .= getCampoDadosContratoVOPorIndice ( $vocontrato, $i, $isExibirContratadaSePreenchido, $nmClass, true, null, $pIsAlterarDemanda);
			
			$i ++;
		}
	} else {
		// caso nao haja contrato, abrira um contrato em branco para ser incluido
		$html = getCampoDadosContratoVOPorIndice ( null, $i, $isExibirContratadaSePreenchido, $nmClass, true,null,$pIsAlterarDemanda );
	}
}
function getCampoDadosVariosContrato($tipoContrato, $cdContrato, $anoContrato, $indice, $nmClass = "camponaoobrigatorio", $complementoHTML=null, $comChaveCompletaSeNulo = true) {
	
	$vocontrato = new vocontrato ();
	$vocontrato->tipo = $tipoContrato;
	$vocontrato->anoContrato = $anoContrato;
	$vocontrato->cdContrato = $cdContrato;
		
	return getCampoDadosContratoVOPorIndice ( $vocontrato, $indice, false, $nmClass, $comChaveCompletaSeNulo, $complementoHTML);
}
function getCampoDadosContratoVOPorIndice($vocontrato, $indice, $isExibirContratadaSePreenchido, $nmClass = "camponaoobrigatorio", $comChaveCompletaSeNulo = true, $complementoHTML=null, $pIsAlterarDemanda=false) {
	
	/*
	 * $tipoContrato = $vocontrato->tipo;
	 * $cdContrato = $vocontrato->cdContrato;
	 * $anoContrato = $vocontrato->anoContrato;
	 * asdas
	 */
	$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
	$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
	$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
	$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	
	$indiceJS = $indice;
	if ($indice == null)
		$indiceJS = "''";
	
	$chamadaFuncaoJS = "\"carregaContratada($indiceJS, '$pNmCampoCdContrato', '$pNmCampoAnoContrato', '$pNmCampoTipoContrato', '$pNmCampoCdEspecieContrato', '$pNmCampoSqEspecieContrato', '$nmCampoDivPessoaContratada');$complementoHTML\"";
	
	$required = "";
	if ($nmClass == constantes::$CD_CLASS_CAMPO_OBRIGATORIO) {
		$required = "required";
	}
	
	//a class sera sempre nao obrigatorio
	//pois o atributo required eh utilizado para alterar a class do css
	$nmClass = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	
	$arrayCssClass = array (
			$nmClass,
			$nmClass,
			$nmClass 
	);
	$arrayComplementoHTML = array (
			" $required onChange=$chamadaFuncaoJS ",
			" $required onBlur=$chamadaFuncaoJS ",
			" $required onChange=$chamadaFuncaoJS " 
	);
	
	// $html = getContratoEntradaDeDadosMais ( $tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $indice );
	$html = getContratoEntradaDeDadosVO ( $vocontrato, $arrayCssClass, $arrayComplementoHTML, $indice, $isExibirContratadaSePreenchido, $comChaveCompletaSeNulo,$pIsAlterarDemanda);
	return $html;
}
/**
 * @deprecated
 * @param unknown $cdProcLic
 * @param unknown $anoProcLic
 * @param unknown $arrayCssClass
 * @param unknown $arrayComplementoHTML
 */
function getProcLicitatorioEntradaDados($cdProcLic, $anoProcLic, $arrayCssClass, $arrayComplementoHTML) {
	$selectExercicio = new selectExercicio ();
	$cssCdProcLic = $arrayCssClass [0];
	$cssAnoProcLic = $arrayCssClass [1];
	
	$htmlCdProcLic = $arrayComplementoHTML [0];
	$htmlAnoProcLic = $arrayComplementoHTML [1];
	
	$pNmCampoCdProcLicitatorio = voProcLicitatorio::$nmAtrCd;
	$pNmCampoAnoProcLicitatorio = voProcLicitatorio::$nmAtrAno;
	
	echo "Número: <INPUT type='text' onkeyup='validarCampoNumericoPositivo(this)' id='" . $pNmCampoCdProcLicitatorio . "' name='" . $pNmCampoCdProcLicitatorio . "'  value='" . complementarCharAEsquerda ( $cdProcLic, "0", TAMANHO_CODIGOS_SAFI ) . "'  class='" . $cssCdProcLic . "' size='5' maxlength='5'  " . $htmlCdProcLic . ">";
	echo "&nbsp;Ano: " . $selectExercicio->getHtmlCombo ( $pNmCampoAnoProcLicitatorio, $pNmCampoAnoProcLicitatorio, $anoProcLic, true, $cssAnoProcLic, false, $htmlAnoProcLic );
}
function consultarContratosDemanda($voDemanda) {
	$db = new dbDemanda ();
	$colecao = $db->consultarDemandaContrato ( $voDemanda );
	return $colecao;
}
function consultarDadosHTMLDemanda($voDemanda) {
	$db = new dbDemanda ();
	$colecao = $db->consultarDadosDemanda ( $voDemanda );
	return $colecao;
}
function consultarContratosPAAP($voPAAP) {
	$db = new dbPA();
	$colecao = $db->consultarContratoPAAP ( $voPAAP );
	return $colecao;
}

function getDadosContratoMod($chave) {
	//echo $chave;
	if ($chave != null && $chave != "") {
		$vo = new vocontrato ();
		$vo->getChavePrimariaVOExplodeParam ( $chave );
		
		$array = explode ( CAMPO_SEPARADOR, $chave );
		//definido em manter.php vocontratomod carregaDadosContrato();
		$dtEfeitoModificacao = $array[6];
		$tipoModificacao = $array[7];
		
		$arrayParamComplemento = array($dtEfeitoModificacao, $tipoModificacao);
		//echo $chave;
		//var_dump($vo);
		try{
			$retorno = getCamposContratoMod($vo, $arrayParamComplemento);
		}catch(excecaoChaveRegistroInexistente $ex){
			$retorno = "Dados: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";		
		}catch(excecaoMaisDeUmRegistroRetornado $ex){
			$retorno = "Dados: <INPUT type='text' class='camporeadonly' size=50 readonly value='MAIS DE UM T.A. VIGENTE - VERIFIQUE O CONTRATO'>\n";
		}			

	}

	return $retorno;
}

function getDadosContratoLicon($chave) {
	//echo $chave;
	if ($chave != null && $chave != "") {		
			$vo = new vocontrato ();
			$vo->getChavePrimariaVOExplodeParam ( $chave );
			$dbcontrato = new dbcontrato();
			
			try{
				$recordSet = $dbcontrato->consultarPorChaveTela($vo, false);
				$retorno = getCamposContratoLicon($recordSet);
			}catch(excecaoChaveRegistroInexistente $ex){
				$retorno = "Dados: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";
			}
						
	}

	return $retorno;
}

function getContratoMater($vocontrato){
	$db = new dbcontrato();
	//$vocontrato = new vocontrato();
	/*$filtro = new filtroManterContrato();
	$filtro->tipo = $vocontrato->tipo;
	$filtro->cdContrato = $vocontrato->cdContrato;
	$filtro->anoContrato = $vocontrato->anoContrato;
	$filtro->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
	//$filtro->sqEspecie = 1
	
	$recordset = $db->consultarFiltroManter($filtro, false);	
	*/
	
	$vocontrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
	$vocontrato->sqEspecie = 1;
	$recordset = $db->consultarContratoPorChave($vocontrato, false);	
	if(isColecaoVazia($recordset)){
		throw new excecaoChaveRegistroInexistente("BiblioHtmlContrato. getContratoMater.");
	}

	if(count($recordset) > 2){
		throw new excecaoMaisDeUmRegistroRetornado();
	}
	return $recordset;
	
}

/**
 * retorna a consulta de consolidacao de um contrato especifico
 * @param unknown $vocontrato
 * @throws excecaoChaveRegistroInexistente
 * @return unknown|string
 */
function getContratoConsolidacao($vocontrato){
	//$vocontrato = new vocontrato();
	$filtro = new filtroConsultarContratoConsolidacao();
	$filtro->tipo = $vocontrato->tipo;
	$filtro->cdContrato = $vocontrato->cdContrato;
	$filtro->anoContrato = $vocontrato->anoContrato;

	$db = new dbContratoInfo();
	$recordset = $db->consultarTelaConsultaConsolidacao($filtro);
	if(isColecaoVazia($recordset)){
		throw new excecaoChaveRegistroInexistente("BiblioHtmlContrato. getContratoConsolidacao.");
		throw new excecaoChaveRegistroInexistente("BiblioHtmlContrato. " . __FUNCTION__);		
	}

	return $recordset;
	//var_dump($recordset);
}

function getContratoVigentePorData($vocontrato, $pData = null, $isTpVigenciaMAxSq=false){
	//$vocontrato = new vocontrato();	
	$filtro = new filtroManterContrato();
	$filtro->tipo = $vocontrato->tipo;
	$filtro->cdContrato = $vocontrato->cdContrato;
	$filtro->anoContrato = $vocontrato->anoContrato;
	$filtro->cdEspecie = array(
			dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER,
			dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO
	);
	/*if($pData == null){
		$pData = $vocontrato->dtAssinatura;
	}*/	
	if($pData == null){
		$filtro->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		//$filtro->sqEspecie = 1;
	}	
		
	$filtro->isTpVigenciaMAxSq = $isTpVigenciaMAxSq;
	$filtro->dtVigencia = $pData;
	
	$db = new dbcontrato();
	$recordset = $db->consultarFiltroManter($filtro, false);
	if(isColecaoVazia($recordset)){
		throw new excecaoChaveRegistroInexistente("BiblioHtmlContrato. getContratoVigentePorData.");
	}
	
	if(count($recordset) > 2){
		throw new excecaoMaisDeUmRegistroRetornado();
	}
	
	return $recordset;
	//var_dump($recordset);	
}

function getCamposContratoMod($vo, $arrayParamComplemento = null){
	$voTermoInseridoTela = clone $vo;
	
	if($arrayParamComplemento != null){
		$dtEfeitoModificacao = $arrayParamComplemento[0];
		$tipoModificacao = $arrayParamComplemento[1];
	}
		
	$dbcontrato = new dbcontrato();
	$registrobanco = $dbcontrato->consultarContratoModificacao($vo, false);
	
	$voContrato = new vocontrato();
	$voContrato->getDadosBanco($registrobanco);

	$voContratoInfo = new voContratoInfo();
	$voContratoInfo->getDadosBanco($registrobanco);
	
	$registroMater = getContratoMater($vo)[0];
	$voContratoMater = new vocontrato();
	$voContratoMater->getDadosBanco($registroMater);
	//var_dump($registrobanco);
	
	$vlMensalAtualizadoParaFinsMod = getMoeda($registrobanco[voContratoModificacao::$nmAtrVlMensalModAtual]); 
	$vlGlobalAtualizadoParaFinsMod = getMoeda($registrobanco[voContratoModificacao::$nmAtrVlGlobalModAtual]);
		
	$vo->dtAssinatura = getData($registrobanco[vocontrato::$nmAtrDtAssinaturaContrato]);

	//consulta o periodo de vigencia do termo inserido apenas se ele nao for um TA
	//quando nesse caso, sendo apostilamento ou qualquer outro, o periodo de vigencia sera determinado pelo TA vigente na data de assinatura
	//if($vo->cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO){
	$voContratoReferencia = clone $voContrato;

	$registro = getContratoVigentePorData($vo, $dtEfeitoModificacao, true)[0];

	//aqui eh o termo inserido na tela
	$voTermoInseridoTela = $dbcontrato->consultarPorChaveVO($voTermoInseridoTela, false);
	//echo $vo->toString();
	$vlMensalTermoInseridoTela = $voTermoInseridoTela->vlMensal;
	$vlGlobalTermoInseridoTela = $voTermoInseridoTela->vlGlobal;
		
	//aqui determina a vigencia do termo inserido na tela
	$voContratoTemp = new vocontrato();
	$voContratoTemp->getDadosBanco($registro);	
					
	$voContrato->dtVigenciaInicial = $voContratoTemp->dtVigenciaInicial;
	$voContrato->dtVigenciaFinal = $voContratoTemp->dtVigenciaFinal;
	/*$voContrato->vlMensal = $voContratoTemp->vlMensal;
	$voContrato->vlGlobal = $voContratoTemp->vlGlobal;*/
	$retorno = getTextoHTMLNegrito("VIGÊNCIA"). " determinada pelo " . getTextoHTMLNegrito(getContratoDetalhamentoAvulso($voContratoTemp, true)) . "<br>";
	$voContratoReferencia = clone $voContratoTemp;
		//var_dump($voContratoReferencia);
		
	//traz o valor atualizado do contrato segundo o e-conti
	$dbcontratomod = new dbContratoModificacao();
	$registroExecucao = $dbcontratomod->consultarExecucaoTermoEspecifico($voContratoMater, $dtEfeitoModificacao);	
	$voContratoModEspecificoReajustado = $registroExecucao[filtroManterContratoModificacao::$NmColVOContratoModReajustado];
	
	if($voContratoModEspecificoReajustado->vlMensalAtual != null){		
		$retorno .= getTextoHTMLNegrito("EXECUÇÃO"). " determinada pelo "
			. getTextoHTMLNegrito(getContratoDetalhamentoAvulso($voContratoModEspecificoReajustado->vocontrato, true));
		if($voContratoModEspecificoReajustado->sq != null){
			$retorno.= " SeqMod.nº. ".$voContratoModEspecificoReajustado->sq;
		}
		$retorno.= "<br>";
		//serve para buscar os valores de execucao atual
		//echo "entrou";
		$voContrato->vlMensal = getMoeda($voContratoModEspecificoReajustado->vlMensalAtual, 2);
		//$voContrato->vlMensal = getMoeda("2000", 2);
		$voContrato->vlGlobal = getMoeda($voContratoModEspecificoReajustado->vlGlobalAtual,2);
	}else{	
		$voContrato = $voContratoReferencia;
	}	

	$vlMensalExecucao = $voContrato->vlMensal;
	if($voContrato != null){
		$numMesesPeriodoTermo = 12;
		$numMesesPeriodoTermo = getQtdMesesEntreDatas($voContrato->dtVigenciaInicial, $voContrato->dtVigenciaFinal);
		$numMesesPeriodoMater = getQtdMesesEntreDatas($voContratoMater->dtVigenciaInicial, $voContratoMater->dtVigenciaFinal);
		$numMesesUltimaProrrogacao = $numMesesPeriodoMater;
		
		$retorno .= " Data Assinatura: " . getInputText(vocontrato::$nmAtrDtAssinaturaContrato, vocontrato::$nmAtrDtAssinaturaContrato, getData($voContrato->dtAssinatura), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= ", Vigência de " . getInputText(vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaInicialContrato, getData($voContrato->dtVigenciaInicial), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= " a " . getInputText(vocontrato::$nmAtrDtVigenciaFinalContrato, vocontrato::$nmAtrDtVigenciaFinalContrato, getData($voContrato->dtVigenciaFinal), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= ", Prazo Termo (meses): " . getInputText(voContratoInfo::$nmAtrNumPrazo, voContratoInfo::$nmAtrNumPrazo, $numMesesPeriodoTermo, constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= "<br>Prazo Contrato Original (meses): " . getInputText(voContratoInfo::$nmAtrNumPrazoMater, voContratoInfo::$nmAtrNumPrazoMater, $numMesesPeriodoMater, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, null, null, "");
		$retorno .= ", Prazo Última Prorrogação (meses): " . getInputText(voContratoModificacao::$ID_REQ_NumPrazoUltimaProrrogacao, voContratoModificacao::$ID_REQ_NumPrazoUltimaProrrogacao, $numMesesUltimaProrrogacao, constantes::$CD_CLASS_CAMPO_READONLY);
		
		$jsCopiaVlMensalReajuste = "document.frm_principal." . voContratoModificacao::$ID_REQ_VL_BASE_REAJUSTE . ".value = this.value;";
		$javaScriptOnKeyUpMoeda = " onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 2, event);$jsCopiaVlMensalReajuste;' ";
		$javaScriptOnBlurVlMensalExecucao= " onBlur='getValorGlobalDoMensal(this, document.frm_principal.".vocontrato::$nmAtrVlGlobalContrato.");' ";
		
		//$retorno .= "<br>Valor Mensal Referência: " . getInputText(vocontrato::$nmAtrVlMensalContrato, vocontrato::$nmAtrVlMensalContrato, $voContrato->vlMensal, constantes::$CD_CLASS_CAMPO_READONLY, null, null, " onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 4, event);' ");
		$retorno .= "<br>Valor Mensal Execução: " . getInputText(vocontrato::$nmAtrVlMensalContrato, vocontrato::$nmAtrVlMensalContrato, $vlMensalExecucao, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, null, null, "$javaScriptOnKeyUpMoeda $javaScriptOnBlurVlMensalExecucao");
		$retorno .= " Valor Mensal Termo: " . getInputText(voContratoModificacao::$ID_REQ_VlMensalContratoInseridoTela, voContratoModificacao::$ID_REQ_VlMensalContratoInseridoTela, $vlMensalTermoInseridoTela, constantes::$CD_CLASS_CAMPO_READONLY);
		//$retorno .= " Valor Mensal Referência (%Acréscimos): " . getInputText(voContratoModificacao::$nmAtrVlMensalModAtual, voContratoModificacao::$nmAtrVlMensalModAtual, $vlMensalAtualizadoParaFinsMod, constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= " Valor Mensal Referência (%Acréscimos): " . getInputText(voContratoModificacao::$nmAtrVlMensalModAtual, voContratoModificacao::$nmAtrVlMensalModAtual, $vlMensalAtualizadoParaFinsMod, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, null, null, $javaScriptOnKeyUpMoeda);

		$retorno .= "<br>Valor Global Execução: " . getInputText(vocontrato::$nmAtrVlGlobalContrato, vocontrato::$nmAtrVlGlobalContrato, $voContrato->vlGlobal, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, null, null, $javaScriptOnKeyUpMoeda);
		$retorno .= " Valor Global Termo: " . getInputText(voContratoModificacao::$ID_REQ_VlGlobalContratoInseridoTela, voContratoModificacao::$ID_REQ_VlGlobalContratoInseridoTela, $vlGlobalTermoInseridoTela, constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= " Valor Global Referência (%Acréscimos): " . getInputText(voContratoModificacao::$nmAtrVlGlobalModAtual, voContratoModificacao::$nmAtrVlGlobalModAtual, $vlGlobalAtualizadoParaFinsMod, constantes::$CD_CLASS_CAMPO_READONLY);
		
		
		//coloca informacao sobre escopo
		$voContratoInfoTemp = new voContratoInfo(); 
		$voContratoInfoTemp->getDadosBanco($registroMater);
		$retorno .= getHTMLContratoPorEscopo($voContratoInfoTemp);
	}

	return $retorno;
}

function getHTMLContratoPorEscopo($vocontratoinfo){
	$inEscopo = $vocontratoinfo->inEscopo;
	$isContratoPorEscopo =  $inEscopo == constantes::$CD_SIM;
	
	$textoContratoEscopo = "Informação 'Contrato por Escopo' inexistente";
	if($inEscopo != null){
		if($isContratoPorEscopo){
			$textoContratoEscopo = "Contrato por Escopo (incluir valor adicional/suprimido em 'Valor Modificação ao Contrato')";
		}else{
			$textoContratoEscopo = "NÃO É Contrato por Escopo";
		}
		$retorno .= getInputHidden(voContratoInfo::$nmAtrInEscopo, voContratoInfo::$nmAtrInEscopo, $inEscopo);
	}
	
	$retorno .= "<br>". getTextoHTMLDestacado($textoContratoEscopo, "red");
	return $retorno;	
}

function getCamposContratoLicon($recordSet){
	$registrobanco = $recordSet;
	$voContrato = new vocontrato();
	$voContrato->getDadosBanco($registrobanco);
	
	$voContratoInfo = new voContratoInfo();
	$voContratoInfo->getDadosBanco($registrobanco);		
	
	if($voContrato != null){		
		$retorno = "Data Vigência Inicial " . getInputText("", "", getData($voContrato->dtVigenciaInicial), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= " a Data Final " . getInputText("", "", getData($voContrato->dtVigenciaFinal), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= "<br>Data Publicação " . getInputText("", "", getData($voContrato->dtPublicacao), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= "<br>Data Assinatura " . getInputText("", "", getData($voContrato->dtAssinatura), constantes::$CD_CLASS_CAMPO_READONLY);
		$retorno .= "<br>PL " . getInputText("", "", $voContrato->procLic, constantes::$CD_CLASS_CAMPO_READONLY);
				
		if($voContrato->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
			/*$portaria = getCPLPorNomePregoeiro($recordSet);
			$nmPregoeiro = $recordSet[voProcLicitatorio::$NmColNomePregoeiro];
			if($portaria != null && $portaria != ""){
				$complemento .= "<br>CPL: " . $portaria;
				if($nmPregoeiro != null){
					$complemento .= "-" . $nmPregoeiro;
				}
				
				$complemento = getTextoHTMLNegrito($complemento);
			}
			$retorno .= $complemento;
			$retorno .= "<br><br>Todas Portarias:<br>" . dominioComissaoProcLicitatorio::getNumPortariaTodasCPL();*/
			
			$retorno .= getInformacaoCPL($registrobanco);
		}		
	}

	return $retorno;
}

function getInformacaoCPL($registroBanco, $mostrarTodasPortarias = true){
	$voContrato = new vocontrato ();
	$voContrato->getDadosBanco ( $registrobanco );
	
	if (! $mostrarTodasPortarias) {
		$anoPortaria = $voContrato->anoContrato;
	}
	
	$nmPregoeiro = $registroBanco [voProcLicitatorio::$NmColNomePregoeiro];
	$proclic = $registroBanco [vocontrato::$nmAtrProcessoLicContrato];
	//echo " pregoeiro $nmPregoeiro";
	if ($nmPregoeiro == null) {
		//var_dump($registroBanco);		
		//echo $proclic;
		$nmPregoeiro = dominioComissaoProcLicitatorio::getNmPregoeiroPorCPL ( $proclic );		
	} 
		
	$cpl = dominioComissaoProcLicitatorio::getDescricao($registroBanco [voProcLicitatorio::$nmAtrCdCPL]);
	if ($cpl == null || $cpl == "") {
		$cpl = dominioComissaoProcLicitatorio::getChaveDeUmaStringPorColecaoSimples($proclic, dominioComissaoProcLicitatorio::getColecaoSimplesApenasDescricao());
	}
	
	if ($cpl != null && $cpl != "") {
		$complemento .= "<br>CPL: " . $cpl;		
	}else if(existeStr1NaStr2("CCD", $proclic)){
		$complemento = "<br>CCD-Comissão de Compra Direta, criada pelo PEIntegrado. Incluir como Processo Administrativo!";
	}
	if ($nmPregoeiro != null) {
		$complemento .= "-" . $nmPregoeiro;
	}	
	$complemento = getTextoHTMLNegrito ( $complemento );
	$retorno .= $complemento;
	$retorno .= "<br><br>Todas Portarias:<br>" . dominioComissaoProcLicitatorio::getNumPortariaTodasCPL ( $anoPortaria );
	
	return $retorno;
}


function getValorNumPercentualAcrescimoContrato($vo, $isSupressao = false){
	$voContratomod = getVOContratoModAcrescimo($vo,$isSupressao);
	$retorno = 0;
	if($voContratomod != null){
		//$retorno = $voContratomod->getPercentualAcrescimoAtual();
		$retorno = $voContratomod->numPercentual;
	}
	return $retorno;
}

/**
 * Verifica se existe alguma modificacao registrada ao contrato
 * @param unknown $vo
 * @return voContratoModificacao
 */
function existeContratoMod($voParam){
	//usando o clone aqui para nao alterar a variagem voParam fora do escodo da funcao
	//tendo em vista que se esta suprimindo informacoes do voParam
	$vo = clone ($voParam);
	$vo->cdEspecie = null;
	$vo->sqEspecie = null;
	$filtro = new filtroManterContratoModificacao(false);
	$dbcontratomod = new dbContratoModificacao();

	$filtro->vocontrato = $vo;
	$filtro->cdAtrOrdenacao = voContratoModificacao::$nmAtrDhUltAlteracao;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
	$recordSet = $dbcontratomod->consultarTelaConsulta(new voContratoModificacao(), $filtro);

	return !isColecaoVazia($recordSet);
}

/**
 * Retorna o percentual de acrescimo ou supressao registrado ao contrato
 * @param unknown $vo
 * @param string $isSupressao
 * @return voContratoModificacao
 */
function getVOContratoModAcrescimo($vo, $isSupressao = false){
	$vo->cdEspecie = null;
	$vo->sqEspecie = null;

	$filtro = new filtroManterContratoModificacao(false);
	$dbcontratomod = new dbContratoModificacao();

	$filtro->vocontrato = $vo;
	//var_dump($filtro);
	$filtro->cdAtrOrdenacao = voContratoModificacao::$nmAtrDhUltAlteracao;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
	//supressao nao compensa!!
	if(!$isSupressao){
		$filtro->tipo = array(dominioTpContratoModificacao::$CD_TIPO_ACRESCIMO);
	}else{
		$filtro->tipo = array(dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO);
	}
	$recordSet = $dbcontratomod->consultarTelaConsulta(new voContratoModificacao(), $filtro);
	//var_dump($recordSet);
	if(!isColecaoVazia($recordSet)){
		$numAcrescimo = 0;
		foreach ($recordSet as $registro){
			$voContratomod = new voContratoModificacao();
			$voContratomod->getDadosBanco($registro);
			
			$numAcrescimo = floatval($voContratomod->numPercentual) + $numAcrescimo;
			//echoo($numAcrescimo);
		}
		
		$voContratomod->numPercentual = $numAcrescimo;
	}
	
	return $voContratomod;
}

/**
 * retorna os eventuais termos que podem alterar a vigencia do contrato
 * servirao como base para a verificacao dos reajustes
 * @param unknown $recordSetContratoMod
 * @return unknown[]
 */
function getColecaoContratosModVigencia($recordSetContratoMod) {
	$retornoTemp = array();
	foreach ( $recordSetContratoMod as $registro) {
		$voContrato = new vocontrato();
		$voContrato->getDadosBanco($registro);
		
		if (existeItemNoArray($voContrato->cdEspecie, dominioEspeciesContrato::getColecaoTermosQuePodemAlterarVigencia())) {
			$retornoTemp[] = $registro;
		}
	}

	return $retornoTemp;
}

/**
 * identifica os reajustes a serem aplicados ao registro atraves da analise da data
 * @param unknown $recordSet
 * @param unknown $voContratoModReajuste
 */
function getColecaoReajustesAAplicarContratoMod($voContrato, $recordSetReajuste) {
	$retornoTemp = array();
	$i = 0;
	//var_dump(voContratoModificacao::$ColecaoReajustesAplicados);echoo("");
	foreach ( $recordSetReajuste as $registro) {
						
		if (isReajusteAAplicarContratoMod($voContrato, $registro)) {
				$retornoTemp[] = $registro;
		}
		$i++;
	}

	return $retornoTemp;
}

/**
 * verifica se o reajuste deve ser aplicado ao contrato
 * @param unknown $voContratoModReajuste
 * @return boolean
 */
function isReajusteAAplicarContratoMod($voContratoAModificar, $registroModificacao){
	$voContratoModReajuste = new voContratoModificacao ();
	$voContratoModReajuste->getDadosBanco ( $registroModificacao );
	
	$voContratoModificacao = new vocontrato();
	$voContratoModificacao->getDadosBanco ( $registroModificacao );
	
	//$voContratoAModificar = new vocontrato();
	//echoo("Analisando se o reajuste  ".$voContratoModReajuste->getValorChavePrimariaContratoModCompleto()." sera aplicado ao ".$voContratoAModificar->toString(true));
	$retorno = false;
	if($voContratoModReajuste != null){
		$dtAssinaturaContratoAModificar = getDataSQL($voContratoAModificar->dtAssinatura);
		$dtAssinaturaReajuste = getDataSQL($voContratoModificacao->dtAssinatura);
		
		$dtEfeitosContratoAModificar = getDataSQL($voContratoAModificar->dtVigenciaInicial);
		$dtEfeitosContratoAModificarFinal = getDataSQL($voContratoAModificar->dtVigenciaFinal);
		$dtEfeitosReajuste = $voContratoModReajuste->dtModificacao;		
		
		/*echoo($voContratoModReajuste->toString(true));
		echoo("data assinatura a modificar vo:" . getData($dtAssinaturaContratoAModificar));
		echoo("data vigencia a modificar vo:" . getData($dtEfeitosContratoAModificar));
		
		echoo("data assinatura reajuste:" . getData($dtAssinaturaReajuste));
		echoo("data efeitos reajuste:" . getData($dtEfeitosReajuste));*/
			
		//para aplicar o reajuste precisa
		//o reajuste ter sido incluido depois
		//se referir a uma data anterior a data do contratomod a verificar
		$chave = $voContratoModReajuste->getValorChavePrimariaContratoModCompleto();
		if(!existeItemNoArray($chave, voContratoModificacao::$ColecaoReajustesAplicados)
				&& strtotime($dtAssinaturaContratoAModificar) <= strtotime($dtAssinaturaReajuste) //o reajuste veio depois
				&& strtotime($dtEfeitosContratoAModificarFinal) >= strtotime($dtEfeitosReajuste) //antes da data final de vigencia do termo a modificar
					//|| (strtotime($dtEfeitosContratoAModificar) >= strtotime($dtEfeitosReajuste) && strtotime($dtEfeitosReajuste) <= strtotime($dtEfeitosContratoAModificarFinal))) //o reajuste tem efeito retroativo								
				){
					//echoo("reajuste ao ".$voContratoAModificar->toString(true) . " aplicado pelo: ". $voContratoModReajuste->getValorChavePrimariaContratoModCompleto());
					//echoo("APLICADO");
					voContratoModificacao::$ColecaoReajustesAplicados[] = $chave;
					$retorno = true;
		}
	}
	return $retorno;
}

/**
 * registra a prorrogacao para que seja exibida na execucao
 * @param unknown $voContratoModificacao
 * @return boolean
 */
function isProrrogacaoNaoRegistrada($voContratoModificacao){
	$retorno = false;
	//$voContratoModificacao = new voContratoModificacao();
	$chave = $voContratoModificacao->getValorChavePrimariaContratoModCompleto();
	if($voContratoModificacao->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO
			&& !in_array($chave, voContratoModificacao::$ColecaoProrrogacoesRegistradas)
			){	
		//registra a prorrogacao
		voContratoModificacao::$ColecaoProrrogacoesRegistradas[] = $chave;
		$retorno = true;
	}
	
	return $retorno;
}

function getLinkPortarias() {
	$link = "../proc_licitatorio/portarias.php";
	return "Portarias " . getLinkPesquisa($link);
}


?>