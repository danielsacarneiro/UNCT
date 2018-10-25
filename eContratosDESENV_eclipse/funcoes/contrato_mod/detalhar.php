<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voContratoModificacao();
$vo->getVOExplodeChave();
//var_dump($vo);

//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
$vo->getDadosBanco($colecao);

$voContrato = new vocontrato();
$voContrato->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = $vo->getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
	$isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	
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

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=voContratoModificacao::$nmAtrSq?>" name="<?=voContratoModificacao::$nmAtrSq?>" value="<?=$vo->sq?>">
 
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
			<?php if($isHistorico){?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sq.Hist:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->sqHist, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                <INPUT type="hidden" id="<?=voContratoInfo::$nmAtrSqHist?>" name="<?=voContratoInfo::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }	       	          
			?>
	        <TR>
	            <?php
	            $complementoHTML = "";
	            require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	            getContratoDet($voContrato, false, true);
	            ?>
	        </TR>	        	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php                        
				echo dominioTpContratoModificacao::getHtmlDetalhamento(voContratoModificacao::$nmAtrTpModificacao, voContratoModificacao::$nmAtrTpModificacao, $vo->tpModificacao);
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
	            			class="camporeadonly"	            			 
	            			size="10" 
	            			maxlength="10" readonly>
				</TD>
	            <TH class="campoformulario" width=1% nowrap>Prazo restante:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>" name="<?=voContratoModificacao::$nmAtrNumMesesParaOFimPeriodo?>"  value="<?php echo(getMoeda($vo->numMesesParaOFimdoPeriodo));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="5" readonly>(meses)
	            </TD>				
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor Referencial:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReferencial?>"  value="<?php echo(getMoeda($vo->vlModificacaoReferencial,4));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario" nowrap>Valor Mensal Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlMensalAtualizado?>"  value="<?php echo(getMoeda($vo->vlMensalAtual));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Modificação ao Contrato<br>(em caso de prorrogação):</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoAoContrato?>"  value="<?php echo(getMoeda($vo->vlModificacaoAoContrato));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario">Valor Global Atualizado:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>" name="<?=voContratoModificacao::$nmAtrVlGlobalAtualizado?>"  value="<?php echo(getMoeda($vo->vlGlobalAtual));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>	            
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Valor Real:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>" name="<?=voContratoModificacao::$nmAtrVlModificacaoReal?>"  value="<?php echo(getMoeda($vo->vlModificacaoReal));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly></TD>
	            <TH class="campoformulario" nowrap>Valor Real Contrato:</TH>
	            <TD class="campoformulario"><INPUT type="text" id="<?=voContratoModificacao::$nmAtrVlGlobalReal?>" name="<?=voContratoModificacao::$nmAtrVlGlobalReal?>"  value="<?php echo(getMoeda($vo->vlGlobalReal));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="15" readonly>
	            </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Percentual:</TH>
	            <TD class="campoformulario" colspan="3">
	            <INPUT type="text" id="<?=voContratoModificacao::$nmAtrNumPercentual?>" name="<?=voContratoModificacao::$nmAtrNumPercentual?>"  value="<?php echo(getMoeda($vo->numPercentual,4));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 4, event);" class="<?=constantes::$CD_CLASS_CAMPO_READONLY_DIREITA?>" size="10" readonly>%
			</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoModificacao::$nmAtrObs?>" name="<?=voContratoModificacao::$nmAtrObs?>" class="camporeadonly" readonly><?=$vo->obs?></textarea>
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