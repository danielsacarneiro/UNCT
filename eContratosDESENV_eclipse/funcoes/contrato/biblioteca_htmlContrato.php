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
function getColecaoContratoDet($colecao) {
	$html = "";
	//var_dump($colecao);
	if(!isColecaoVazia($colecao)){
		foreach ( $colecao as $voContrato ) {
			$html .= getContratoDet ( $voContrato );
		}
	}else{
		$html = "NAO ENCONTRADO";
	}
	return $html;
}
function getContratoDetalhamento($voContrato, $colecao,  $detalharContratoInfo = false, $isDetalharChaveCompleta=false) {
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
			if (! isArrayMultiDimensional ( $colecao )) {
				$nmpessoa = $colecao [vopessoa::$nmAtrNome];
				$docpessoa = $colecao [vopessoa::$nmAtrDoc];
				$campoContratado = getCampoContratada ( $nmpessoa, $docpessoa, $voContrato->sq );
			} else {
				$campoContratado = "";
				$tamanhoColecao = count ( $colecao );
				for($i = 0; $i < $tamanhoColecao; $i ++) {
					$nmpessoa = $colecao [$i] [vopessoa::$nmAtrNome];
					$docpessoa = $colecao [$i] [vopessoa::$nmAtrDoc];
					$campoContratado .= getCampoContratada ( $nmpessoa, $docpessoa, $voContrato->sq ) . "<br>";
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
		?>							
				<div id=""><?=$campoContratado?></div></TD>
</TR>
<?php
	}
}
function getContratoDescricaoEspecie($voContrato){
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
// function getContratoEntradaDeDadosMais($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $comChaveCompletaSeNulo = true) {
function getContratoEntradaDeDadosVO($vocontrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $isExibirContratadaSePreenchido, $comChaveCompletaSeNulo = true, $pIsAlterarDemanda=false) {
	if ($vocontrato != null) {
		$tipoContrato = $vocontrato->tipo;
		$cdContrato = $vocontrato->cdContrato;
		$anoContrato = $vocontrato->anoContrato;
	}
	
	$isOpcaoMultiplos = $indiceContrato != null;
	
	$combo = new select ( dominioTipoContrato::getColecao () );
	$comboEspecie = new select ( dominioEspeciesContrato::getColecao () );
	$selectExercicio = new selectExercicio ();
	
	$cssTipoContrato = $arrayCssClass [0];
	$cssCdContrato = $arrayCssClass [1];
	$cssAnoContrato = $arrayCssClass [2];
	
	$htmlTipoContrato = $arrayComplementoHTML [0];
	$htmlCdContrato = $arrayComplementoHTML [1];
	$htmlAnoContrato = $arrayComplementoHTML [2];
	
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
	);
	echo getBorracha($nmCamposContrato, "");
	
	if($pIsAlterarDemanda)
		$paramAlterarDemanda = "true";
	else
		$paramAlterarDemanda = "false";
	
	if ($isOpcaoMultiplos) {
		echo "&nbsp;" . getImagemLink ( "javascript:carregaNovoCampoContrato('$nmCampoDivNovoContrato', $indiceContrato);\" ", "sinal_mais.gif" );
		
		// if($indiceContrato > 1){
		echo "&nbsp;" . getImagemLink ( "javascript:limparCampoContrato('$nmCampoDivContratoAnterior', $indiceContrato, '$nmCampoDivPessoaContratada', '$strCamposALimparSeparador',$paramAlterarDemanda);\" ", "sinal_menos.gif" );
		// }
	}
	
	if ($comChaveCompletaSeNulo) {
		?>
<INPUT type="hidden"
	id="<?=$pNmCampoCdEspecieContrato.$indiceContrato?>"
	name="<?=$pNmCampoCdEspecieContrato?>"
	value="<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>">
<INPUT type="hidden"
	id="<?=$pNmCampoSqEspecieContrato.$indiceContrato?>"
	name="<?=$pNmCampoSqEspecieContrato?>" value="1">
<?php }?>
<div id="<?=$nmCampoDivPessoaContratada?>">
<?php
	if ($isExibirContratadaSePreenchido && $vocontrato != null) {
		$vocontrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$vocontrato->sqEspecie = 1;
				
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
	}

	return $retorno;
}


?>