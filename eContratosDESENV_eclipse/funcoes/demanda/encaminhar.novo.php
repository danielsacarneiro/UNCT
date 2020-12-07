<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voDemandaTramitacao.php");
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
}	

function isFormularioValido() {

	campoSetorDestino = document.frm_principal.<?=voDemandaTramitacao::$nmAtrCdSetorDestino?>;
	campoTipoDemanda = document.frm_principal.<?=voDemandaTramitacao::$nmAtrTipo?>;
	//verifica se tem algum contrato selecionado atraves do campo pessoa contratada preenchido
	campoPessoaContrato = document.getElementsByName("<?=vopessoa::getID_REQ_ColecaoContrato()?>")[0];

	if(campoSetorDestino.value != "" && !isCampoTextoValido(document.frm_principal.<?=voDemandaTramitacao::$nmAtrTexto?>, true))	
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

			
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
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
	        
	        $votram->dtReferencia = dtHoje;
	        
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
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "camporeadonly", false, " disabled ");
	            $comboSimNao = new select(dominioSimNao::getColecao());
	            echo " | " . getTextoHTMLTagMouseOver(getTextoHTMLDestacado("Monitorar?"), voDemanda::$MSG_IN_MONITORAR) . ": ";
	            echo $comboSimNao->getHtmlCombo(voDemanda::$nmAtrInMonitorar,voDemanda::$nmAtrInMonitorar, $vo->inMonitorar, true, "camponaoobrigatorio", false, "");
	            echo getInputHiddenACompararBanco(voDemanda::$nmAtrInMonitorar, voDemanda::$nmAtrInMonitorar, $vo->inMonitorar);
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
	            <TH class="campoformulario" nowrap width="1%">Data.Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            	            	            
	            <INPUT type="text" value="<?=getData($vo->dtReferencia);?>"  class="camporeadonly" size="12" readonly>
            	</TD>	        
            </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php 
	            echo $comboSituacao->getHtmlCombo("","", $vo->situacao, true, "camporeadonly", false, " disabled ");	            
	             ?>
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Fase:</TH>
	            <TD class="campoformulario" colspan=1>
	            <?php 
	            $nmCampoFaseHtml = voDemanda::$nmAtrFase."[]";
	            //echo dominioFaseDemanda::getHtmlChecksBoxDetalhamento($nmCampoFaseHtml, $vo->fase, 1);
	            echo dominioFaseDemanda::getHtmlChecksBox($nmCampoFaseHtml, $vo->fase, null, 1, false, "", false, " required ");
	            //serve para comparar, ao enviar ao banco, se a fase foi alterada no encaminhamento, autorizando a alteracao do vodemanda
	            echo getInputHidden(voDemandaTramitacao::$nmAtrFaseRegistroBanco, voDemandaTramitacao::$nmAtrFaseRegistroBanco, $vo->fase);
	             ?>
	            (Somente alterar se tiver certeza!)</TD>	            
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

	            if($isInclusao){
	            	$classRespATJA = $classResp = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	            	$jsResp = "";
	            	
	            	$jsRespATJA = " required ";
	            	
	            }else{
	            	$classRespATJA = $classResp = constantes::$CD_CLASS_CAMPO_READONLY;
	            	$jsRespATJA = $jsResp = "disabled";
	            }
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
	            echo getTextoHTMLTagMouseOver("UNCT.", "Colaborador responsável por acompanhar a demanda na UNCT.") . ":&nbsp;"
    			.getComboUsuarioPorSetor($arrayParamUsuario, dominioSetor::$CD_SETOR_UNCT) . "&nbsp";	             
	             
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
	            <TH class="campoformulario" nowrap width="1%">Texto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voDemandaTramitacao::$nmAtrTexto?>" name="<?=voDemandaTramitacao::$nmAtrTexto?>" class="camponaoobrigatorio" <?=$complementoHTML?>></textarea>
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" value=""  class="camponaoobrigatorio" size="30" <?=$complementoHTML?>>
	            <!-- <INPUT type="checkbox" id="<?=voDemandaTramitacao::$nmAtrInResponsabilidadePRT?>" name="<?=voDemandaTramitacao::$nmAtrInResponsabilidadePRT?>" value="" onClick="checkResponsabilidade();"> *Assumo a responsabilidade de não incluir PRT/SEI. -->	            	                        	                        
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
	            <TH class="campoformulario" nowrap>Data:</TH>
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
