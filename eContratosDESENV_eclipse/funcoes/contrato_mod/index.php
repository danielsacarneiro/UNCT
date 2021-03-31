<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

try{
//inicia os parametros
inicio();

$vo = new voContratoModificacao();

$titulo = "CONSULTAR " . voContratoModificacao::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterContratoModificacao(FALSE, TRUE);
 
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);

$isHistorico = $filtro->isHistorico(); 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsulta($vo, $filtro);

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
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

function excluirVarios() {

	if(!isCheckBoxConsultaSelecionado("rdb_consulta"))
		return;
	
	funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR_VARIOS?>";
	alert("Implementar Funcao.");
	var chave = retornarValoresCheckBoxesSelecionadosComoString("rdb_consulta");
	//location.href="confirmar.php?funcao=" + funcao + "&chave=" + chave;
}

function detalhar(isExcluir) {

	if(!isApenasUmCheckBoxSelecionado("rdb_consulta"))
		return;
	
    if(isExcluir == null || !isExcluir)
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    else
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";

/*    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;*/
    	
	//chave = document.frm_principal.rdb_consulta.value;
    var chave = retornarValoresCheckBoxesSelecionadosComoArray("rdb_consulta")[0];	
	var lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
}

function excluir() {
	if(isApenasUmCheckBoxSelecionado("rdb_consulta", true)){
		detalhar(true);
	}else{
		excluirVarios();
	}
}

function incluir() {
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    /*if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;*/
	if(!isApenasUmCheckBoxSelecionado("rdb_consulta"))
        return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

function abrirExecucao(){
	if(!isApenasUmCheckBoxSelecionado("rdb_consulta"))
        return;

	/*if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }*/
  	//marreta
	//chave = document.frm_principal.rdb_consulta.value;	
    var chave = retornarValoresCheckBoxesSelecionadosComoArray("rdb_consulta")[0];
    url = "../contrato/execucao.php?chave=" + chave;	
    abrirJanelaAuxiliar(url, true, false, false);
}

function movimentacoes(){
    /*if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }*/
	if(!isApenasUmCheckBoxSelecionado("rdb_consulta"))
        return;
    
  	//marreta
	chave = montarChaveContrato();
	//alert(chave);	
    url = "../contrato/movimentacaoContrato.php?chave=" + chave;	
    abrirJanelaAuxiliar(url, true, false, false);
}

function montarChaveContrato(){
	//var chave = document.frm_principal.rdb_consulta.value;
	if(!isApenasUmCheckBoxSelecionado("rdb_consulta"))
        return;
    
	var chave = retornarValoresCheckBoxesSelecionadosComoArray("rdb_consulta")[0];
	
	var array = chave.split("*");
	chave = "hist*" + array[0] + "*" + array[1] + "*" +  array[2] + "*CM*1";
	//alert(chave);
	return chave;
}

</SCRIPT>
<?=setTituloPagina(voContratoModificacao::getTituloJSP())?>
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
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        ?>
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3><?php

	            $voContratoFiltro = new vocontrato();	            
	            $voContratoFiltro->tipo = $filtro->vocontrato->tipo;
	            //echo $filtro->vocontrato->tipo;
	            $voContratoFiltro->cdContrato = $filtro->vocontrato->cdContrato;
	            $voContratoFiltro->anoContrato = $filtro->vocontrato->anoContrato;
	            $voContratoFiltro->cdEspecie = $filtro->vocontrato->cdEspecie;
	            $voContratoFiltro->sqEspecie = $filtro->vocontrato->sqEspecie;
	             
	            $pArray = array($voContratoFiltro,
	            		constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,
	            		false,
	            		true,
	            		false,
	            		null,	            		
	            		null);
	             
	            getContratoEntradaArrayGenerico($pArray);
			?></TD>
	        </TR>
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50"></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
            <TR>
                <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
                <TD class="campoformulario" width="1%">
                <?php
                $comboTipo = new select(dominioTpContratoModificacao::getColecao());
                echo $comboTipo->getHtmlCombo(voContratoModificacao::$nmAtrTpModificacao,voContratoModificacao::$nmAtrTpModificacao, $filtro->tipo, true, "camponaoobrigatorio", false, "");
                ?>
				</TD>
                <TD class="campoformulario" width="1%" colspan=2>
                Exceto: 
                <?php
                echo $comboTipo->getHtmlCombo(filtroManterContratoModificacao::$ID_REQ_TipoExceto, filtroManterContratoModificacao::$ID_REQ_TipoExceto."[]", $filtro->tipoExceto, true, "camponaoobrigatorio", false, " multiple ");
                ?>
				</TD>				
            </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Dt.Modificação:</TH>
	            <TD class="campoformulario" colspan=3>
	            	            	<INPUT type="text" 
	            	       id="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            	       name="<?=voContratoModificacao::$nmAtrDtModificacao?>" 
	            			value="<?php echo(getData($filtro->dtPublicacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">	            
				</TD>				
            </TR>            
                        
	        
       <?php
       echo getComponenteConsultaFiltro($vo->temTabHistorico, $filtro);
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
                  <TH class="headertabeladados" width="1%"><?=getXGridConsulta("rdb_consulta", true)?></TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladadosalinhadocentro" width="1%" nowrap>Sq.</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Contrato</TH>
                    <TH class="headertabeladados" width="1%">Espécie</TH>
                    <TH class="headertabeladados" width="20%">Contratada</TH>                    
					<TH class="headertabeladados" width="1%">Tipo</TH>
					<TH class="headertabeladados" width="1%" nowrap>Índice</TH>
					<TH class="headertabeladados" width="1%" nowrap>Valor.Ref</TH>					
					<TH class="headertabeladados" width="1%">Mensal.Atual</TH>
					<TH class="headertabeladados" width="1%" >Global.Atual</TH>
					<TH class="headertabeladados" width="1%">Vl.Referência</TH>
					<TH class="headertabeladados" width="1%" nowrap>%.Contrato</TH>
					<TH class="headertabeladados" width="1%" nowrap>Dt.Início</TH>
					<TH class="headertabeladados" width="1%" nowrap>Dt.Fim</TH>
					<TH class="headertabeladados" width="1%">Assinatura</TH>					
                    <TH class="headertabeladados" width="1%" >Registro</TH>
                    <TH class="headertabeladados" width="1%">Usu.Registro</TH>
                </TR>
                <?php				
                                
                if (is_array($colecao))
                	$tamanho = sizeof($colecao);
                else 
                    $tamanho = 0;
                $numTotalRegistros = $tamanho;
                
                //echoo($tamanho);                                
                $colspan=18;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                $dominioAutorizacao = new dominioAutorizacao();
                
                $vlConsolidadoRef = 0;
                $vlConsolidadoMod = 0;
                
                $percentualSupressao = 0;
                $percentualAcrescimo = 0;
                
               for ($i=0;$i<$tamanho;$i++) {
               		$registroBanco = $colecao[$i];
               		$voAtual = new voContratoModificacao();
               		$voAtual->getDadosBanco($registroBanco);
               		 
                        $voPessoa = new voPessoa();
                        $voPessoa->getDadosBanco($registroBanco);                        
                                                                   
                        $dsPessoa = $voPessoa->nome;
                        if($dsPessoa == null){
                        	$dsPessoa = "<B>CONTRATO NÃO INCLUÍDO</B>";
                        }
                        
                        $dsPessoa = substr($dsPessoa, 0, 14) . "...";
                        
                        $vocontrato = $voAtual->vocontrato;
                        $tipo = $vocontrato->tipo;
                        $tipo = dominioTipoContrato::getDescricaoStatic($tipo);
                        //var_dump($voAtual);                        
                        if($vocontrato!=null){
                        		$complementoContrato = getContratoDescricaoEspecie($vocontrato);
                        		$contrato = 
                        		 formatarCodigoAnoComplemento($vocontrato->cdContrato,
                        		 		$vocontrato->anoContrato,
                        				$dominioTipoContrato->getDescricao($vocontrato->tipo))
                        				;
                        }
                        
                        $tipoModificacao = dominioTpContratoModificacao::getDescricaoStatic($voAtual->tpModificacao);
                        $vlMater = $registroBanco[filtroManterContratoModificacao::$NmColVlGlobalMater];
                        $vlMensalAtual = floatval($voAtual->vlMensalAtual);
                        $vlGlobalAtual = floatval($voAtual->vlGlobalAtual);
                        $vlGlobaModAtual = floatval($voAtual->vlGlobalModAtual);
                        
                        $percAcrescimo = $voAtual->getPercentualAcrescimoAtual();                        
                        $isAcrescimo = $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_ACRESCIMO;
                        $isSupressao = $voAtual->tpModificacao == dominioTpContratoModificacao::$CD_TIPO_SUPRESSAO;
                        
                        //echoo($percentual);
                        $percentual = $voAtual->numPercentual;
                        
                        if($isAcrescimo && $percentual > 0){
                        	$percentualAcrescimo = $percentualAcrescimo + $percentual;  
                        }else if($isSupressao && $percentual < 0){
                        	$percentualSupressao = $percentualSupressao + $percentual;
                        }
                        
                        $percAcrescimo = getMoeda($percAcrescimo,2)."%";                        
                        
                        $numMeses = floatval($voAtual->numMesesParaOFimdoPeriodo);
                        $vlAEmpenhar = $vlMensalAtual*$numMeses;
                        
                        $vlConsolidadoRef = $vlConsolidadoRef + floatval($voAtual->vlModificacaoReferencial);
                        $vlConsolidadoMod = $vlConsolidadoMod + floatval($voAtual->vlModificacaoReal);
                        
                        $nmUsuario = $registroBanco[vousuario::$nmAtrName];
                   ?>
                <TR class="dados">
                    <TD class="tabeladados">					
                    <?php
                    //echo getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);
                    echo getHTMLCheckBoxConsulta("rdb_consulta", "rdb_consulta", $voAtual);                    
                    ?>
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][$voAtual::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>
                    <TD class="tabeladados"><?php echo complementarCharAEsquerda($voAtual->sq, "0", TAMANHO_CODIGOS_SAFI);?></TD>                    
                    <TD class="tabeladadosalinhadodireita"><?php echo $contrato;?></TD>
                    <TD class="tabeladados"><?php echo $complementoContrato?></TD>					
					<TD class="tabeladados"><?php echo $dsPessoa?></TD>
                    <TD class="tabeladados"><?php echo $tipoModificacao?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->numPercentual)?>%</TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlModificacaoReferencial,2)?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlMensalAtual)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlGlobalAtual)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlGlobalModAtual)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo $percAcrescimo?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($voAtual->dtModificacao)?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($voAtual->dtModificacaoFim)?></TD>
					<TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($vocontrato->dtAssinatura)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getDataHora($voAtual->dhUltAlteracao)?></TD>                    
                    <TD class="tabeladadosalinhadodireita"><?php echo $nmUsuario?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
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
	                   		<TD class="botaofuncao"><?=getBotao("bttExecucao", "Execução", null, false, "onClick='javascript:abrirExecucao();' accesskey='x'")?></TD>
	                   		<TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD> 
                            <?php
                            $arrayBotoesARemover = array(constantes::$CD_FUNCAO_ALTERAR);
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
