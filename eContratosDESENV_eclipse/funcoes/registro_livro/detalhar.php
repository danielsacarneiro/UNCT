<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

//inicia os parametros
inicio();

$vo = new voRegistroLivro();
$vo->getVOExplodeChave();
//var_dump($vo);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
$vo->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = voRegistroLivro::getTituloJSP();
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
<?=setTituloPagina(voRegistroLivro::getTituloJSP())?>
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
                <INPUT type="hidden" id="<?=voRegistroLivro::$nmAtrSqHist?>" name="<?=voRegistroLivro::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>  
            <?php }            	
            
            getContratoDet($vo->voContrato, false, true);            
            ?>
			<TR>
	            <TH class="campoformulario" nowrap>Livro:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo getInputText(voRegistroLivro::$nmAtrNumLivro, voRegistroLivro::$nmAtrNumLivro, $vo->numLivro, constantes::$CD_CLASS_CAMPO_READONLY, 3, 3, "");
	            echo " Folha" . getInputText(voRegistroLivro::$nmAtrNumFolha, voRegistroLivro::$nmAtrNumFolha, $vo->numFolha, constantes::$CD_CLASS_CAMPO_READONLY, 3, 3, "");
	            ?>
				</TD>
	        </TR>
            <TR>
	            <TH class="campoformulario" nowrap>Dt.Registro:</TH>
				<TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voRegistroLivro::$nmAtrDtRegistro?>" 
	            	       name="<?=voRegistroLivro::$nmAtrDtRegistro?>" 
	            			value="<?php echo(getData($vo->dtRegistro));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly> 				            
			</TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voRegistroLivro::$nmAtrObservacao?>" name="<?=voRegistroLivro::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
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