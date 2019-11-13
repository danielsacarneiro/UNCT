<?php
include_once("../../config_lib.php");
include(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicio();

$voContrato = new vocontrato();
$voContratoInfo = new voContratoInfo();
$voContratoMod = new voContratoModificacao();

$classChaves = "camporeadonly";
$readonly = "readonly";

$voContratoMod->getVOExplodeChave();
$isHistorico = ($voContratoMod->sqHist != null && $voContratoMod->sqHist != "");

$dbcontrato = new dbcontrato();
$dbContratoMod = new dbContratoModificacao();	
$colecao = $dbContratoMod->consultarPorChaveTela($voContratoMod, $isHistorico);
$voContratoMod->getDadosBanco($colecao);
$voContrato->getDadosBanco($colecao);   
$voContratoInfo->getDadosBanco($colecao);

$nmGestor  = $voContrato->gestor;
$nmGestorPessoa  = $voContrato->nmGestorPessoa;

$funcao = @$_GET["funcao"];

$titulo = "EXECUÇÃO";
setCabecalho($titulo);
?>
<!DOCTYPE html>
<HTML lang="pt-BR">

<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	window.close();
	//location.href="index.php";	
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
	location.href="detalharContrato.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;	
}

</SCRIPT>
<?=setTituloPagina($titulo)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarManterContrato.php">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqHist?>" name="<?=vocontrato::$nmAtrSqHist?>" value="<?=$voContrato->sqHist?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqContrato?>" name="<?=vocontrato::$nmAtrSqContrato?>" value="<?=$voContrato->sq?>">
 
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
        require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");        
        //$voContrato = new vocontrato();
        $voContrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        $voContrato->sqEspecie = 1;
        $voContrato = $dbcontrato->consultarPorChaveVO($voContrato, false);        
        
       	$complementoDet .= "Período: " . getInputText("", "", $voContrato->dtVigenciaInicial, constantes::$CD_CLASS_CAMPO_READONLY);
       	$complementoDet .= " a " . getInputText("", "", $voContrato->dtVigenciaFinal, constantes::$CD_CLASS_CAMPO_READONLY);
       	$complementoDet .= ", " . getTextoHTMLNegrito(getQtdMesesEntreDatas($voContrato->dtVigenciaInicial, $voContrato->dtVigenciaFinal)) . " meses";
       	$complementoDet .= "<br>Valor MATER: Mensal " . getInputText("", "", $voContrato->vlMensal, constantes::$CD_CLASS_CAMPO_READONLY);
       	$complementoDet .= " Global: " . getInputText("", "", $voContrato->vlGlobal, constantes::$CD_CLASS_CAMPO_READONLY);
       	
        $arrayParametro[0] = $voContrato;
        $arrayParametro[1] = $colecao;
        $arrayParametro[2] = false;
        $arrayParametro[3] = false;        
        $arrayParametro[4] = $complementoDet;        
        
        getContratoDetalhamentoParam($arrayParametro);
                
        ?>
        </TBODY>
    </TABLE>
    </DIV>
    </TD>
    </TR>
    
    <TR>
       <TD class="textoseparadorgrupocampos">Execução</TD>
    </TR>
    
    <TR>
           <TD class='conteinertabeladados'>
            <DIV id='div_tabeladados' class='tabeladados'>
             <TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'>						
                 <TBODY>
                    <TR>
                      	<TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2>X</TH>
                        <TH class='headertabeladados' rowspan=2 nowrap>Espécie</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Tipo</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Índice</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Data.Ini.</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Data.Fim</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Meses</TH>
						<TH class='headertabeladados' width='1%' rowspan=2 nowrap>Vl.Referencial</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Vl.Execução</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Vl.Licon</TH>
                        <TH class='headertabeladadosalinhadocentro' width='40%' nowrap colspan=2>Vl.Mensal</TH>
                        <TH class='headertabeladadosalinhadocentro' width='40%' nowrap colspan=3>Vl.Global</TH>
                    </TR>
                    <TR>
                        <TH class='headertabeladadosalinhadodireita'>Documentado</TH>
                        <TH class='headertabeladadosalinhadodireita'>Execução</TH>
                        <TH class='headertabeladadosalinhadodireita'>Documentado</TH>
                        <TH class='headertabeladadosalinhadodireita'>Execução</TH>
                        <TH class='headertabeladadosalinhadodireita'>Prorrogação</TH>
                    </TR>
                    <?php
                    
                    //$colecaoMov = $dbContratoMod->consultarExecucao($voContrato);
                    $colecaoMov = $dbContratoMod->consultarExecucaoValorGlobalReferencial($voContrato);
                    if (is_array($colecaoMov))
                    	$tamanho = sizeof($colecaoMov);
                    	else
                    		$tamanho = 0;
                    	
                    //var_dump($colecaoMov);
                    
                    $colspan=15;
                    if($isHistorico){
                    	$colspan++;
                    }
                    
                    $numMesesPeriodoMater = getQtdMesesEntreDatas($voContrato->dtVigenciaInicial, $voContrato->dtVigenciaFinal);
                    $vlGlobalSeProrrogado = "Verifique o prazo do contrato mater.";                              
                    for ($i=0;$i<$tamanho;$i++) {
                    	$registro = $colecaoMov[$i];
                        $voAtual = new voContratoModificacao();
                        $voAtual->getDadosBanco($registro);
                        
                        $voContratoAtual = new vocontrato();
                        $voContratoAtual->getDadosBanco($registro);
                        $especie = getDsEspecie($voContratoAtual);
						                            
                        $tipo = dominioTpContratoModificacao::getDescricaoStatic($voAtual->tpModificacao);
                        $percentual = getMoeda($voAtual->numPercentual,4) . "%";
                        
                        $voContratoModReajuste = $registro[filtroManterContratoModificacao::$NmColVOContratoModReajustado];
                        $vlMensalAtual = $voContratoModReajuste->vlMensalAtual;
                        $vlGlobalAtual = $voContratoModReajuste->vlGlobalAtual;
                        //$vlGlobalReal = $voAtual->vlGlobalReal;
                        $vlGlobalReal = $vlGlobalAtual;
                        
                        if($numMesesPeriodoMater != null){                        	
                    		$vlGlobalSeProrrogado = $numMesesPeriodoMater*$vlMensalAtual;                    		
                    	}

                    	//reajuste NAO muda o valor global referencia para fins de valores no licon
                    	$isReajuste = $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REAJUSTE
                    	|| $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO;
                    	if(!$isReajuste){
                    		$vlLicon = $voAtual->vlModificacaoReferencial*$numMesesPeriodoMater;
                    	}else{
                    		$vlLicon = 0;
                    	}
                    	                         
                    ?>
                    <TR class='dados'>
                        <TD class='tabeladados' width=1%>
                        <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual, false);?>
                        </TD>                        
                        <TD class='tabeladados' nowrap><?php echo $especie?></TD>
                        <TD class='tabeladados'><?php echo $tipo?></TD>
                        <TD class='tabeladados'><?php echo $percentual?></TD>
                        <TD class='tabeladados'><?php echo getData($voAtual->dtModificacao)?></TD>
                        <TD class='tabeladados'><?php echo getData($voAtual->dtModificacaoFim)?></TD>
                        <TD class='tabeladadosalinhadodireita'><?php echo $voAtual->numMesesParaOFimdoPeriodo?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($voAtual->vlModificacaoReferencial)?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($voAtual->vlModificacaoReal)?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($vlLicon)?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo $voContratoAtual->vlMensal?></TD>                    
                        <TD class='tabeladadosalinhadodireita' ><?php echo getTextoHTMLNegrito(getMoeda($vlMensalAtual))?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo $voContratoAtual->vlGlobal?></TD>                    
                        <TD class='tabeladadosalinhadodireita' ><?php echo getTextoHTMLNegrito(getMoeda($vlGlobalReal))?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getTextoHTMLNegrito(getMoeda($vlGlobalSeProrrogado))?></TD>
                        </TD>
                    </TR>					
                    <?php
                    }		
                    ?>
	                <TR>
	                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$i?></TD>
	                </TR>
	                <TR>
	                    <TD class="totalizadortabeladadosalinhadoesquerda" colspan=<?=$colspan?>>
	                    <?php 
		                    $complementoDet = " Valor ATUAL(havendo prorrogação): Mensal " . getInputText("", "", getMoeda($vlMensalAtual), constantes::$CD_CLASS_CAMPO_READONLY);
		                    $complementoDet .= " Global: " . getInputText("", "", getMoeda($vlGlobalSeProrrogado), constantes::$CD_CLASS_CAMPO_READONLY);
		                    
		                    echo getTextoHTMLNegrito($complementoDet);
	                    ?>
	                    </TD>
	                </TR>
	                	                
	        </TD>
    </TR>  
</TBODY>
</TABLE>
</TD>
</TR>
</TBODY>

        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
	                    		<?php
	                    		//getBotoesRodape();
	                    		$arrayBotoesARemover = array(constantes::$CD_FUNCAO_SELECIONAR);
	                    		echo getBotoesRodapeComRestricao($arrayBotoesARemover, true);
	                    		?>
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

