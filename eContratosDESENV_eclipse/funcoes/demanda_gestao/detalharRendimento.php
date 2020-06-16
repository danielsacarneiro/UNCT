<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

try{
//inicia os parametros
inicio();
$chave = @$_GET["chave"];
$titulo = "DETALHAR " . voDemanda::getTituloDemandaRendimentoJSP();
setCabecalho($titulo);

$vo = new voDemanda();
$filtro  = new filtroConsultarDemandaRendimento(false, true); 
$filtro->voPrincipal = $vo;
//$filtro = filtroManter::verificaFiltroSessao($filtro);
$filtro = getObjetoSessao($filtro->nmFiltro, true);
//$ano = @$_GET[voDemanda::$nmAtrAno];
//$filtro->vodemanda->ano = $ano;
$filtro->vodemanda->cdSetor = $chave;
$filtro->groupby = voDemanda::$nmAtrDtReferencia;
$filtro->cdAtrOrdenacao = voDemanda::$nmAtrDtReferencia;

//$filtro = new filtroManter();
$filtro->isValidarConsulta = false;
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsultaRendimentoDemanda($filtro);

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

function detalharDemandaRendimento(){
	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
	chave = document.frm_principal.rdb_consulta.value;
    url = "../demanda_gestao/detalharDemandaRendimento.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S";	
    abrirJanelaAuxiliar(url, true, false, false);
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloDemandaRendimentoJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="indexRendimento.php?consultar=S">

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
	        	include_once(caminho_util. "dominioSimNao.php");
	        	$comboSimNao = new select(dominioSimNao::getColecao());	         
	            $selectExercicio = new selectExercicio(constantes::$ANO_INICIO);
			  ?>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
                <TD class="campoformulario" nowrap colspan=3>
                Ano <?php echo getTextoHTMLNegrito($filtro->vodemanda->ano);?>
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario" nowrap colspan=3><?php echo getTextoHTMLNegrito(dominioSetor::getDescricao($chave));?></TD>
            </TR>
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
                    <TH class="headertabeladados" width="90%">Mês</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Rendimento</TH>   
                    <TH class="headertabeladados"width="1%" nowrap >Demandas</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Entradas</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Saídas</TH>                                       
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=5;
                //laco para calcular o total
                
                $numTotalEntradas = 0;
                $numTotalSaidas = 0;
                $numTotalDemandas = 0;
                $numTotalRegistros=0;
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$numSaidas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuSaidas];
                	$numEntradas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuEntradas];
                	$numDemandas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNumTotalDemandas];
                	
                	$numTotalEntradas = $numTotalEntradas + $numEntradas;
                	$numTotalSaidas = $numTotalSaidas + $numSaidas;     
                	$numTotalDemandas = $numTotalDemandas + $numDemandas;
                	
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$mes = $colecao[$i][voDemanda::$nmAtrDtReferencia];
                	$mes = dominioMeses::getDescricao($mes);
                	
                	$numSaidas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuSaidas];
                	$numEntradas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuEntradas];
                	$numDemandas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNumTotalDemandas];
                	
                	$numRendimento = intval($numSaidas-$numDemandas);                	                	
                	$percentRendimento=$numDemandas?100*($numRendimento/$numDemandas):0;
                    
                ?>
                <TR class="dados">
                    <TD class="tabeladados"><?php echo $mes?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap>
                    <?php 
                    $str = $numRendimento>=0?complementarCharAEsquerda($numRendimento, "0", constantes::$TAMANHO_CODIGOS_SAFI):$numRendimento;
                    $str .= " (" . complementarCharAEsquerda(getMoeda($percentRendimento,2), "0", constantes::$TAMANHO_CODIGOS_SAFI) . "%)";
                    echo $numRendimento>=0?getTextoHTMLDestacado($str, "blue", false):getTextoHTMLDestacado($str, "red", false);
                    
                    ?>
                    </TD>                                        
                    <TD class="tabeladadosalinhadodireita" nowrap>
                    <?php 
                    $str = complementarCharAEsquerda(getMoeda($numDemandas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI);
                    echo $str;
                    ?>                    
                    <TD class="tabeladadosalinhadodireita" nowrap>
                    <?php 
                    $str = complementarCharAEsquerda(getMoeda($numEntradas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI) 
                    		. " (" . complementarCharAEsquerda(getMoeda(100*$numEntradas/$numTotalEntradas,2), "0", constantes::$TAMANHO_CODIGOS_SAFI) . "%)";
                    echo $str;
                    ?>
                    </TD>
                    <TD class="tabeladadosalinhadodireita" nowrap>
                    <?php 
                    $str = complementarCharAEsquerda(getMoeda($numSaidas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)
                    . " (" . complementarCharAEsquerda(getMoeda(100*$numSaidas/$numTotalSaidas,2), "0", constantes::$TAMANHO_CODIGOS_SAFI) . "%)";
                    echo $str;
                    ?>
                    </TD>                    
                </TR>					
                <?php
                	$numTotalRegistros++;
				}				
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan-3?>>Total:</TD>
					<TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda(getMoeda($numTotalDemandas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>                    
                    <TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda(getMoeda($numTotalEntradas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
					<TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda(getMoeda($numTotalSaidas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
                </TR>
				<TR>
                    <TD class="totalizadortabeladados" colspan=<?=$colspan?>>Total de registros(s): <?=$numTotalRegistros?></TD>
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
                            echo getBotaoFechar();
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
