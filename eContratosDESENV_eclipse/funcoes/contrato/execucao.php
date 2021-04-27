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
       	
       	$complementoDet .= getHTMLContratoPorEscopo($voContratoInfo);
       	
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
       <TD class="textoseparadorgrupocampos">EXECUÇÃO - a diferença entre os valores documentados e executados é resultado dos reajustes APROXIMADOS e retroativos</TD>
    </TR>
    
    <TR>
           <TD class='conteinertabeladados'>
            <DIV id='div_tabeladados' class='tabeladados'>
             <TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'>						
                 <TBODY>
                    <TR>
                      	<TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2>X</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Sq</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2 nowrap>Tipo</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 >Índice Legal</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 >Índice Simples</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Data.Ini.</TH>
                        <TH class='headertabeladados' width='1%' rowspan=2 nowrap>Data.Fim</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2 nowrap>Meses<br>Restantes</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2 nowrap>Meses<br>Contrato</TH>
						<TH class='headertabeladados' width='1%' rowspan=2 nowrap>Vl.Refer.</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2 nowrap>Vl.Licon<br>(Prazo<br>Restante)</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%' rowspan=2 nowrap>Vl.Licon<br>(Prazo<br>Cheio)</TH>
                        <TH class='headertabeladadosalinhadocentro' width='40%' nowrap colspan=2>Vl.Mensal</TH>
                        <TH class='headertabeladadosalinhadocentro' width='40%' nowrap colspan=3>Vl.Global</TH>
                    </TR>
                    <TR>
                        <TH class='headertabeladadosalinhadocentro' width='1%'>Doc.</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%'>Exec.</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%'>Doc.</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%'>Exec.</TH>
                        <TH class='headertabeladadosalinhadocentro' width='1%'>Prorrog.</TH>
                    </TR>
                    <?php
                    
                    $colecaoMov = $dbContratoMod->consultarExecucaoValorGlobalReferencial($colecao);
                    
                    if (is_array($colecaoMov))
                    	$tamanho = sizeof($colecaoMov);
                    else
                    	$tamanho = 0;
                    	
                    //var_dump($colecaoMov);
                    
                    $colspan=17;
                    if($isHistorico){
                    	$colspan++;
                    }
                    
                    $numMesesPeriodoMater = getQtdMesesEntreDatas($voContrato->dtVigenciaInicial, $voContrato->dtVigenciaFinal);
                    $vlGlobalSeProrrogado = "Verifique o prazo do contrato mater.";
                    $numMesesPeriodoAtual = $numMesesPeriodoMater;
                    
                    $isEscopo = $voContratoInfo->inEscopo == "S";
                    //echoo("execucao contrato por escopo? $voContratoInfo->inEscopo");
                    
                    if($tamanho > 0){
                    	$registro = $colecaoMov[0];
                    	$voAtual = new voContratoModificacao();
                    	$voAtual->getDadosBanco($registro); 
                    	//serve para comparar as execucoes de um termo especifico
                    	$chaveTermoAnterior = $voAtual->getValorChavePrimariaTermo();
                    	
	                    for ($i=0;$i<$tamanho;$i++) {
	                    	$registro = $colecaoMov[$i];
	                    	
	                        $voAtual = new voContratoModificacao();	                        	
	                        $voAtual->getDadosBanco($registro);
	                        $chaveTermoAtual = $voAtual->getValorChavePrimariaTermo();
	                        
	                        $voContratoAtual = new vocontrato();
	                        $voContratoAtual->getDadosBanco($registro);
	                        //if($voContratoAtual->cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
	                        $especie = getDsEspecie($voContratoAtual, false);
	                        $tipo = dominioTpContratoModificacao::getDescricaoStatic($voAtual->tpModificacao);
	                        	                        
	                        $isProrrogacao = $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO;
	                        $vlModReferencial = $voAtual->vlModificacaoReferencial;
	                        if($isProrrogacao){
	                        	$numMesesPeriodoAtual = getQtdMesesEntreDatas($voContratoAtual->dtVigenciaInicial, $voContratoAtual->dtVigenciaFinal);
	                        	//o valor referencial da prorrogacao sera o valor licon usado como referencial
	                        	//$vlModReferencial = $vlLiconReferencial;
	                        }	                         
	                        
	                        $voContratoModReajuste = $registro[filtroManterContratoModificacao::$NmColVOContratoModReajustado];
	                        $vlMensalAtual = $voContratoModReajuste->vlMensalAtual;
	                        //echoo($vlMensalAtual);
	                        $vlGlobalAtual = $voContratoModReajuste->vlGlobalAtual;
	                        //$vlGlobalReal = $voAtual->vlGlobalReal;
	                        $vlGlobalReal = $vlGlobalAtual;                      
	                        
	                        if($numMesesPeriodoAtual != null){                        	
	                    		$vlGlobalSeProrrogado = $numMesesPeriodoAtual*$vlMensalAtual;
	                    		//echo "$especie $vlGlobalSeProrrogado| $vlMensalAtual <br>";
	                    	}
	
	                    	/*//reajuste NAO muda o valor global referencia para fins de valores no licon
	                    	$isReajuste = $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REAJUSTE
	                    		|| $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_REPACTUACAO;*/
	                    	
	                    		                    	 
	                    	/*verifica se eh o mesmo termo
	                    	if($chaveTermoAtual == $chaveTermoAnterior){
	                    		$vlLiconReferencial = $vlLiconReferencial + $vlModReferencial;
	                    	}else{
	                    		$vlLiconReferencial = $vlModReferencial;
	                    	}
	                    	o trecho acima dava o valor acumulado, ou seja, se a supressao e o acrescimo estivessem no mesmo termo
	                    	o resultado final seria a compensacao entre eles: como o LICON lista separadamente os acrescimos e supressoes
	                    	devemos listar separado aqui tambem: pra isso o trecho acima fio substituido pelo trecho abaixo $vlLiconReferencial = $vlModReferencial;
	                    	*/	                    		                    	
	                    	$vlLiconReferencial = $vlModReferencial;
	                    	
	                    	/*echoo("$chaveTermoAtual == $chaveTermoAnterior & periodoAtual = $numMesesPeriodoAtual");
	                    	echoo("$chaveTermoAtual & $tipo & periodoAtual = $numMesesPeriodoAtual");
	                    	echoo("liconreferencial = $vlLiconReferencial");*/
	                    	                    	                    		                    	
	                    	$tipo = getTextoHTMLDestacado($tipo, dominioTpContratoModificacao::getCorTpModificacao($voAtual->tpModificacao), false);
	                    	$numMesesPrazoRestante = $voAtual->numMesesParaOFimdoPeriodo;                    	
	                    	
	                    	//o valor licon eh acumulado pelos valores referenciais do MESMO termo
	                    	//$vlLicon = $vlModReferencial*$numMesesPeriodoAtual;
	                    	$vlLiconPrazoRestante = ($vlLiconReferencial)*$numMesesPrazoRestante;
	                    	$vlLicon = ($vlLiconReferencial)*$numMesesPeriodoAtual;
	                    	
	                    	if($isEscopo){
	                    		//se por escopo, valor licon eh igual ao valor por "prazo restante"
	                    		//$vlLicon = $voAtual->vlModificacaoReal;
	                    		$vlLicon = $vlLiconPrazoRestante;
	                    		$vlMensalAtual = $vlGlobalReal/$numMesesPeriodoAtual;
	                    		$vlGlobalSeProrrogado = $vlGlobalReal;
	                    	}
	                    	
	                    	$numMesesTela = getTextoHTMLDestacado(intval($numMesesPrazoRestante), dominioTpContratoModificacao::getCorTpModificacao($voAtual->tpModificacao), false);
	                    	
	                    	if($isProrrogacao){
	                    		$numMesesPrazoContrato = getTextoHTMLDestacado($numMesesPeriodoAtual , dominioTpContratoModificacao::getCorTpModificacao($voAtual->tpModificacao), false);
	                    	}else{
	                    		$numMesesPrazoContrato = "-";
	                    	}
	                    	
	                    	$percentual = getMoeda($voAtual->numPercentual,4) . "%";
	                    	$percentualSimples = getMoeda($voAtual->vlModificacaoReferencial/$voAtual->vlMensalAnterior*100,4). "%";
	                    	$vlMensalAtualStrCelula = !$isEscopo?getTextoHTMLNegrito(getMoeda($vlMensalAtual)):getTextoHTMLDestacado(constantes::$DS_OPCAO_NAO_SEAPLICA, "red", false);
	                    	$vlMensalAtualStrInput = !$isEscopo?getMoeda($vlMensalAtual):constantes::$DS_OPCAO_NAO_SEAPLICA;
	                    	$vlMensalContratoAtual = $voContratoAtual->vlMensal;
	                    	$vlGlobalContratoAtual = $voContratoAtual->vlGlobal;
                    ?>
                    <TR class='dados'>
                        <TD class='tabeladados' width=1%>
                        <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual, false);?>
                        </TD>                        
                        <TD class='tabeladados' nowrap><?php echo complementarCharAEsquerda($voAtual->sq, "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
                        <TD class='tabeladados'><?php echo "$especie<br>$tipo"?></TD>
                        <TD class='tabeladadosalinhadodireita'><?php echo getTextoHTMLNegrito($percentual)?></TD>
						<TD class='tabeladadosalinhadodireita' ><?php echo !$isEscopo?$percentualSimples:"a corrigir"?></TD>
                        <TD class='tabeladados'><?php echo getData($voAtual->dtModificacao)?></TD>
                        <TD class='tabeladados'><?php echo getData($voAtual->dtModificacaoFim)?></TD>
                        <TD class='tabeladadosalinhadodireita'><?php echo $numMesesTela?></TD>
                        <TD class='tabeladadosalinhadodireita'><?php echo $numMesesPrazoContrato?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($vlLiconReferencial)?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($vlLiconPrazoRestante)?></TD>
                        <TD class='tabeladadosdestacadoamarelo'><?php echo getTextoHTMLNegrito(getMoeda($vlLicon)) . "<br>($numMesesPeriodoAtual meses)"?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo $vlMensalContratoAtual?></TD>                    
                        <TD class='tabeladadosalinhadodireita' ><?php echo $vlMensalAtualStrCelula?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo $voContratoAtual->vlGlobal?></TD>                    
                        <TD class='tabeladadosalinhadodireita' ><?php echo getTextoHTMLNegrito(getMoeda($vlGlobalReal))?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getTextoHTMLNegrito(getMoeda($vlGlobalSeProrrogado))?></TD>
                        </TD>
                    </TR>					
                    <?php
                    		$chaveTermoAnterior = $chaveTermoAtual;
	                        //}
                   		}                    
                    ?>
	                <TR>
	                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$i?></TD>
	                </TR>
	                <TR>
	                    <TD class="totalizadortabeladadosalinhadoesquerda" colspan=<?=$colspan?>>
	                    <?php 
	                    try{
	                    	//$vlContratoPErcentual = getVarComoDecimal($vlMensalContratoAtual);
	                    	//$vlContratoModPErcentual = $vlMensalAtual;
	                    	
	                    	$vlContratoPErcentual = getVarComoDecimal($vlGlobalContratoAtual);
	                    	$vlContratoModPErcentual = $vlGlobalSeProrrogado;
	                    	
	                    	//echoo($vlContratoPErcentual);
	                    	//echoo($vlContratoModPErcentual);
	                    	
	                    	if($vlContratoPErcentual>$vlContratoModPErcentual){
	                    		$percMargemErro = $vlContratoModPErcentual/$vlContratoPErcentual;
	                    	}else{
	                    		$percMargemErro = $vlContratoPErcentual/$vlContratoModPErcentual;
	                    	}
	                    	
	                    	$percMargemErro = 100*(1-$percMargemErro);
	                    	$percMargemErro = round($val, 2);
	                    	//echoo($percMargemErro );
	                    	if($percMargemErro != 0){
	                    		echo "Margem de erro é " . getTextoHTMLDestacado(getMoeda($percMargemErro)."%") .", por conta das aproximações (ou cadastro incorreto).<br>";
	                    	}
	                    }catch (excecaoGenerica $exTem){
	                    	if($exTem instanceof excecaoAtributoInvalido){
	                    		throw $exTem;
	                    	}else{
	                    		echo "Erro ao calcular margem de erro<br>.";
	                    	}
	                    }	                    	
	                    
		                    $complementoDet = " Valor ATUAL (havendo prorrogação): Mensal " . getInputText("", "", $vlMensalAtualStrInput, constantes::$CD_CLASS_CAMPO_READONLY);
		                    $complementoDet .= " Global: " . getInputText("", "", getMoeda($vlGlobalSeProrrogado), constantes::$CD_CLASS_CAMPO_READONLY);
		                    
		                    echo getTextoHTMLNegrito($complementoDet);
                    }
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

