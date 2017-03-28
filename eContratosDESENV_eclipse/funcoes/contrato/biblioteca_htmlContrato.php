<?php
require_once(caminho_util."selectExercicio.php");

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

function getContratoEntradaDeDados($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML){
	require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioTipoContrato.php");
	$combo = new select(dominioTipoContrato::getColecao());
	require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioEspeciesContrato.php");
	$comboEspecie = new select(dominioEspeciesContrato::getColecao());
	$selectExercicio = new selectExercicio();
		
	$cssTipoContrato = $arrayCssClass[0];
	$cssCdContrato = $arrayCssClass[1];
	$cssAnoContrato = $arrayCssClass[2];	
	
	$htmlTipoContrato = $arrayComplementoHTML[0];	
	$htmlCdContrato = $arrayComplementoHTML[1];
	$htmlAnoContrato = $arrayComplementoHTML[2];
	
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome; 
	?>
	
<SCRIPT language="JavaScript" type="text/javascript">
function carregaContratada() {
	//ta na biblioteca_funcoes_pessoa.js
	pNmCampoCdContrato = '<?=vocontrato::$nmAtrCdContrato;?>';
	pNmCampoAnoContrato = '<?=vocontrato::$nmAtrAnoContrato;?>';
	pNmCampoTipoContrato = '<?=vocontrato::$nmAtrTipoContrato;?>';
	pNmCampoCdEspecieContrato = '<?=vocontrato::$nmAtrCdEspecieContrato;?>';
	pNmCampoSqEspecieContrato = '<?=vocontrato::$nmAtrSqEspecieContrato;?>';
	pNmCampoDiv = '<?=$nmCampoDivPessoaContratada;?>';

	//alert(pNmCampoCdContrato + " " + pNmCampoAnoContrato + " " + pNmCampoTipoContrato + " " + pNmCampoCdEspecieContrato + " " + pNmCampoSqEspecieContrato + " " + pNmCampoDiv);
	carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato,pNmCampoDiv);    
}
</SCRIPT>	
	            <?php echo $combo->getHtmlCombo(vocontrato::$nmAtrTipoContrato,vocontrato::$nmAtrTipoContrato, $tipoContrato, true, $cssTipoContrato, false, $htmlTipoContrato);?>
	            Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"  class="<?=$cssCdContrato?>" size="4" maxlength="3" <?=$readonlyChaves?> <?=$htmlCdContrato?>>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(vocontrato::$nmAtrAnoContrato,vocontrato::$nmAtrAnoContrato, $anoContrato, true, $cssAnoContrato, false, $htmlAnoContrato);?>
				<!--<?php echo "<br>Espécie: " . $comboEspecie->getHtmlCombo(vocontrato::$nmAtrCdEspecieContrato,vocontrato::$nmAtrCdEspecieContrato, $vo->cdespecie, true, $cssClass, false, " required onChange='carregaContratada();'");?>	            
         		Seq: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vocontrato::$nmAtrSqEspecieContrato?>" name="<?=vocontrato::$nmAtrSqEspecieContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->sqEspecie, "0", TAMANHO_CODIGOS_SAFI));?>"  class="<?=$cssClass?>" size="3" maxlength="3" <?=$readonlyChaves?> onBlur='carregaContratada();'> º-->
				
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrCdEspecieContrato?>" name="<?=vocontrato::$nmAtrCdEspecieContrato?>"  value="<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>">         		
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqEspecieContrato?>" name="<?=vocontrato::$nmAtrSqEspecieContrato?>"  value="1">
			  <div id="<?=$nmCampoDivPessoaContratada?>">
	          </div>	        
<?php 
}

?>
