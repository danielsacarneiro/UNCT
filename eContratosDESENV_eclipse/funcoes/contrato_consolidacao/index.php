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
//$filtro->voPrincipal = new vocontrato();
$filtro = filtroManter::verificaFiltroSessao($filtro);

$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsultaConsolidacao($filtro, true);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

$nmCampoCaracteristicasHtml = filtroConsultarContratoConsolidacao::$ID_REQ_Caracteristicas . "[]";
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
	            <TD class="campoformulario" nowrap>
	            <?php 
	            getContratoEntradaDeDados($filtro->tipoContrato, $filtro->cdContrato, $filtro->anoContrato, $arrayCssClass, null, null);
	            ?>
	            </TD>	            
	            <TH class="campoformulario" nowrap width="1%">Gestor:</TH>
	            <TD class="campoformulario">
	            <?php 
	            echo "Tem? ".$comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrCdPessoaGestor,voContratoInfo::$nmAtrCdPessoaGestor
							, $filtro->inGestor, true, "camponaoobrigatorio", false,"");
	            ?>
	            </TD>	            
			</TR>
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50"></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
            <?php 
            $comboAutorizacao = new select(dominioAutorizacao::getColecao());            
            include_once(caminho_funcoes. "contrato/dominioTpGarantiaContrato.php");            
            $comboGarantia = new select(dominioTpGarantiaContrato::getColecao());            
            ?>                    
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				//$combo = new select(dominioAutorizacao::getColecao());				
				$nmCheckAutorizacaoArray = voContratoInfo::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" nowrap>Autoriza��o:</TH>
	            <TD class="campoformulario" width="1%">
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroConsultarContratoConsolidacao::$NmAtrInOR_AND,filtroConsultarContratoConsolidacao::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>
	            <!-- <TH class="campoformulario" nowrap width="1%">Autoriza��o:</TH>
	            <TD class="campoformulario" width="1%"><?php echo $comboAutorizacao->getHtmlCombo(voContratoInfo::$nmAtrCdAutorizacaoContrato, voContratoInfo::$nmAtrCdAutorizacaoContrato, $filtro->cdAutorizacao, true, "camponaoobrigatorio", false, "");?></TD>-->	            
	            <TH class="campoformulario" nowrap width="1%">Garantia:</TH>
	            <TD class="campoformulario">
	            Tem?: <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInTemGarantia,voContratoInfo::$nmAtrInTemGarantia, $filtro->inTemGarantia
	            		, true, "camponaoobrigatorio", false,"");
	            ?>
	            Tipo: <?php echo $comboGarantia->getHtmlCombo(voContratoInfo::$nmAtrTpGarantia,voContratoInfo::$nmAtrTpGarantia, $filtro->tpGarantia, true, "camponaoobrigatorio", true, "");?>
	            </TD>
			</TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioClassificacaoContrato.php");
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());
				?>
	            <TH class="campoformulario" nowrap>Classifica��o:</TH>
	            <TD class="campoformulario" width="1%" colspan=3>
	            <?php 
	            echo $comboClassificacao->getHtmlCombo(voContratoInfo::$nmAtrCdClassificacao,voContratoInfo::$nmAtrCdClassificacao, $filtro->cdClassificacao, true, "camponaoobrigatorio", true, "");
	            //$radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;M�o de obra inclu�da (planilha de custos)?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " required " );
	            
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());	             
	            echo "&nbsp;&nbsp;Terceiriza��o (planilha de custos)?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $filtro->inMaoDeObra, true, "camponaoobrigatorio", false,"");
	            
	            $comboProrrogacaoContrato = new select(dominioProrrogacaoFiltroConsolidacao::getColecao());
	            ?>
	        </TR>
			<TR>
				<TH class="campoformulario" nowrap>Objeto:</TH>
				<TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>"  value="<?php echo($filtro->objeto);?>"  class="camponaoobrigatorio" size="30" ></TD>
               <TH class="campoformulario" nowrap>Valor(em 12 meses):</TH>
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
       			<TH class="campoformulario" >Caracter�sticas:</TH>
                <TD class="campoformulario" colspan=3>
                <?php 
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
	            echo dominioFaseDemanda::getHtmlChecksBoxArray($pArrayCaracteristica);
	            ?>                        			
                </TD>
            </TR>			
	            <?php 
	            $comboProrrogacaoContrato = new select(dominioProrrogacaoContrato::getColecao());
	            $comboFiltroProrrogacaoConsolidacao= new select(dominioProrrogacaoFiltroConsolidacao::getColecao());
	            ?>
			
			<TR>
               <TH class="campoformulario" nowrap>Prorroga��o:</TH>
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
				
				//echo $comboProrrogacaoContrato->getHtmlCombo(voContratoInfo::$nmAtrInPrazoProrrogacao,voContratoInfo::$nmAtrInPrazoProrrogacao, $filtro->inPrazoProrrogacao, true, "camponaoobrigatorio", false,"");
				echo $comboProrrogacaoContrato->getHtmlComboArray($arrayTemp);
				echo "| Situa��o: ". $comboFiltroProrrogacaoConsolidacao->getHtmlCombo(filtroConsultarContratoConsolidacao::$nmAtrInProrrogacao,filtroConsultarContratoConsolidacao::$nmAtrInProrrogacao, $filtro->inProrrogacao, true, "camponaoobrigatorio", false,"");
				echo "| Ser� prorrogado?:";
				echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInSeraProrrogado,
						voContratoInfo::$nmAtrInSeraProrrogado,
						$filtro->inSeraProrrogado, true, "camponaoobrigatorio", false, " ");
				?>                        			
                </TD>
            </TR>
			<TR>
               <TH class="campoformulario" nowrap>Prazo:</TH>
               <TD class="campoformulario" colspan=3>               				
				Per�odo (anos):
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
            </TR>
            
            <TR>
               <TH class="campoformulario" nowrap>Fim.Vig�ncia:</TH>
               <TD class="campoformulario" colspan=3> Encerram em:
                        	<?php
                        	$comboMeses = new select(dominioMeses::getColecao());
                        	echo "M�s " . $comboMeses->getHtmlCombo(filtroConsultarContratoConsolidacao::$ID_REQ_MesIntervaloFimVigencia
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
               				Per�odo: 
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
				<TH class="campoformulario" nowrap>Tp.Vig�ncia:</TH>
				<?php
				include_once(caminho_util."dominioTpVigencia.php");
				$comboVigencia = new select(dominioTpVigencia::getColecaoComVazio());						
				?>
	            <TD class="campoformulario" nowrap>
	            <?php 
	            echo $comboVigencia->getHtmlOpcao($filtro::$nmAtrTpVigencia,$filtro::$nmAtrTpVigencia, $filtro->tpVigencia, false);
	            
	            $comboSimNaoEfeitos = new select(array(constantes::$CD_SIM => "Com efeitos", constantes::$CD_NAO => "�lt.Termo"));
	            $textoTag = getTextoHTMLTagMouseOver("�ltimo Termo: ", "Exibe somente os termos formalizados.");
	            echo "| $textoTag". $comboSimNaoEfeitos->getHtmlCombo(
	            		filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos,
	            		filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos, 
	            		$filtro->inProduzindoEfeitos, true, "camponaoobrigatorio", false, "");
	            
	            $nmCamposVigencia = array(
	            		filtroConsultarContratoConsolidacao::$nmAtrTpVigencia,
	            		filtroConsultarContratoConsolidacao::$nmAtrDtVigencia,
	            		filtroConsultarContratoConsolidacao::$ID_REQ_InProduzindoEfeitos,
	            );
	            echo getBorracha($nmCamposVigencia, "");
	             
	            ?></TD>
               <TH class="campoformulario" nowrap>Proposta:</TH>
               <TD class="campoformulario" >
               Dias.Vencimento:
	            <INPUT type="text" id="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimentoProposta?>" name="<?=filtroConsultarContratoConsolidacao::$nmAtrQtdDiasParaVencimentoProposta?>"  
								value="<?php echo($filtro->qtdDiasParaVencimentoProposta);?>"  class="camponaoobrigatorio" size="3">
                </TD>		                	            
		    </TR>					
	        
       <?php
       echo getComponenteConsultaFiltro(true, $filtro);
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
                    <TH class="headertabeladados" width="40%" <?=$rowspan?>>Gestor</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Proposta</TH>                    
                    <TH class="headertabeladadosalinhadocentro" width="1%" colspan="3">Prorroga��o(do)</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Dt.In�cio</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Dt.Fim</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?> >Anos</TH>
                    <TH class="headertabeladados" width="1%" <?=$rowspan?>>Autoriza��o</TH>
                </TR>
                <TR>
					<TH class="headertabeladados" width="1%">Ser�?</TH>
                    <TH class="headertabeladados" width="1%">Normal?</TH>
                    <TH class="headertabeladados" width="1%">Excep.?</TH>                
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;                                
                                
                $colspan=13;
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
                                           
                        $tipo = $dominioTipoContrato->getDescricao($registro["ct_tipo"]);
                        $autorizacaoAtual = $registro[filtroConsultarContratoConsolidacao::$NmColAutorizacao];
                        
                        $dsPessoa = $voPessoa->nome;
                        if($dsPessoa == null){
                        	$dsPessoa = "<B>CONTRATO N�O INCLU�DO NA PLANILHA</B>";
                        }
                        
                        $cdEspeciaAtual = $registro[filtroConsultarContratoConsolidacao::$NmColCdEspecieContratoAtual];
                        $sqEspeciaAtual = $registro[filtroConsultarContratoConsolidacao::$NmColSqEspecieContratoAtual];
                        if($sqEspeciaAtual == null){
                        	//var_dump($registro);
                        	//echo $cdEspeciaAtual;
                        	$termoAtual = getTextoHTMLDestacado("Verifique<br>Vig�ncia");
                        }else{
                        	$termoAtual = $cdEspeciaAtual==dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?dominioEspeciesContrato::$DS_ESPECIE_CONTRATO_MATER:$sqEspeciaAtual ."o $cdEspeciaAtual";
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
                        
                        $datafimSQL = $registro[filtroConsultarContratoConsolidacao::$NmColDtFimVigencia];
                        $dataFinal = getData($datafimSQL);
                        $validaAlerta = true;                        
                        try{
                        	$qtDiasFimVigencia = getQtdDiasEntreDatas(dtHojeSQL, $datafimSQL);
                        }catch (Exception $e){
                        	$validaAlerta = false;
                        }
                        
                        $classColuna = "tabeladados";
                        $mensagemAlerta = "";
                        
                         if($validaAlerta){
                         	if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_VERMELHO)
                         		$classColuna = "tabeladadosdestacadovermelho";
                         	else if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_AMARELO)
                         			$classColuna = "tabeladadosdestacadoamarelo";
                        
                         	$mensagemAlerta = "onMouseOver=toolTip('".$qtDiasFimVigencia."dias') onMouseOut=toolTip()";
                         }
                         
                         $tagCelula = "class='$classColuna' " . $mensagemAlerta;
                         
                         $contrato = formatarCodigoAnoComplemento($voAtual->cdContrato,
                         		$voAtual->anoContrato,
                         		$dominioTipoContrato->getDescricao($voAtual->tipo));                         
                                                
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
					<TD class="tabeladados" ><?php echo $gestor?></TD>
					<TD class="tabeladados" nowrap><?php echo getData($voAtual->dtProposta)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $inSeraProrrogado?></TD>
                    <TD class="tabeladados" nowrap><?php echo $inprorrogavel?></TD>					
					<TD class="tabeladados" nowrap><?php echo $inprorrogacaoExcepcional?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($colecao[$i][filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia])?></TD>
                    <TD <?=$tagCelula?>><?php echo getData($dataFinal)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][filtroConsultarContratoConsolidacao::$NmColPeriodoEmAnos]?></TD>
                    <TD class="tabeladados" nowrap><?php echo $dominioAutorizacao->getDescricao($autorizacaoAtual)?></TD>
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
                            //echo getBotoesRodape();                            
                            ?>
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoValidacaoAcesso("bttDetalhar", "Detalhar", "botaofuncaop", false, false,true,false,"onClick='javascript:detalhar();' accesskey='g'");
                            ?>                                                        	                   	
                            </TD>
                            <TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimenta��es", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD>
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
