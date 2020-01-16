<?php

function getSolicCompraDetalhamento($voSolicCompra,$temLupa=true) {
	//$voSolicCompra = new voSolicCompra();
	if($voSolicCompra != null){
	?>
<TR>
	<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrCd?>"
		name="<?=voSolicCompra::$nmAtrCd?>"
		value="<?=$voSolicCompra->cd?>">
	<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrAno?>"
		name="<?=voSolicCompra::$nmAtrAno?>"
		value="<?=$voSolicCompra->ano?>">
	<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrUG?>"
		name="<?=voSolicCompra::$nmAtrUG?>"
		value="<?=$voSolicCompra->ug?>">
	<TH class="campoformulario" nowrap width=1%><?=voSolicCompra::getNomeObjetoJSP()?>:</TH>
	<TD class="campoformulario" colspan=3>
	<?php
	$arrayParametroXNmAtributo = array ("cd" => voSolicCompra::$nmAtrCd,
			"ano" => voSolicCompra::$nmAtrAno,
	);	
	echo getCampoDadosVOAnoCdDetalhamento($voSolicCompra,$arrayParametroXNmAtributo,$temLupa);
	//echo getTextoHTMLNegrito(dominioModalidadeSolicCompra::getDescricaoStatic($voSolicCompra->cdModalidade));
	$complemento = $voSolicCompra->ug;
	echo getTextoHTMLNegrito($complemento);
	
	if($voSolicCompra->objeto != null){
		echo "<br>" . getInputTextArea(voSolicCompra::$nmAtrObjeto, voSolicCompra::$nmAtrObjeto, $voSolicCompra->objeto, constantes::$CD_CLASS_CAMPO_READONLY);
	}		
	
	?>
	</TD>	
</TR>
<?php
	}
	
}

function getCampoDadosSolicCompra($voSolicCompra, $nmClass = "camponaoobrigatorio", $complementoHTML=null) {
	$arrayParametroXNmAtributo = array ("cd" => voSolicCompra::$nmAtrCd,
			"ano" => voSolicCompra::$nmAtrAno,
	);
	
	getCampoDadosVOAnoCd($voSolicCompra, $arrayParametroXNmAtributo, $nmClass, $complementoHTML);
	
	$comboUG = new select(dominioUGSolicCompra::getColecao());
	$pIDCampoCdUG = $pNmCampoCdUG = voSolicCompra::$nmAtrUG;
	$htmlCdUG = $complementoHTML [2];
	echo " UG: " . $comboUG->getHtmlCombo ( $pIDCampoCdUG , $pNmCampoCdUG , $voSolicCompra->ug , true, $nmClass , false, $htmlCdUG);
	
}
