<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_filtros . "filtroManterPA.php");

//inicia os parametros
inicio();

$vo = new voPA();
$titulo = "CONSULTAR " . voPA::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterPA();
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPAAP($vo, $filtro);

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

function limparFormulario() {	

	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
	}	
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

function validaFormulario() {
	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;
	if(isConsultarArquivo == "S")
		document.frm_principal.<?=vopessoa::$nmAtrNome?>.required = true;
	else
		document.frm_principal.<?=vopessoa::$nmAtrNome?>.required = false;
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
	            <TH class="campoformulario" nowrap width="1%">PAAP.:</TH>
	            <TD class="campoformulario" nowrap width="1%" colspan="3">
	            <?php
	            $selectExercicio = new selectExercicio();
	            echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoPA,voPA::$nmAtrAnoPA, $filtro->anoPA, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdPA, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			</TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" nowrap width="1%" colspan="3">
	            <?php

	            echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoDemanda,voPA::$nmAtrAnoDemanda, $filtro->anoDemanda, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdDemanda?>" name="<?=voPA::$nmAtrCdDemanda?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemanda, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			</TR>			            
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan="3"><?php getContratoEntradaDeDados($filtro->tipoContrato, $filtro->cdContrato, $filtro->anoContrato, $arrayCssClass, null, null);?></TD>        	            
			</TR>        
			<TR>
                <TH class="campoformulario" nowrap>Contratada:</TH>
                <TD class="campoformulario" width="1%">
                Cód: <INPUT type="text" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>"  value="<?php echo complementarCharAEsquerda($filtro->cdPessoa, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" >
                Nome: <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="30" ></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
        
            <?php            
            $comboTpDoc = new select(dominioTpDocumento::getColecaoDocsPAAP());
            ?>	                    
            <TR>
	            <TH class="campoformulario" nowrap>Situação:</TH>
				<TD class="campoformulario" width="1%">
                     <?php
                    include_once("biblioteca_htmlPA.php");                    
                    echo getComboSituacaoPA(voPA::$nmAtrSituacao, voPA::$nmAtrSituacao, $filtro->situacao, "camponaoobrigatorio", "");                                        
                    ?>
				<TH class="campoformulario" nowrap width="1%">Doc.Anexo:</TH>
				<TD class="campoformulario" >
				<?php echo $comboTpDoc->getHtmlSelect(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tpDocumento, true, "camponaoobrigatorio", true);?>								
			</TR>
            <TR>
				<TH class="campoformulario" nowrap>Servidor Responsável:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");                    
                    echo getComboPessoaRespPA(voPA::$nmAtrCdResponsavel, voPA::$nmAtrCdResponsavel, $filtro->cdResponsavel, "camponaoobrigatorio", "");                                        
                    ?>
            </TR>
	        <?php	        
	        $comboTipo = new select(dominioTipoPenalidade::getColecao());
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Penalidade:</TH>
	            <TD class="campoformulario" nowrap colspan=3>
				<?php echo $comboTipo->getHtmlCombo(voPenalidadePA::$nmAtrTipo, voPenalidadePA::$nmAtrTipo, $filtro->tipoPenalidade, true, "camponaoobrigatorio", true, "");?>
			  </TD>			  
			</TR>            
       <?php
        /*$comboOrdenacao = new select(voPA::getAtributosOrdenacao($cdHistorico));
        $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
        echo getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, true, $cdHistorico)*/
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
		                <TH class="headertabeladados" rowspan="2" width="1%">&nbsp;&nbsp;X</TH>
		                  <?php if($isHistorico){?>
		                  	<TH class="headertabeladados" rowspan="2" width="1%">Sq.Hist</TH>
		                  <?php }?>
						<TH class="headertabeladados" colspan="2">
						<center>P.A.</center>
						</TH>
						<TH class="headertabeladados" colspan="2">
						<center>Demanda</center>
						</TH>
						<TH class="headertabeladados" rowspan="2">Contrato/PL</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap >Doc.Contratada</TH>
	                    <TH class="headertabeladados" rowspan="2" width="90%">Descrição</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap>Servidor.Resp.</TH>
	                    <TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Dt.Abertura</TH>
	                    <TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Situação</TH>
                    </TR>
                    <TR>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
                    </TR>                 
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
                include_once (caminho_funcoes."contrato/dominioTipoContrato.php");
                //require_once ("dominioSituacaoPA.php");
                $dominioTipoContrato = new dominioTipoContrato();
                $domSiPA = new dominioSituacaoPA();
                
                $colspan=13;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voPA();
                        $voContratoAtual = new vocontrato();
                        $registroAtual = $colecao[$i];
                        $voAtual->getDadosBanco($registroAtual);     
                        $voContratoAtual->getDadosBanco($registroAtual);
                        $voDemandaPL = new voDemandaPL();
                        $voDemandaPL->getDadosBanco($registroAtual);
                        
                        $cdSituacaoDemanda = $registroAtual[voDemanda::$nmAtrSituacao];
                        $cdSituacao = $voAtual->situacao;
                        
                        $classColunaSituacao = "tabeladadosdestacado";
                        $situacao = $domSiPA->getDescricao($cdSituacao);
                        
                        if(!dominioSituacaoPA::existeItem($cdSituacao,dominioSituacaoPA::getColecaoSituacaoIndependenteDemanda())){
	                        if($cdSituacao == dominioSituacaoPA::$CD_SITUACAO_PA_ARQUIVADO
	                        		|| $cdSituacao == dominioSituacaoPA::$CD_SITUACAO_PA_ENCERRADO){
	                        			$classColunaSituacao = "tabeladadosdestacadoazulclaro";
	                        }else if($cdSituacaoDemanda == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO){
	                        	$situacao = dominioSituacaoDemanda::getDescricaoStatic($cdSituacaoDemanda);
	                        	$classColunaSituacao = "tabeladadosdestacadoverde";
	                        }
                        }
                        $contrato = "";
                        if($voContratoAtual->cdContrato != null){                        	 
                        		$contrato = formatarCodigoAnoComplemento($voContratoAtual->cdContrato,
                        				$voContratoAtual->anoContrato,
                        				$dominioTipoContrato->getDescricao($voContratoAtual->tipo));                        	
                        	//$tipo = $tipo . ":". $contrato;
                        }else if($voDemandaPL->cdProcLic != null){
                        	$contrato = formatarCodigoAnoComplemento($voDemandaPL->cdProcLic,
                        			$voDemandaPL->anoProcLic,
                        			"");
                        }
                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voPA::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->anoPA;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->cdPA, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voAtual->anoDemanda;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->cdDemanda, "0", TAMANHO_CODIGOS);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $contrato;?></TD>
                    <TD class="tabeladados" nowrap><?php echo documentoPessoa::getNumeroDocFormatado($colecao[$i][vopessoa::$nmAtrDoc]);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][$filtro->nmColNomePessoaContrato];?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][$filtro->nmColNomePessoaResponsavel];?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtAbertura);?></TD>
                    <TD class="<?=$classColunaSituacao?>" nowrap><?php echo $situacao;?></TD>
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
