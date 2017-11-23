<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

try{
//inicia os parametros
inicio();

$vo = new voPenalidadePA();
$vo->getVOExplodeChave();

//var_dump($vo->varAtributos);
$isHistorico = $vo->isHistorico();

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">
<INPUT type="hidden" id="<?=voPA::$nmAtrAnoPA?>" name="<?=voPA::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
 
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
                <INPUT type="hidden" id="<?=voPenalidadePA::$nmAtrSqHist?>" name="<?=voPenalidadePA::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }
            ?>
            <TR>
		         <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo(getDetalhamentoHTMLCodigoAno($vo->anoPA, $vo->cdPA, TAMANHO_CODIGOS_SAFI));?>
				 </TD>
	        </TR>
			<TR>
		         <TH class="campoformulario" nowrap width="1%">Número:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo getInputText(voPenalidadePA::$nmAtrSq, voPenalidadePA::$nmAtrSq, complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_READONLY);?>
				</TD>
	        </TR>	        
            <?php
 
			getDemandaDetalhamento($voDemanda);
			getContratoDet($voContrato);

			$comboTipo = new select(dominioTipoPenalidade::getColecaoComReferenciaLegal());
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	            <TD class="campoformulario" nowrap colspan=3>
				  <?php
				  $dominioTipo = new dominioTipoPenalidade();
				  echo $dominioTipo->getHtmlDetalhamento(voPenalidadePA::$nmAtrTipo, voPenalidadePA::$nmAtrTipo, $vo->tipo, true);
				  ?>
			  </TD>			  
			</TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Fundamento:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="3" cols="80" class="camporeadonly" readonly><?php echo($vo->fundamento);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data Aplicação:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voPenalidadePA::$nmAtrDtAplicacao?>" 
	            	       name="<?=voPenalidadePA::$nmAtrDtAplicacao?>" 
	            			value="<?php echo(getData($vo->dtAplicacao));?>"	            			 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
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
<?php 
}catch(Exception $ex){
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex);	
}
?>
