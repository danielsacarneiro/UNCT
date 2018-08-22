<?php  
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();
//inicioComValidacaoUsuario(true);
$titulo = "AGENDAMENTO ALERTAS";
setCabecalho($titulo);
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
</HEAD>
<?=setTituloPagina($titulo)?>
<BODY CLASS="paginadados">
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
        			<TR>
            			<TD class="conteinerconteudodados">

<?php
echo "<b>EXECUCAO DE AGENDADOR DE TAREFAS WINDOWS</b>.<br><br>";

//Relatório diário
echo getTagHTMLAbreFormulario();
require (caminho_funcoes. "alertas/alerta_email_unico.php");
echo getTagHTMLFechaFormulario();

//Mensageria: envio de email aos contratos a vencer cadastrados
require (caminho_funcoes. "alertas/alerta_email_gestor.php");

?>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
</BODY>
</HTML>