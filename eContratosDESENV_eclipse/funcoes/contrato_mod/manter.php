<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

try{
	//inicia os parametros
	inicioComValidacaoUsuario(true);

	$vo = new voContratoModificacao();
	$funcao = @$_GET["funcao"];

	$readonly = "";
	$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

	$classChaves = "campoobrigatorio";
	$readonlyChaves = "";
	
	$nmFuncao = "";
	if($isInclusao){
		$nmFuncao = "INCLUIR ";
	}else{
		$classChaves = "camporeadonly";
		$readonlyChaves = "readonly";

		$readonly = "readonly";
		$vo->getVOExplodeChave();
		$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

		$dbprocesso = $vo->dbprocesso;
		$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
		$vo->getDadosBanco($colecao);
		
		$voContrato = new vocontrato();
		$voContrato->getDadosBanco($colecao);
		
		putObjetoSessao($vo->getNmTabela(), $vo);

		$nmFuncao = "ALTERAR ";
	}
	
	$titulo = voContratoModificacao::getTituloJSP();
	$titulo = $nmFuncao . $titulo;
	setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {

	var campoEspecieContrato = document.frm_principal.<?=vocontrato::$nmAtrCdEspecieContrato?>;
	var cdEspecieContrato = campoEspecieContrato.value;

	if(cdEspecieContrato == "<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>"){
		exibirMensagem("Opera��o n�o permitida para contrato MATER.");
		return false;
	}

	if(!isCampoMoedaValido(document.frm_principal.<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>, 
			2, true, 0.1)){
		return false;
	}
	
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido())
		return false;

	return confirm("Confirmar Alteracoes?");    
}

function formataForm(pLimparCampos) {
	if(pLimparCampos == null){
		pLimparCampos = true;
	}
	var colecaoIDCamposVlMensal = [
		"<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>"];

	var colecaoIDCamposVlGlobal= [
		"<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>"];

	var colecaoIDCamposVlReferenciais = [
		"<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>",
		"<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>"];

	var colecaoIDCamposOutros = [
		"<?=voContratoModificacao::$nmAtrVlModificacaoReal?>",
		"<?=voContratoModificacao::$nmAtrVlGlobalReal?>",
		"<?=voContratoModificacao::$nmAtrNumPercentual?>",
		"<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>",
		"<?=voContratoModificacao::$ID_REQ_NUM_PERCENTUAL_GESTOR?>",
		"<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL_GESTOR?>"
		];
	
	if(pLimparCampos){
		//biblioprincipal
		limparCamposColecaoFormulario(colecaoIDCamposVlMensal.concat(colecaoIDCamposVlGlobal).concat(colecaoIDCamposVlReferenciais).concat(colecaoIDCamposOutros));
	}
		
	var campoTpModificacao = document.frm_principal.<?=voContratoModificacao::$nmAtrTpModificacao?>;	
	var tpModificacao = campoTpModificacao.value;	
	var isReajuste = tpModificacao == <?=dominioTpContratoModificacao::$CD_TIPO_REAJUSTE?>;
	var isProrroga��o = tpModificacao == <?=dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO?>;

	var tornarReadOnlyReferenciais = isReajuste || isProrroga��o; 
	var tornarReadOnlyVlMensal = isProrroga��o;
	var tornarReadOnlyVlGlobal = !isReajuste || isProrroga��o;

	//tornarReadOnlyCamposColecaoFormulario(colecaoIDCampos, pIsReadOnly, pIsCampoObrigatorio, pIsAlinhadoDireita);
	tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlMensal, tornarReadOnlyVlMensal, true, true);
	tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlGlobal, tornarReadOnlyVlGlobal, true, true);
	tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlReferenciais, tornarReadOnlyReferenciais, true, true);	
	
	/*if(isReajuste){
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlReferenciais, true, true, true);
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlContrato, false, true, true);
	}else{
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlReferenciais, false, true, true);
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposVlContrato, true, true, true);
	}*/	

}

function calcular(eElement){

	pCampoModContrato = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>;
	pCampoVlReferencial = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>;

	pCampoVlMensalAtualizado = document.frm_principal.<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>;
	pCampoVlGlobalAtualizado = document.frm_principal.<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>;
	
	pArrayCampos = new Array();
	pArrayCampos[0] = pCampoVlReferencial;
	pArrayCampos[1] = pCampoModContrato;
	pArrayCampos[2] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoReal?>;
	pArrayCampos[3] = pCampoVlMensalAtualizado;
	pArrayCampos[4] = pCampoVlGlobalAtualizado;
	pArrayCampos[5] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlGlobalReal?>;
	//valor base referencia
	pArrayCampos[6] = document.frm_principal.<?=vocontrato::$nmAtrVlMensalContrato?>;
	pArrayCampos[7] = document.frm_principal.<?=vocontrato::$nmAtrVlGlobalContrato?>;
	
	pArrayCampos[8] = document.frm_principal.<?=voContratoModificacao::$nmAtrDtModificacaoFim?>;
	pArrayCampos[9] = document.frm_principal.<?=voContratoModificacao::$nmAtrDtModificacao?>;
	pArrayCampos[10] = document.frm_principal.<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>;
	pArrayCampos[11] = document.frm_principal.<?=voContratoModificacao::$nmAtrTpModificacao?>;
	pArrayCampos[12] = document.frm_principal.<?=voContratoModificacao::$nmAtrNumPercentual?>;
	pArrayCampos[13] = document.frm_principal.<?=voContratoInfo::$nmAtrNumPrazo?>;
	pArrayCampos[14] = <?=dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO?>;	
	pArrayCampos[15] = <?=dominioTpContratoModificacao::$CD_TIPO_REAJUSTE?>;
	pArrayCampos[16] = <?=dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO?>;
	pArrayCampos[17] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlMensalModAtual?>;
	pArrayCampos[18] = document.frm_principal.<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>;

	pArrayCampos[19] = document.frm_principal.<?=voContratoModificacao::$ID_REQ_NUM_PERCENTUAL_GESTOR?>;
	pArrayCampos[20] = document.frm_principal.<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL_GESTOR?>;
	
	//ou um ou outro define o valor a ser utilizado
	/*if(eElement.name == pCampoVlReferencial.name){
		pCampoModContrato.value = "0,00";
	}else if(eElement.name == pCampoModContrato.name){
		pCampoVlReferencial.value = "0,00";
	}else if(eElement.name == pCampoVlMensalAtualizado.name){
		pCampoVlGlobalAtualizado.value = "0,00";
	}else if(eElement.name == pCampoVlGlobalAtualizado.name){
		pCampoVlMensalAtualizado.value = "0,00";
	}*/
			
	calcularModificacaoNovo(pArrayCampos);
}

function carregaDadosContrato(){
	str = "";

	cdContrato = document.frm_principal.<?=voContratoLicon::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrAnoContrato?>.value;
	tipoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrTipoContrato?>.value;
	cdEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrCdEspecieContrato?>.value;
	sqEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrSqEspecieContrato?>.value;
	
	if(cdContrato != "" && anoContrato != "" && tipoContrato !="" && cdEspecie !=""){

		if(cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>'){		
			sqEspecie = 1;
		}

		if(sqEspecie != ""){		
			str = "null"+ '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + cdContrato + '<?=CAMPO_SEPARADOR?>' 
			+ tipoContrato + '<?=CAMPO_SEPARADOR?>' + cdEspecie
			+ '<?=CAMPO_SEPARADOR?>' + sqEspecie;
			//vai no ajax
			getDadosPorChaveGenerica(str, "../contrato_mod/campoDadosContratoMod.php", '<?=voContratoModificacao::$ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO?>');
		}
	}

}

function iniciar(){
	carregaDadosContrato();
	//formataForm(false);
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro"><?=cabecalho?></TD>
		</TR>
        <TR>
            <TD class="conteinerfiltro">
            <DIV id="div_filtro" class="div_filtro">
            <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
            <TBODY>            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php
	            $complementoHTML = "";
	            require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	            
	            $pArray = array($voContrato,constantes::$CD_CLASS_CAMPO_OBRIGATORIO,true,true,false,true,"carregaDadosContrato();");
	            getContratoEntradaArray($pArray);	             	            	 
	            ?>
				<div id="<?=voContratoModificacao::$ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO?>">
				</div>	            
	            </TD>
	        </TR>	        	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php                        
				$combo = new select(dominioTpContratoModificacao::getColecao());                        
				//cria o combo
				echo $combo->getHtmlCombo(voContratoModificacao::$nmAtrTpModificacao, voContratoModificacao::$nmAtrTpModificacao, $vo->tpModificacao, true, "camponaoobrigatorio", false, " onChange='formataForm();calcular(this);' required");
				?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data:</TH>
	            <TD class="campoformulario" width=1%>
	            	<INPUT type="text" 
	            	       id="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            	       name="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            			value="<?php echo(getData($vo->dtModificacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);"
		            		onBlur="calcular(this);" 
	            			class="camponaoobrigatorio"	            			 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
	            <TH class="campoformulario" width=1% nowrap>Prazo restante:</TH>
	            <TD class="campoformulario" colspan="3">
	            <INPUT type="text" 
		            id="<?=voContratoModificacao::$nmAtrDtModificacaoFim?>" 
		            name="<?=voContratoModificacao::$nmAtrDtModificacaoFim?>" 
		            value="<?php echo(getData($vo->dtModificacao));?>"
		            onkeyup="formatarCampoData(this, event, false);"
			        onBlur="calcular(this);" 
		            class="camponaoobrigatorio"	            			 
		            size="10" 
		            maxlength="10" required>	            
	            ou
	            <INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>" name="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>"  value="<?php echo(getMoeda($vo->numMesesParaOFimdoPeriodo));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY?>" size="5" readonly>(meses)
	            </TD>				
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor Mensal Referencial:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>"  value="<?php echo(getMoeda($vo->vlModificacaoReferencial,4));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" onBlur='calcular(this, false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario" nowrap>Valor Mensal Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>"  value="<?php echo(getMoeda($vo->vlMensalAtual));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" onBlur='calcular(this, false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	            <?php 
				$nmCamposVlBorracha = array(
						voContratoModificacao::$nmAtrVlModificacaoReferencial,
						voContratoModificacao::$nmAtrVlMensalAtualizado,
						voContratoModificacao::$nmAtrVlModificacaoAoContrato,
						voContratoModificacao::$nmAtrVlGlobalAtualizado,
						voContratoModificacao::$nmAtrVlModificacaoReal,
						voContratoModificacao::$nmAtrVlGlobalReal,						
				);
				echo getBorracha($nmCamposVlBorracha, "");
	            ?>
	            </TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Modifica��o ao Contrato<br>(em caso de prorroga��o):</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>"  value="<?php echo(getMoeda($vo->vlModificacaoAoContrato));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" onBlur='calcular(this, false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario">Valor Global Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>"  value="<?php echo(getMoeda($vo->vlGlobalAtual));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" onBlur='calcular(this, false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Real:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>"  value="<?php echo(getMoeda($vo->vlModificacaoReal));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario" nowrap>Valor Real Contrato:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalReal?>" name="<?=voContratoModificacao::$nmAtrVlGlobalReal?>"  value="<?php echo(getMoeda($vo->vlGlobalReal));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>",
		            	"<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>",
	            		"<?=voContratoModificacao::$nmAtrVlModificacaoReal?>",
	            		"<?=voContratoModificacao::$nmAtrVlGlobalReal?>"
	            		];
	            </SCRIPT>
	            <INPUT type="checkbox" id="edicao" name="edicao" value="" onClick="validaFormReadOnlyCheckBox(this, colecaoIDCamposRequired, false, true, true);"> *Habilitar edi��o.	            
	            </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Percentual:</TH>
	            <TD class="campoformulario" colspan="3">
	            <INPUT type="text" id="<?=voContratoModificacao::$ID_REQ_NUM_PERCENTUAL_GESTOR?>" name="<?=voContratoModificacao::$ID_REQ_NUM_PERCENTUAL_GESTOR?>"  value=""
	            class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="10" readonly>%
				de <INPUT type="text" id="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL_GESTOR?>" name="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL_GESTOR?>"  value="<?php echo(getMoeda($vo->vlMensalAtual));?>"
	            class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	         	| <b>Valor para fins de Acr�scimo: <INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumPercentual?>" name="<?=voContratoModificacao::$nmAtrNumPercentual?>"  value="<?php echo(getMoeda($vo->numPercentual,4));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" onBlur='calcular(false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="10" readonly>%
				de <INPUT type="text" id="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>" name="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>"  value="<?php echo(getMoeda($vo->vlModificacaoReferencial));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	            </b>	            	            
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observa��o:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoModificacao::$nmAtrObs?>" name="<?=voContratoModificacao::$nmAtrObs?>" class="camponaoobrigatorio"><?=$vo->obs?></textarea>
				</TD>
	        </TR>	        
<TR>
	<TD halign="left" colspan="4">
	<DIV class="textoseparadorgrupocampos">&nbsp;</DIV>
	</TD>
</TR>        	        	
	        <?php
	        if(!$isInclusao){
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
	        }
	        ?>
            </TBODY>
            </TABLE>
            </DIV>
            </TD>
        </TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
							<?=getBotoesRodape();?>
							</TR>
		                    </TABLE>
	                    </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);
}
?>