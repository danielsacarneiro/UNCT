<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

try{
//inicia os parametros
inicio();

$vo = new voDemanda();
$filtro  = new filtroConsultarDemandaGestao(false, true);
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
//para nao colocar na sessao
$filtro->setFiltroFormularioEncadeadoSetorDemanda();
//echo "teste";

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = new dbDemanda();
$colecao = $dbprocesso->consultarTelaGestaoDemandaDetalhePorSetor($filtro);

$nmFuncao = "DETALHAR ";
$titulo = voDemanda::getTituloDemandaGestaoPorSetor();
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
<?=setTituloPagina(voDemanda::getTituloDemandaGestaoPorSetor())?>
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
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	//location.href="index.php?consultar=<?=$inConsultaHTML?>&lupa="+ lupa;
	location.href = document.referrer;
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
            include_once("telaFiltro.php");
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
                    <TH class="headertabeladados" width="90%">Setor</TH>
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
                	$setor = $colecao[$i][voDemandaTramitacao::$nmAtrCdSetorDestino];
                	$setorDesc = dominioSetor::getDescricao($setor);
                	
                	$numTotal = $colecao[$i][filtroConsultarDemandaGestao::$NmColNumTotalDemandas];
                    $tempoMedioVida = $registro[filtroConsultarDemandaGestao::$NmColNumTempoVidaMedio];
                    
                    $numTotalDeDemandas = $numTotalDeDemandas + $numTotal;
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $tipo);?>					
                    </TD>
                                       
                    <TD class="tabeladados"><?php echo $setorDesc?></TD>
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
