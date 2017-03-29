<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos . "voDemanda.php");
include_once(caminho_filtros . "filtroManterDemanda.php");

//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voDemanda::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterDemanda();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$vo = new voDemanda();
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
<?=setTituloPagina(null)?>
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
	location.href="encaminhar.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manterDemanda.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

function encaminhar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite encaminhamento.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="encaminhar.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;
}

</SCRIPT>

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
	        	$comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        	$comboSetor = new select(dominioSetor::getColecao());
	        	$comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	            $selectExercicio = new selectExercicio();
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, "");?>
			  N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->vodemanda->cd, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			</TR>			            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Setor.Resp.:</TH>
                <TD class="campoformulario" width="1%" ><?php echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $filtro->vodemanda->cdSetor, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Setor.Atual.:</TH>
                <TD class="campoformulario" ><?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $filtro->vodemanda->cdSetorDestino, true, "camponaoobrigatorio", false, "");?></TD>
            </TR>            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Situa��o:</TH>
                <TD class="campoformulario" width="1%"><?php echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao, $filtro->vodemanda->situacao, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
                <TD class="campoformulario" ><?php echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $filtro->vodemanda->prioridade, true, "camponaoobrigatorio", false, "");?></TD>                                
            </TR>
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" width="1%"><?php getContratoEntradaDeDados($filtro->vocontrato->tipo, $filtro->vocontrato->cdContrato, $filtro->vocontrato->anoContrato, $arrayCssClass, null, null);?></TD>
			</TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");				
				$combo = new select(dominioAutorizacao::getColecao());						
				?>
	            <TH class="campoformulario" nowrap>Autoriza��o:</TH>
	            <TD class="campoformulario" colspan="3"><?php echo $combo->getHtmlSelect(vocontrato::$nmAtrCdAutorizacaoContrato,vocontrato::$nmAtrCdAutorizacaoContrato, $filtro->vocontrato->cdAutorizacao, true, "camponaoobrigatorio", true);?>	            
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
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
                    <TH class="headertabeladados" width="1%">N�m.</TH>
                    <TH class="headertabeladados" width="1%">Setor.Resp</TH>
                    <TH class="headertabeladados" width="1%">Setor.Atual</TH>
                    <TH class="headertabeladados" width="1%">Tipo</TH>
                    <TH class="headertabeladados"width="90%" nowrap >Texto</TH>
                    <TH class="headertabeladados" width="1%">Situa��o</TH>                    
                    <TH class="headertabeladados" width="1%">Prior.</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Usu�rio</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Dt.Abertura</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                //require_once ("dominioSituacaoPA.php");
                $dominioSituacao = new dominioSituacaoDemanda();
                $dominioSetor = new dominioSetor();
                $dominioTipo = new dominioTipoDemanda();
                $dominioPrioridade = new dominioPrioridadeDemanda();
                                
                $colspan=11;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voDemanda();
                        $voAtual->getDadosBanco($colecao[$i]);     
                        
                        //$especie = getDsEspecie($voAtual);
                        $cdSituacao = $voAtual->situacao;
                        $situacao = $dominioSituacao->getDescricao($cdSituacao);
                        $classColunaSituacao = "tabeladadosdestacadoazulclaro";                        
                        if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA){
                        	$classColunaSituacao = "tabeladadosdestacado";
                        }
                        
                        $setor = $dominioSetor->getDescricao($voAtual->cdSetor);
                        
                        $setorDestinoAtual = $colecao[$i][voDemandaTramitacao::$nmAtrCdSetorDestino];
                        if($setorDestinoAtual == null){
                        	$setorDestinoAtual = $setor;
                        }
                        $setorDestinoAtual = $dominioSetor->getDescricao($setorDestinoAtual);
                        
                        $tipo = $dominioTipo->getDescricao($voAtual->tipo);
                        $prioridade = $dominioPrioridade->getDescricao($voAtual->prioridade);
                        
                        $nmUsuario = $voAtual->nmUsuarioInclusao;
                        if($isHistorico){
                        	$nmUsuario = $voAtual->nmUsuarioOperacao;
                        }
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
                    <TD class="tabeladadosalinhadodireita"><?php echo $voAtual->ano;?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo complementarCharAEsquerda($voAtual->cd, "0", TAMANHO_CODIGOS)?></TD>
					<TD class="tabeladados" nowrap><?php echo $setor?></TD>
					<TD class="tabeladados" nowrap><?php echo $setorDestinoAtual?></TD>
					<TD class="tabeladados" nowrap><?php echo $tipo?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voAtual->texto;?></TD>
                    <TD class="<?=$classColunaSituacao;?>" nowrap><?php echo $situacao?></TD>                    
                    <TD class="tabeladados" nowrap><?php echo $prioridade?></TD>
                    <TD class="tabeladados" nowrap><?php echo $nmUsuario;?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtReferencia);?></TD>
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
                            /*$arrayBotoesARemover = array(constantes::$CD_FUNCAO_ALTERAR);
                            echo getBotoesRodapeComRestricao($arrayBotoesARemover);*/
                            echo getBotoesRodape();
                            ?>
                            <TD class='botaofuncao'>
                            <?php echo getBotaoValidacaoAcesso("bttEncaminhar", "Encaminhar", "botaofuncaop", false, false,true,false,"onClick='javascript:encaminhar();' accesskey='e'");?>
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