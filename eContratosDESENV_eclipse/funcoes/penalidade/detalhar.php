<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbpenalidade.php");

//inicia os parametros
inicio();

$vo = new vopenalidade();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);
$vo->getDadosBanco($colecao);
putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = "PENALIDADE";
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
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>

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

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=vopenalidade::$nmAtrCdContrato?>" name="<?=vopenalidade::$nmAtrCdContrato?>" value="<?=$vo->cdContrato?>">
<INPUT type="hidden" id="<?=vopenalidade::$nmAtrAnoContrato?>" name="<?=vopenalidade::$nmAtrAnoContrato?>" value="<?=$vo->anoContrato?>">
<INPUT type="hidden" id="<?=vopenalidade::$nmAtrTipoContrato?>" name="<?=vopenalidade::$nmAtrTipoContrato?>" value="<?=$vo->tpContrato?>">

<INPUT type="hidden" id="<?=vopenalidade::$nmAtrCdPA?>" name="<?=vopenalidade::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">
<INPUT type="hidden" id="<?=vopenalidade::$nmAtrAnoPA?>" name="<?=vopenalidade::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
<INPUT type="hidden" id="<?=vopenalidade::$nmAtrCd?>" name="<?=vopenalidade::$nmAtrCd?>" value="<?=$vo->cd?>">
 
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
                <TH class="campoformulario" nowrap width=1%>Código:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
            </TR>   
            <?php           
            include_once (caminho_funcoes."contrato/dominioTipoContrato.php");
            $dominioTipoContrato = new dominioTipoContrato();
            
            $contrato = formatarCodigoAnoComplemento($colecao[vopenalidade::$nmAtrCdContrato],
            		$colecao[vopenalidade::$nmAtrAnoContrato],
            		$dominioTipoContrato->getDescricao($colecao[vopenalidade::$nmAtrTipoContrato]));
            
            $procAdm = formatarCodigoAno($colecao[vopenalidade::$nmAtrCdPA],
            		$colecao[vopenalidade::$nmAtrAnoPA]);
            
            ?>         
			<TR>
                <TH class="campoformulario" nowrap width=1%>PA:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($procAdm);?>"  class="camporeadonlyalinhadodireita" size="10" readonly></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Contrato:</TH>
				<TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonlyalinhadodireita" size="17" readonly></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Proc.Licitatório:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($vo->processoLic);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>  
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
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