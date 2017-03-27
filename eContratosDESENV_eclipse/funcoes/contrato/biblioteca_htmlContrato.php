<?php

function getContratoDetalhamento($voContrato, $colecao){
$vo = new vocontrato();

//so exibe contrato se tiver
if($voContrato != null && $voContrato->cdContrato){

	require_once (caminho_funcoes."contrato/dominioTipoContrato.php");
	$dominioTipoContrato = new dominioTipoContrato();
	$contrato = formatarCodigoAnoComplemento($voContrato->cdContrato,
			$voContrato->anoContrato,
			$dominioTipoContrato->getDescricao($voContrato->tipo));

	include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");
	$nmpessoa = $colecao[vopessoa::$nmAtrNome];
	$docpessoa = $colecao[vopessoa::$nmAtrDoc];
	$campoContratado = getCampoContratada($nmpessoa, $docpessoa, $voContrato->sq);
	?>
			<TR>
	            <INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>" value="<?=$voContrato->anoContrato?>">
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>" value="<?=$voContrato->cdContrato?>">	            			  
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>" value="<?=$voContrato->tipo?>">
                <TH class="campoformulario" nowrap width=1%>Contrato:</TH>
				<TD class="campoformulario" colspan=3>Número:&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonlyalinhadodireita" size="<?=strlen($contrato)?>" readonly>				
				<div id=""><?=$campoContratado?></div></TD>
            </TR>	                
<?php }

}


?>
