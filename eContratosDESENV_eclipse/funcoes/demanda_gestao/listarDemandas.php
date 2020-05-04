<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

try{
//inicia os parametros
inicio();

$vo = new voDemanda();

$filtro  = new filtroConsultarDemandaGestao(false, false,true);
$filtro = $filtro->getNovoFiltroComAtributosAnterior();
$filtro->voPrincipal = $vo;
$filtro->setFiltroFormularioEncadeadoSetorDemanda();

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
//$colecao = $dbprocesso->consultarTelaGestaoDemandaDetalhePorTipo($filtro);
$colecao = $dbprocesso->consultarTelaConsulta($vo, $filtro);

$nmFuncao = "LISTAR DETALHAR ";
$titulo = voDemanda::getTituloDemandaGestaoJSP();
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

$inConsultaHTML = getInConsultarHTMLString();
?>

<!DOCTYPE html>
<HEAD>
<?=setTituloPagina(voDemanda::getTituloDemandaGestaoJSP())?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {

	lupa = document.frm_principal.lupa.value;
	//location.href="index.php?consultar=S&lupa="+ lupa;	
	location.href="index.php?consultar=<?=$inConsultaHTML?>&lupa="+ lupa;
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarAlteracaoDemanda.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
 
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
		        <?php	        	
		        	include_once(caminho_util. "dominioSimNao.php");
		        	$comboSimNao = new select(dominioSimNao::getColecao());	         
		            $selectExercicio = new selectExercicio(constantes::$ANO_INICIO);
				  ?>
				<TR>
	                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
	                <TD class="campoformulario" nowrap colspan=3><?php echo $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, " disabled ");?></TD>
	            </TR>
				<TR>
	                <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	                <TD class="campoformulario" nowrap colspan=3><?php echo getTextoHTMLNegrito(dominioTipoDemanda::getDescricao($filtro->vodemanda->tipo));?></TD>
	            </TR>	            
				<TR>
	                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
	                <TD class="campoformulario" nowrap colspan=3><?php echo getTextoHTMLNegrito(dominioSetor::getDescricao($filtro->vodemanda->cdSetorDestino));?></TD>
	            </TR>
				<?php
				$colecaoTramitacao = $vo->dbprocesso->consultarDemandaGestaoTramitacao($vo);
				//mostrarGridDemandaGestao($colecaoTramitacao, true);
				?>       	    

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
	                    	<?=getBotoesRodape();?>
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
<?php 
}catch(Exception $ex){
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex);	
}
?>
