<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_filtros . "filtroManterPenalidade.php");

//inicia os parametros
inicio();

$vo = new voPenalidadePA();
$titulo = "CONSULTAR " . voPenalidadePA::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterPenalidade();
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPenalidadeTelaConsulta($vo, $filtro);

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
	            echo "Ano: " . $selectExercicio->getHtmlCombo(voPenalidadePA::$nmAtrAnoPA,voPenalidadePA::$nmAtrAnoPA, $filtro->anoPA, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPenalidadePA::$nmAtrCdPA?>" name="<?=voPenalidadePA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdPA, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
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
	        $comboTipo = new select(dominioTipoPenalidade::getColecaoComReferenciaLegal());
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	            <TD class="campoformulario" nowrap colspan=3>
				  <?php echo $comboTipo->getHtmlCombo(voPenalidadePA::$nmAtrTipo, voPenalidadePA::$nmAtrTipo, $filtro->tipoPenalidade, true, "camponaoobrigatorio", true, "");?>
			  </TD>			  
			</TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Fundamento:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voPenalidadePA::$nmAtrFundamento?>" name="<?=voPenalidadePA::$nmAtrFundamento?>" value="<?=$filtro->fundamento?>"  class="camponaoobrigatorio" size="50">
	            </TD>
	            </TD>	            	                        	                        
	        </TR>            
			
       <?php
        /*$comboOrdenacao = new select(voPenalidadePA::getAtributosOrdenacao($cdHistorico));
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
		                <TH class="headertabeladados" rowspan="2" width="1%">Num.</TH>		                  
						<TH class="headertabeladados" colspan="2">
						<center>P.A.</center>
						</TH>
	                    <TH class="headertabeladados" rowspan="2" nowrap width="1%">Penalidade</TH>
						<TH class="headertabeladados" colspan="3">
						<center>Contrato</center>
						</TH>
	                    <TH class="headertabeladados" rowspan="2" width="40%">Contratada</TH>
						<TH class="headertabeladados" rowspan="2"  width="60%">Fundamento</TH>
						<TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Data</TH>
                    </TR>
                    <TR>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
	                    <TH class="headertabeladados" width="1%">Tipo</TH>	                    
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
                        $voAtual = new voPenalidadePA();
                        $voContratoAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecao[$i]);
                        $voContratoAtual->getDadosBanco($colecao[$i]);
                                                
                        $tipoContrato = $dominioTipoContrato->getDescricao($voContratoAtual->tipo);                        
                        $tipoPenalidade = dominioTipoPenalidade::getDescricaoStatic($voAtual->tipo);
                        
                        $contratada = $colecao[$i][$filtro->nmColNomePessoaContrato];
                        //$doccontratada = documentoPessoa::getNumeroDocFormatado($colecao[$i][vopessoa::$nmAtrDoc]);                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voPenalidadePA::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->sq, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voAtual->anoPA;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->cdPA, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipoPenalidade;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voContratoAtual->anoContrato;?></TD>                    
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voContratoAtual->cdContrato, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipoContrato;?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][$filtro->nmColNomePessoaContrato];?></TD>
                    <TD class="tabeladados"><?php echo $voAtual->fundamento;?></TD>
					<TD class="tabeladados" nowrap><?php echo getData($voAtual->dtAplicacao);?></TD>
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
