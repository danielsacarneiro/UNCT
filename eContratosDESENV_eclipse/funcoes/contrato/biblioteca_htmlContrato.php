<?php
require_once(caminho_util."selectExercicio.php");
//require_once(caminho_util."constantes.class.php");
include_once(caminho_funcoes. "pessoa/biblioteca_htmlPessoa.php");

function isContratoValido($voContrato){
	//so exibe contrato se tiver
	return $voContrato != null && $voContrato->cdContrato;	
}

function getContratoDet($voContrato){
	$colecao = consultarPessoasContrato($voContrato);	
	return getContratoDetalhamento($voContrato, $colecao);	
}

function getColecaoContratoDet($colecao){
	$html = "";
	//var_dump($colecao);
	
	foreach ($colecao as $voContrato) {
		$html .= getContratoDet($voContrato);
	}
	return $html;
}

function getContratoDetalhamento($voContrato, $colecao){
	$vo = new vocontrato();

	//so exibe contrato se tiver
	if(isContratoValido($voContrato)){

	require_once (caminho_funcoes."contrato/dominioTipoContrato.php");
	$dominioTipoContrato = new dominioTipoContrato();
	$contrato = formatarCodigoAnoComplemento($voContrato->cdContrato,
			$voContrato->anoContrato,
			$dominioTipoContrato->getDescricao($voContrato->tipo));

	include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");
	
	//$voContrato = new vocontrato();
	$chaveContrato = $voContrato->getValorChaveHTML();
	$campoContratado = getCampoContratada("", "", $chaveContrato);
	$temLupa = false;
	if($colecao != ""){
		$temLupa = true;
		if(!isArrayMultiDimensional($colecao)){
			$nmpessoa = $colecao[vopessoa::$nmAtrNome];
			$docpessoa = $colecao[vopessoa::$nmAtrDoc];		
			$campoContratado = getCampoContratada($nmpessoa, $docpessoa, $voContrato->sq);		
		}else{
			$campoContratado = "";
			$tamanhoColecao = count($colecao);
			for($i=0; $i<$tamanhoColecao;$i++){
				$nmpessoa = $colecao[$i][vopessoa::$nmAtrNome];
				$docpessoa = $colecao[$i][vopessoa::$nmAtrDoc];
				$campoContratado .= getCampoContratada($nmpessoa, $docpessoa, $voContrato->sq)."<br>";				
			}
			
		}	
	}
	
	?>
			<TR>
	            <INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>" value="<?=$voContrato->anoContrato?>">
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>" value="<?=$voContrato->cdContrato?>">	            			  
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>" value="<?=$voContrato->tipo?>">
                <TH class="campoformulario" nowrap width=1%>Contrato:</TH>
				<TD class="campoformulario" colspan=3>Número:&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonlyalinhadodireita" size="<?=strlen($contrato)+1?>" readonly>	
				<?php				
				//$voContrato = new vocontrato();
				if($voContrato->cdEspecie == null){
					$voContrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
				}
				if($voContrato->sqEspecie == null){
					$voContrato->sqEspecie = 1;
				}
								
				if($temLupa){
		        	echo getLinkPesquisa("../contrato/detalharContrato.php?funcao=".constantes::$CD_FUNCAO_DETALHAR."&chave=".$chaveContrato);
				}		        		        
		        ?>							
				<div id=""><?=$campoContratado?></div></TD>
            </TR>	                
<?php }

}
function getContratoEntradaDeDados($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $comChaveCompletaSeNulo = true){
	return getContratoEntradaDeDadosMais($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, null, $comChaveCompletaSeNulo);
}

function getContratoEntradaDeDadosMais($tipoContrato, $cdContrato, $anoContrato, $arrayCssClass, $arrayComplementoHTML, $indiceContrato, $comChaveCompletaSeNulo = true){
	$isOpcaoMultiplos = $indiceContrato != null;			
		
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
	
	//parametros para a recuperacao de dados
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	if($isOpcaoMultiplos){
		$nmCampoDivNovoContrato = vocontrato::$ID_REQ_CAMPO_CONTRATO. $indiceContrato;		 
		$nmCampoDivContratoAnterior = vocontrato::$ID_REQ_CAMPO_CONTRATO. ($indiceContrato-1);
		$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome. $indiceContrato;
	}
	
	$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato; 
	$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;	
	$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
	$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
	
	$pIDCampoCdContrato = $pNmCampoCdContrato.$indiceContrato;
	$pIDCampoAnoContrato = $pNmCampoAnoContrato.$indiceContrato;
	$pIDCampoTipoContrato = $pNmCampoTipoContrato.$indiceContrato;
	$pIDCampoCdEspecieContrato = $pNmCampoCdEspecieContrato.$indiceContrato;
	$pIDCampoSqEspecieContrato = $pNmCampoSqEspecieContrato.$indiceContrato;
	
	$strCamposALimparSeparador = $pIDCampoCdContrato . "*" . $pIDCampoAnoContrato . "*". $pIDCampoTipoContrato; 
	
	/*$nmCampoDivPessoaContratada .= $indiceContrato;
	$nmCampoDivNovoContrato .= $indiceContrato;
	$pNmCampoCdContrato .= $indiceContrato;
	$pNmCampoAnoContrato .= $indiceContrato;
	$pNmCampoTipoContrato .= $indiceContrato;
	$pNmCampoCdEspecieContrato .= $indiceContrato;
	$pNmCampoSqEspecieContrato .= $indiceContrato;*/
	
	echo $combo->getHtmlCombo($pIDCampoTipoContrato,$pNmCampoTipoContrato, $tipoContrato, true, $cssTipoContrato, false, $htmlTipoContrato);?>
	Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=$pIDCampoCdContrato?>" name="<?=$pNmCampoCdContrato?>"  value="<?php echo(complementarCharAEsquerda($cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"  class="<?=$cssCdContrato?>" size="4" maxlength="3" <?=$htmlCdContrato?>>
	<?php 
	echo "Ano: " . $selectExercicio->getHtmlCombo($pIDCampoAnoContrato,$pNmCampoAnoContrato, $anoContrato, true, $cssAnoContrato, false, $htmlAnoContrato);
	if($isOpcaoMultiplos){
		echo "&nbsp;" . getImagemLink("javascript:carregaNovoCampoContrato('$nmCampoDivNovoContrato', $indiceContrato);\" ", "sinal_mais.gif");
		
		//if($indiceContrato > 1){
			echo "&nbsp;" . getImagemLink("javascript:limparCampoContrato('$nmCampoDivContratoAnterior', $indiceContrato, '$nmCampoDivPessoaContratada', '$strCamposALimparSeparador');\" ", "sinal_menos.gif");
		//}
	}
	
	if($comChaveCompletaSeNulo){?>	   				
				<INPUT type="hidden" id="<?=$pNmCampoCdEspecieContrato.$indiceContrato?>" name="<?=$pNmCampoCdEspecieContrato?>"  value="<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>">         		
				<INPUT type="hidden" id="<?=$pNmCampoSqEspecieContrato.$indiceContrato?>" name="<?=$pNmCampoSqEspecieContrato?>"  value="1">
	<?php }?>				
			  <div id="<?=$nmCampoDivPessoaContratada?>">
	          </div>	          
			  <div id="<?=$nmCampoDivNovoContrato?>">
	          </div>	        
<?php 
}

function getCdAutorizacaoMaisRecenteContrato($voContrato){
	$colecao = consultarPessoasContrato($voContrato);
	return getContratoDetalhamento($voContrato, $colecao);
}

function consultarDadosContratoCompilado($voContrato){
	$retorno = "";
	
	if(isContratoValido($voContrato)){	
		$nmTabela = $voContrato->getNmTabelaEntidade(false);
		
		$nmAtributosWhere = array(
				vocontrato::$nmAtrAnoContrato => $voContrato->anoContrato,
				vocontrato::$nmAtrCdContrato => $voContrato->cdContrato,
				vocontrato::$nmAtrTipoContrato => "'$voContrato->tipo'"
		);
	
		$query = "SELECT * ";
		$query.= "\n FROM ".$nmTabela;
		$query.= "\n WHERE ";
		$query.= $voContrato->getValoresWhereSQL($voContrato, $nmAtributosWhere);
		$query.= "\n ORDER BY " . vocontrato::$nmAtrSqContrato;
		
		$db = new dbcontrato();
		$retorno = $db->consultarEntidade($query, false);
		$retorno = $retorno[0];
	}

	//echo $query;
	return $retorno;
}

function getCampoDadosContratoSimples($nmClass = "camponaoobrigatorio") {
	return getCampoDadosContratoMultiplosPorIndice(null,$nmClass);	
}
function getCampoDadosContratoMultiplos($nmClass = "campoobrigatorio") {
	$indiceQtdContrato = 1;
	$html = getCampoDadosContratoMultiplosPorIndice($indiceQtdContrato, $nmClass);
	//$html .= "<INPUT type='hidden' id='". vocontrato::$ID_REQ_QTD_CONTRATOS . "' name='" . vocontrato::$ID_REQ_QTD_CONTRATOS . "'  value='".$indiceQtdContrato."'>";
	return 	$html;	
}
function getCampoDadosContratoMultiplosPorIndice($indice, $nmClass = "camponaoobrigatorio"){
	$pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
	$pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	$pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
	$pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato;
	$pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;	
	$nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	
	$indiceJS = $indice;
	if($indice == null)
		$indiceJS = "''";
	
	$chamadaFuncaoJS = "\"carregaContratada($indiceJS, '$pNmCampoCdContrato', '$pNmCampoAnoContrato', '$pNmCampoTipoContrato', '$pNmCampoCdEspecieContrato', '$pNmCampoSqEspecieContrato', '$nmCampoDivPessoaContratada');\"";
	
	$required = "";
	if($nmClass == constantes::$CD_CLASS_CAMPO_OBRIGATORIO){
		$required = "required";
	}
	
	$arrayCssClass = array($nmClass,$nmClass, $nmClass);
	$arrayComplementoHTML = array(" $required onChange=$chamadaFuncaoJS ",
			" $required onBlur=$chamadaFuncaoJS ",
			" $required onChange=$chamadaFuncaoJS "
	);	
	$html = getContratoEntradaDeDadosMais("", "", "", $arrayCssClass, $arrayComplementoHTML, $indice);
	return 	$html;
}

function getProcLicitatorioEntradaDados($cdProcLic, $anoProcLic, $arrayCssClass, $arrayComplementoHTML){

	$selectExercicio = new selectExercicio();
	$cssCdProcLic = $arrayCssClass[0];
	$cssAnoProcLic = $arrayCssClass[1];

	$htmlCdProcLic = $arrayComplementoHTML[0];
	$htmlAnoProcLic = $arrayComplementoHTML[1];
	
	$pNmCampoCdProcLicitatorio = voProcLicitatorio::$nmAtrCdProcLicitatorio;
	$pNmCampoAnoProcLicitatorio = voProcLicitatorio::$nmAtrAnoProcLicitatorio;

	
	echo "Número: <INPUT type='text' onkeyup='validarCampoNumericoPositivo(this)' id='" . $pNmCampoCdProcLicitatorio . "' name='". $pNmCampoCdProcLicitatorio ."'  value='". complementarCharAEsquerda($cdProcLic, "0", TAMANHO_CODIGOS_SAFI) . "'  class='" . $cssCdProcLic . "' size='4' maxlength='3'  ". $htmlCdProcLic . ">";	 
	echo "&nbsp;Ano: " . $selectExercicio->getHtmlCombo($pNmCampoAnoProcLicitatorio,$pNmCampoAnoProcLicitatorio, $anoProcLic, true, $cssAnoProcLic, false, $htmlAnoProcLic);
}

?>