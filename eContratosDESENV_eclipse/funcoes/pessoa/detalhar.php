<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbpessoa.php");

//inicia os parametros
inicio();

$vo = new vopessoa();
//var_dump($vo->varAtributos);
$chave = @$_GET["chave"];
$array = explode("*",$chave);

$vo->getVOExplodeChave($chave);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = new dbpessoa();					
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
$vo->getDadosBanco($colecao[0]);
putObjetoSessao($vo->getNmTabela(), $vo);

$descricao = $colecao[0][vogestor::$nmAtrDescricao];
$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;


$nmFuncao = "DETALHAR ";
$titulo = "PESSOA";
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
			<TR>
                <TH class="campoformulario" nowrap width=1%>Código:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
            </TR>                                            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Nome:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camporeadonly" size="50" readonly></TD>
                <TH class="campoformulario" width="1%" nowrap>CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camporeadonly" size="20" maxlength="18" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone:</TH>
                <TD class="campoformulario" width="1%" colspan=3><INPUT type="text" id="<?=vopessoa::$nmAtrTel?>" name="<?=vopessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Endereço:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="3" cols="60" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camporeadonly" readonly><?php echo($vo->endereco);?></textarea>
				</TD>
            </TR>
            <TR>
                <TH class="campoformulario" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once("biblioteca_htmlPessoa.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, $colecao[0][vopessoavinculo::$nmAtrCd], "camporeadonly", " disabled ");
                    
                    //quando trouxer o gestor
                    if(false){
                    ?>                    
                    Órgão Gestor: <INPUT type="text" id="<?=vogestor::$nmAtrDescricao?>" name="<?=vogestor::$nmAtrDescricao?>"  value="<?php echo($descricao);?>>"  class="camporeadonly" size="50" readonly>
                    <?php
                    }?>
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