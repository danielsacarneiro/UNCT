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

//alerta 1
require (caminho_funcoes. "alertas/alerta_encaminhamento_SAD.php");

//alerta 2
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste 
require (caminho_funcoes. "alertas/alerta_demanda_proposta_vencida.php");

//alerta 3
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
require (caminho_funcoes. "alertas/alerta_fim_prazo_NI_PA.php");

?>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
</BODY>
</HTML>