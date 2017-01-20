<?php
// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

require 'includes/gerar_captcha.php';
session_start();
$_SESSION['captcha'] = $codigo_captcha; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Cadastro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="André Luis">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/cadastro.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript">$(document).ready(cadastro);</script></head>
<body><div id="central" align="center">
<div id="header"><img src="imagens/bg_top.jpg" width="759" height="46" /></div>
<div id="conteudo">
<form id="form1" action="">
<div class="div_titlefield"><span class="titulo4">Cadastro </span></div><br class="clearboth"/>

<div class="div_formfield"><span class="titulo1">Nome</span></div>
<div class="div_textfield"><input name="nome" type="text" class="textfield_1" id="nome" size="25" maxlength="15" onblur="verificar_nome()"/><img src="imagens/ajuda.gif" alt="" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Digite seu nome completo.',this, event, '150px')" /></div><br class="clearboth"/>

<div class="div_formfield"><span class="titulo1">Login</span></div>
<div class="div_textfield"><input name="login" type="text" class="textfield_1" id="login" size="25" maxlength="15" onblur="verificar_login()"/><img src="imagens/ajuda.gif" alt="" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Digite seu login com no minímo 4 caracteres.Use apenas letras,números ou símbolos como .(ponto),@(arroba), -(hífen), _ (underline).',this, event, '150px')" /></div><br class="clearboth"/>
<div class="div_titlefield" id="login_disponibilidade"><span class="texto2">Disponibilidade  : </span><span class="texto2_disponivel"> Aguarde</span></div><br class="clearboth"/>

<div class="div_formfield"><span class="titulo1">Senha</span></div>
<div class="div_textfield"><input name="senha" type="password" class="textfield_1" id="senha" onkeyup="testaSenha(this.value);" size="25" maxlength="15"><img src="imagens/ajuda.gif" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Digite sua senha com no minímo 4 caracteres.Use apenas letras,números ou símbolos como .(ponto),@(arroba), -(hífen), _ (underline).',this, event, '150px')" /><br class="clearboth"/></div>
<div class="div_titlefield"><div class="forca_senha"><span class="texto2">For&ccedil;a 
</span></div><div class="forca_senha_barra" id="bricks"><!--  --></div></span></div><br class="clearboth"/>

<div class="div_formfield"><span class="titulo1">Email</span></div>
<div class="div_textfield"><input name="email" type="text" class="textfield_1" id="email" size="25" maxlength="25" onblur="verificar_email()"><img src="imagens/ajuda.gif" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Informe seu email corretamente, pois é o único modo para recuperar sua senha.',this, event, '150px')" /></div>

<div class="div_titlefield" id="email_disponibilidade"><span class="texto2">Disponibilidade  : </span><span class="texto2_disponivel">Aguarde</span></div><br class="clearboth"/>

<div class="div_formfield"><a href="#" onclick="alternar_cpf()" id="cpf-href" class="titulo1">CPF/</a><a href="#" onclick="alternar_cnpj()" class="titulo1-desativado" id="cnpj-href">CNPJ</a></div>
<div class="div_textfield"><input name="cpf" type="text" class="textfield_1" id="cpf" size="25" onblur="verificar_cpf()">
<img src="imagens/ajuda.gif" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Selecione o tipo de cadastro e preencha corretamente.',this, event, '150px')" /></div><br class="clearboth"/>

<div id="alt01" class="div_formselect"><span class="titulo1">Sexo</span></div>

<div class="div_textfield"><select name="sexo" id="sexo"><option value="M" selected="selected">Masculino</option><option value="F">Feminino</option></select>
 <img src="imagens/ajuda.gif" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Insira o nome da empresa ou selecione o sexo.',this,event,'150px')" /></div><br class="clearboth"/>
 
<div class="div_formfield"><span class="titulo1">Telefone</span></div>
<div class="div_textfield"><input name="telefone" type="text" class="textfield_1" id="telefone" size="25" onblur="verificar_telefone()"/><img src="imagens/ajuda.gif" alt="" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Digite seu telefone com DDD.',this, event, '150px')" /></div><br class="clearboth"/><br class="clearboth"/>
 
<div class="div_titlefield" id="div_captcha"><img name="captcha_img" id="captcha_img" src="captcha/captcha.<? echo rand(1,99999);?>.php" width="150" height="50" /></div><br class="clearboth"/>

<div class="div_formfield"><span class="titulo1">C&oacute;digo</span></div>
<div class="div_textfield"><input name="captcha" type="text" class="textfield_1" id="captcha" size="25" maxlength="7" onblur="verificar_captcha()"><a href="javascript:recarregar_captcha()"><img src="imagens/recarregar.gif"  width="16" height="16" class="icone_ajuda" onmouseover="showhint('Recarregar imagem',this, event, '120px')" /></a><img src="imagens/ajuda.gif" width="16" height="16" class="icone_ajuda" onmouseover="showhint('Insira o código exibido na imagem.',this, event, '150px')" /></div><br class="clearboth"/>
<div class="div_titlefield" id="captcha_verificar"></div><br class="clearboth"/>

<div id="div_checkbox" style="margin-left:100px;float:left;clear:both;"><span class="texto2_disponivel">
<input type="checkbox" id="aceitar" name="aceitar" onclick="aceitar_regras()" />
<span class="texto2">Eu aceito os termos do contrato.</span></span></div>
<br class="clearboth"/><br class="clearboth"/>

<div class="div_formselect"><input name="botao_enviar" type="button" class="botao_enviar1" id="botao_enviar" value="Cadastrar" onclick="return cadastrar()"/></div></form><br /><br />
</div><div><img src="imagens/bg_bottom.jpg" width="759" height="46"/></div></body></html>