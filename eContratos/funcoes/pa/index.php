<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbPA.php");
include_once(caminho_vos . "vopessoa.php");
include_once(caminho_vos . "voPA.php");
include_once(caminho_filtros . "filtroManterPA.php");

//inicia os parametros
inicio();

$titulo = "CONSULTAR P.A.´S";
setCabecalho($titulo);

$filtro  = new filtroManterPA();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$vo = new voPA();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPenalidade($vo, $filtro);
//remove a colecao de tramitacao da sessao pra iniciar do zero
removeObjetoSessao(voPA::$nmAtrColecaoTramitacao);

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
	lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
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

function validaFormulario() {
	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;
	if(isConsultarArquivo == "S")
		document.frm_principal.<?=vopessoa::$nmAtrNome?>.required = true;
	else
		document.frm_principal.<?=vopessoa::$nmAtrNome?>.required = false;
}

</SCRIPT>
<?=setTituloPagina(null)?>
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
                <TH class="campoformulario" nowrap>Cód.Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>"  value="<?php echo complementarCharAEsquerda($filtro->cdPessoa, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" ></TD>
                <TH class="campoformulario" nowrap width="1%">Nome:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="50" ></TD>
            </TR>            
            <TR>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
				<TH class="campoformulario" nowrap>Situação:</TH>
                <TD class="campoformulario" colspan="1">
                     <?php
                    include_once("biblioteca_htmlPA.php");                    
                    echo getComboSituacaoPA(voPA::$nmAtrSituacao, voPA::$nmAtrSituacao, $filtro->situacao, "camponaoobrigatorio", "");                                        
                    ?>
            </TR>
            <TR>
				<TH class="campoformulario" nowrap>Servidor Responsável:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");                    
                    echo getComboPessoaRespPA(voPA::$nmAtrCdResponsavel, voPA::$nmAtrCdResponsavel, $filtro->cdResponsavel, "camponaoobrigatorio", "");                                        
                    ?>
            </TR>
            <TR>
				<?php 
				include_once(caminho_util."radiobutton.php");
				$arraySimNao = array("S" => "Sim",
						"N" => "Não");		
				$radioArquivo = new radiobutton($arraySimNao);				
				?>									
               <TH class="campoformulario" nowrap>Procurar em arquivos:</TH>
				<TD class="campoformulario" colspan="3">
					<?php echo $radioArquivo->getHtmlRadioButton(filtroManter::$nmAtrCdConsultarArquivo,filtroManter::$nmAtrCdConsultarArquivo, $filtro->cdConsultarArquivo, false, "onClick='validaFormulario();'");?>&nbsp;&nbsp;
				</TD>
			</TR>
            
       <?php
        /*$comboOrdenacao = new select(voPA::getAtributosOrdenacao($cdHistorico));
        $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
        echo getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, true, $cdHistorico)*/
       echo getComponenteConsultaFiltro($vo->temTabHistorico, $filtro);
        ?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<?php 

if($filtro->cdConsultarArquivo == "S"){
	include("grid_arquivo.php");
}else{
	include("grid_index.php");
}
?>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
