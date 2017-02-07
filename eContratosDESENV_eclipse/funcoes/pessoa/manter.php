<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos . "vopessoavinculo.php");
include_once("dominioVinculoPessoa.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new vopessoa();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
    $vo->getVOExplodeChave($chave);
    $isHistorico = ($vo->sqHist != null && $vo->sqHist != "");
    
	$dbprocesso = new dbpessoa(null);					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);    
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}

$titulo = "PESSOA";
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;

?>
<!DOCTYPE html>

<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">

function transferirDadosOrgaoGestor(cdGestor, dsGestor) {		   
	document.getElementsByName("<?=vogestor::$nmAtrCd?>").item(0).value = completarNumeroComZerosEsquerda(cdGestor, <?=TAMANHO_CODIGOS?>);
	document.getElementsByName("<?=vogestor::$nmAtrDescricao?>").item(0).value = dsGestor;
}

function limpaCampoGestor() {		   
	document.getElementsByName("<?=vogestor::$nmAtrCd?>").item(0).value = "";
	document.getElementsByName("<?=vogestor::$nmAtrDescricao?>").item(0).value = "";
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!validaVinculo())
		return false;	
		
	return true;
}

function cancela() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return confirm("Confirmar Alteracoes?");    
}

/*function carregaGestorPessoa(){    
	<?php
		    $idDiv = "DIV";
		    $idCampoGestor = vogestor::$nmAtrDescricao;
	?>
	str = "";
	campoGestor = document.frm_principal.<?=$idCampoGestor?>;
	if(campoGestor != null)
		str = campoGestor.value;	 
		
	if(str.length > 3)    
		getDadosResponsavel('<?=$idCampoGestor?>', '<?=$idDiv?>');
}

function limparDiv(){
	document.frm_principal.<?=$idCampoGestor?>.value = "";
	//document.frm_principal.<?=$idCampoGestor?>.required = false;
	document.frm_principal.<?=$idCampoGestor?>.style.display = "none";
	
	campo = document.getElementById("<?=$idDiv?>");	
	campo.innerHTML = "";	
}

function validaVinculo(){
	vinculo = document.frm_principal.<?=vopessoavinculo::$nmAtrCd?>.value;
	if(vinculo == <?=dominioVinculoPessoa::$CD_VINCULO_RESPONSAVEL?>){
		if (!isRadioButtonConsultaSelecionado("document.frm_principal.<?=vogestor::$nmAtrCd?>", true)){
			exibirMensagem("Selecione o órgão gestor!");
			document.frm_principal.<?=vogestor::$nmAtrDescricao?>.focus();
			return false;	
		}
	}
	
	return true;
}*/

function validaVinculo(){
	vinculo = document.frm_principal.<?=vopessoavinculo::$nmAtrCd?>.value;
	if(vinculo == <?=dominioVinculoPessoa::$CD_VINCULO_RESPONSAVEL?>){
		
		if (!isCampoTextoValido(document.frm_principal.<?=vogestor::$nmAtrCd?>, true, 1, <?=TAMANHO_CODIGOS?>, true)){
			exibirMensagem("Selecione o Órgão Gestor!");
		    return false;
		}

	}
	
	return true;
}

function verificaVinculo(){
	vinculo = document.frm_principal.<?=vopessoavinculo::$nmAtrCd?>.value;
	campo = document.getElementById("<?=vogestor::getNmTabela()?>");
	if(vinculo == <?=dominioVinculoPessoa::$CD_VINCULO_RESPONSAVEL?>){
		campo.style.display = "";		
	}
	else{ 
		campo.style.display = "none";
		limpaCampoGestor();
	}	
}

function iniciar(){
	verificaVinculo();	
}

function abrirJanelaAuxiliarGestor(){
	//abrirJanelaAuxiliar('".$link."',true, false, false);\" "		
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>" value="<?=$vo->cd?>">
 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro"><?=cabecalho?></TD>
		</TR>
        <TR>
            <TD class="conteinerfiltro">
            <DIV id="div_filtro" class="div_filtro">
            <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
            <TBODY>
	        <?php if(!$isInclusao){?>
	        	<TR>
	        	<TH class="campoformulario" nowrap width=1%>Código:</TH>
	        	<TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
	        	</TR>        	 
	        <?php }?>            
            <!-- <TR>
                <TH class="campoformulario" nowrap>Órgão Gestor:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes. "gestor/biblioteca_htmlGestor.php");
                    echo getComboGestor(null, $nmAtrCdGestor, $nmAtrCdGestor, $vo->cdGestor);                    
                    ?>
            </TR>-->                            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Nome:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="50" required></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camponaoobrigatorio" size="50" required></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrTel?>" name="<?=vopessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camponaoobrigatorio" size="50"></TD>
            </TR>    
			<TR>
                <TH class="campoformulario" nowrap width=1%>Endereço:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="3" cols="60" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camponaoobrigatorio" ><?php echo($vo->endereco);?></textarea>
				</TD>
            </TR>     
            <TR>
                <TH class="campoformulario" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once("biblioteca_htmlPessoa.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, $colecao[vopessoavinculo::$nmAtrCd], "camponaoobrigatorio", " required onChange='verificaVinculo();' ");                    
                    ?>                     
                    <div id="<?=vogestor::getNmTabela()?>">
	                    Órgão Gestor/Código:<INPUT type="text" id="<?=vogestor::$nmAtrCd?>" name="<?=vogestor::$nmAtrCd?>" value=""  class="camporeadonly" size="5" readonly>
	                    Descrição: <INPUT type="text" id="<?=vogestor::$nmAtrDescricao?>" name="<?=vogestor::$nmAtrDescricao?>" value=""  class="camporeadonly" size="30" readonly>
	                    <?php echo getLinkPesquisa("../gestor");?>
                    </div>
                    <!-- <div id="<?=vogestor::getNmTabela()?>">
                    Órgão Gestor/Código:<INPUT type="text" id="<?=vogestor::$nmAtrCd?>" name="<?=vogestor::$nmAtrCd?>"  onKeyUp="verificaVinculo();" value=""  class="campoobrigatorio" size="5">
                    Descrição: <INPUT type="text" id="<?=vogestor::$nmAtrDescricao?>" name="<?=vogestor::$nmAtrDescricao?>"  onKeyUp="verificaVinculo();" value=""  class="campoobrigatorio" size="15">
                    </div>
	                <div id="<?=$idDiv?>">
	                <?php	                 
	                 //echo getComboGestorResponsavel(new dbgestorpessoa(), vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $voContrato->cdGestor, $voContrato->cdGestorPessoa);                    
	                //echo getComboGestorResponsavel("", "");
	                 ?>
	                </div> -->                    
            </TR>               

        <?php if(!$isInclusao){
            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
        }?>
            </TBODY>
            </TABLE>
            </DIV>
            </TD>
        </TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
								<?php
								if($funcao == "I" || $funcao == "A"){
								?>
                                    <TD class="botaofuncao"><?=getBotaoConfirmar()?></TD>
								<?php
								}?>
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="button" accesskey="c">Cancelar</button></TD>                                
						    </TR>
		                    </TABLE>
	                    </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
