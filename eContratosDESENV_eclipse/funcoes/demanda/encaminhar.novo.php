<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voDemandaTramitacao.php");
include_once(caminho_vos."voUsuarioInfo.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDemanda();
$votram = new voDemandaTramitacao();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "campoobrigatorio";
$readonlyChaves = "";

session_start();

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
	$classChaves = "camporeadonly";
	$readonlyChaves = "readonly";
	
    $readonly = "readonly";
    $vo->getVOExplodeChave();
    $isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");
	
	$dbprocesso = $vo->dbprocesso;	
	$vo = $dbprocesso->consultarPorChaveTelaColecaoContrato($vo, $isHistorico);	
	
	$dbDemandaTramitacao = new dbDemandaTramitacao();
	//$dbDemandaTramitacao->validarEncaminhamento($vo);
	
	putObjetoSessao($vo->getNmTabela(), $vo);
		
    $nmFuncao = "ENCAMINHAR ";
}

if($vo->dtReferencia == null|| $vo->dtReferencia == "")
	$vo->dtReferencia = dtHoje;
	
$titulo = voDemanda::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nmCampoTpDemandaContratoSimples = voDemanda::$nmAtrTpDemandaContrato;
$nmCampoTpDemandaContrato = $nmCampoTpDemandaContratoSimples."[]";
?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function formataPrioridade() {
	campoPrioridade = document.frm_principal.<?=voDemandaTramitacao::$nmAtrPrioridade?>;
	campoTipoDemanda = document.frm_principal.<?=voDemandaTramitacao::$nmAtrTipo?>;

	if(campoTipoDemanda.value == "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL?>"){
		campoPrioridade.value = <?=dominioPrioridadeDemanda::$CD_PRIORI_ALTA?>;
	}
	
		//alert(pCampoSituacao.type);
		if(campoPrioridade.value == <?=dominioPrioridadeDemanda::$CD_PRIORI_BAIXA?>){
			var continuar = confirm("Esta prioridade retira o monitoramento da demanda pelo sistema. Deseja continuar?");
			if(!continuar){
				campoPrioridade.value = <?=dominioPrioridadeDemanda::$CD_PRIORI_MEDIA?>;
			}		
		}
		
}	

function isFormularioValido() {

	var campoRespUNCT = document.frm_principal.<?=voDemandaTramitacao::$nmAtrCdPessoaRespUNCT?>;
	var campoSetorDestino = document.frm_principal.<?=voDemandaTramitacao::$nmAtrCdSetorDestino?>;
	var campoTipoDemanda = document.frm_principal.<?=voDemandaTramitacao::$nmAtrTipo?>;
	//verifica se tem algum contrato selecionado atraves do campo pessoa contratada preenchido
	var campoPessoaContrato = document.getElementsByName("<?=vopessoa::getID_REQ_ColecaoContrato()?>")[0];
	var setorDestino = campoSetorDestino.value;
	
	if( setorDestino != "" && !isCampoTextoValido(document.frm_principal.<?=voDemandaTramitacao::$nmAtrTexto?>, true))	
		return false;		
	
	if(campoTipoDemanda.value == "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL?>"){		
		if(campoPessoaContrato != null){		
			exibirMensagem("Tipo da Demanda não permite inclusão de contrato");	
			return false;		
		}
	}

	campoDataReferencia = document.frm_principal.<?=voDemandaTramitacao::$nmAtrDtReferencia?>;
	campoAno = document.frm_principal.<?=voDemandaTramitacao::$nmAtrAno?>;
 
	if("<?=$isInclusao?>"=="1"){

		//obriga a selecao do tpDemandaContrato
		var temContratoSelecionado = !isCheckBoxConsultaSelecionado('<?=vodemanda::$ID_REQ_InTemContrato?>', true);
		if(temContratoSelecionado && campoTipoDemanda.value == "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO?>"){
			if(!isCheckBoxConsultaSelecionado('<?=$nmCampoTpDemandaContrato?>', true)){
				exibirMensagem('Demanda de Contrato exige preenchimento das informações complementares.');			
				return false;		
			}
		}
	
		if(!isCampoDataValidoPorCampoAno(campoDataReferencia, campoAno)){
			exibirMensagem("Data deve pertencer ao ano selecionado.");	
			return false;		
		}
 
	}

	//para enviar a SAFI, lembrar de manifestacao juridica
	if("<?=(dominioSetor::$CD_SETOR_ATJA == $vo->cdSetorAtual)?>"!="1" && setorDestino == "<?=dominioSetor::$CD_SETOR_SAFI?>"){
		exibirMensagem("Para envio à SAFI, verifique a necessidade de manifestação do jurídico.");
	}

	//so deixa tramitar saindo da UNCT se o responsavel UNCT tiver inserido
	if("<?=(dominioSetor::$CD_SETOR_UNCT == $vo->cdSetorAtual)?>"=="1"
		&& campoRespUNCT.value == ""){
		exibirMensagem("Selecione o usuário responsável na UNCT.");
		campoRespUNCT.focus();
		return false;
	}

	//biblio.demanda
	if(!isContinuarMesmoComLembrete('<?=vocontrato::$ID_REQ_InTemLembreteDemanda?>')){
		return false
	}
			
	return true;
}

function formatarSituacaoEncaminhar(pCampoSituacao){	
	//alert(pCampoSituacao.type);
	if(pCampoSituacao != null && pCampoSituacao.value == "<?=dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA?>"){
		//exibirMensagem("Situação não permitida. Alterando para '<?=dominioSituacaoDemanda::$DS_SITUACAO_DEMANDA_EM_ANDAMENTO?>'.");
		pCampoSituacao.value = "<?=dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO?>";		
	}
}

function cancelar() {
	//history.back();
	//location.href="index.php?consultar=S";	
	location.href="<?=getLinkRetornoConsulta()?>";
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


	pColecaoNmObjetosFormSolicCompra = ['<?=voSolicCompra::$nmAtrCd;?>', '<?=voSolicCompra::$nmAtrAno;?>', '<?=voSolicCompra::$nmAtrUG;?>'];
	formataFormPorTpDemanda('<?=voDemanda::$nmAtrTipo?>', <?=dominioTipoDemanda::$CD_TIPO_DEMANDA_SOLIC_COMPRA?>, pColecaoNmObjetosFormSolicCompra);
	
	//formataFormEditalPorTpDemanda('<?=voDemanda::$nmAtrTipo?>', pColecaoNmObjetosFormSolicCompra, <?=dominioTipoDemanda::$CD_TIPO_DEMANDA_SOLIC_COMPRA?>, null);
	formataFormTpDemandaContrato();
}

<?php
//guarda os setores do econti
$varColecaoGlobalSetor = "_globalColecaoSetor";
echo getColecaoComoVariavelJS(dominioSetor::getColecao(), $varColecaoGlobalSetor);
?>

function transferirDadosDocumento(sq, cdSetor, ano, tpDoc){
	chave = ano
	+ CD_CAMPO_SEPARADOR +  cdSetor
	+ CD_CAMPO_SEPARADOR +  tpDoc
	+ CD_CAMPO_SEPARADOR +  sq;

	colecaoSetor=<?=$varColecaoGlobalSetor?>;

	if("<?=$vo->tipo?>" != "<?=dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM?>" 
		&& tpDoc == "<?=dominioTpDocumento::$CD_TP_DOC_PUBLICACAO_PAAP?>"){
		exibirMensagem("Somente é permitido anexar '<?=dominioTpDocumento::$DS_TP_DOC_PUBLICACAO_PAAP?>' aos PAAP.");
		return;
	}
	
	document.getElementsByName("<?=voDocumento::getNmTabela()?>").item(0).value = chave;
	document.getElementsByName("<?=voDocumento::$nmAtrSq?>").item(0).value = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc, colecaoSetor);

	//alert(document.frm_principal.<?=voDocumento::getNmTabela()?>.value);
}

function formataFormTpDemandaContrato(){
	<?php
	$dominioTipoDemanda = new dominioTipoDemanda(dominioTipoDemanda::getColecaoTipoDemandaContratoGenero());
	echo $dominioTipoDemanda->getArrayHTMLChaves("colecaoTpDemandaContrato");	
	?>		

	var nmCampoCheckTpDemandaContrato = '<?=$nmCampoTpDemandaContrato?>';
	//console.log(nmCampoCheckTpDemandaContrato);
	var arrayCheckSelecionado = retornarValoresCheckBoxesSelecionadosComoArray(nmCampoCheckTpDemandaContrato);
	//alert(arrayCheckSelecionado);		
	var tpReajuste = '<?=dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE?>';
	var isReajusteSelecionado = arrayCheckSelecionado.indexOf(tpReajuste) != -1;
		
	formataFormTpDemandaReajusteContrato("<?=voDemanda::$nmAtrTipo?>", 
			"<?=voDemanda::$ID_REQ_DIV_REAJUSTE_MONTANTE_A?>", 
			colecaoTpDemandaContrato, 
			"<?=voDemanda::$nmAtrInTpDemandaReajusteComMontanteA?>",
			isReajusteSelecionado, 
			false);
	formataFormTpDemanda('<?=voDemanda::$nmAtrTipo?>', nmCampoCheckTpDemandaContrato);
}

function verificaSEIExistente(pNmCampoDiv, pIDCampo){
	//alert(inDiasUteis);
	var SEI = "";
	if(pIDCampo != null){
		SEI = document.getElementById(pIDCampo).value;
	}	
	//alert(SEI);	
	if(SEI != "" && SEI.length > 5){
		chave = SEI;				
		link = "campoDadosSEIExistente.php";
		//biblio ajax
		getDadosPorChaveGenerica(chave, link, pNmCampoDiv);
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}	
}

function iniciar(){	
	formataFormTpDemandaContrato();
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<?=getInputHidden(voDemanda::$nmAtrInLegado, voDemanda::$nmAtrInLegado, "N")?>
<?=getInputHidden(voDemanda::$ID_PAGINA_ENCAMINHAR_NOVO, voDemanda::$ID_PAGINA_ENCAMINHAR_NOVO, voDemanda::$ID_PAGINA_ENCAMINHAR_NOVO)?>
 
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
	        $comboTipo = new select(dominioTipoDemanda::getColecaoTipoDemanda(false));
	        $comboSetor = new select(dominioSetor::getColecao());
	        $comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        $comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	        $selectExercicio = selectExercicio::getSelectColecaoAnoInicio();
	        
	        $votram->dtReferencia = getDataHoje();
	        
	        $complementoHTML = "";
	        
	        if(!$isInclusao){
	        	//ALTERACAO
	        	$complementoHTML = " required ";	        	
	        	$complementoHTMLSetorDestino .=  $complementoHTML;
	        	$readonlyChaves = " readonly ";
	        	
	        	getDemandaDetalhamento($vo);
	        ?>	        	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Responsável:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo $comboSetor->getHtmlCombo("","", $vo->cdSetor, true, "camporeadonly", false, " disabled ");?>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario" >
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "camporeadonly", false, " onChange='formataPrioridade();' disabled ");
	            $comboSimNao = new select(dominioSimNao::getColecao());
	            if(isUsuarioChefia()){
		            echo " | " . getTextoHTMLTagMouseOver(getTextoHTMLDestacado("Monitorar?"), voDemanda::$MSG_IN_MONITORAR) . ": ";
		            echo $comboSimNao->getHtmlCombo(voDemanda::$nmAtrInMonitorar,voDemanda::$nmAtrInMonitorar, $vo->inMonitorar, true, "camponaoobrigatorio", false, "");
		            echo getInputHiddenACompararBanco(voDemanda::$nmAtrInMonitorar, voDemanda::$nmAtrInMonitorar, $vo->inMonitorar);
	            }
	            ?>
	            	            
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" value="<?=$vo->texto?>"  class="camporeadonly" size="80" readonly>
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php 
	            //echo $comboSituacao->getHtmlCombo("","", $vo->situacao, true, "camporeadonly", false, " disabled ");
	            echo $comboSituacao->getHtmlCombo(voDemandaTramitacao::$nmAtrSituacao,voDemandaTramitacao::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio"
					, false, " onChange='formatarSituacaoEncaminhar(this)' required ");
	             ?>
	            </TD>            		        
	            <TH class="campoformulario" width="1%">Data.Demanda:</TH>
	            <TD class="campoformulario">	            	            	            
	            <INPUT type="text" value="<?=getData($vo->dtReferencia);?>"  class="camporeadonly" size="12" readonly>
            	</TD>
            </TR>
			<TR>
	            <TH class="campoformulario" width="1%">Fase:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            $varCamposPermiteAlteracao = "varJSCamposCaracteristicasPermitidos";
	            echo getTagHtmlJavaScript(getColecaoComoVariavelJS(dominioFaseDemanda::getColecaoPermissaoCaracteristicasUsuario(), 
	            		$varCamposPermiteAlteracao, true));	             
	            $pPropriedadeValorCondicao = getVarComoString(getCaracteristicasUsuarioLogado());	        
	            //formatarSituacaoDemanda esta em bibli.demanda...js
	            //formatarSituacaoDemanda(pIdCampoSituacao, pIdCampoCheckBoxRevisado, pCampoCheckBoxRevisadoOpcional)
	            $jsMudarSituacao = "formatarSituacaoDemanda('".voDemanda::$nmAtrSituacao."','".dominioFaseDemanda::$CD_REVISAO_UNCT."', this);";
	            $jsOnClickAdicional = "isCheckBoxPermiteAlteracao(this, $pPropriedadeValorCondicao, $varCamposPermiteAlteracao);$jsMudarSituacao";
	             
	            $nmCampoFaseHtml = voDemanda::$nmAtrFase."[]";
	            //echo dominioFaseDemanda::getHtmlChecksBoxDetalhamento($nmCampoFaseHtml, $vo->fase, 1);
	            echo dominioFaseDemanda::getHtmlChecksBox($nmCampoFaseHtml, $vo->fase, null, 1, false, "$jsOnClickAdicional", false, " required ");
	            //serve para comparar, ao enviar ao banco, se a fase foi alterada no encaminhamento, autorizando a alteracao do vodemanda
	            echo getInputHidden(voDemandaTramitacao::$nmAtrFaseRegistroBanco, voDemandaTramitacao::$nmAtrFaseRegistroBanco, $vo->fase);
	             ?>
	            </TD>	            
	        </TR>
	        
	        <?php
	        }else{
	        	//INCLUSAO
	        	$comboTipoEditado = new select(dominioTipoDemanda::getColecaoTipoDemanda(false));
	        	//$comboTipoEditado = new select(dominioTipoDemanda::getColecaoComElementosARemover(dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO));
	        	//var_dump($comboTipoEditado->colecao);
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php	             
	            echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, anoDefault, true, "campoobrigatorio", false, " required ");
	            echo "Tipo: " . $comboTipoEditado->getHtmlCombo(voDemanda::$nmAtrTipo,voDemanda::$nmAtrTipo, "", true, "campoobrigatorio", false, " required onChange='validaFormulario();'");
	            ?>			  
	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . voSolicCompra::getNmTabela() . "/biblioteca_htmlSolicCompra.php"); asdas
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%"><?=voSolicCompra::getNomeObjetoJSP()?>:</TH>
	            <TD class="campoformulario" colspan=3><?php getCampoDadosSolicCompra($voSolicCompra);?>
	            </TD>
	        </TR>	        
	        <?php	        
	        require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitatório:</TH>
	            <TD class="campoformulario" colspan=3><?php getCampoDadosProcLicitatorio($voProcLicitatorio);?>
	            </TD>
	        </TR>	        
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
				<TD class="campoformulario" colspan=3><?php
				//echo getCampoDadosContratoMultiplos();
				//echo getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, "", false);
				echo getContratoEntradaDeDadosVOSimples(null, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, true, false, true);
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
                    <div id="<?=voDemanda::$ID_REQ_DIV_REAJUSTE_MONTANTE_A?>"> <b>Informações complementares</b>
		                <?php		                
			            echo dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, "", dominioTipoDemandaContrato::getColecao(), 2, true, "formataFormTpDemandaContrato();");
			            $comboTpReajuste = new select(dominioTipoReajuste::getColecao());
			            //echo "Tipo de reajuste: " . $comboTpReajuste->getHtmlCombo(voDemanda::$nmAtrInTpDemandaReajusteComMontanteA,voDemanda::$nmAtrInTpDemandaReajusteComMontanteA, "", true, "camponaoobrigatorio", false,"");
			            echo "Reajuste: " . $comboTpReajuste->getHtmlComObrigatorio(voDemanda::$nmAtrInTpDemandaReajusteComMontanteA,voDemanda::$nmAtrInTpDemandaReajusteComMontanteA, "", false,false);			             
			            ?>
                    </div>	            
	            <INPUT type="checkbox" id="<?=vodemanda::$ID_REQ_InTemContrato?>" name="<?=vodemanda::$ID_REQ_InTemContrato?>" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Não tem contrato.
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Origem:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, "", true, "campoobrigatorio", false, " required ");?>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario">
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "campoobrigatorio", false, " required onChange='formataPrioridade();' ");?>
				</TD>				
	        </TR>	 
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value=""  class="campoobrigatorio" size="80" required>	            	                        	                        
	        </TR>
	        <?php 
	       }	       
	       ?>
			</TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Resp.:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 

	            $classRespATJA = $classResp = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	            $jsRespATJA = $jsResp = " required ";
	            
	            $classResp = $classRespATJA;
	            $jsResp = $jsRespATJA;
	             
	            $isUsuATJA = isUsuarioPorCaracteristica(dominioUsuarioCaracteristicas::$CD_ATJA);
	            $arrayParamUsuario = array(
	            		voDemanda::$nmAtrCdPessoaRespUNCT,
	            		voDemanda::$nmAtrCdPessoaRespUNCT,
	            		$vo->cdPessoaRespUNCT,
	            		true,
	            		false,
	            		$classResp,
	            		false,
	            		$jsResp,
	            );
	             
	            echo getTextoHTMLTagMouseOver("UNCT.", "Colaborador responsável por acompanhar a demanda na UNCT.") . ":&nbsp;";
	            if($isUsuATJA){
	            	$jsResp .= " disabled ";
	            	$arrayParamUsuario[0] = "";
	            	$arrayParamUsuario[1] = "";	            	
	            	$arrayParamUsuario[7] = $jsResp;
	            	//retira o id e inclui o id oculto para nao causar alteracao na base
	            	echo getInputHidden(voDemanda::$nmAtrCdPessoaRespUNCT, voDemanda::$nmAtrCdPessoaRespUNCT, $vo->cdPessoaRespUNCT);
	            }
	            
	            echo getComboUsuarioPorSetor($arrayParamUsuario, dominioSetor::$CD_SETOR_UNCT) . "&nbsp";
	             
	            echo getTextoHTMLTagMouseOver("ATJA.", "Assessor responsável por acompanhar a demanda na ATJA.") . ":&nbsp;"
						.getComboPessoaRespPAConsulta(
								voDemanda::$nmAtrCdPessoaRespATJA, 
								voDemanda::$nmAtrCdPessoaRespATJA, 
								$vo->cdPessoaRespATJA, 
								$classRespATJA, 
								$jsRespATJA);	            
	             ?>
	            </TD>				
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI(principal):</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" value="<?=$vo->prt?>"  class="camporeadonly" size="30" readonly>
	        </TR>	       
			<TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
				<DIV class="campoformulario" id="div_tramitacao">&nbsp;&nbsp;Novo Encaminhamento
				</DIV>
				</TH>
			</TR>
			
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Destino:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $vo->cdSetorDestino, true, "camponaoobrigatorio", false, $complementoHTMLSetorDestino);?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Despacho:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voDemandaTramitacao::$nmAtrTexto?>" name="<?=voDemandaTramitacao::$nmAtrTexto?>" class="camponaoobrigatorio" <?=$complementoHTML?>></textarea>
				</TD>
	        </TR>
	        <TR>
 				<?php
 				$idSEI = voDemandaTramitacao::$nmAtrProtocolo;
 				$nmDivSEIExistente = voDemanda::$NM_DIV_SEI_EXISTENTE; 					
 					if($isInclusao){
						$javaScriptSEIExistente = "onBlur=\"verificaSEIExistente('$nmDivSEIExistente', '$idSEI');\"";
 					}
				?>				
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" 
	            name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" <?=$javaScriptSEIExistente?> value="" class="camponaoobrigatorio" size="30" <?=$complementoHTML?>>
 				<?php
					if($isInclusao){
						$nmCampos = array($idSEI);
				       echo getBorracha($nmCampos, "verificaSEIExistente('$nmDivSEIExistente');");
				       echo getTagHTMLDIV($nmDivSEIExistente);
 					}
				?>				
	        </TR>
	        <TR>
		        <TH class="campoformulario" width="1%" nowrap>Documento:</TH>
		        <TD class="campoformulario" nowrap colspan=3>
		        	<INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>" class="camporeadonly" size="15" readonly>
		        	<INPUT type="hidden" id="<?=voDocumento::getNmTabela()?>" name="<?=voDocumento::getNmTabela()?>" value="">
		        	<?php 
		        	echo getLinkPesquisa("../documento");
		        	$nmCampo = array(voDocumento::getNmTabela(), voDocumento::$nmAtrSq);
		        	echo getBorracha($nmCampo);
		        	?>
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Data:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voDemandaTramitacao::$nmAtrDtReferencia?>" 
	            	       name="<?=voDemandaTramitacao::$nmAtrDtReferencia?>" 
	            			value="<?php echo($votram->dtReferencia);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="campoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
	
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequiredTramitacao = [
	            		"<?=voDemanda::$nmAtrInTpDemandaReajusteComMontanteA?>",
	            		"<?=voDemanda::$nmAtrCdPessoaRespUNCT?>",
	            		"<?=voDemanda::$nmAtrCdPessoaRespATJA?>",
	            		"<?=voDemandaTramitacao::$nmAtrProtocolo?>",
	            		<?=dominioFaseDemanda::getColecaoCdsSeparador()?>
	            		];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequiredTramitacao);"> 
	            <?=getTextoHTMLNegrito(voMensageria::$DS_RESPONSABILIDADE_CAMPO_OBR)?>	            	
				</TD>
        	</TR>
        		<?php 
				if(!$isInclusao){
					include_once 'biblioteca_htmlDemanda.php';
					$colecaoTramitacao = $vo->dbprocesso->consultarDemandaTramitacao($vo);
					mostrarGridDemanda($colecaoTramitacao, true);
				}

	        if(!$isInclusao){
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
	        }
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
<?php 
}catch(Exception $ex){
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex);	
}
?>
