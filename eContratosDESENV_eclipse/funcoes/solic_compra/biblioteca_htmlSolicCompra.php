<?php

function getSolicCompraDetalhamento($voSolicCompra,$temLupa=true) {
	//$voSolicCompra = new voSolicCompra();
	if($voSolicCompra != null){
	?>
<TR>
	<TH class="campoformulario" nowrap width=1%><?=voSolicCompra::getNomeObjetoJSP()?>:</TH>
	<TD class="campoformulario" colspan=3>
	<?php
	$arrayParametroXNmAtributo = array ("cd" => voSolicCompra::$nmAtrCd,
			"ano" => voSolicCompra::$nmAtrAno,
			"ug" => voSolicCompra::$nmAtrUG,
	);	
	echo getCampoDetalhamentoVOChave($voSolicCompra,$arrayParametroXNmAtributo,$temLupa);	
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
