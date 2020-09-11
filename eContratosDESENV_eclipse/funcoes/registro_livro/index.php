<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voRegistroLivro();
$titulo = "CONSULTAR " . voRegistroLivro::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterRegistroLivro();
$filtro->voPrincipal = $vo;
//echo "existe filtro sessao:" . dominioSimNao::getDescricao(existeObjetoSessao($filtro->nmFiltro));
$filtro = filtroManter::verificaFiltroSessao($filtro);
//echo "existe filtro sessao:" . dominioSimNao::getDescricao(existeObjetoSessao($filtro->nmFiltro));
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$arrayParamConsulta = array ($filtro);
$colecao = $dbprocesso->consultarTelaConsultaParam($arrayParamConsulta);

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
<?=setTituloPagina($vo->getTituloJSP())?>
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
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3><?php

	            $voContratoFiltro = new vocontrato();
	            $voContratoFiltro->tipo = $filtro->voRegistroLivro->voContrato->tipo;
	            $voContratoFiltro->cdContrato = $filtro->voRegistroLivro->voContrato->cdContrato;
	            $voContratoFiltro->anoContrato = $filtro->voRegistroLivro->voContrato->anoContrato;
	            $voContratoFiltro->cdEspecie = $filtro->voRegistroLivro->voContrato->cdEspecie;
	            $voContratoFiltro->sqEspecie = $filtro->voRegistroLivro->voContrato->sqEspecie;
	             
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
	            <TH class="campoformulario" nowrap>Dt.Registro:</TH>
				<TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=filtroManterRegistroLivro::$ID_REQ_DtRegistroInicial?>" 
	            	       name="<?=filtroManterRegistroLivro::$ID_REQ_DtRegistroInicial?>" 
	            			value="<?php echo($filtro->dtRegistroInicial);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"> a 
	            	<INPUT type="text" 
	            	       id="<?=filtroManterRegistroLivro::$ID_REQ_DtRegistroFinal?>" 
	            	       name="<?=filtroManterRegistroLivro::$ID_REQ_DtRegistroFinal?>" 
	            			value="<?php echo($filtro->dtRegistroFinal);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">					            
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
		                  <?php if($isHistorico){?>
		                  	<TH class="headertabeladados"  width="1%">Sq.Hist</TH>
		                  <?php }?>
	                    <TH class="headertabeladados"  width="90%">Contrato</TH>
	                    <TH class="headertabeladados" width="1%" nowrap>Num.Livro</TH>
	                    <TH class="headertabeladados"  width="1%" nowrap>Num.Folha</TH>
	                    <TH class="headertabeladados"  width="1%" nowrap>Registro</TH>
                    </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=5;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voRegistroLivro();                        
                        $registroAtual = $colecao[$i];
                        $voAtual->getDadosBanco($registroAtual);
                                                                        
                        $contrato = getTextoGridContrato($voAtual->voContrato);                        
                                                
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voRegistroLivro::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $contrato;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->numLivro, "0", TAMANHO_CODIGOS_SAFI);?></TD>
					<TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->numFolha, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados"><?php echo getData($voAtual->dtRegistro)?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
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
