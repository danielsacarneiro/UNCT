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

require (caminho_funcoes. "alertas/alerta_email_unico.php");

$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];
$isEnvioEmailRelatorio = getAtributoComoBooleano($enviarEmail);

if($isEnvioEmailRelatorio && voMensageria::$ATIVADO){
	//so deve enviar a mensageria se se tratar do agendamento automatico
	//para tanto o $isEnvioEmailRelatorio ser� true
	echoo("Enviando email aos gestores.");
	require (caminho_funcoes. "alertas/alerta_email_gestor.php");
}
?>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
</BODY>
</HTML>