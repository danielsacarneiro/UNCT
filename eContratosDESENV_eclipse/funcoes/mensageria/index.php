<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();

$vo = new voMensageria();

$titulo = "CONSULTAR " . $vo::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterMensageria();
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

function detalhar(isExcluir) {
    if(isExcluir == null || !isExcluir){
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
        if (!isApenasUmCheckBoxSelecionado("document.frm_principal.rdb_consulta")){
            return;
        }                    
    }else{
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";

        if (!isCheckBoxConsultaSelecionado("rdb_consulta"))
    		return;        

        if (!isApenasUmCheckBoxSelecionado("document.frm_principal.rdb_consulta", true)){
        	if(confirm("Confirmar Múltiplas Exclusões?")){
        		excluirMultiplos();
        		return;
    		}else{
    	        if (!isApenasUmCheckBoxSelecionado("document.frm_principal.rdb_consulta")){
    	            return;
    	        }                           		
        	}
        }
            
    }   

    var array = retornarValoresCheckBoxesSelecionadosComoArray("rdb_consulta");
	chave = array[0];	
	lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
}

function excluir() {
    detalhar(true);
}

function excluirMultiplos() {
    
    if (!isCheckBoxConsultaSelecionado("rdb_consulta"))
            return;
   
	var chave = retornarValoresCheckBoxesSelecionadosComoString("rdb_consulta");
	var lupa = document.frm_principal.lupa.value;

	alert("Nem pense nisso eduardo!");
		
	//location.href="detalhar.php?funcao=<?=dbMensageria::$NM_FUNCAO_EXCLUIR_MULTIPLOS?>" + "&chave=" + chave + "&lupa="+ lupa;
}

function incluir() {
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isApenasUmCheckBoxSelecionado("document.frm_principal.rdb_consulta"))
        return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>

	var array = retornarValoresCheckBoxesSelecionadosComoArray("rdb_consulta");
	chave = array[0];		
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

</SCRIPT>
<?=setTituloPagina($vo::getTituloJSP())?>
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
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>
            <TR>            
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan="3"><?php getContratoEntradaDeDados($filtro->tipoContrato, $filtro->cdContrato, $filtro->anoContrato, $arrayCssClass, null, null);?></TD>
			</TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Sq.Mensageria:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php				                        
				echo getInputText(voMensageria::$nmAtrSq, voMensageria::$nmAtrSq, $filtro->sq, constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO, 3, 3, " onkeyup='validarCampoNumerico(this, event, false);'");
				?>
				</TD>
	        </TR>			
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50"></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
            <TR>
                <TH class="campoformulario" nowrap width="1%">Habilitado:</TH>
                <TD class="campoformulario" width="1%">
                <?php
                $comboSimNao = new select(dominioSimNao::getColecao());
                echo $comboSimNao->getHtmlCombo(voMensageria::$nmAtrInHabilitado,voMensageria::$nmAtrInHabilitado, $filtro->inHabilitado, true, "camponaoobrigatorio", false, "");
                ?>
				</TD>
                <TH class="campoformulario" nowrap>Será.Prorrog?:</TH>
                <TD class="campoformulario" >
                <?php
                echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInSeraProrrogado,voContratoInfo::$nmAtrInSeraProrrogado, $filtro->inSeraProrrogado, true, "camponaoobrigatorio", false, "");
                ?>
				</TD>								
            </TR>
			<TR>
				<TH class="campoformulario" nowrap>Tp.Vigência:</TH>
				<?php
				include_once(caminho_util."dominioTpVigencia.php");
				$comboVigencia = new select(dominioTpVigencia::getColecao());						
				?>
	            <TD class="campoformulario" nowrap colspan=3><?php echo $comboVigencia->getHtmlOpcao(filtroManterMensageria::$nmAtrTpVigencia,filtroManterMensageria::$nmAtrTpVigencia, $filtro->tpVigencia, false);?></TD>
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
                    <TH class="headertabeladados" width="1%" nowrap>Alerta</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Contrato</TH>
                    <TH class="headertabeladados" width="80%">Contratada</TH>
                    <TH class="headertabeladados" width="1%">CNPJ/CNPF</TH>                    
                    <TH class="headertabeladados" width="1%">Tipo</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Inclusão</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Inicio</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Fim</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dh.Ult.Envio</TH>
                    <TH class="headertabeladados" width="1%">Habilitado</TH>                    
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
                //echoo($tamanho);                                
                $colspan=11;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                
               for ($i=0;$i<$tamanho;$i++) {
               		$registroBanco = $colecao[$i];
                        $voAtual = new voMensageria();
                        $voAtual->getDadosBanco($registroBanco);

                        $voAtualMsgRegistro = new voMensageriaRegistro();
                        $voAtualMsgRegistro->getDadosBanco($registroBanco);
                        
                        $voPessoa = new voPessoa();
                        $voPessoa->getDadosBanco($registroBanco);                        
                                                                   
                        $dsPessoa = $voPessoa->nome;
                        if($dsPessoa == null){
                        	$dsPessoa = "<B>CONTRATO NÃO INCLUÍDO NA PLANILHA</B>";
                        }
                        $habilitado = dominioSimNao::getDescricaoStatic($voAtual->inHabilitado);
                        $tipo = $registroBanco[voDemanda::$nmAtrTipo];
                        $tipo = dominioTipoDemanda::getDescricaoStatic($tipo);
                        //var_dump($voAtual);
                        						
                        $vocontrato = $voAtual->vocontratoinfo;
                        $contrato = formatarCodigoAnoComplemento($vocontrato->cdContrato,
                        				$vocontrato->anoContrato,
                        				$dominioTipoContrato->getDescricao($vocontrato->tipo))
                        				;
                        
                        if($empresa != null){
                        	$contrato .= ": ".$empresa;
                        } 
                        
                        $dtUltEnvio = $registroBanco[voMensageria::$nmCOLDhUltimoEnvio];
                        
                   ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLCheckBoxConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][$voAtual::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                  
                  	<TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->sq, "0", constantes::$TAMANHO_CODIGOS)?></TD>  
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo $contrato;?></TD>                    
					<TD class="tabeladados" nowrap><?php echo $dsPessoa?></TD>					
					<TD class="tabeladados" nowrap><?php echo documentoPessoa::getNumeroDocFormatado($voPessoa->doc)?></TD>
                    <TD class="tabeladados" nowrap><?php echo dominioTipoMensageria::getDescricao($voAtual->tipo)?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dhInclusao)?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtInicio)?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtFim)?></TD>
                    <TD class="tabeladados" nowrap><?php echo getDataHora($dtUltEnvio)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $habilitado?></TD>                    
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
