<?php  
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();

$titulo = "AGENDAMENTO ALERTAS";

setCabecalho($titulo);
cabecalho;
?>
<!DOCTYPE html>
<HTML>
<HEAD>
</HEAD>
<?=setTituloPagina($titulo)?>
<BODY CLASS="paginadados">
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
        			<TR>
            			<TD class="conteinerconteudodados">

<?php
echo "EXECUCAO DE AGENDADOR DE TAREFAS WINDOWS.<br><br>";

require (caminho_funcoes. "alertas/alertas_ativos.php");


//envia alertas de contratos a vencer
//require (caminho_funcoes. "alertas/alerta_contratos_a_vencer.php");
?>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
</BODY>
</HTML>