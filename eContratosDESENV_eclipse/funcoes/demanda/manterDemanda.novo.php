<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDemanda();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$vo = $dbprocesso->consultarPorChaveTelaColecaoContrato($vo, $isHistorico);
putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "ALTERAR ";
$titulo = $vo->getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
	$isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);

$nmCampoTpDemandaContratoSimples = voDemanda::$nmAtrTpDemandaContrato;
$nmCampoTpDemandaContrato = $nmCampoTpDemandaContratoSimples."[]";
$nmCampoFaseHtml = voDemanda::$nmAtrFase."[]";
$nmCampoCaracteristicasHtml = voDemanda::$nmAtrInCaracteristicas ."[]";
$nmDivInformacoesComplementares = voDemanda::$ID_REQ_DIV_REAJUSTE_MONTANTE_A;
$nmCampoTpDemandaReajuste = voDemanda::$nmAtrInTpDemandaReajusteComMontanteA;
?>

<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {

	campoTipoDemanda = document.frm_principal.<?=voDemandaTramitacao::$nmAtrTipo?>;
	campoPessoaContrato = document.getElementsByName("<?=vopessoa::getID_REQ_ColecaoContrato()?>")[0];
	if(campoTipoDemanda.value == "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL?>" && campoPessoaContrato != null){
		exibirMensagem("Tipo da Demanda não permite inclusão de contrato");	
		return false;		
	}
	
	//obriga a selecao do tpDemandaContrato
	var temContratoSelecionado = !isCheckBoxConsultaSelecionado('<?=vodemanda::$ID_REQ_InTemContrato?>', true);
	if(temContratoSelecionado && campoTipoDemanda.value == "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO?>"){
		if(!isCheckBoxConsultaSelecionado('<?=$nmCampoTpDemandaContrato?>', true)){
			exibirMensagem('Demanda de Contrato exige preenchimento das informações complementares.');			
			return false;		
		}
	}
			
	return true;
}


function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return confirm("Confirmar Alteracoes?");    
}

function validaFormulario() {
	arrayPrioridadeAlta = ['<?=voDemanda::$nmAtrPrioridade?>', <?=dominioPrioridadeDemanda::$CD_PRIORI_ALTA;?>];

	pColecaoNmObjetosFormEdital = ['<?=voProcLicitatorio::$nmAtrCd;?>', '<?=voProcLicitatorio::$nmAtrAno;?>', '<?=voProcLicitatorio::$nmAtrCdModalidade;?>'];
	formataFormEditalPorTpDemanda('<?=voDemanda::$nmAtrTipo?>', pColecaoNmObjetosFormEdital, <?=dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL?>, arrayPrioridadeAlta);
	formataFormTpDemandaContrato();
}

function formataFormTpDemandaContrato(){
	<?php
	$dominioTipoDemanda = new dominioTipoDemanda(dominioTipoDemanda::getColecaoTipoDemandaContratoGenero());
	echo $dominioTipoDemanda->getArrayHTMLChaves("colecaoTpDemandaContrato");	
	?>		

	var nmCampoCheckTpDemandaContrato = '<?=$nmCampoTpDemandaContrato?>';
	var arrayCheckSelecionado = retornarValoresCheckBoxesSelecionadosComoArray(nmCampoCheckTpDemandaContrato);
	//alert(arrayCheckSelecionado);
	var tpReajuste = '<?=dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE?>';
	var isReajusteSelecionado = arrayCheckSelecionado.indexOf(tpReajuste) != -1;
		
	formataFormTpDemandaReajusteContrato("<?=voDemanda::$nmAtrTipo?>", 
			"<?=$nmDivInformacoesComplementares?>", 
			colecaoTpDemandaContrato, 
			"<?=$nmCampoTpDemandaReajuste?>",
			isReajusteSelecionado, 
			false);
	formataFormTpDemanda('<?=voDemanda::$nmAtrTipo?>', nmCampoCheckTpDemandaContrato);
}

function iniciar(){	
	formataFormTpDemandaContrato();
}

</SCRIPT>

</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmarAlteracaoDemanda.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<?=getInputHidden(voDemanda::$nmAtrInLegado, voDemanda::$nmAtrInLegado, "N")?>
 
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
            
	        $comboTipo = new select(dominioTipoDemanda::getColecaoTipoDemanda());
	        $comboSetor = new select(dominioSetor::getColecao());
	        $comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        $comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	        $selectExercicio = new selectExercicio();	         
	        	        	        
	        $complementoHTML = "";
	        
	        getDemandaDetalhamentoComLupa($vo, false, false, null, true);
	        //function getDemandaDetalhamentoComLupa($voDemanda, $temLupaDet, $exibeTipoDemanda = true, $colspan=null, $comProcLici = true){
	        ?>	        	        
	        <TR>
            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>	        
	        <TD class="campoformulario" colspan=3>
				<TABLE id="table_tipo" class="filtro" cellpadding="0" cellspacing="0">
					<TBODY>
					<TR>
		            <TD class="campoformulario">					
	            <?php 
	            echo "Tipo: " . $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo, $vo->tipo, true, "campoobrigatorio", false, " required onChange='validaFormulario();'");
	            echo getTpDemandaContrato($nmCampoTpDemandaContrato, $nmCampoTpDemandaReajuste, $nmDivInformacoesComplementares, $vo->tpDemandaContrato, $vo->inTpDemandaReajusteComMontanteA);	            
	            ?>
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Características:</TH>
	            <TD class="campoformulario" nowrap>
	            <?php
	            echo dominioCaracteristicasDemanda::getHtmlChecksBox($nmCampoCaracteristicasHtml , $vo->inCaracteristicas, null, 1, false, "");
	          	?>	 
	          	 </TD>
		          	</TR>
		          	</TBODY>
		         </TABLE>
		    </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Responsável:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo $comboSetor->getHtmlCombo("","", $vo->cdSetor, true, "camporeadonly", false, " disabled ");?>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario" >
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "campoobrigatorio", false, " required ");
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());
	            echo " | " . getTextoHTMLTagMouseOver(getTextoHTMLDestacado("Monitorar?"), voDemanda::$MSG_IN_MONITORAR) . ": ";
	            echo $comboSimNao->getHtmlCombo(voDemanda::$nmAtrInMonitorar,voDemanda::$nmAtrInMonitorar, $vo->inMonitorar, true, "camponaoobrigatorio", false, "");
	            echo "|" . getTextoHTMLTagMouseOver("Dt.Monitoramento", "Data de início do monitoramento: apenas para demandas não urgentes!");
	            ?>
	            : 
	            	 <INPUT type="text" 
	            	       id="<?=voDemandaTramitacao::$nmAtrDtMonitoramento?>" 
	            	       name="<?=voDemandaTramitacao::$nmAtrDtMonitoramento?>" 
	            			value="<?php echo(getData($vo->dtMonitoramento));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">
				</TD>				
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value="<?=getVarComoStringHTML($vo->texto)?>"  class="campoobrigatorio" size="80" required>
	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitatório:</TH>
	            <TD class="campoformulario" colspan=3><?php getCampoDadosProcLicitatorio($vo->voProcLicitatorio);?>
	            </TD>
	        </TR>	        
	        <?php
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");	        
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" nowrap colspan=3>
	            <?php	            
	            //getCampoDadosColecaoContratos($vo->colecaoContrato, true, "camponaoobrigatorio", true);
	            getContratoEntradaDeDadosVOSimples($vo->colecaoContrato[0], constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, true, true, true);
	            ?>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	 //segue com o numero 1 ao fim do id porque o contrato eh definido por indices
	            	 //para entender, basta olhar o metodo acima getCampoDadosContratoMultiplos
	            	colecaoIDCamposRequired = ["<?=vocontrato::$nmAtrTipoContrato?>",
		            	"<?=vocontrato::$nmAtrCdContrato?>",
		            	"<?=vocontrato::$nmAtrAnoContrato?>",
		            	"<?=vocontrato::$nmAtrCdEspecieContrato?>",
		            	"<?=vocontrato::$nmAtrSqEspecieContrato?>",
		            	];
	            </SCRIPT>
	            <INPUT type="checkbox" id="<?=vodemanda::$ID_REQ_InTemContrato?>" name="<?=vodemanda::$ID_REQ_InTemContrato?>" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Não tem contrato.	            
				</TD>
			</TR>
				        <TR>
				            <TH class="campoformulario" width="1%">Resp.:</TH>
				            <TD class="campoformulario" colspan=3>
				            <?php
				            $arrayParamUsuario = array(
				            		voDemanda::$nmAtrCdPessoaRespUNCT,
				            		voDemanda::$nmAtrCdPessoaRespUNCT,
				            		$vo->cdPessoaRespUNCT,
				            		true,
				            		false,
				            		constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,
				            		false,
				            		"required",
				            );
				            
				            echo "UNCT:&nbsp;".getComboUsuarioPorSetor($arrayParamUsuario, dominioSetor::$CD_SETOR_UNCT) . "&nbsp";
				            echo "ATJA:&nbsp;".getComboPessoaRespPA(voDemanda::$nmAtrCdPessoaRespATJA, voDemanda::$nmAtrCdPessoaRespATJA, $vo->cdPessoaRespATJA, "camponaoobrigatorio", " required ");
				          	?>
							</TD>				        
			            <TR>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php 
	            echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");
	            ?>
				</TD>
	            <TH class="campoformulario" width="1%">Data.Demanda:</TH>
	            <TD class="campoformulario">	            	            	            
	            <INPUT type="text" value="<?=getData($vo->dtReferencia);?>"  class="camporeadonly" size="12" readonly>
            	</TD>				
	        </TR>
			<TR>
	            <TH class="campoformulario" width="1%">Fases:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php
	            echo dominioFaseDemanda::getHtmlChecksBox($nmCampoFaseHtml, $vo->fase, null, 1, true, "");
	          	?>
				</TD>				
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI(principal):</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemanda::$nmAtrProtocolo?>" name="<?=voDemanda::$nmAtrProtocolo?>" value="<?=$vo->prt?>"  class="camponaoobrigatorio" size="30" required>
	        </TR>	        
			<TR>
				<TD halign="left" colspan="4">
				<DIV class="textoseparadorgrupocamposalinhadodireita">
							<SCRIPT language="JavaScript" type="text/javascript">
				            	colecaoIDCdNaoObrigatorio = 
					            	["<?=$nmCampoTpDemandaReajuste?>",
					            	"<?=voDemanda::$nmAtrCdPessoaRespUNCT?>",
					            	"<?=voDemanda::$nmAtrCdPessoaRespATJA?>",
					            	"<?=voDemanda::$nmAtrFase?>",
					            	"<?=voDemanda::$nmAtrProtocolo?>"
					            	];
				            </SCRIPT>
				            <INPUT type="checkbox" id="checkCdNaoObrigatorio" name="checkCdNaoObrigatorio" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCdNaoObrigatorio);"> <?=constantes::$DS_RESPONSABILIDADE_NAO_INCLUSAO_CAMPOS?>
				
				&nbsp;</DIV>
				</TD>
			</TR>
			<?php 
				include_once 'biblioteca_htmlDemanda.php';
				$colecaoTramitacao = $vo->dbprocesso->consultarDemandaTramitacao($vo);
				mostrarGridDemanda($colecaoTramitacao, true);
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";	        	
	        ?>
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