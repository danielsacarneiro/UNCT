<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

try{
//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voDemanda::getTituloDemandaGestaoJSP();
setCabecalho($titulo);

$vo = new voDemanda();
$filtro  = new filtroConsultarDemandaGestao(false, true,true);
$filtro->zerarFiltroControleSessao();
$filtro->voPrincipal = $vo;
//$filtro = filtroManter::verificaFiltroSessao($filtro);

//$filtro->setNmFiltroAnteriorSessao();
$nmFiltroSessao = $filtro->getNmFiltroAnteriorSessao();

if($nmFiltroSessao != null){
	$filtro = getObjetoSessao($nmFiltroSessao, true);	
	echoo ("BUSCOU NA SESSAO " . $nmFiltroSessao);
	echoo ("PEGOU DA SESSAO FILTRO ATUAL" . $filtro->nmFiltro);
}

listarObjetoSessaoPorString("filtro");
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
echoo("nome filtro da consulta:".$filtro->nmFiltroOriginal);
echoo("TIPO:");
var_dump($filtro->vodemanda->tipo);
$colecao = $dbprocesso->consultarTelaConsultaGestaoDemanda($filtro);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

$inConsultaHTML = getInConsultarHTMLString();
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

<?php 
//getFuncaoJSDetalhar()
?>

function detalhar(isExcluir) {

	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;

    var chave = document.frm_principal.rdb_consulta.value;	
	var lupa = "N";
	try{
		lupa = document.frm_principal.lupa.value;
	}catch(ex){
	}

	var nmFiltroAnterior = "<?=$filtro->nmFiltro?>";
	var linkNovo ="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&nmFiltroAnterior="+ nmFiltroAnterior + "&lupa="+ lupa;
	linkNovo = linkNovo + "&consultar=S";
	location.href=linkNovo;
}


</SCRIPT>
<?=setTituloPagina($vo->getTituloDemandaGestaoJSP())?>
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
	   <?php	        	
	   include_once("telaFiltro.php");
       echo getComponenteConsultaFiltro(false, $filtro);
        ?>
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
                    <TH class="headertabeladados" width="90%">Tipo</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Tempo.Médio(dias)</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Num.Demandas</TH>                    
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=4;                
                
                $numTotalDeDemandas = 0;
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$tipo = $colecao[$i][voDemanda::$nmAtrTipo];
                	$tipoDesc = dominioTipoDemanda::getDescricao($tipo);
                	
                	$numTotal = $colecao[$i][filtroConsultarDemandaGestao::$NmColNumTotalDemandas];
                    $tempoMedioVida = $registro[filtroConsultarDemandaGestao::$NmColNumTempoVidaMedio];
                    
                    $numTotalDeDemandas = $numTotalDeDemandas + $numTotal;
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $tipo);?>					
                    </TD>
                                       
                    <TD class="tabeladados"><?php echo $tipoDesc?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo complementarCharAEsquerda(getMoeda($tempoMedioVida,2), "0", constantes::$TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo complementarCharAEsquerda(getMoeda($numTotal,0), "0", constantes::$TAMANHO_CODIGOS_SAFI);?></TD>                    
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total: <?=complementarCharAEsquerda(getMoeda($numTotalDeDemandas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
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
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoValidacaoAcesso("bttDetalharTipo", "Detalhar", "botaofuncaop", false, false,true,false,"onClick='javascript:detalhar();' accesskey='d'");
                            ?>                                                        
                            </TD>                            
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
	tratarExcecaoHTML($ex, $vo);
}
?>
