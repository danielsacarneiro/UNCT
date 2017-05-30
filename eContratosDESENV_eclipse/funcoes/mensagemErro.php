<?php
include_once("../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

inicio();
setCabecalhoPorNivel(null,1);

$msgErro = @$_GET["texto"];
$cdTela = @$_GET["cdTela"];

$vo = getObjetoSessao(constantes::$ID_REQ_SESSAO_VO);

//tela sucesso
if($cdTela != null && $cdTela == 1){
	$paginaEncaminhamento = "../index.php";
	$classMensagem = "campomensagemverde";
	$msg = "OPERACAO REALIZADA COM SUCESSO.<br>";	
}else{
	
	if($vo != null){
		$paginaEncaminhamento = $vo->getNmTabela()."/index.php?consultar=S";
	}else{
		$paginaEncaminhamento = "../index.php";
	}	
	$classMensagem = "campomensagemvermelho";
	$msg = "OPERACAO $nmFuncao FALHOU.<br>$msgErro";
}

?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPaginaPorNivel(null,1)?>

<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	//history.back();
    //window.location.history.go(-2);
    //history.go(-2);
	//location.href="index.php";	
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="<?=$paginaEncaminhamento?>"> 
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
                    <TH class="<?=$classMensagem?>" width="100%"><?=$msg?></TH>			
                </TR>
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
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="submit" accesskey="o">OK</button></TD>
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
