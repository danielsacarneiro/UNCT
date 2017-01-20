// Desenvovildo por André Luis
// Contato : djinn22001@yahoo.com.br / stopa190@hotmail.com
//  www.ajaxdesign.webege.com      

function cadastro(){
//  Preload da image de loading e criação das váriaveis	
var imagem_ajax=$('<img />').attr('src','imagens/ajax-loader.gif');
// Desativa o botão enviar
$("#botao_enviar").attr('disabled','disabled');

// Ativa o filtro de teclas do plugin Jquery Key Filter baseado em expressões regulares
// Permitido os símbolos . @ - _
$("#login").keyfilter(/[a-zA-Z0-9-@._]/);
$("#senha").keyfilter(/[a-zA-Z0-9-@._]/);
$("#email").keyfilter(/[a-z0-9-@._]/);
$("#captcha").keyfilter(/[a-zA-Z0-9]/);

// Cria as váriaveis de validação que serão usadas com valor nulo
loginok='';emailok='';telefoneok='';captchaok='';senhaok='';cpfok='';cnpj='';empresaok='';

// Ativa máscara nos campos CPF e Telefone usando o plugin Jquery Masked Input
$("#telefone").mask("(99)9999-9999");
$("#cpf").mask("999.999.999-99");}

// Funções para alternar entre os campos de CPF e CNPJ
// Ativa máscara nos campos CPF e CNPJ usando o plugin Jquery Masked Input
function alternar_cpf(){
$("#cnpj").replaceWith("<input type='text' class='textfield_1' name='cpf' id='cpf' size='25' onblur='verificar_cpf()'><input name='cadastro' type='hidden' id='cadastro' value='cpf' />");
$("#alt01").removeClass("div_formfield");$("#alt01").addClass("div_formselect");
$("#alt01").html("<span class='titulo1'>Sexo</span>");
$("#empresa").replaceWith("<select name='sexo' id='sexo'><option value='M' selected='selected'>Masculino</option><option value='F'>Feminino</option></select>");
$("#cpf-href").removeClass("titulo1-desativado");$("#cpf-href").addClass("titulo1");
$("#cnpj-href").removeClass("titulo1");$("#cnpj-href").addClass("titulo1-desativado");
$("#cpf").mask("999.999.999-99");}

function alternar_cnpj(){
$("#cpf").replaceWith("<input name='cnpj' type='text' class='textfield_1' id='cnpj' size='25' onblur='verificar_cnpj()'>");
$("#alt01").removeClass("div_formselect");$("#alt01").addClass("div_formfield");
$("#alt01").html("<span class='titulo1'>Empresa</span>");
$("#sexo").replaceWith("<input type='text' name='empresa' class='textfield_1' id='empresa' size='25' onblur='verificar_empresa()'>");
$("#cpf-href").removeClass("titulo1");$("#cpf-href").addClass("titulo1-desativado");
$("#cnpj-href").removeClass("titulo1-desativado");$("#cnpj-href").addClass("titulo1");
$("#cnpj").mask("99.999.999/9999-99");}

// Recarregar captcha
function recarregar_captcha(){jQuery.post("resetar_captcha.php");$('#div_captcha').empty();$('#div_captcha').load("recarregar_captcha.php");}

// Força da senha - Algoritmo simples - permitido os símbolos . @ - _
function verCaracterDaSenha(valor){var erespeciais=/[.@-_]/,ermaiuscula=/[A-Z]/,erminuscula=/[a-z]/,ernumeros=/[0-9]/,cont=0;if(erespeciais.test(valor))cont++;if(ermaiuscula.test(valor))cont++;if(erminuscula.test(valor))cont++;if(ernumeros.test(valor))cont++;return cont}
function segurancaBaixa(d){$('#bricks').css("background-position","0px -11px");senhaok = '0';$('#senha').addClass("textfield_erro")}
function segurancaMedia(d){$('#bricks').css("background-position","0px -22px");$('#senha').addClass("textfield_ok");senhaok = '1';}
function segurancaAlta(d){$('#bricks').css("background-position","0px -33px");$('#senha').addClass("textfield_ok");senhaok = '1';}
function segurancaRecomendada(d){$('#bricks').css("background-position","0px -44px");$('#senha').addClass("textfield_ok");senhaok = '1';}
function testaSenha(valor){var c=verCaracterDaSenha(valor),t=valor.length;if(t<5&&c<=2){segurancaBaixa()}else if(t>=5&&t<=8&&c<=2){segurancaMedia()}else if(t>8&&t<=11&&c<=3){segurancaAlta()}else if(t>11&&c>=3)segurancaRecomendada()}

// Verificar cadastro
function verificar_login(){$.ajax({type:"POST",data:login=$('#login'),cache:false,url:"verificar.php",beforeSend:function(){$('#login_disponibilidade').html("<img src='imagens/disponivel.png' width='16' height='16' /> <span class='texto2_disponivel'>Verificando login.</span>")},success:function(data){if(data=="invalido"){loginok='invalido';$('#login_disponibilidade').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'>Login inválido.</span>");$('#login').addClass("textfield_erro")}else if(data!="0"){loginok='0';$('#login_disponibilidade').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'>Este login ja está sendo usado.</span>");$('#login').addClass("textfield_erro")}else{loginok='1';$('#login_disponibilidade').html("<img src='imagens/disponivel.png' width='14' height='16' /> <span class='texto2_disponivel'>Login disponível.</span>");$('#login').addClass("textfield_ok")}}})}

function verificar_email(){$.ajax({type:"POST",data:email=$('#email'),cache:false,url:"verificar.php",beforeSend:function(){$('#email_disponibilidade').html("<img src='imagens/disponivel.png' width='16' height='16' /> <span class='texto2_disponivel'> Verificando email.</span>")},success:function(data){if(data=="invalido"){emailok='invalido';$('#email_disponibilidade').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'>Email inválido.</span>");$('#email').addClass("textfield_erro")}else if(data!="0"){emailok='0';$('#email_disponibilidade').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'> Este email ja está sendo usado.</span>");$('#email').addClass("textfield_erro")}else{emailok='1';$('#email_disponibilidade').html("<img src='imagens/disponivel.png' width='14' height='16' /> <span class='texto2_disponivel'> Email disponível.</span>");$('#email').addClass("textfield_ok")}}})}

function verificar_captcha(){if($("#captcha")==''){captchaok='0';return false};$.ajax({type:"POST",data:captcha=$('#captcha'),cache:false,url:"verificar.php",beforeSend:function(){$('#captcha_verificar').html("<span class='texto2_disponivel'>Verificando ...</span>")},success:function(data){if(data=="invalido"){captchaok='0';$('#captcha_verificar').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'>Código incorreto.</span>");$('#captcha').addClass("textfield_erro")}else if(data!="0"){captchaok='0';$('#captcha_verificar').html("<img src='imagens/indisponivel.png' width='14' height='16' /> <span class='texto2_indisponivel'>Código incorreto.</span>");$('#captcha').addClass("textfield_erro")}else{captchaok='1';$('#captcha').addClass("textfield_ok");$('#captcha_verificar').empty()}}})}

function verificar_cpf(){if ($("#cpf").val().match(/^([0-9]{3}\.){2}[0-9]{3}-[0-9]{2}$/)) {
$('#cpf').addClass("textfield_ok");cpfok='1';cnpjok='1';empresaok='1';$("#cpf").after("<input name='cadastro' id='cadastro' type='hidden' value='cpf'/>");   } 
else{$("#cpf").addClass("textfield_erro");cpfok='0'; }}

function verificar_cnpj(){
if($("#cnpj").val().match("^[0-9]{2}[.]{1}[0-9]{3}[.]{1}[0-9]{3}([/]{1})[0-9]{4}[-]{1}[0-9]{2}$")){
$('#cnpj').addClass("textfield_ok");cnpjok='1';cpfok='1';$("#cnpj").after("<input name='cadastro' id='cadastro' type='hidden' value='cnpj'/>");                     } 
else{$('#cnpj').addClass("textfield_erro");cnpjok='0';}}

function verificar_telefone(){if($("#telefone").val().match(/^([(]{1}[0-9]{2}[)]{1}[0-9]{4}[-]{1}[0-9]{4})$/)){
$('#telefone').addClass("textfield_ok");telefoneok = '1';}
else{$('#telefone').addClass("textfield_erro");telefoneok = '0';}}

function verificar_empresa(){if($("#empresa") == ''){$('#empresa').addClass("textfield_erro");empresaok = '0';}
else{$('#empresa').addClass("textfield_ok");empresaok = '1';}}

// Função para validar nome. São permitidos letras,espaço e acentos.
function IsAlphanumeric(Expression){RefString="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzçÇêóõáãéüÓÕÁÃÉÜÊ ";
if(Expression.length<1)
return(false);for(var i=0;i<Expression.length;i++){var ch=Expression.substr(i,1)
var a=RefString.indexOf(ch,0)
if(a==-1)
return(false);}return(true);}
function verificar_nome(){teste = $("#nome").val();
if(IsAlphanumeric(teste)== false){$("#nome").addClass("textfield_erro");nomeok = '0';}
else{$("#nome").addClass("textfield_ok");nomeok = '1';}}

// Se o checkbox for marcado e os campos anteriores estiverem correto ativa o botão de enviar
function aceitar_regras(){
if($('#aceitar').attr('checked')=='1'&&loginok=='1'&&emailok=='1'&&senhaok=='1'&&captchaok=='1'&&nomeok=='1'&&empresaok=='1'&&cpfok=='1'&&cnpjok=='1'&&telefoneok=='1'){
$('#botao_enviar').removeAttr("disabled")}else{$("#botao_enviar").attr('disabled','disabled');$("#aceitar").removeAttr('checked')}}

// Verifica se os campos estão corretos se o valor das váriaveis é diferente do inicial
// Envia o formulário para o arquivo cadastrar.php e executa a função atualizar()
function cadastrar(){var poststr=$("#form1").serialize();
$('#conteudo').html("<div align='center' style='height:350px;margin-top:20px;'><span class='titulo1'>Enviando, aguarde...</span><br /><img src='imagens/ajax-loader.gif' width='150' height='13'/></div>");
jQuery.post('cadastrar.php',poststr,atualizar)}

// Ajax data - atualiza o conteúdo da Div com a resposta do script php
function atualizar(res){$("#conteudo").html(res)};

// Tooltip - Dynamicdrive.com
var horizontal_offset="9px",vertical_offset="0",ie=document.all,ns6=document.getElementById&&!document.all;
function getposOffset(what,offsettype){var totaloffset=(offsettype=="left")?what.offsetLeft:what.offsetTop,parentEl=what.offsetParent;while(parentEl!=null){totaloffset=(offsettype=="left")?totaloffset+parentEl.offsetLeft:totaloffset+parentEl.offsetTop;parentEl=parentEl.offsetParent};return totaloffset}
function iecompattest(){return(document.compatMode&&document.compatMode!="BackCompat")?document.documentElement:document.body}
function clearbrowseredge(obj,whichedge){var edgeoffset=(whichedge=="rightedge")?parseInt(horizontal_offset)*-1:parseInt(vertical_offset)*-1;if(whichedge=="rightedge"){var windowedge=ie&&!window.opera?iecompattest().scrollLeft+iecompattest().clientWidth-30:window.pageXOffset+window.innerWidth-40;dropmenuobj.contentmeasure=dropmenuobj.offsetWidth;if(windowedge-dropmenuobj.x<dropmenuobj.contentmeasure)edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)}else{var windowedge=ie&&!window.opera?iecompattest().scrollTop+iecompattest().clientHeight-15:window.pageYOffset+window.innerHeight-18;dropmenuobj.contentmeasure=dropmenuobj.offsetHeight;if(windowedge-dropmenuobj.y<dropmenuobj.contentmeasure)edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight};return edgeoffset}
function showhint(menucontents,obj,e,tipwidth){if((ie||ns6)&&document.getElementById("hintbox")){dropmenuobj=document.getElementById("hintbox");dropmenuobj.innerHTML=menucontents;dropmenuobj.style.left=dropmenuobj.style.top=-500;if(tipwidth!=""){dropmenuobj.widthobj=dropmenuobj.style;dropmenuobj.widthobj.width=tipwidth};dropmenuobj.x=getposOffset(obj,"left");dropmenuobj.y=getposOffset(obj,"top");dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj,"rightedge")+obj.offsetWidth+"px";dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj,"bottomedge")+"px";dropmenuobj.style.visibility="visible";obj.onmouseout=hidetip}}
function hidetip(e){dropmenuobj.style.visibility="hidden";dropmenuobj.style.left="-500px"}
function createhintbox(){var divblock=document.createElement("div");divblock.setAttribute("id","hintbox");document.body.appendChild(divblock)};if(window.addEventListener){window.addEventListener("load",createhintbox,false)}else if(window.attachEvent){window.attachEvent("onload",createhintbox)}else if(document.getElementById)window.onload=createhintbox;

// Jquery Key Filter plugin
(function($){var defaultMasks={num:/[0-9]/,email:/[a-z0-9_\.\-@]/i,alpha:/[a-z_]/i,alphanum:/[a-z0-9_]/i,custom:/[a-z0-9-@._]/i},Keys={TAB:9,RETURN:13,ESC:27,BACKSPACE:8,DELETE:46},SafariKeys={63234:37,63235:39,63232:38,63233:40,63276:33,63277:34,63272:46,63273:36,63275:35},isNavKeyPress=function(e){var k=e.keyCode;k=$.browser.safari?(SafariKeys[k]||k):k;return(k>=33&&k<=40)||k==Keys.RETURN||k==Keys.TAB||k==Keys.ESC},isSpecialKey=function(e){var k=e.keyCode,c=e.charCode;return k==9||k==13||(k==40&&(!$.browser.opera||!e.shiftKey))||k==27||k==16||k==17||(k>=18&&k<=20)||($.browser.opera&&!e.shiftKey&&(k==8||(k>=33&&k<=35)||(k>=36&&k<=39)||(k>=44&&k<=45)))},getKey=function(e){var k=e.keyCode||e.charCode;return $.browser.safari?(SafariKeys[k]||k):k},getCharCode=function(e){return e.charCode||e.keyCode||e.which};$.fn.keyfilter=function(re){return this.keypress(function(e){if(e.ctrlKey||e.altKey)return;var k=getKey(e);if($.browser.mozilla&&(isNavKeyPress(e)||k==Keys.BACKSPACE||(k==Keys.DELETE&&e.charCode==0)))return;var c=getCharCode(e),cc=String.fromCharCode(c),ok=true;if(!$.browser.mozilla&&(isSpecialKey(e)||!cc))return;if($.isFunction(re)){ok=re.call(this,cc)}else ok=re.test(cc);if(!ok)e.preventDefault()})};$.extend($.fn.keyfilter,{defaults:{masks:defaultMasks},version:1.7});$(document).ready(function(){var tags=$('input[class*=mask],textarea[class*=mask]');for(var key in $.fn.keyfilter.defaults.masks)tags.filter('.mask-'+key).keyfilter($.fn.keyfilter.defaults.masks[key])})})(jQuery)
