<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voContratoTramitacao.php");

//inicia os parametros
inicio();

$vo = new voContratoTramitacao();
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
<?=setTituloPagina(null)?>
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

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrCdContrato?>" name="<?=voContratoTramitacao::$nmAtrCdContrato?>" value="<?=$vo->cdContrato?>">
<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrAnoContrato?>" name="<?=voContratoTramitacao::$nmAtrAnoContrato?>" value="<?=$vo->anoContrato?>">
<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrTipoContrato?>" name="<?=voContratoTramitacao::$nmAtrTipoContrato?>" value="<?=$vo->tipoContrato?>">
<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrSq?>" name="<?=voContratoTramitacao::$nmAtrSq?>" value="<?=$vo->sq?>">
 
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
                <INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrSqHist?>" name="<?=voContratoTramitacao::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }
                     
            require_once (caminho_funcoes."contrato/dominioTipoContrato.php");
            $dominioTipoContrato = new dominioTipoContrato();
            
            $contrato = formatarCodigoAnoComplemento($colecao[voContratoTramitacao::$nmAtrCdContrato],
            		$colecao[voContratoTramitacao::$nmAtrAnoContrato],
            		$dominioTipoContrato->getDescricao($colecao[voContratoTramitacao::$nmAtrTipoContrato]));
                        
            $docContratada = $colecao[vopessoa::$nmAtrDoc];
            $nmContratada = $colecao[vopessoa::$nmAtrNome];            
            ?>         
			<TR>
                <TH class="campoformulario" nowrap width=1%>Contrato:</TH>
				<TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonlyalinhadodireita" size="17" readonly></TD>
            </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
	            <TD class="campoformulario" width="1%"><INPUT type="text" id="nmContratada" name="nmContratada"  value="<?php echo($nmContratada);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
	            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="docContratada" name="docContratada"  value="<?php echo($docContratada);?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camporeadonly" size="20" maxlength="20" <?=$readonly?>></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Texto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Referência:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voContratoTramitacao::$nmAtrDtReferencia?>" 
	            	       name="<?=voContratoTramitacao::$nmAtrDtReferencia?>" 
	            			value="<?php echo(getData($vo->dtReferencia));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
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