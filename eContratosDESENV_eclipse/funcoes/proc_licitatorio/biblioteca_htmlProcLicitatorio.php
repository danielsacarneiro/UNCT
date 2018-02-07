<?php

function isProcLicitatorioValido($voProcLicitatorio) {
	// so exibe contrato se tiver
	return $voProcLicitatorio != null && $voProcLicitatorio->cd;
}

function getProcLicitatorioDetalhamento($voProcLicitatorio,$temLupa=true) {
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
	?>
	</TD>	
</TR>
<?php
	}
	
}

function getCampoDadosProcLicitatorio($voProcLicitatorio, $nmClass = "camponaoobrigatorio", $complementoHTML=null) {
	$arrayParametroXNmAtributo = array ("cd" => voProcLicitatorio::$nmAtrCd,
			"ano" => voProcLicitatorio::$nmAtrAno
	);
	
	getCampoDadosVOAnoCd($voProcLicitatorio, $arrayParametroXNmAtributo, $nmClass, $complementoHTML);
}
