<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voMensageria();
$vo->getVOExplodeChave();

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
	            <TH class="campoformulario" nowrap width="1%">Alerta:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo getDetalhamentoHTML(voMensageria::$nmAtrSq, voMensageria::$nmAtrSq, complementarCharAEsquerda($vo->sq, "0", constantes::$TAMANHO_CODIGOS));?>
				</TD>
	        </TR>            
            <?php	       	          
 	        require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
 	        //getContratoDetalhamento($voContrato, $colecao);
 	        getContratoDet($voContrato, false, true);
			?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Gestor:</TH>
	            <TD class="campoformulario" colspan="3">
                    Código:<INPUT type="text" id="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" name="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" value="<?=complementarCharAEsquerda($colecao[voContratoInfo::$nmAtrCdPessoaGestor], "0", TAMANHO_CODIGOS)?>"  class="camporeadonly" size="5" readonly>
                    Nome: <INPUT type="text" id="<?=voContratoInfo::$IDREQNmPessoaGestor?>" name="<?=voContratoInfo::$IDREQNmPessoaGestor?>" value="<?=$colecao[voContratoInfo::$IDREQNmPessoaGestor]?>"   class="camporeadonly" size="30" readonly>
                    E-mail: <INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>" value="<?=$colecao[vopessoa::$nmAtrEmail]?>"   class="camporeadonly" size="30" readonly>
	            </TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php	             
	            echo dominioTipoMensageria::getHtmlDetalhamento(voMensageria::$nmAtrTipo, voMensageria::$nmAtrTipo, $vo->tipo);
	            ?>
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Habilitado:</TH>
	            <TD class="campoformulario">
	            <?php
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());
	            echo dominioSimNao::getHtmlDetalhamento(voMensageria::$nmAtrInHabilitado, voMensageria::$nmAtrInHabilitado, $vo->inHabilitado, false);
	            ?></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Frequência:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php				                        
				echo getDetalhamentoHTML(voMensageria::$nmAtrNumDiasFrequencia, voMensageria::$nmAtrNumDiasFrequencia, $vo->numDiasFrequencia);
				?>(dias)
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Período:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php
				$dataInicio = getData($vo->dtInicio);
				echo getDetalhamentoHTML(voMensageria::$nmAtrDtInicio, voMensageria::$nmAtrDtInicio, $dataInicio);
				echo " a " . getDetalhamentoHTML(voMensageria::$nmAtrDtFim, voMensageria::$nmAtrDtFim, getData($vo->dtFim));
				?>
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voMensageria::$nmAtrObs?>" name="<?=voMensageria::$nmAtrObs?>" class="camporeadonly" readonly><?=$vo->obs?></textarea>
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