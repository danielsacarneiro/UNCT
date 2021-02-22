<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos . "voDemanda.php");
include_once(caminho_filtros . "filtroManterDemanda.php");

try{
//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voDemanda::getTituloJSP();
setCabecalho($titulo);

$vo = new voDemanda();
$filtro  = new filtroManterDemanda();
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsulta($vo, $filtro);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

$inConsultaHTML = getInConsultarHTMLString();
$nmCampoFaseHtml = voDemanda::$nmAtrFase."[]";
$nmCampoFasePlanilhaHtml = filtroManterDemanda::$NmAtrFasePlanilha ."[]";
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

<?=getFuncaoJSDetalhar()?>

function excluir() {
    detalhar(true);
}

function incluir() {
	//location.href="encaminhar.novo.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
	location.href="<?=getLinkManter("encaminhar.novo.php")?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manterDemanda.novo.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;
}

function alertar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="demandaAlerta.php?consultar=<?=$inConsultaHTML?>&chave=" + chave;	
}

function encaminhar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite encaminhamento.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="encaminhar.novo.php?funcao=<?=dbDemandaTramitacao::$NM_FUNCAO_ENCAMINHAR?>&chave=" + chave;
}

function detalharDemandaGestao(){
	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
	chave = document.frm_principal.rdb_consulta.value;
    url = "../demanda_gestao/detalharDemandaGestao.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S";	
    abrirJanelaAuxiliar(url, true, false, false);
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
	        <?php	        	
	        include_once(caminho_util. "dominioSimNao.php");
	        $comboSimNao = new select(dominioSimNao::getColecao());
	         
	        	$comboTipo = new select(dominioTipoDemanda::getColecao(false));
	        	$comboSituacao = new select(dominioSituacaoDemanda::getColecaoHTMLConsulta());
	        	$comboSetor = new select(dominioSetor::getColecao());
	        	$comboSetorImplantacaoEconti = new select(dominioSetor::getColecaoImplantacaoEcontiDemanda());
	        	$comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	            $selectExercicio = new selectExercicio(constantes::$ANO_INICIO);
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" nowrap width="1%">
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->vodemanda->cd, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
	            <!-- <TH class="campoformulario" nowrap width="1%">Intervalo.Demanda:</TH>
	            <TD class="campoformulario" >
				  Inicial: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$NmAtrCdDemandaInicial?>" name="<?=filtroManterDemanda::$NmAtrCdDemandaInicial?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemandaInicial, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
				  Final: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$NmAtrCdDemandaFinal?>" name="<?=filtroManterDemanda::$NmAtrCdDemandaFinal?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemandaFinal, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>-->
			  	<TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
                <TD class="campoformulario" ><?php echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $filtro->vodemanda->prioridade, true, "camponaoobrigatorio", false, "");?></TD>			  		  
			</TR>			            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Setor.Resp.:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                Setor.Resp.: <?php echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $filtro->vodemanda->cdSetor, true, "camponaoobrigatorio", false, "");?>                
				<font color=red><b>Setor.Atual</b>:</font> <?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $filtro->vodemanda->cdSetorDestino, true, "camponaoobrigatorio", false, "");?>
				A partir da implementação em: <?php echo $comboSetorImplantacaoEconti->getHtmlCombo(filtroManterDemanda::$NmAtrCdSetorImplementacaoEConti,filtroManterDemanda::$NmAtrCdSetorImplementacaoEConti, $filtro->cdSetorImplementacaoEconti, true, "camponaoobrigatorio", false, "");?>
				</TD>				
            </TR>           
            <TR>
                <TH class="campoformulario" nowrap width="1%">Situação:</TH>
                <TD class="campoformularioalinhadomeio" width="1%">
	                <TABLE class="filtro" cellpadding="0" cellspacing="0">
	                <TR>
	                	<TD class="campoformulario" width="1%">
						<?php echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao."[]", $filtro->vodemanda->situacao, true, "camponaoobrigatorio", false, " multiple ");?>
	                	<TD class="campoformulario" width="1%">Passou.por</TD>
	                	<TD class="campoformulario" >
						<?php 
						echo $comboSetor->getHtmlCombo(filtroManterDemanda::$NmAtrCdSetorPassagem,filtroManterDemanda::$NmAtrCdSetorPassagem."[]", $filtro->cdSetorPassagem, true, "camponaoobrigatorio", false, " multiple ");
						?>	                	
						</TD>
	                </TR>
	                </TABLE>
                </TD>
                <TH class="campoformulario" nowrap width="1%" rowspan=3>Tipo:</TH>
                <TD class="campoformulario" rowspan=3>
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
	        require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
	        require_once (caminho_funcoes . voPA::getNmTabela() . "/biblioteca_htmlPA.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitatório:</TH>
	            <TD class="campoformulario"><?php getCampoDadosProcLicitatorioComCPL($filtro->voproclic);?>
	            </TD>
	        </TR>	                    
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PAAP:</TH>
	            <TD class="campoformulario" nowrap>
	            <?php getCampoDadosPAAP($filtro->voPA);
	            echo " Tem?: " . $comboSimNao->getHtmlCombo(filtroManterDemanda::$NmAtrInComPAAPInstaurado,
	            								filtroManterDemanda::$NmAtrInComPAAPInstaurado, 
	            								$filtro->inComPAAPInstaurado, true, "camponaoobrigatorio", false,"");
	            ?>
	            </TD>
	        </TR>	                    
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" nowrap width="1%">				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value="<?=$filtro->vodemanda->texto?>"  class="camponaoobrigatorio" size="50">
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI:</TH>
	            <TD class="campoformulario">				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" value="<?php echo($filtro->vodemanda->prt);?>" class="camponaoobrigatorio" size="30">
	            <?php 
	            echo " é SEI?: " . $comboSimNao->getHtmlCombo(filtroManterDemanda::$NmAtrInSEI,
	            								filtroManterDemanda::$NmAtrInSEI, 
	            								$filtro->inSEI, true, "camponaoobrigatorio", false,"");?>
	            </TD>	            	                        	                        
	        </TR>            
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Referência:</TH>
	            <TD class="campoformulario" width="1%">
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtReferenciaInicial?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtReferenciaInicial?>" 
	            			value="<?php echo($filtro->dtReferenciaInicial);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"> a 
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtReferenciaFinal?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtReferenciaFinal?>" 
	            			value="<?php echo($filtro->dtReferenciaFinal);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">
	            			
	            <?php 
	            echo "| Monitorar?: " . $comboSimNao->getHtmlCombo(filtroManterDemanda::$ID_REQ_InMonitorar,
	            								filtroManterDemanda::$ID_REQ_InMonitorar, 
	            								$filtro->inMonitorar, true, "camponaoobrigatorio", false,"");
	            ?>
	            								            
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Dt.Últ.Mov.:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoInicial?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoInicial?>" 
	            			value="<?php echo($filtro->dtUltMovimentacaoInicial);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"> a 
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoFinal?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoFinal?>" 
	            			value="<?php echo($filtro->dtUltMovimentacaoFinal);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">				
				</TD>					            	            
	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%" ROWSPAN=2>Contrato:</TH>
	            <TD class="campoformulario" ROWSPAN=2><?php

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
			             	            		
	            ?>
	            </TD>
	            <TH class="campoformulario" >Valor Global:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>"  value="<?php echo($filtro->vlGlobalInicial);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            							a <INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>"  value="<?php echo($filtro->vlGlobalFinal);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            							</TD>        	            
			</TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				//$combo = new select(dominioAutorizacao::getColecao());				
				$nmCheckAutorizacaoArray = vocontrato::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->vocontrato->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario">
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroManterDemanda::$NmAtrInOR_AND,filtroManterDemanda::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>					            	            
	        </TR>			
			<TR>
                <TH class="campoformulario" nowrap>Contratada:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                Nome: <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50">                
                CNPJ/CPF: <INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18">
                </TD>
            </TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioClassificacaoContrato.php");
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());
				?>
	            <TH class="campoformulario" nowrap>Classificação:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php 
	            echo $comboClassificacao->getHtmlCombo(voContratoInfo::$nmAtrCdClassificacao,voContratoInfo::$nmAtrCdClassificacao, $filtro->cdClassificacaoContrato, true, "camponaoobrigatorio", false, "");
	            //$radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " required " );
	            
	            echo "&nbsp;&nbsp;Planilha.Custos?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $filtro->inMaoDeObra, true, "camponaoobrigatorio", false,"");
	            ?>
				<TD class="campoformulario" width="1%"  colspan=2>	            
	            <?php 
	            $pArrayFasePlanilha = array(
	            		$nmCampoFasePlanilhaHtml,
	            		$filtro->fasePlanilha,
	            		dominioFaseDemanda::getColecaoPlanilha(),
	            		1,
	            		true,
	            		"",
	            		true,
	            		"",
	            		false,
	            		filtroManterDemanda::$NmAtrInOR_AND_Fase,
	            		$filtro->inOR_AND_Fase,
	            		false,
	            		true,
	            		true
	            );
	            echo dominioFaseDemanda::getHtmlChecksBoxArray($pArrayFasePlanilha);	            	            
	            ?>
	            </TD>
	        </TR>
            <?php            
            $comboTpDoc = new select(dominioTpDocumento::getColecaoConsulta());

            $voUsuario = new voUsuarioInfo();
            $filtroUsu = new filtroManterUsuario(false);
            $filtroUsu->cdAtrOrdenacao = voUsuarioInfo::$nmAtrName;
            $colecaoUsu = $voUsuario->dbprocesso->consultarTelaConsulta($voUsuario, $filtroUsu);
            
            $comboUsuTramitacao = new select($colecaoUsu, voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName);
            ?>	                    
            <TR>
				<TH class="campoformulario" nowrap width="1%">Doc.Anexo:</TH>
				<TD class="campoformulario" colspan=3>				
				Ano: <?php echo $selectExercicio->getHtmlCombo(voDocumento::$nmAtrAno,voDocumento::$nmAtrAno, $filtro->anoDocumento, true, "camponaoobrigatorio", false, "");?>
				Setor: <?php 
				echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $filtro->cdSetorDocumento, true, "camponaoobrigatorio", false, "");				
				echo "Tipo: ". $comboTpDoc->getHtmlSelect(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tpDocumento, true, "camponaoobrigatorio", true);
				?>
				Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo(complementarCharAEsquerda($filtro->sqDocumento, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
				<?php 
				$nmCamposDoc = array(
						voDocumento::$nmAtrTp,
						voDocumento::$nmAtrAno,
						voDocumento::$nmAtrSq,
						voDocumento::$nmAtrCdSetor,
				);
				echo getBorracha($nmCamposDoc, "");
				?>
			</TR>
            <TR>            
	            <TH class="campoformulario" nowrap width="1%">Resp.:</TH>
				<TD class="campoformulario" width="1%" colspan=3>
				<?php 
				//echo "Tram.:&nbsp;".$comboUsuTramitacao->getHtmlSelect(filtroManterDemanda::$NmAtrCdUsuarioTramitacao,filtroManterDemanda::$NmAtrCdUsuarioTramitacao, $filtro->cdUsuarioTramitacao, true, "camponaoobrigatorio", false). "&nbsp";
				$arrayParamUsuarioTram = array(
						filtroManterDemanda::$NmAtrCdUsuarioTramitacao,
						filtroManterDemanda::$NmAtrCdUsuarioTramitacao,
						$filtro->cdUsuarioTramitacao,
						true,
						true,
						"camponaoobrigatorio",
						false,
						"",
						20
				);				
				echo "Tram.:&nbsp;".$comboUsuTramitacao->getHtmlComboArray($arrayParamUsuarioTram);
				
				
				$arrayParamUsuario = array(
						voDemanda::$nmAtrCdPessoaRespUNCT,
						voDemanda::$nmAtrCdPessoaRespUNCT,
						$filtro->vodemanda->cdPessoaRespUNCT,
						true,
						true,
						"camponaoobrigatorio",
						false,
						"",
						20
				);								
				echo "UNCT:&nbsp;".getComboUsuarioPorSetor($arrayParamUsuario, dominioSetor::$CD_SETOR_UNCT) . "&nbsp";				
				
				$arrayATJAResp = array(
						voDemanda::$nmAtrCdPessoaRespATJA,
						voDemanda::$nmAtrCdPessoaRespATJA,
						$filtro->vodemanda->cdPessoaRespATJA,
						true,
						true,
						"camponaoobrigatorio",
						false,
						"",
						20
				);
				
				echo "ATJA.:&nbsp;".getComboPessoaRespATJAConsulta($arrayATJAResp);
				?>				
				</TD>			
			</TR>
            <TR>            
	            <TH class="campoformulario" nowrap width="1%">Fase:</TH>
				<TD class="campoformulario" colspan=3> 
				<?php				
				$pArrayFase = array(
						$nmCampoFaseHtml,
						$filtro->vodemanda->fase,
						null,
						1,
						true,
						"",
						false,
						"",
						true,
						filtroManterDemanda::$NmAtrInOR_AND_Fase,
						$filtro->inOR_AND_Fase,
						false,
						true,
				);
				echo dominioFaseDemanda::getHtmlChecksBoxArray($pArrayFase);
				?>
				</TD>												
			</TR>
            <TR>            
	            <TH class="campoformulario" nowrap width="1%">Tempo.Vida.Mínimo:</TH>
				<TD class="campoformulario" colspan=3>
				Última Tramitação: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram?>" name="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram?>"  value="<?php echo(complementarCharAEsquerda($filtro->nuTempoVidaMinimoUltimaTram, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="3" maxlength="3"> (dias)|				
				Total: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo?>" name="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo?>"  value="<?php echo(complementarCharAEsquerda($filtro->nuTempoVidaMinimo, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="3" maxlength="3"> (dias)
				<?php 
				$nmCamposDoc = array(
						filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo,
						filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram,
				);
				echo getBorracha($nmCamposDoc, "");
				?>
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
                  <TH class="headertabeladados" width="1%" rowspan=2>&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%" rowspan=2>Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%" nowrap rowspan=2>Ano</TH>
                    <TH class="headertabeladados" width="1%" rowspan=2>Núm.</TH>
                    <TH class="headertabeladados" width="1%" rowspan=2>Orig</TH>
                    <TH class="headertabeladados" width="1%" rowspan=2>Atual</TH>
                    <TH class="headertabeladados" width="1%" rowspan=2>Tipo</TH>
                    <TH class="headertabeladados" width="40%" rowspan=2>Contrato/PL</TH>
                    <TH class="headertabeladados"width="50%"  rowspan=2>Título</TH>                    
                    <TH class="headertabeladados" width="1%" rowspan=2>PRT/SEI</TH>
                    <TH class="headertabeladados"width="1%" nowrap rowspan=2>Abertura</TH>
                    <TH class="headertabeladados"width="1%" nowrap rowspan=2>Últ.Movim</TH>
                    <TH class="headertabeladadosalinhadocentro"width="1%" nowrap colspan="2">Prazo</TH>
                    <TH class="headertabeladados" width="1%" rowspan=2>Situação</TH>                    
                </TR>
                <TR>
                    <TH class="headertabeladados"width="1%">Últ.</TH>
                    <TH class="headertabeladados" width="1%">Total</TH>                    
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                //require_once ("dominioSituacaoPA.php");
                $dominioSituacao = new dominioSituacaoDemanda();
                $dominioSetor = new dominioSetor();
                //$dominioPrioridade = new dominioPrioridadeDemanda();
                                
                $colspan=14;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                		$contrato = "";
                        $voAtual = new voDemanda();
                        $voAtual->getDadosBanco($colecao[$i]);    
                        
                        $voDemandaContrato = new voDemandaContrato();
                        $voDemandaContrato->getDadosBanco($colecao[$i]);
                        
                        $voDemandaPL = new voDemandaPL();
                        $voDemandaPL->getDadosBanco($colecao[$i]);
                        
                        $qtContratos = $colecao[$i][filtroManterDemanda::$NmColQtdContratos];
                        
                                                
                        //$especie = getDsEspecie($voAtual);
                        $cdSituacao = $voAtual->situacao;
                        $situacao = $dominioSituacao->getDescricao($cdSituacao);                        
                        $classColunaSituacao = dominioSituacaoDemanda::getCorColuna($cdSituacao);
                        
                        $setor = $dominioSetor->getDescricao($voAtual->cdSetor);
                        
                        $setorDestinoAtual = $colecao[$i][voDemandaTramitacao::$nmAtrCdSetorDestino];
                        $setorDestinoAtual = $dominioSetor->getDescricao($setorDestinoAtual);
                        
                        $cdTipoDemanda = $voAtual->tipo;
                        $tipo = dominioTipoDemanda::getDescricaoStatic($cdTipoDemanda);
                        $dsTpDemandaContrato = $voAtual->tpDemandaContrato;
                        $dsTpDemandaContrato = dominioTipoDemandaContrato::getDescricaoColecaoChave($dsTpDemandaContrato, false, dominioTipoDemandaContrato::getColecaoAntiga());

                        /*if($voAtual->tpDemandaContrato != null){
                         $tipo = "$tipo<br>$dsTpDemandaContrato";
                         }*/
                        
                        if($voAtual->tipo == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){
                        	$tpTempContrato = dominioTipoContrato::getDescricaoStatic($voDemandaContrato->voContrato->tipo, dominioTipoContrato::getColecaoInstrumentos());
                        	$tipo = "$tpTempContrato<br>$dsTpDemandaContrato";
                        }else if($voAtual->tpDemandaContrato != null){
                        	$tipo = "$tipo<br>$dsTpDemandaContrato";
                        }
                        
                        $empresa = $colecao[$i][vopessoa::$nmAtrNome];                        
                        if($voDemandaContrato->voContrato->cdContrato != null){
                        	
                        	if($qtContratos > 1){
                        		$contrato = "VÁRIOS";
                        	}else{                        	
	                        	$contrato = formatarCodigoAnoComplemento($voDemandaContrato->voContrato->cdContrato,
	                        			$voDemandaContrato->voContrato->anoContrato,
	                        			$dominioTipoContrato->getDescricao($voDemandaContrato->voContrato->tipo));
	                        	
	                        	$complementoContrato = getContratoDescricaoEspecie($voDemandaContrato->voContrato);
	                        	if($complementoContrato != ""){
	                        		$contrato .= "|$complementoContrato";
	                        	}
	                        	
	                        	if($empresa != null){
	                        		$contrato .= ": ".$empresa;
	                        	}
                        	}                        	 
                        	//$tipo = $tipo . ":". $contrato;
                        }else if($voDemandaPL->cdProcLic != null){                        	
	                        	$contrato = formatarCodigoAnoComplemento($voDemandaPL->cdProcLic,
	                        			$voDemandaPL->anoProcLic,
	                        			$voDemandaPL->cdModProcLic);
                        }else if($cdTipoDemanda == dominioTipoDemanda::$CD_TIPO_DEMANDA_TERMOADESAO){                        	
	                        	$contrato = $voAtual->prt;
                        }
                        	
                        //$prioridade = $dominioPrioridade->getDescricao($voAtual->prioridade);
                        //$prt = voDemanda::getNumeroPRTComMascara($voAtual->prt);
                        $prt = $voAtual->prt;
                        
                        /*$nmUsuario = $voAtual->nmUsuarioInclusao;
                        if($isHistorico){
                        	$nmUsuario = $voAtual->nmUsuarioOperacao;
                        }*/
                                                
                        $dataUltimaMovimentacao = $registro[filtroManterDemanda::$NmColDhUltimaMovimentacao];
                        $tempovida = $registro[filtroManterDemanda::$NmColNuTempoVida];
                        $tempoUltTram = $registro[filtroManterDemanda::$NmColNuTempoUltimaTram];
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
                    <TD class="tabeladadosdestacadonegrito"><?php echo complementarCharAEsquerda($voAtual->cd, "0", TAMANHO_CODIGOS)?></TD>
					<TD class="tabeladados" nowrap><?php echo $setor?></TD>
					<TD class="tabeladados" nowrap><?php echo $setorDestinoAtual?></TD>
					<TD class="tabeladados"><?php echo $tipo?></TD>
					<TD class="tabeladados" ><?php echo truncarStringHTML($contrato, 60, true)?></TD>
                    <TD class="tabeladados" ><?php echo truncarStringHTML(strtolower($voAtual->texto), 100, true);?></TD>                    
                    <TD class="tabeladados" nowrap><?php echo $prt?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtReferencia);?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($dataUltimaMovimentacao);?></TD>
					<TD class="tabeladadosalinhadodireita" nowrap><?php echo complementarCharAEsquerda($tempoUltTram, "0", 3);?></TD>
					<TD class="tabeladadosalinhadodireita" nowrap><?php echo complementarCharAEsquerda($tempovida, "0", 3);?></TD>                    
                    <TD class="<?=$classColunaSituacao;?>" ><?php echo $situacao?></TD>                    
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
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoValidacaoAcesso("bttDetalharDemandaGestao", "Gestao.Prazo", "botaofuncaop", false, false,true,false,"onClick='javascript:detalharDemandaGestao();' accesskey='g'");
                            ?>                                                        
                            </TD>	                   	
                            <?php
                            $arrayBotoesARemover = array(constantes::$CD_FUNCAO_EXCLUIR);
                            echo getBotoesRodapeComRestricao($arrayBotoesARemover);
                            //echo getBotoesRodape();
                            ?>
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoValidacaoAcesso("bttEncaminhar", "Encaminhar", "botaofuncaop", false, false,true,false,"onClick='javascript:encaminhar();' accesskey='e'");
                            /*if(isUsuarioAdmin()){
                            	echo getBotaoValidacaoAcesso("bttIncluirNovo", "IncluirNovo", "botaofuncaop", false, false,true,false,"onClick='javascript:incluirNovo();' accesskey='n'");
                            	echo getBotaoValidacaoAcesso("bttAlterarNovo", "AlterarNovo", "botaofuncaop", false, false,true,false,"onClick='javascript:alterarNovo();' accesskey='v'");
                            	echo getBotaoValidacaoAcesso("bttAlertar", "Alertar", "botaofuncaop", false, false,true,false,"onClick='javascript:alertar();' accesskey='l'");
                            }*/
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
