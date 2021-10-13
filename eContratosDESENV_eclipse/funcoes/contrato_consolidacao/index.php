<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voContratoInfo();

$titulo = "CONSULTAR " . voContratoInfo::getTituloJSPConsolidacao();
setCabecalho($titulo);

$filtro  = new filtroConsultarContratoConsolidacao();

//$filtro->voPrincipal = $vo;
$nmMetodoExportarPlanilha = "consultarTelaConsultaConsolidacao";
$arrayObjetosExportarPlanilha = array($vo, $nmMetodoExportarPlanilha);
$filtro->setArrayObjetosExportarPlanilha($arrayObjetosExportarPlanilha);
$filtro = filtroManter::verificaFiltroSessao($filtro);

$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->$nmMetodoExportarPlanilha($filtro);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

$nmCampoCaracteristicasHtml = filtroConsultarContratoConsolidacao::$ID_REQ_Caracteristicas . "[]";
$nmCampoFaseDemandaHtml = filtroConsultarContratoConsolidacao::$ID_REQ_FaseDemanda ."[]";
$isPrimeiraConsulta = !$filtro->isConsultaRealizada();
if($isPrimeiraConsulta){
	$filtro->faseDemanda = array(dominioFaseDemanda::$CD_PUBLICADO . constantes::$CD_CAMPO_SEPARADOR . constantes::$CD_SIM);
}

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

/*function detalhar(isExcluir) {    
    if(isExcluir == null || !isExcluir)
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    else
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";
    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
    	
	chave = document.frm_principal.rdb_consulta.value;	
	lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
}*/

function detalhar(){
	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
	chave = document.frm_principal.rdb_consulta.value;
    url = "../contrato_info/detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S";	
    abrirJanelaAuxiliar(url, true, false, false);
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


function execucao(){
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
  	//marreta
	chave = "hist*" + document.frm_principal.rdb_consulta.value + "CM*1";	
    url = "../contrato/execucao.php?chave=" + chave;	
    abrirJanelaAuxiliar(url, true, false, false);    
}

function movimentacoes(){
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
  	//marreta
	chave = "hist*" + document.frm_principal.rdb_consulta.value + "CM*1";	
    url = "../contrato/movimentacaoContrato.php?chave=" + chave;	
    abrirJanelaAuxiliar(url, true, false, false);
}

</SCRIPT>
<?=setTituloPagina(voContratoInfo::getTituloJSPConsolidacao())?>
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
	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" nowrap width="1%">
	            <?php 
	            getContratoEntradaDeDados($filtro->tipoContrato, $filtro->cdContrato, $filtro->anoContrato, $arrayCssClass, null, null);
	            ?>
	            </TD>
                <TH class="campoformulario" nowrapwidth="1%" >Contratada:</TH>
                <TD class="campoformulario" >Nome: <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="15">
                |CNPJ/CPF: <INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18">
                </TD>
			</TR>
			<TR>
	            <?php 
	            $comboProrrogacaoContrato = new select(dominioProrrogacaoContrato::getColecao());
	            $comboFiltroProrrogacaoConsolidacao= new select(dominioProrrogacaoFiltroConsolidacao::getColecao());
	            ?>			
               <TH class="campoformulario" nowrap>Prorrogação:</TH>
               <TD class="campoformulario" colspan=3>
				<?php 
				$arrayTemp = array(
						voContratoInfo::$nmAtrInPrazoProrrogacao,
						voContratoInfo::$nmAtrInPrazoProrrogacao,
						$filtro->inPrazoProrrogacao,
						true,
						true,
						"camponaoobrigatorio",
						false,
						"",
						select::$TAM_PADRAO
				);
				echo $comboProrrogacaoContrato->getHtmlComboArray($arrayTemp);
				echo "| Situação: ". $comboFiltroProrrogacaoConsolidacao->getHtmlCombo(filtroConsultarContratoConsolidacao::$nmAtrInProrrogacao,filtroConsultarContratoConsolidacao::$nmAtrInProrrogacao, $filtro->inProrrogacao, true, "camponaoobrigatorio", false,"");
				echo "| Será prorrogado?:";
				echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInSeraProrrogado,
						voContratoInfo::$nmAtrInSeraProrrogado,
						$filtro->inSeraProrrogado, true, "camponaoobrigatorio", false, " ");
				echo "|" .  getTextoHTMLNegrito("Iniciada?") . ":";
				echo $comboSimNao->getHtmlCombo(filtroConsultarContratoConsolidacao::$ID_REQ_InTemDemandaProrrogacao,
						filtroConsultarContratoConsolidacao::$ID_REQ_InTemDemandaProrrogacao,
						$filtro->inTemDemandaProrrogacao, true, "camponaoobrigatorio", false, " ");
				?>                        			
                </TD>
            </TR>
			<TR>
				<TH class="campoformulario" nowrap>Objeto:</TH>
				<TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>"  value="<?php echo($filtro->objeto);?>"  class="camponaoobrigatorio" size="30" ></TD>
               <TH class="campoformulario" nowrap width="1%">Valor(em 12 meses):</TH>
               <TD class="campoformulario">               
               				<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_ValorInicial?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_ValorInicial?>" 
                        			value="<?php echo($filtro->valorInicial);?>" 
                        			onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" 
                        			class="camponaoobrigatorio" 
                        			size="<?=CONSTANTES::$TAMANHO_CAMPO_VALOR?>"
                        			> a               
                        	<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_ValorFinal?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_ValorFinal?>" 
                        			value="<?php echo($filtro->valorFinal);?>" 
                        			onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" 
                        			class="camponaoobrigatorio" 
                        			size="<?=CONSTANTES::$TAMANHO_CAMPO_VALOR?>"
                        			>
                </TD>				
			</TR>
			<TR>
       			<TH class="campoformulario" ><?=getTextoHTMLDestacado("Efeitos", "red", false)?>:</TH>
                <TD class="campoformulario" colspan=3>
                <?php 
                $pArrayFaseDemanda = array(
                		$nmCampoFaseDemandaHtml,
                		$filtro->faseDemanda,
                		dominioFaseDemanda::getColecaoPlanilha(),
                		1,
                		true,
                		"",
                		true,
                		"",
                		false,
                		"", //filtroManterDemanda::$NmAtrInOR_AND_Fase,
                		"", //$filtro->inOR_AND_Fase,
                		false,
                		true,
                		true
                );
                echo dominioFaseDemanda::getHtmlChecksBoxArray($pArrayFaseDemanda);
                ?>                        			
                </TD>
            </TR>			
            <TR>
               <TH class="campoformulario" nowrap>Fim.Vigência:</TH>
               <TD class="campoformulario" colspan=3>
                        	<?php
                        	$comboMeses = new select(dominioMeses::getColecao());
                        	echo "Mês " . $comboMeses->getHtmlCombo(filtroConsultarContratoConsolidacao::$ID_REQ_MesIntervaloFimVigencia
                        			,filtroConsultarContratoConsolidacao::$ID_REQ_MesIntervaloFimVigencia, 
                        			$filtro->mesIntervaloFimVigencia
                        			, true
                        			, "camponaoobrigatorio"
                        			, true
                        			, "");
                        	$selectExercicio = new selectExercicio ();
                        	echo " Ano ". $selectExercicio->getHtmlCombo ( filtroConsultarContratoConsolidacao::$ID_REQ_AnoIntervaloFimVigencia, 
                        			filtroConsultarContratoConsolidacao::$ID_REQ_AnoIntervaloFimVigencia, 
                        			$filtro->anoIntervaloFimVigencia, 
                        			true
                        			, "camponaoobrigatorio"
                        			, false
                        			, "");
                        	?>               
               				ou
               				Período: 
               				<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaInicial?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaInicial?>" 
                        			value="<?php echo($filtro->dtFimVigenciaInicial);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a               
                        	<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaFinal?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaFinal?>" 
                        			value="<?php echo($filtro->dtFimVigenciaFinal);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                        <?php 
                        $nmCamposDtFimVigencia = array(
                        		filtroConsultarContratoConsolidacao::$ID_REQ_MesIntervaloFimVigencia,
                        		filtroConsultarContratoConsolidacao::$ID_REQ_AnoIntervaloFimVigencia,
                        		filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaInicial,
                        		filtroConsultarContratoConsolidacao::$ID_REQ_DtFimVigenciaFinal,
                        );
                        echo getBorracha($nmCamposDtFimVigencia, "");
                        ?>                        			                        			                	 
                </TD>
            </TR>			
			<TR>
				<TH class="campoformulario" nowrap>Tp.Vigência:</TH>
				<?php
				include_once(caminho_util."dominioTpVigencia.php");
				$comboVigencia = new select(dominioTpVigencia::getColecaoComVazio());						
				?>
	            <TD class="campoformulario" nowrap colspan=3>
	            <?php 
	            echo $comboVigencia->getHtmlOpcao($filtro::$nmAtrTpVigencia,$filtro::$nmAtrTpVigencia, $filtro->tpVigencia, false);
	            
	            //$comboSimNaoEfeitos = new select(array(constantes::$CD_SIM => "Com efeitos", constantes::$CD_NAO => "Últ.Termo"));
	            /*$comboSimNaoEfeitos = new select(dominioContratoProducaoEfeitos::getColecao());
	            $textoTag = getTextoHTMLTagMouseOver("Último Termo: ", "Exibe somente os termos formalizados.");
	            echo "| $textoTag". $comboSimNaoEfeitos->getHtmlCombo(
	            		filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos,
	            		filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos, 
	            		$filtro->inProduzindoEfeitos, true, "camponaoobrigatorio", false, "");*/
	            
	            echo "| Vigente na data: "	             
	            ?>
                        	<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$nmAtrDtVigencia?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$nmAtrDtVigencia?>" 
                        			value="<?php echo($filtro->dtVigencia);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                <?php
                $nmCamposVigencia = array(
                		filtroConsultarContratoConsolidacao::$nmAtrTpVigencia,
                		filtroConsultarContratoConsolidacao::$nmAtrDtVigencia,
                		//filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos,
                );
                
                echo getBorracha($nmCamposVigencia, "");?>
	            </TD>
		    </TR>			
			<TR>
	            <TD class="headertabeladados" colspan=4>
	            <?php
	            $pArray= array($filtro);
	            echo getTagHTMlAbreLDivFiltroExpansivel($pArray);
	            ?>
	            </TD>
            <TR>
				<?php
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());
				?>
	            <TH class="campoformulario" nowrap>Classificação:</TH>
	            <TD class="campoformulario" width="1%" colspan=3>
	            <?php 
	            echo $comboClassificacao->getHtmlCombo(voContratoInfo::$nmAtrCdClassificacao,voContratoInfo::$nmAtrCdClassificacao, $filtro->cdClassificacao, true, "camponaoobrigatorio", true, "");
	            //$radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " required " );
	            
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());	             
	            echo "&nbsp;&nbsp;Terceirização (planilha de custos)?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $filtro->inMaoDeObra, true, "camponaoobrigatorio", false,"");
	            
	            $pArrayCaracteristica = array(
	            		$nmCampoCaracteristicasHtml,
	            		$filtro->inCaracteristicas,
	            		filtroConsultarContratoConsolidacao::getColecaoCaracteristicas(),
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
	            echo dominio::getHtmlChecksBoxArray($pArrayCaracteristica);
	            ?>
	        </TR>            
			<TR>
				<?php
				//require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				//include_once(caminho_funcoes. "contrato/dominioTpGarantiaContrato.php");
				$comboGarantia = new select(dominioTpGarantiaContrato::getColecao());
				$comboAutorizacao = new select(dominioAutorizacao::getColecao());				
				$nmCheckAutorizacaoArray = voContratoInfo::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" width="1%" nowrap >Autorização:</TH>
	            <TD class="campoformulario" width="1%" nowrap>
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroConsultarContratoConsolidacao::$NmAtrInOR_AND,filtroConsultarContratoConsolidacao::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>
               <TH class="campoformulario" nowrap>Assinatura:</TH>
               <TD class="campoformulario"> 
               <?php
               echo " Ano ". $selectExercicio->getHtmlCombo ( filtroConsultarContratoConsolidacao::$ID_REQ_AnoAssinatura,
               		filtroConsultarContratoConsolidacao::$ID_REQ_AnoAssinatura,
               		$filtro->anoAssinatura,
               		true
               		, "camponaoobrigatorio"
               		, false
               		, "");
                
               ?>  ou Período:
               				<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaInicial?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaInicial?>" 
                        			value="<?php echo($filtro->dtAssinaturaInicial);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a               
                        	<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaFinal?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaFinal?>" 
                        			value="<?php echo($filtro->dtAssinaturaFinal);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                        <?php 
                        $nmCamposDtAssinatura = array(
                        		filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaFinal,
                        		filtroConsultarContratoConsolidacao::$ID_REQ_DtAssinaturaInicial,
                        		filtroConsultarContratoConsolidacao::$ID_REQ_AnoAssinatura,
                        );
                        echo getBorracha($nmCamposDtAssinatura, "");
                        ?>                        			                        			                	 
                </TD>
			</TR>
			<TR>
               <TH class="campoformulario" nowrap>Prazo:</TH>
               <TD class="campoformulario" nowrap width="1%">               				
				Anos:
               				<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_NumPeriodoEmAnosInicial?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_NumPeriodoEmAnosInicial?>" 
                        			value="<?php echo($filtro->numPeriodoEmAnosInicial);?>" 
                        			onkeyup="validarCampoNumericoPositivo(this, event);" 
                        			class="camponaoobrigatorio" 
                        			size="3" 
                        			maxlength="3" > a               
                        	<INPUT type="text" 
                        	       id="<?=filtroConsultarContratoConsolidacao::$ID_REQ_NumPeriodoEmAnosFinal?>" 
                        	       name="<?=filtroConsultarContratoConsolidacao::$ID_REQ_NumPeriodoEmAnosFinal?>" 
                        			value="<?php echo($filtro->numPeriodoEmAnosFinal);?>" 
                        			onkeyup="validarCampoNumericoPositivo(this, event);" 
                        			class="camponaoobrigatorio" 
                        			size="3" 
                        			maxlength="3" >
               | Dias.Vencimento:
	            <INPUT type="text" id="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimento?>" name="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimento?>"  
								value="<?php echo($filtro->qtdDiasParaVencimento);?>"  class="camponaoobrigatorio" size="3">                        			
                </TD>
               <TH class="campoformulario" nowrap>Proposta:</TH>
               <TD class="campoformulario" >
               Dias.Vencimento:
	            <INPUT type="text" id="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimentoProposta?>" name="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimentoProposta?>"  
								value="<?php echo($filtro->qtdDiasParaVencimentoProposta);?>"  class="camponaoobrigatorio" size="3">
                </TD>                
            </TR>
            
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Garantia:</TH>
				<TD class="campoformulario" nowrap width="1%">
	            Tem?: <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInTemGarantia,voContratoInfo::$nmAtrInTemGarantia, $filtro->inTemGarantia
	            		, true, "camponaoobrigatorio", false,"");?>
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Gestor:</TH>
	            <TD class="campoformulario">
	            <?php 
	            echo "Tem? ".$comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrCdPessoaGestor,voContratoInfo::$nmAtrCdPessoaGestor
							, $filtro->inGestor, true, "camponaoobrigatorio", false,"");
	            ?>	            
	            |Nome: <INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($filtro->gestor);?>"  class="camponaoobrigatorio" size="10" >
	            |Órgão: <INPUT type="text" id="<?=vogestor::$nmAtrDescricao?>" name="<?=vogestor::$nmAtrDescricao?>"  value="<?php echo($filtro->orgaoGestor);?>"  class="camponaoobrigatorio" size="10" >
	            </TD>	                        
            </TR>
				<?=getTagHTMlFechaDivFiltroExpansivel();?>
	            </TD>
	        </TR>
	        	        
       <?php
       //$colecaoPlanilha = filtroConsultarContratoConsolidacao::montarColecaoExportarPlanilha($colecao);
       $pArrayFiltroConsulta = array(
       		$filtro,
       		false,
       		true,
       );       
       
       //echo getComponenteConsultaFiltro($voContrato->temTabHistorico, $filtro, true, $colecao);
       echo getComponenteConsultaPaginacaoArray($pArrayFiltroConsulta);
        
       //echo getComponenteConsultaFiltro(false, $filtro);
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
                <?php
                $rowspan="rowspan=2";
                ?>
                  <TH class="headertabeladados" width="1%" <?=$rowspan?>>&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%" <?=$rowspan?>>Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Contrato</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Atual</TH>
                    <TH class="headertabeladados" width="30%" <?=$rowspan?>>Contratada</TH>
                    <TH class="headertabeladados" width="70%" <?=$rowspan?>>Objeto</TH>
                    <TH class="headertabeladados" width="15%" <?=$rowspan?>>Gestor</TH>                    
                    <TH class="headertabeladadosalinhadocentro" width="1%" colspan="3">Prorrogação</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Início</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Fim</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Anos</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Mensal</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Global</TH>
                </TR>
                <TR>
					<TH class="headertabeladados" width="1%">Será?</TH>
                    <TH class="headertabeladados" width="1%">Permite?</TH>
                    <TH class="headertabeladados" width="1%">Excep.?</TH>                
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;                                
                                
                $colspan=14;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                $dominioAutorizacao = new dominioAutorizacao();
                
                for ($i=0;$i<$tamanho;$i++) {
                		$registro = $colecao[$i];
                		
                        $voAtual = new voContratoInfo();
                        $voAtual->getDadosBanco($registro);
                        
                        $voPessoa = new voPessoa();
                        $voPessoa->getDadosBanco($registro);                        
                        
                        $voContratoAtual = new vocontrato();
                        $voContratoAtual->getDadosBanco($registro);
                        
                        $tipo = $dominioTipoContrato->getDescricao($registro["ct_tipo"]);
                        $autorizacaoAtual = $registro[filtroConsultarContratoConsolidacao::$NmColAutorizacao];
                        
                        $dsPessoa = $voPessoa->nome;
                        if($dsPessoa == null){
                        	$dsPessoa = "<B>CONTRATO NÃO INCLUÍDO</B>";
                        }
                        
                        $dataFinalHtml = $datafimSQL = $registro[filtroConsultarContratoConsolidacao::$NmColDtFimVigencia];
                        $dataFinalHtml = getData($dataFinalHtml);
                        
                        $cdEspeciaAtual = $registro[filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual];
                        $sqEspeciaAtual = $registro[filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual];
                        $isTermoAtualOP = $cdEspeciaAtual == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO;
                        if($sqEspeciaAtual == null){
                        	//var_dump($registro);
                        	//echo $cdEspeciaAtual;
                        	$termoAtual = getTextoHTMLDestacado("Verifique<br>Vigência");
                        }else{
                        	$termoAtual = $cdEspeciaAtual==dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?dominioEspeciesContrato::$DS_ESPECIE_CONTRATO_MATER:$sqEspeciaAtual ."o $cdEspeciaAtual";
                        	if($isTermoAtualOP){
                        		$termoAtual = getTextoHTMLTagMouseOver(getTextoHTMLDestacado($termoAtual), "Vigência alterada devido às ordens de paralisação.");
                        	}
                        }
                        //$termoAtual = $sqEspeciaAtual ."o $cdEspeciaAtual";
                        $gestor = $registro[vocontrato::$nmAtrGestorContrato];
                        
                        $inPrazoProrrogacao = $registro[voContratoInfo::$nmAtrInPrazoProrrogacao];
                        
                        //$inSeraProrrogado = dominioSimNao::getDescricao($voAtual->inSeraProrrogado);
                        $inSeraProrrogado = $registro[filtroConsultarContratoConsolidacao::$NmColInSeraProrrogadoConsolidado];
                        $inSeraProrrogado = dominioSimNao::getDescricao($inSeraProrrogado);
                        $inprorrogavel = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogavel];
                        $inprorrogacaoExcepcional = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogacaoExcepcional];
                        if($inPrazoProrrogacao == null){
                        	$inprorrogavel = $inprorrogacaoExcepcional= getTextoHTMLDestacado(constantes::$DS_OPCAO_NAO_INFORMADO, "red", false);
                        	$inSeraProrrogado = getTextoHTMLTagMouseOver(getTextoHTMLDestacado(constantes::$DS_SIM)
                        			, voMensageria::$MSG_IN_VERIFICAR_SERA_PRORROGADO);
                        	 
                        }else{
                        	//se nao permitir qualquer prorrogacao, nao sera prorrogado, ainda que o contratoinfo informe algo diferente
                        	//dai destacar a informacao que esta incorreta no contratoinfo
                        	/*if(!$inprorrogavel && !$inprorrogacaoExcepcional && $inSeraProrrogado == constantes::$DS_SIM){
                        		$inSeraProrrogado = getTextoHTMLTagMouseOver(getTextoHTMLDestacado($inSeraProrrogado)
                        				, voMensageria::$MSG_IN_VERIFICAR_SERA_PRORROGADO);
                        	}*/
                        	if($inSeraProrrogado == filtroConsultarContratoConsolidacao::$CD_ATENCAO){
	                        		$inSeraProrrogado = getTextoHTMLTagMouseOver(getTextoHTMLDestacado(constantes::$DS_NAO)
	                        				, voMensageria::$MSG_IN_VERIFICAR_SERA_PRORROGADO);
                        	}                        	 
                        	 
                        	$inprorrogavel = dominioSimNao::getDescricao($inprorrogavel);
                        	$inprorrogacaoExcepcional = dominioSimNao::getDescricao($inprorrogacaoExcepcional);
                        }
                                                
                        $validaAlerta = true;                        
                        try{
                        	$qtDiasFimVigencia = getQtdDiasEntreDatas(dtHojeSQL, $datafimSQL);
                        }catch (Exception $e){
                        	$validaAlerta = false;
                        }
                        
                        $classColuna = "tabeladados";
                        $mensagemAlerta = "";
                        
                         if($validaAlerta){
                         	if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_VERMELHO){
                         	//if(true){
                         		$classColuna = "tabeladadosdestacadovermelho";
                         	}
                         	else if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_AMARELO){
                         			$classColuna = "tabeladadosdestacadoamarelo";
                         	}else if($isTermoAtualOP){
                         		//muda a cor da datafim
                        		$dataFinalHtml = getTextoHTMLDestacado($dataFinalHtml);
                        	}                         		
                        
                         	$mensagemAlerta = "onMouseOver=toolTip('".$qtDiasFimVigencia."dias') onMouseOut=toolTip()";
                         }
                         
                         $tagCelula = "class='$classColuna' " . $mensagemAlerta;
                         
                         $contrato = formatarCodigoAnoComplemento($voAtual->cdContrato,
                         		$voAtual->anoContrato,
                         		$dominioTipoContrato->getDescricao($voAtual->tipo));
                         
                         $objeto = $registro[vocontrato::$nmAtrObjetoContrato];
                         $chaveContratoTemp = $voAtual->getValorChaveHTML();
                         $nmDivObjeto = "div".$chaveContratoTemp;
                         $objeto = truncarStringHTMLComDivExpansivel($nmDivObjeto, $objeto, 200);                                                
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
                    <TD class="tabeladados" nowrap><?php echo $contrato?></TD>
                    <TD class="tabeladados" nowrap><?php echo $termoAtual?></TD>
                    <TD class="tabeladados"><?php echo $dsPessoa?></TD>
                    <TD class="tabeladados"><?php echo $objeto?></TD>
					<TD class="tabeladados" ><?php echo $gestor?></TD>
                    <TD class="tabeladados" nowrap><?php echo $inSeraProrrogado?></TD>
                    <TD class="tabeladados" nowrap><?php echo $inprorrogavel?></TD>					
					<TD class="tabeladados" nowrap><?php echo $inprorrogacaoExcepcional?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($colecao[$i][filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia])?></TD>
                    <TD <?=$tagCelula?>><?php echo $dataFinalHtml?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][filtroConsultarContratoConsolidacao::$NmColPeriodoEmAnos]?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voContratoAtual->vlMensal?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voContratoAtual->vlGlobal?></TD>
                </TR>					
                <?php
				}
				
				if($filtro->temColecaoTotalizadores()){
					$arrayTotalizadores = $filtro->getColecaoTotalizadores();
					$colspanTotalizador = $colspan - sizeof($arrayTotalizadores)+1;
					//var_dump($arrayTotalizadores);
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspanTotalizador?>>TOTAL:</TD>
                    <TD class="totalizadortabeladadosalinhadodireita"><?php echo getMoeda($arrayTotalizadores[vocontrato::$nmAtrVlMensalContrato])?></TD>
                    <TD class="totalizadortabeladadosalinhadodireita"><?php echo getMoeda($arrayTotalizadores[vocontrato::$nmAtrVlGlobalContrato])?></TD>
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
                            //echo getBotoesRodape();                            
                            ?>
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoValidacaoAcesso("bttDetalhar", "Detalhar", "botaofuncaop", false, false,true,false,"onClick='javascript:detalhar();' accesskey='g'");
                            ?>                                                        	                   	
                            </TD>
                            <TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD>
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
