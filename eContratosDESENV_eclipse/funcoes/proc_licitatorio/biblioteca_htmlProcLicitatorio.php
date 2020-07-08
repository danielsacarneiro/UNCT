<?php
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");

function isProcLicitatorioValido($voProcLicitatorio) {
	// so exibe contrato se tiver
	return $voProcLicitatorio != null && $voProcLicitatorio->cd;
}

function getCodigoPLSEFAZ($voProcLicitatorio) {
	$sefaz = "SEFAZ-PE";
	//0044.2020.CPL-II.PE.0024.SEFAZ-PE
	//$voProcLicitatorio = new voProcLicitatorio();
	$retorno = complementarCharAEsquerda($voProcLicitatorio->cd, "0", 4);
	$retorno .= "." . complementarCharAEsquerda($voProcLicitatorio->ano, "0", 4);
	$retorno .= "." . dominioComissaoProcLicitatorio::getDescricao($voProcLicitatorio->cdCPL);
	$retorno .= "." . $voProcLicitatorio->cdModalidade;
	$retorno .= "." . complementarCharAEsquerda($voProcLicitatorio->numModalidade, "0", 4);
	$retorno .= "." . $sefaz;
	
	return $retorno;
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
	<INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrCdModalidade?>"
		name="<?=voProcLicitatorio::$nmAtrCdModalidade?>"
		value="<?=$voProcLicitatorio->cdModalidade?>">
	<TH class="campoformulario" nowrap width=1%>Proc.Licitatório:</TH>
	<TD class="campoformulario" colspan=3>
	<?php
	$arrayParametroXNmAtributo = array ("cd" => voProcLicitatorio::$nmAtrCd,
			"ano" => voProcLicitatorio::$nmAtrAno
	);	
	echo getInputText("", "", getCodigoPLSEFAZ($voProcLicitatorio), constantes::$CD_CLASS_CAMPO_READONLY);
	
	echo getCampoDadosVOAnoCdDetalhamento($voProcLicitatorio,$arrayParametroXNmAtributo,$temLupa);
	//echo getTextoHTMLNegrito(dominioModalidadeProcLicitatorio::getDescricaoStatic($voProcLicitatorio->cdModalidade));
	$detModalidade = dominioModalidadeProcLicitatorio::getDescricaoStatic($voProcLicitatorio->cdModalidade);
	/*if($voProcLicitatorio->numModalidade != null){
		$detModalidade.="." . complementarCharAEsquerda($voProcLicitatorio->numModalidade, "0", 3);
	}*/
	echo getTextoHTMLNegrito($detModalidade);
	
	if($voProcLicitatorio->cdPregoeiro != null){
		//echo getComboPessoaPregoeiro(voProcLicitatorio::$nmAtrCdPregoeiro, voProcLicitatorio::$nmAtrCdPregoeiro, $voProcLicitatorio->cdPregoeiro, "camponaoobrigatorio", " disabled ");
		//echo "-" . dominioComissaoProcLicitatorio::getDescricao($voProcLicitatorio->cdCPL);		
		echo getInputText("", "", dominioComissaoProcLicitatorio::getNmPregoeiroPorCPL($voProcLicitatorio->cdCPL), constantes::$CD_CLASS_CAMPO_READONLY);
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

function getCampoDadosProcLicitatorioComCPL($voProcLicitatorio, $nmClass = "camponaoobrigatorio", $complementoHTML=null) {
	getCampoDadosProcLicitatorio($voProcLicitatorio, $nmClass, $complementoHTML, true);
}

function getCampoDadosProcLicitatorio($voProcLicitatorio, $nmClass = "camponaoobrigatorio", $complementoHTML=null, $comCampoCPL=false) {
	$arrayParametroXNmAtributo = array ("cd" => voProcLicitatorio::$nmAtrCd,
			"ano" => voProcLicitatorio::$nmAtrAno,
			"cdModalidade" => voProcLicitatorio::$nmAtrCdModalidade
	);
	
	getCampoDadosVOAnoCd($voProcLicitatorio, $arrayParametroXNmAtributo, $nmClass, $complementoHTML);
	
	$comboModalidade = new select(dominioModalidadeProcLicitatorio::getColecao());
	$pIDCampoCdModalidade = $pNmCampoCdModalidade = voProcLicitatorio::$nmAtrCdModalidade;
	$htmlCdModalidade = $complementoHTML [2];
	echo " Mod.: " . $comboModalidade->getHtmlCombo ( $pIDCampoCdModalidade , $pNmCampoCdModalidade , $voProcLicitatorio->cdModalidade , true, $nmClass , false, $htmlCdModalidade);
	
	if($comCampoCPL){
		$comboCPL = new select(dominioComissaoProcLicitatorio::getColecao());
		$pIDCampoCdCPL = $pNmCampoCdCPL = voProcLicitatorio::$nmAtrCdCPL;		
		echo " CPL.: " . $comboCPL->getHtmlCombo(voProcLicitatorio::$nmAtrCdCPL,voProcLicitatorio::$nmAtrCdCPL, $voProcLicitatorio->cdCPL, true, $nmClass, false, $htmlCdModalidade);		
	}
	
}
