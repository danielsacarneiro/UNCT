<?php

function isProcLicitatorioValido($voProcLicitatorio) {
	// so exibe contrato se tiver
	return $voProcLicitatorio != null && $voProcLicitatorio->cd;
}


function getProcLicitatorioDetalhamento($voProcLicitatorio,$temLupa=true) {
	
	// so exibe edital se tiver
	if (isProcLicitatorioValido ( $voProcLicitatorio )) {

		$cd = formatarCodigoAnoComplemento ( $voProcLicitatorio->cd, $voProcLicitatorio->ano, "");
		// $voContrato = new vocontrato();
		$chave = $voProcLicitatorio->getValorChaveHTML ();
		
		?>
<TR>
	<INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrCd?>"
		name="<?=voProcLicitatorio::$nmAtrCd?>"
		value="<?=$voProcLicitatorio->cd?>">
	<INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrAno?>"
		name="<?=voProcLicitatorio::$nmAtrAno?>"
		value="<?=$voProcLicitatorio->ano?>">
	<TH class="campoformulario" nowrap width=1%>Proc.Licitatório:</TH>
	<TD class="campoformulario" colspan=3>Número:&nbsp;&nbsp;&nbsp;&nbsp; <INPUT
		type="text" value="<?php echo($cd);?>"
		class="camporeadonlyalinhadodireita" size="<?=strlen($cd)+1?>"
		readonly>	
	<?php		
	if ($temLupa) {
		echo getLinkPesquisa ( "../proc_licitatorio/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chave );
	}
	?>	
</TR>
<?php
	}
}

function getCampoDadosProcLicitatorio($voProcLicitatorio, $nmClass = "camponaoobrigatorio", $complementoHTML=null) {

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
		);
	$arrayComplementoHTML = array (	
			" $required onBlur=$chamadaFuncaoJS ",
			" $required onChange=$chamadaFuncaoJS "
	);

	$html = getProcLicitatorioEntradaDeDadosVO($voProcLicitatorio, $arrayCssClass, $arrayComplementoHTML);
	return $html;
}

function getProcLicitatorioEntradaDeDadosVO($voProcLicitatorio, $arrayCssClass, $arrayComplementoHTML) {
	//$voProcLicitatorio = new voProcLicitatorio();
	
	if ($voProcLicitatorio != null) {
		$cdProcLic = $voProcLicitatorio->cd;
		$anoProcLic = $voProcLicitatorio->ano;
	}
	
	$selectExercicio = new selectExercicio ();

	$cssCdProcLic = $arrayCssClass [0];
	$cssAnoProcLic = $arrayCssClass [1];

	$htmlCdProcLic = $arrayComplementoHTML [0];
	$htmlAnoProcLic = $arrayComplementoHTML [1];

	$pNmCampoCdProcLic = voProcLicitatorio::$nmAtrCd;
	$pNmCampoAnoProcLic = voProcLicitatorio::$nmAtrAno;

	$pIDCampoCdProcLic = $pNmCampoCdProcLic;
	$pIDCampoAnoProcLic = $pNmCampoAnoProcLic;

	$strCamposALimparSeparador = $pIDCampoCdProcLic . "*" . $pIDCampoAnoProcLic;
	?>
	Número:
<INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)"
	id="<?=$pIDCampoCdProcLic?>" name="<?=$pNmCampoCdProcLic?>"
	value="<?php echo(complementarCharAEsquerda($cdProcLic, "0", TAMANHO_CODIGOS_SAFI));?>"
	class="<?=$cssCdProcLic?>" size="4" maxlength="3" <?=$htmlCdProcLic?>>
<?php
	echo "Ano: " . $selectExercicio->getHtmlCombo ( $pIDCampoAnoProcLic, $pNmCampoAnoProcLic, $anoProcLic, true, $cssAnoProcLic, false, $htmlAnoProcLic );
	
}