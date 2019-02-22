<?php
include_once("../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

$vo = new voDocumento();
$vo->getVOExplodeChave();
$isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);
$vo->getDadosBanco($colecao);

$link = $vo->getEnderecoTpDocumento();
	
inicioComValidacaoUsuario(false);
	
$titulo = constantes::$nomeSistema;
setCabecalhoPorNivel($titulo,1);
$nmFuncao = "EXIBIR DOCUMENTO";
?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPaginaPorNivel($titulo,1)?>

<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	//history.back().back();
    //window.location.history.go(-2);
    //history.go(-2);
	//location.href="index.php";	
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action=""> 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
	<TR>
        <TD class="conteinerfiltro"><?=cabecalho?></TD>
	</TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
								<TD class="botaofuncao">
								<embed src="<?=$link?>" width="760" height="500" type='application/pdf'>
								</TD>
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
