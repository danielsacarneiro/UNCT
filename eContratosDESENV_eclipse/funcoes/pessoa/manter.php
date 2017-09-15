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

$titulo = $vo::getTituloJSP();
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
	campoDIVGestor = document.getElementById("<?=vogestor::getNmTabela()?>");
	campoDIVContratado = document.getElementById("<?=vopessoa::$ID_REQ_DIV_CONTRATADO?>");
	if(vinculo == <?=dominioVinculoPessoa::$CD_VINCULO_RESPONSAVEL?>){
		campoDIVGestor.style.display = "";		
	}
	else{ 
		campoDIVGestor.style.display = "none";
		limpaCampoGestor();
	}

	if(vinculo == <?=dominioVinculoPessoa::$CD_VINCULO_CONTRATADO?>){
		campoDIVContratado.style.display = "";		
	}
	else{ 
		campoDIVContratado.style.display = "none";
	}	
		
}

function iniciar(){
	verificaVinculo();	
}

function abrirJanelaAuxiliarGestor(){
	//abrirJanelaAuxiliar('".$link."',true, false, false);\" "		
}

</SCRIPT>
<?=setTituloPagina(null)?>
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
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo(documentoPessoa::getNumeroDocFormatado($doc));?>" required class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camponaoobrigatorio" size="50" required></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrTel?>" name="<?=vopessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camponaoobrigatorio" size="50" maxlength="100"></TD>
            </TR>    
			<TR>
                <TH class="campoformulario" nowrap width=1%>Endereço:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->endereco);?></textarea>
				</TD>
            </TR>     
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrObservacao?>" name="<?=vopessoa::$nmAtrObservacao?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
                				
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=vopessoa::$nmAtrEmail?>",
	            							"<?=vopessoa::$nmAtrDoc?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Assumo a responsabilidade de não incluir os valores obrigatórios.
                				
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
	                    Órgão Gestor/Código:<INPUT type="text" id="<?=vogestor::$nmAtrCd?>" name="<?=vogestor::$nmAtrCd?>" value="<?=complementarCharAEsquerda($colecao[vogestor::$nmAtrCd], "0", TAMANHO_CODIGOS)?>"  class="camporeadonly" size="5" readonly>
	                    Descrição: <INPUT type="text" id="<?=vogestor::$nmAtrDescricao?>" name="<?=vogestor::$nmAtrDescricao?>" value="<?=$colecao[vogestor::$nmAtrDescricao]?>"   class="camporeadonly" size="30" readonly>
	                    <?php echo getLinkPesquisa("../gestor");?>
                    </div>
                    <div id="<?=vopessoa::$ID_REQ_DIV_CONTRATADO?>">
		                <?php 
			            include_once(caminho_util. "dominioSimNao.php");
			            $comboSimNao = new select(dominioSimNao::getColecao());	             
			            echo "Participa do PAT (Programa de Alimentação do Trabalhador)?: ";
			            echo $comboSimNao->getHtmlCombo(vopessoa::$nmAtrInPAT,vopessoa::$nmAtrInPAT, $vo->inPAT, true, "camponaoobrigatorio", false,"");
			            ?>
                    </div>
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
