<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

try{
//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voDemanda::getTituloDemandaRendimentoJSP();
setCabecalho($titulo);

$vo = new voDemanda();
$filtro  = new filtroConsultarDemandaRendimento(false, true);
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
$filtro->inDesativado = constantes::$CD_NAO;
	
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
	var strAno = "<?=voDemanda::$nmAtrAno."=".$filtro->vodemanda->ano?>";
    url = "../demanda_gestao/detalharRendimento.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S&"+strAno;	
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
	        	$comboTipo = new select(dominioTipoDemanda::getColecao(false));
	            $selectExercicio = new selectExercicio(constantes::$ANO_INICIO);
	            $comboTipoContrato = new select(dominioTipoContrato::getColecao());
	            $comboCPL = new select(dominioComissaoProcLicitatorio::getColecao());
	            $comboOR_AND = new selectOR_AND();
	         ?>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
                <TD class="campoformulario" nowrap colspan=3>
                Ano <?php echo $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, "");?>
                | Tp. <?php echo $comboTipoContrato->getHtmlCombo(voDemandaContrato::$nmAtrTipoContrato,voDemandaContrato::$nmAtrTipoContrato, $filtro->vocontrato->tipo, true, "camponaoobrigatorio", false, "");?>
                &nbsp;<?php echo $comboOR_AND->getHtmlCombo(filtroManterDemanda::$NmAtrInOR_AND,filtroManterDemanda::$NmAtrInOR_AND, $filtro->inOR_AND == null?constantes::$CD_OPCAO_AND:$filtro->inOR_AND, false, "camponaoobrigatorio", false, "");?>  
                &nbsp;CPL <?php echo $comboCPL->getHtmlCombo(voProcLicitatorio::$nmAtrCdCPL,voProcLicitatorio::$nmAtrCdCPL, $filtro->voproclic->cdCPL, true, "camponaoobrigatorio", false, "");?>
                </TD>
            </TR>
            <TR>
                <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
                <TD class="campoformulario" colspan=3>
	                <TABLE class="filtro" cellpadding="0" cellspacing="0">
	                <TR>
	                	<TD class="campoformulario" width="1%">Incluindo:</TD>
	                	<TD class="campoformulario" width="1%">
		                <?php //echo $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo, $filtro->vodemanda->tipo, true, "camponaoobrigatorio", false, "") . "<br>";
	                	  echo $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo."[]", $filtro->vodemanda->tipo, true, "camponaoobrigatorio", false, " multiple ");
	                	  $nmCampoTpDemandaContrato = voDemanda::$nmAtrTpDemandaContrato."[]";
	                	  //echo dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, $filtro->vodemanda->tpDemandaContrato, dominioTipoDemandaContrato::getColecaoConsulta(), 2, false, "", true);
		               	?>
	                	<TD class="campoformulario" width="1%">Excluindo</TD>
	                	<TD class="campoformulario" >
						<?php echo $comboTipo->getHtmlCombo(filtroManterDemanda::$NmAtrTipoExcludente, filtroManterDemanda::$NmAtrTipoExcludente."[]", $filtro->tipoExcludente, true, "camponaoobrigatorio", false, " multiple ");?>	                	
						</TD>
	                </TR>
	                <TR>
	                	<TD class="campoformulario" colspan=4>
	                	<?php
	                		echo dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, $filtro->vodemanda->tpDemandaContrato, dominioTipoDemandaContrato::getColecaoConsulta(), 2, true, "", true);
	                		$comboTpReajuste = new select(dominioTipoReajuste::getColecao());
	                		echo "Reajuste: " . $comboTpReajuste->getHtmlComObrigatorio(voDemanda::$nmAtrInTpDemandaReajusteComMontanteA,voDemanda::$nmAtrInTpDemandaReajusteComMontanteA, $filtro->vodemanda->inTpDemandaReajusteComMontanteA, false,false);	                		 
	                	?>
	                	</TD>
	                </TR>
	                </TABLE>
                </TD>                                
            </TR>
            
	   <?php	        	
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
                    <TH class="headertabeladados" width="90%">Setor</TH>
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
                $numTotalRegistros=0;
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$numSaidas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuSaidas];
                	$numEntradas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuEntradas];
                	
                	$numTotalEntradas = $numTotalEntradas + $numEntradas;
                	$numTotalSaidas = $numTotalSaidas + $numSaidas;                	 
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$cdSetor = $colecao[$i][voDemanda::$nmAtrCdSetor];
                	$setor = dominioSetor::getDescricao($cdSetor);
                	
                	$numSaidas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuSaidas];
                	$numEntradas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNuEntradas];
                	$numDemandas = $colecao[$i][filtroConsultarDemandaRendimento::$NmColNumTotalDemandas];
                    
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $cdSetor);?>					
                    </TD>
                                       
                    <TD class="tabeladados"><?php echo $setor?></TD>
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
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan-2?>>Total:</TD>
                    <TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda(getMoeda($numTotalEntradas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
					<TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda(getMoeda($numTotalSaidas,0), "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
                </TR>
				<TR>
                    <TD class="totalizadortabeladados" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
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
                            echo getBotaoValidacaoAcesso("bttDetalharSetor", "Detalhar", "botaofuncaop", false, false,true,false,"onClick='javascript:detalharDemandaRendimento();' accesskey='d'");
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
