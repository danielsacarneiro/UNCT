<?php
// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

require 'includes/gerar_captcha.php';
session_start();
$_SESSION['captcha'] = $codigo_captcha;  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PHP DEBUG</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head><body><form action="cadastrar.php" method="post">
  <div align="center">
    <table width="319" border="0" cellpadding="2" cellspacing="2" class="tabela">
      <tr>
        <th colspan="2" scope="col" bgcolor="#FFFFFF"><img src="imagens/codigo.gif" width="35" height="35" /><br />
          <span class="titulo4">Teste o sistema sem javascript</span></th>
      </tr>
      <tr>
        <th width="168" class="titulo1" scope="col">NOME</th>
        <td width="144" scope="col">
          <input type="text" name="nome" id="nome" />
        </td>
      </tr>
      <tr>
        <th class="titulo1" scope="col">LOGIN</th>
        <td scope="col"><input type="text" name="login" id="login" /></td>
      </tr>
      <tr>
        <th class="titulo1" scope="col">SENHA</th>
        <th scope="col"><input type="text" name="senha" id="senha" /></th>
      </tr>
      <tr>
        <th class="titulo1" scope="col">EMAIL</th>
        <th scope="col"><input type="text" name="email" id="email" /></th>
      </tr>
      <tr>
        <th class="titulo1" scope="col">TELEFONE</th>
        <th scope="col"><input type="text" name="telefone" id="telefone" /></th>
      </tr>
      <tr>
        <th class="titulo1" scope="col">CPF</th>
        <th scope="col"><input type="text" name="cpf" id="cpf" /></th>
      </tr>
      <tr>
      <tr>
        <th class="titulo1" scope="col">CNPJ</th>
        <th scope="col"><input type="text" name="cnpj" id="cnpj" /></th>
      </tr>
      <tr>
      <tr>
        <th class="titulo1" scope="col">SEXO</th>
        <th scope="col"><label>
            <select name="sexo" id="sexo">
              <option value="M">M</option>
              <option value="F">F</option>
            </select>
        </label></th>
      </tr>
      <tr>
      <tr>
        <th class="titulo1" scope="col">EMPRESA</th>
        <th scope="col"><input type="text" name="empresa" id="empresa" /></th>
      </tr>
      <tr>
         <tr>
        <th class="titulo1" scope="col">TIPO</th>
        <th scope="col"><select name="cadastro" id="cadastro">
          <option value="cpf">Fisica</option>
          <option value="cnpj">Juridica</option>
        </select></th>
      </tr>
      <tr>
        <th colspan="2" scope="col"><img name="captcha_img" id="captcha_img" src="captcha/captcha.<? echo rand(1,99999);?>.php" width="150" height="50" /></th>
      </tr>
       <tr>
        <th class="titulo1" scope="col">C&Oacute;DIGO</th>
        <th scope="col"><input type="text" name="captcha" id="captcha" /></th>
      </tr>
      <tr>
        <th colspan="2" scope="col"><input name="button" type="submit" class="textfield_1" id="button" value="Testar" /></th>
      </tr>
    </table>
  </div>
</form>
<body>
</body>
</html>