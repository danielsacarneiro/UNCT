<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();

$vo = new voContratoModificacao();

$titulo = "CONSULTAR " . voContratoModificacao::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterContratoModificacao();
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
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
	            <TH class="campoformulario" nowrap width="1%">Dt.Modifica��o:</TH>
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
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%" nowrap>Contrato</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Esp�cie</TH>
					<TH class="headertabeladados" width="1%" nowrap>Dt.Assinatura</TH>
                    <TH class="headertabeladados" width="50%">Contratada</TH>                    
					<TH class="headertabeladados" width="1%" nowrap>Tipo</TH>
					<TH class="headertabeladados" width="1%" nowrap>�ndice</TH>
					<TH class="headertabeladados" width="1%" nowrap>Valor</TH>
					<TH class="headertabeladados" width="1%" nowrap>Mensal.Atual</TH>
					<TH class="headertabeladados" width="1%" nowrap>Mensal.Anterior</TH>					
					<TH class="headertabeladados" width="1%" nowrap>Global.Atual</TH>
					<TH class="headertabeladados" width="1%" nowrap>Vl.Empenhar</TH>
					<TH class="headertabeladados" width="1%" nowrap>Vl.Refer�ncia</TH>
					<TH class="headertabeladados" width="1%" nowrap>%.Contrato</TH>
					<TH class="headertabeladados" width="1%" nowrap>Dt.In�cio</TH>
					<TH class="headertabeladados" width="1%" nowrap>Dt.Fim</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Registro</TH>                    
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
                //echoo($tamanho);                                
                $colspan=17;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                $dominioAutorizacao = new dominioAutorizacao();
                
               for ($i=0;$i<$tamanho;$i++) {
               		$registroBanco = $colecao[$i];
                        $voAtual = new voContratoModificacao();
                        $voAtual->getDadosBanco($registroBanco);

                        $voPessoa = new voPessoa();
                        $voPessoa->getDadosBanco($registroBanco);                        
                                                                   
                        $dsPessoa = $voPessoa->nome;
                        if($dsPessoa == null){
                        	$dsPessoa = "<B>CONTRATO N�O INCLU�DO NA PLANILHA</B>";
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
                        
                        /*echoo($vlGlobalAtual);
                        echoo($vlGlobaModAtual);*/
                        
                        /*if($voAtual->tpModificacao != dominioTpContratoModificacao::$CD_TIPO_REAJUSTE
                        		&& $voAtual->tpModificacao != dominioTpContratoModificacao::$CD_TIPO_PRORROGACAO){
	                        $percAcrescimo = 100*(($vlGlobalAtual - $vlGlobaModAtual)/$vlGlobaModAtual);	                        
	                        $percAcrescimo = getMoeda($percAcrescimo,2) . "%";	                        
                        }*/
                        $percAcrescimo = $voAtual->getPercentualAcrescimoAtual();
                        $percAcrescimo = getMoeda($percAcrescimo,2)."%";
                        
                        $numMeses = floatval($voAtual->numMesesParaOFimdoPeriodo);
                        $vlAEmpenhar = $vlMensalAtual*$numMeses;
                   ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][$voAtual::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo $contrato;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $complementoContrato?></TD>
					<TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($vocontrato->dtAssinatura)?></TD>
					<TD class="tabeladados"><?php echo $dsPessoa?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipoModificacao?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->numPercentual)?>%</TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlModificacaoAoContrato,2)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlMensalAtual)?></TD>
					<TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlMensalAnterior)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlGlobalAtual)?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($vlAEmpenhar)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getMoeda($voAtual->vlGlobalModAtual)?></TD>                    
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo $percAcrescimo?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($voAtual->dtModificacao)?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getData($voAtual->dtModificacaoFim)?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo getDataHora($voAtual->dhUltAlteracao)?></TD>                    
                </TR>					
                <?php
				}                
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na p�gina: <?=$i?></TD>
                </TR>
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
                            <?php
                            //$arrayBotoesARemover = array(constantes::$CD_FUNCAO_ALTERAR);
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
