<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbpessoa.php");
include_once(caminho_filtros . "filtroManterPessoa.php");

//inicia os parametros
inicio();

$titulo = "CONSULTAR PESSOAS";
setCabecalho($titulo);

$filtro  = new filtroManterPessoa();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = ("S" == $cdHistorico); 

$dbprocesso = new dbpessoa();
$voPessoa = new vopessoa();
$colecao = $dbprocesso->consultarPessoa($voPessoa, $filtro);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function limparFormulario() {	

	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
	}	
}

function detalhar(isExcluir) {    
    if(isExcluir == null || !isExcluir)
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    else
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";
    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
    	
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave;
}

function excluir() {
    detalhar(true);
}

function incluir() {
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">

<INPUT type="hidden" name="utilizarSessao" value="N">
<INPUT type="hidden" id="numTotalRegistros" value="<?=$numTotalRegistros?>">
<INPUT type="hidden" name="consultar" id="consultar" value="N">    

<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro">
        <?=cabecalho?>
		</TD>
		</TR>
<TR>
    <TD class="conteinerfiltro">
    <DIV id="div_filtro" class="div_filtro">
    <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
        <TBODY>
			<TR>
                <TH class="campoformulario" nowrap>Nome:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="50" ></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>            
            <TR>
                <TH class="campoformulario" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once("biblioteca_htmlPessoa.php");
                    include_once(caminho_vos . "vopessoavinculo.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, "", "camponaoobrigatorio", "");                                        
                    ?>
            </TR>
            <TR>
                <TH class="campoformulario" nowrap>Gestor:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes. "gestor/biblioteca_htmlGestor.php");
                    echo getComboGestor(null, vogestor::$nmAtrCd, vogestor::$nmAtrCd, $filtro->cdGestor);                    
                    ?>
            </TR>
        <?php
        $comboOrdenacao = new select(vopessoa::getAtributosOrdenacao());
        $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
        echo getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, false, $cdHistorico)?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                    <TH class="headertabeladados" width="1%">Código</TH>
                    <TH class="headertabeladados">Nome</TH>
                    <TH class="headertabeladados">vínculo</TH>
                    <TH class="headertabeladados" width="1%">Email</TH>
                    <TH class="headertabeladados" width="1%">Telefone</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;								 
                            
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vopessoa();
                        $voAtual->getDadosBanco($colecao[$i]);
                                                                
                        $dsGestor = "";
                                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                    <TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][vopessoa::$nmAtrCd], "0", TAMANHO_CODIGOS);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrNome];?></TD>
                    <TD class="tabeladados"><?php echo $dsGestor;?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrEmail];?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][vopessoa::$nmAtrTel]?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=12><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>				
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
                            <TD class="botaofuncao"><button id="bttdetalhar" class="botaofuncaop" type="button" onClick="javascript:detalhar(false);" accesskey="d">Detalhar</button></TD>
                            <TD class="botaofuncao"><?=getBotaoIncluir()?></TD>
                            <TD class="botaofuncao"><?=getBotaoAlterar()?></TD>
                            <TD class="botaofuncao"><?=getBotaoExcluir()?></TD>                            
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
