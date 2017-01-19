<?
// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

// Removido a letra O e o dígito 0 para evitar erros
$char = substr(str_shuffle('ABCDEFGHIJKLMNPQRSTUVWXYZ123456789'), 0, 5);
$codigo_captcha = rand(1, 5) . rand(1, 5) . $char;
?>