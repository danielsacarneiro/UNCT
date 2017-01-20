<?
// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

//Configuração do Banco de dados
$SQLHOST = 'localhost' ; //Endereço do servidor
$SQLLOGIN = 'root' ; //Login do usuario
$SQLPASS = '' ; //Senha
$DATABASE = 'usuarios' ; //Nome do banco de dados

// Função para registrar os erros do banco de dados
function log_evento($mensagem){
$hora = gmdate("H:i:s");
$data = gmdate("d/m/y");	
$arquivo = fopen("cadastro.log","a+");
$registro = "$hora - $data : $mensagem.\r\n";
fputs ($arquivo,$registro); 
fclose($arquivo); 
exit("<div align='center'><img src='imagens/indisponivel.png' width='14' height='16'/> <span class='titulo1'>O sistema está temporariamente indisponível.Tenta novamente mais tarde.</span></div></div><div><img src='imagens/bg_bottom.jpg' width='759' height='46'/></div></div></body></html>");}

//Conecta ao servidor MySQL
$conectar = @mysql_connect("$SQLHOST","$SQLLOGIN","$SQLPASS") OR log_evento("Falha ao conectar no servidor MySQL");
$db = @mysql_select_db($DATABASE,$conectar) OR log_evento("Falha ao selecionar o banco de dados");

?>