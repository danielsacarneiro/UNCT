<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voContratoLicon();
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

$voDemanda = new voDemanda();
$voDemanda->getDadosBanco($colecao);

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
                <INPUT type="hidden" id="<?=voContratoLicon::$nmAtrSqHist?>" name="<?=voContratoLicon::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }	        	        	        
	       	          
 	        $selectExercicio = new selectExercicio();
			?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php	            
	            echo getDetalhamentoHTMLCodigoAno($vo->vodemandacontrato->anoDemanda, $vo->vodemandacontrato->cdDemanda);
	            echo getInputHidden(voDemandaContrato::$nmAtrAnoDemanda, voDemandaContrato::$nmAtrAnoDemanda, $vo->vodemandacontrato->anoDemanda);
	            echo getInputHidden(voDemandaContrato::$nmAtrCdDemanda, voDemandaContrato::$nmAtrCdDemanda, $vo->vodemandacontrato->cdDemanda);
	            $vodemandaTemp = new voDemanda(array($vo->vodemandacontrato->anoDemanda, $vo->vodemandacontrato->cdDemanda));
	            echo getLinkPesquisa ( "../".voDemanda::getNmTabela()."/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $vodemandaTemp->getValorChaveHTML() );
	             
				echo "Tipo: " . dominioTipoDemanda::getHtmlDetalhamento("", "", $voDemanda->tipo, false);
				?>	            
	        </TR>
			<?php 
			require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
			getContratoDet($voContrato, false, true);
			?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php                        
				echo dominioSituacaoContratoLicon::getHtmlDetalhamento("", "", $vo->situacao, false);
				?>
				</TD>
	        </TR>	        
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Vigência:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtVigenciaInicial) . " a " . getDetalhamentoHTML("", "", $voContrato->dtVigenciaFinal)?>
				</TD>
	        </TR>
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Dt.Publicação:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtPublicacao)?>
				</TD>				
	            <TH class="campoformulario" nowrap width="1%">Dt.Assinatura:</TH>
	            <TD class="campoformulario">
	            <?php echo getDetalhamentoHTML("", "", $voContrato->dtAssinatura)?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoLicon::$nmAtrObs?>" name="<?=voContratoLicon::$nmAtrObs?>" class="camporeadonly" readonly><?=$vo->obs?></textarea>
				</TD>
	        </TR>	        
<TR>
	<TD halign="left" colspan="4">
	<DIV class="textoseparadorgrupocampos">&nbsp;</DIV>
	</TD>
</TR>        	        	
	        <?php
	        echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";	        
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