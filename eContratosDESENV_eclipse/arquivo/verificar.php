<?php
// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

session_start();
include "includes/mysql.php";
$login = $_POST['login'];
$captcha = strtoupper($_POST['captcha']);
$email = $_POST['email'];

if($login != ''){
if(eregi("^[a-zA-Z0-9.@_-]{4,15}$", $login)) {
$sql = "select login from usuarios where login = '$login' ";
$rsd = mysql_query($sql);
$msg = mysql_num_rows($rsd); }
else { $msg = "invalido";}
echo $msg; }

if($email != ''){
if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
$sql = "select email from usuarios where email = '$email' ";
$rsd = mysql_query($sql);
$msg = mysql_num_rows($rsd); }
else { $msg = "invalido";}
echo $msg; }

if($captcha != ''){
if(eregi("^([A-Z0-9]{5,7})$", $captcha) || $captcha == $_SESSION['captcha']) {
echo "0"; }
else { $msg = "invalido";}
echo $msg; }

?>