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

	var campoEspecieContrato = "<?=vocontrato::$nmAtrCdEspecieContrato?>";
	var cdEspecieContrato = campoEspecieContrato.value;

	if(cdEspecieContrato == "<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>"){
		exibirMensagem("Operação não permitida para contrato MATER.");
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

function formataForm() {
	var idCampoPercentual = "<?=voContratoModificacao::$nmAtrNumPercentual?>";
	var colecaoIDCamposRequired = [idCampoPercentual];

	var campoPercentual = document.getElementById(idCampoPercentual);	
	var campoTpModificacao = document.frm_principal.<?=voContratoModificacao::$nmAtrTpModificacao?>;
	var campoVlReferencial = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>;
	
	var tpModificacao = campoTpModificacao.value;
	var isReajuste = tpModificacao == <?=dominioTpContratoModificacao::$CD_TIPO_REAJUSTE?>;
	if(isReajuste){
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposRequired, false, true, true);
	}else{
		tornarReadOnlyCamposColecaoFormulario(colecaoIDCamposRequired, true, false, true);
	}
	setValorCampoMoedaComSeparadorMilhar(campoVlReferencial, 0, 4);
	calcular(false);
}

function calcular(pNaoAlterarPrazoMeses){
	pArrayCampos = new Array();
	pArrayCampos[0] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>;
	pArrayCampos[1] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>;
	pArrayCampos[2] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlModificacaoReal?>;
	pArrayCampos[3] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>;
	pArrayCampos[4] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>;
	pArrayCampos[5] = document.frm_principal.<?=voContratoModificacao::$nmAtrVlGlobalReal?>;
	pArrayCampos[6] = document.frm_principal.<?=vocontrato::$nmAtrVlMensalContrato?>;
	pArrayCampos[7] = document.frm_principal.<?=vocontrato::$nmAtrVlGlobalContrato?>;
	pArrayCampos[8] = document.frm_principal.<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>;
	pArrayCampos[9] = document.frm_principal.<?=voContratoModificacao::$nmAtrDtModificacao?>;
	pArrayCampos[10] = document.frm_principal.<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>;
	pArrayCampos[11] = document.frm_principal.<?=voContratoModificacao::$nmAtrTpModificacao?>;
	pArrayCampos[12] = document.frm_principal.<?=voContratoModificacao::$nmAtrNumPercentual?>;
	pArrayCampos[13] = document.frm_principal.<?=voContratoInfo::$nmAtrNumPrazo?>;
	pArrayCampos[14] = <?=dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO?>;	
	pArrayCampos[15] = <?=dominioTpContratoModificacao::$CD_TIPO_REAJUSTE?>;
	pArrayCampos[16] = pNaoAlterarPrazoMeses;
	pArrayCampos[17] = document.frm_principal.<?=voContratoModificacao::$nmColVlMensalParaFinsDeModAtual?>;
	pArrayCampos[18] = document.frm_principal.<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>;
			
	calcularModificacao(pArrayCampos);
}

function carregaDadosContrato(){
	str = "";

	cdContrato = document.frm_principal.<?=voContratoLicon::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrAnoContrato?>.value;
	tipoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrTipoContrato?>.value;
	cdEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrCdEspecieContrato?>.value;
	sqEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrSqEspecieContrato?>.value;
		
	if(cdContrato != "" && anoContrato != "" && cdEspecie !="" && tipoContrato !="" ){

		cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>';
		sqEspecie = 1;

		if(sqEspecie != ""){		
			str = "null"+ '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + cdContrato + '<?=CAMPO_SEPARADOR?>' 
			+ tipoContrato + '<?=CAMPO_SEPARADOR?>' + cdEspecie
			+ '<?=CAMPO_SEPARADOR?>' + sqEspecie;
			//vai no ajax
			getDadosPorChaveGenerica(str, "../contrato_mod/campoDadosContratoMod.php", '<?=voContratoModificacao::$ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO?>');
		}
	}
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
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
	        <?php	        
	        $complementoHTML = "";
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        
	        if(!$isInclusao){
	        getContratoDet($voContrato, false, true);
	        ?>	        
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Vigência:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtVigenciaInicial) . " a " . getDetalhamentoHTML("", "", $voContrato->dtVigenciaFinal)?>
				</TD>
	        </TR>
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Dt.Assinatura:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtAssinatura)?>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Dt.Publicação:</TH>
	            <TD class="campoformulario">
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtPublicacao)?>
				</TD>				
	        </TR>	        
	        
	        <?php
	        }else{
	        	//INCLUSAO
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            //getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, "carregaDadosContrato()", false);	            
	            $pArray = array(null,constantes::$CD_CLASS_CAMPO_OBRIGATORIO,true,true,false,true,"carregaDadosContrato();");
	            getContratoEntradaArray($pArray);	            	 
	            ?>
				<div id="<?=voContratoModificacao::$ID_REQ_DIV_DADOS_CONTRATO_MODIFICACAO?>">
				</div>	            
	            </TD>
	        </TR>	        	        
	        <?php 
	       }	       
	       ?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php                        
				$combo = new select(dominioTpContratoModificacao::getColecao());                        
				//cria o combo
				echo $combo->getHtmlCombo(voContratoModificacao::$nmAtrTpModificacao, voContratoModificacao::$nmAtrTpModificacao, $vo->situacao, true, "camponaoobrigatorio", false, " onChange='formataForm();calcular();' required");
				?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data:</TH>
	            <TD class="campoformulario" width=1%>
	            	<INPUT type="text" 
	            	       id="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            	       name="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            			value="<?php echo(getData($vo->dtPublicacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);"
		            		onBlur="calcular();" 
	            			class="camponaoobrigatorio"	            			 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
	            <TH class="campoformulario" width=1% nowrap>Prazo restante:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>" name="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" onBlur="calcular(true);" class="<?=constantes::$CD_CLASS_CAMPO_OBRIGATORIO_DIREITA?>" size="5" required>(meses)
	            </TD>				
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor Referencial:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" onBlur='calcular();' class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
	            <TH class="campoformulario" nowrap>Valor Mensal Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Modificação ao Contrato<br>(em caso de prorrogação):</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario">Valor Global Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Real:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario" nowrap>Valor Real Contrato:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalReal?>" name="<?=voContratoModificacao::$nmAtrVlGlobalReal?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>",
		            	"<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>",
		            	"<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>",
	            		"<?=voContratoModificacao::$nmAtrVlModificacaoReal?>",
	            		"<?=voContratoModificacao::$nmAtrVlGlobalReal?>"
	            		];
	            </SCRIPT>
	            <INPUT type="checkbox" id="edicao" name="edicao" value="" onClick="validaFormReadOnlyCheckBox(this, colecaoIDCamposRequired, false, true, true);"> *Habilitar edição.	            
	            </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Percentual:</TH>
	            <TD class="campoformulario" colspan="3">
	            <INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumPercentual?>" name="<?=voContratoModificacao::$nmAtrNumPercentual?>"  value="<?php echo(getMoeda($vo->numPercentual));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" onBlur='calcular(false);' class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="10" readonly>%
				de <INPUT type="text" id="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>" name="<?=voContratoModificacao::$ID_REQ_VL_BASE_PERCENTUAL?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
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
