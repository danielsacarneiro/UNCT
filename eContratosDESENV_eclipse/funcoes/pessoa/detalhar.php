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
$vo->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$descricao = $colecao[vogestor::$nmAtrDescricao];
$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
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
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo(documentoPessoa::getNumeroDocFormatado($doc));?>" class="camporeadonly" size="20" maxlength="18" readonly></TD>
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
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camporeadonly" readonly><?php echo($vo->endereco);?></textarea>
				</TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrObservacao?>" name="<?=vopessoa::$nmAtrObservacao?>" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>            
            <TR>
                <TH class="campoformulario" nowrap>Vínculo:</TH>
		       <TD class="campoformulario" colspan=3>
		         <TABLE id="campoformulario" class="campoformulario" cellpadding="0" cellspacing="0">						
		             <TBODY>
		                <?php	
		                include_once("biblioteca_htmlPessoa.php");
		                $vinculos = $colecao[vopessoavinculo::$nmAtrCd];
		                $colecaoVinculo = explode(CAMPO_SEPARADOR,$vinculos);
		                
		                $gestores = $colecao[vogestor::$nmAtrDescricao];
		                $cdGestor = $colecao[vogestor::$nmAtrCd];
		                $gestor = "";
		                if($gestores != null && $gestores !=""){
		                	$colecaoGestores = explode(CAMPO_SEPARADOR,$gestores);
		                	$gestor = $colecaoGestores[0];
		                }                
		                
		                if (is_array($colecaoVinculo))
		                        $tamanho = sizeof($colecaoVinculo);
		                else 
		                        $tamanho = 0;			
		                
		                $dominioPessoaVinculo = new dominioVinculoPessoa();
		                            
		                for ($i=0;$i<$tamanho;$i++) {
		                        $cdVinculo = $colecaoVinculo[$i];		                       
		                ?>
		                <TR >
		                    <TD class="tabeladados"><?php echo $cdVinculo;?></TD>
		                    <TD class="tabeladados"><?php echo $dominioPessoaVinculo->getDescricao($cdVinculo);?></TD>
			                <?php
			                if($cdVinculo == dominioVinculoPessoa::$CD_VINCULO_RESPONSAVEL){
			                ?>		                    
		                    <TD class="tabeladados">Órgão Gestor: <?php echo complementarCharAEsquerda($cdGestor, "0", TAMANHO_CODIGOS) . " - " . $gestor;?></TD>
			                <?php
							}				
			                ?>
		                </TR>					
		                <?php
						}				
		                ?>
		            </TBODY>
		        </TABLE>
		       </TD>                    
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