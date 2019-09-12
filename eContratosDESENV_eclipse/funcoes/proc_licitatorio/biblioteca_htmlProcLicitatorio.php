<?php
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");

function isProcLicitatorioValido($voProcLicitatorio) {
	// so exibe contrato se tiver
	return $voProcLicitatorio != null && $voProcLicitatorio->cd;
}

function getProcLicitatorioDetalhamento($voProcLicitatorio,$temLupa=true) {
	//$voProcLicitatorio = new voProcLicitatorio();
	if(isProcLicitatorioValido($voProcLicitatorio)){
	?>
<TR>
	<INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrCd?>"
		name="<?=voProcLicitatorio::$nmAtrCd?>"
		value="<?=$voProcLicitatorio->cd?>">
	<INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrAno?>"
		name="<?=voProcLicitatorio::$nmAtrAno?>"
		value="<?=$voProcLicitatorio->ano?>">
	<TH class="campoformulario" nowrap width=1%>Proc.Licitatório:</TH>
	<TD class="campoformulario" colspan=3>
	<?php
	$arrayParametroXNmAtributo = array ("cd" => voProcLicitatorio::$nmAtrCd,
			"ano" => voProcLicitatorio::$nmAtrAno
	);	
	echo getCampoDadosVOAnoCdDetalhamento($voProcLicitatorio,$arrayParametroXNmAtributo,$temLupa);
	//echo getTextoHTMLNegrito(dominioModalidadeProcLicitatorio::getDescricaoStatic($voProcLicitatorio->cdModalidade));
	$detModalidade = dominioModalidadeProcLicitatorio::getDescricaoStatic($voProcLicitatorio->cdModalidade);
	if($voProcLicitatorio->numModalidade != null){
		$detModalidade.="." . complementarCharAEsquerda($voProcLicitatorio->numModalidade, "0", 3);
	}
	echo getTextoHTMLNegrito($detModalidade);
	
	if($voProcLicitatorio->cdPregoeiro != null){
		echo getComboPessoaPregoeiro(voProcLicitatorio::$nmAtrCdPregoeiro, voProcLicitatorio::$nmAtrCdPregoeiro, $voProcLicitatorio->cdPregoeiro, "camponaoobrigatorio", " disabled ");
	}	
	if($voProcLicitatorio->objeto != null){
		echo "<br>" . getInputTextArea(voProcLicitatorio::$nmAtrObjeto, voProcLicitatorio::$nmAtrObjeto, $voProcLicitatorio->objeto, constantes::$CD_CLASS_CAMPO_READONLY);
	}		
	
	?>
	</TD>	
</TR>
<?php
	}
	
}

function getCampoDadosProcLicitatorio($voProcLicitatorio, $nmClass = "camponaoobrigatorio", $complementoHTML=null) {
	$arrayParametroXNmAtributo = array ("cd" => voProcLicitatorio::$nmAtrCd,
			"ano" => voProcLicitatorio::$nmAtrAno,
			"cdModalidade" => voProcLicitatorio::$nmAtrCdModalidade
	);
	
	getCampoDadosVOAnoCd($voProcLicitatorio, $arrayParametroXNmAtributo, $nmClass, $complementoHTML);
	
	$comboModalidade = new select(dominioModalidadeProcLicitatorio::getColecao());
	$pIDCampoCdModalidade = $pNmCampoCdModalidade = voProcLicitatorio::$nmAtrCdModalidade;
	$htmlCdModalidade = $complementoHTML [2];
	echo " Mod.: " . $comboModalidade->getHtmlCombo ( $pIDCampoCdModalidade , $pNmCampoCdModalidade , $voProcLicitatorio->cdModalidade , true, $nmClass , false, $htmlCdModalidade);
	
}
