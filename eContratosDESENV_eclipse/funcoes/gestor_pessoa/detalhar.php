<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."vousuario.php");
include_once(caminho_vos."dbgestorpessoa.php");

//inicia os parametros
inicio();

$vo = new vogestorpessoa();
//var_dump($vo->varAtributos);
$chave = @$_GET["chave"];
$array = explode("*",$chave);

$vo->cd = $array[0];
$vo->cdHistorico = $array[1];
$isHistorico = ("S" == $vo->cdHistorico);    
if($isHistorico){
    $sqHist = $array[2];
    $vo->sqHist = $sqHist;
}

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = new dbgestorpessoa();					
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
$vo->getDadosBanco($colecao[0]);
$descricao = $colecao[0][vogestor::$nmAtrDescricao];
$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;


$nmFuncao = "DETALHAR ";
$titulo = "RESPONSÁVEL";
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
    $complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
    $isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);    


?>
<!DOCTYPE html>
<HTML lang="pt-BR">
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancela() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=vogestorpessoa::$nmAtrCd?>" name="<?=vogestorpessoa::$nmAtrCd?>" value="<?=$vo->cd?>">
 
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
			<TR>
                <TH class="campoformulario" nowrap width=1%>Código:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
            </TR>                            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Órgão Gestor:</TH>
                <TD class="campoformulario" colspan="3"><INPUT type="text" value="<?php echo($descricao);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>                
			<TR>
                <TH class="campoformulario" nowrap width=1%>Nome:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vogestorpessoa::$nmAtrNome?>" name="<?=vogestorpessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camporeadonly" size="50" readonly></TD>
                <TH class="campoformulario" width="1%" nowrap>CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vogestorpessoa::$nmAtrDoc?>" name="<?=vogestorpessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camporeadonly" size="20" maxlength="18" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vogestorpessoa::$nmAtrEmail?>" name="<?=vogestorpessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vogestorpessoa::$nmAtrTel?>" name="<?=vogestorpessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camporeadonly" size="50" readonly></TD>
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
                                if($isExclusao){
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