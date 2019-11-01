<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

//inicia os parametros
inicio();

$voproc = new voProcLicitatorio();

$titulo = "PORTARIAS";
setCabecalho($titulo);
?>

<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>

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
<?=setTituloPagina($titulo)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">
 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro"><?=cabecalho?></TD>
		</TR>
            <TR>
		         <TD class="campoformulario" colspan=3>
		         <?php
		         $colecao = dominioComissaoProcLicitatorio::getColecaoConsulta();
		         $chaves = array_keys($colecao);
		         
		         for ($i=0; $i < sizeof($chaves); $i++){
		         	$chave = $chaves[$i];
		         	$numCpl = dominioComissaoProcLicitatorio::getDescricaoStatic($chave);
		         	$nmPregoeiro = dominioComissaoProcLicitatorio::getNmPregoeiroPorCPL($chave);
		         	echoo(getTextoHTMLDestacado( "$numCpl - $nmPregoeiro"));
		         }
		         echo "<br>Todas Portarias:<br>" . dominioComissaoProcLicitatorio::getNumPortariaTodasCPL($anoPortaria);
		         ?>
				 </TD>
	        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>