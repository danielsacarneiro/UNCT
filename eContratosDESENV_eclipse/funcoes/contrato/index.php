<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbcontrato.php");

//inicia os parametros
try{
inicio();

$titulo = "CONSULTAR CONTRATOS";
setCabecalho($titulo);

$voContrato = new vocontrato();

$filtro  = new filtroManterContrato();
$filtro->voPrincipal = $voContrato;
$nmMetodoExportarPlanilha = "consultarFiltroManterContrato";
$arrayObjetosExportarPlanilha = array($voContrato, $nmMetodoExportarPlanilha);
$filtro->setArrayObjetosExportarPlanilha($arrayObjetosExportarPlanilha);

$filtro = filtroManter::verificaFiltroSessao($filtro);

$cdContrato = $filtro->cdContrato;
$anoContrato = $filtro->anoContrato;
$tipo = $filtro->tipo;
    
$especie  = $filtro->especie;
$cdEspecie  = $filtro->cdEspecie;
$nmGestor  = $filtro->gestor;
$nmContratada  = $filtro->contratada;
$docContratada = $filtro->docContratada;
$dsObjeto  = $filtro->objeto;
$dtVigenciaInicial  = $filtro->dtVigenciaInicial;
$dtVigenciaFinal  = $filtro->dtVigenciaFinal;
$dtVigencia  = $filtro->dtVigencia;
$dtInicio1  = $filtro->dtInicio1;
$dtInicio2  = $filtro->dtInicio2;
$dtFim1  = $filtro->dtFim1;
$dtFim2  = $filtro->dtFim2;
$cdHistorico = $filtro->cdHistorico;
$isHistorico = ("S" == $cdHistorico); 

$complementoTitulo = "";
if($filtro->cdConsultarArquivo == null){
	$filtro->cdConsultarArquivo = "N";
}else if($filtro->cdConsultarArquivo != "N"){
	$complementoTitulo = "/ARQUIVO";
}

$requiredArquivo = "";
/*if($filtro->cdConsultarArquivo == "S"){
	$requiredArquivo = "required";
}*/

$dbprocesso = new dbcontrato();
//$colecao = $dbprocesso->consultarComPaginacao($voContrato, $filtro, $numTotalRegistros, $pagina, $qtdRegistrosPorPag);
$colecao = $dbprocesso->$nmMetodoExportarPlanilha($filtro);

//aqui verifica se pelo menos um filtro valido foi inserido
//se nao, seta os filtros defalts para diminuir o retorno da consulta
//o trecho deve ficar depois da consulta que eh quando sao setados no filtro os valores default
//if($filtro->temValorDefaultSetado){
	$anoContrato  = $filtro->anoContrato;
//}

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
/*function formataForm() {
	var campoConsultarArquivo = null;
	try{
		campoConsultarArquivo = document.frm_principal.cdConsultarArquivo;
	}catch(ex){
		var isConsultarArquivo = campoConsultarArquivo.value;
	
		if(isConsultarArquivo != "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_COMUM?>"){
			document.frm_principal.<?=filtroManterContrato::$nmAtrAnoArquivo?>.disabled = false;
		}else{
			document.frm_principal.<?=filtroManterContrato::$nmAtrAnoArquivo?>.disabled = true;
		}
		
		//if(isConsultarArquivo == "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_ARQUIVO_CONTRATO_ASSINADO?>"){
		if(isConsultarArquivo != "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_COMUM?>"){	
				//por conta da estrutura de arquivos da _dag$/UNCT, deve-se obrigar o nome da contratada			
			document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = true;
		}else{
			document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = false;
		}
	
}

function validaFormulario() {

	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;
	if(isConsultarArquivo != "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_COMUM?>"){							
		var colecaoNmCamposForm = ["<?=vocontrato::$nmAtrCdContrato?>", "<?=vocontrato::$nmAtrContratadaContrato?>"];
		var colecaoDescricaoCamposForm = ["N�mero do Contrato", " ou Nome Contratada"];
		if(!isPeloMenosUmCampoFormularioSelecionado(colecaoNmCamposForm, colecaoDescricaoCamposForm, true))
			return false;	
	}

	return true;			
}*/

// Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
		cdReferenciaLegal = array[0];
		dsReferenciaLegal = array[1];
		dtInicioVigenciaReferenciaLegal = array[2];
		dtFimVigenciaReferenciaLegal = array[3];
		window.opener.transferirDadosReferenciaLegal(cdReferenciaLegal, dsReferenciaLegal, dtInicioVigenciaReferenciaLegal, dtFimVigenciaReferenciaLegal);
		window.close();
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
	location.href="detalharContrato.php?funcao=" + funcao + "&chave=" + chave;
}


function movimentacoes(){    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }    
  	
	/*var array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "*", false);
	especie = array[4];
	
	if(especie != '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>'){
		alert("Opera��o permitida apenas para contrato Mater.");
		return;
	}*/

	chave = document.frm_principal.rdb_consulta.value;
    url = "movimentacaoContrato.php?chave=" + chave;
	
    abrirJanelaAuxiliar(url, true, false, false);
    
}

function excluir() {
    detalhar(true);
}

function incluir() {
	//location.href="manterContrato.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
	location.href="<?=getLinkManter("manterContrato.php", constantes::$CD_FUNCAO_INCLUIR)?>";	
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	//location.href="manterContrato.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;
	location.href="<?=getLinkManter("manterContrato.php",constantes::$CD_FUNCAO_ALTERAR)?>&chave=" + chave;

}

function confirmar() {
	/*if (!validaFormulario()) {
		return false;
	}*/	
	    
	return true;
}

function estatisticas(){
	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isCampoSelectValido(document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>)){
            return;
    }
	/*var chave = document.frm_principal.rdb_consulta.value;
	var strAno = "<?=vocontrato::$nmAtrAnoContrato."=$anoContrato"?>";
    url = "detalharEstatisticas.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S&"+strAno;
	*/
	var chave = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value;
    url = "detalharEstatisticas.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S";	
    	
    abrirJanelaAuxiliar(url, true, false, false);
}


</SCRIPT>
<?=setTituloPagina(vocontrato::getTituloJSP().$complementoTitulo)?>
</HEAD>
<BODY class="paginadados" onload="">	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S" onSubmit="return confirmar();">

<INPUT type="hidden" id="evento" name="<%=PRManterReferenciaLegal.ID_REQ_EVENTO%>" value=""> 
<INPUT type="hidden" name="utilizarSessao" value="N"> 
<INPUT type="hidden" id="numTotalRegistros" value="<?=$numTotalRegistros?>">

<!--  <INPUT type="hidden" id="<?=constantes::$ID_REQ_CD_CONSULTAR?>" name="<?=constantes::$ID_REQ_CD_CONSULTAR?>" value="<?=getInConsultarHTMLString()?>">  -->
<INPUT type="hidden" id="<?=constantes::$ID_REQ_CD_CONSULTAR?>" name="<?=constantes::$ID_REQ_CD_CONSULTAR?>" value="N">

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
            <TD class="campoformulario" colspan=4>
	            <TABLE class="filtro" cellpadding="0" cellspacing="0">
	        	<TBODY>
	        	<TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" width="1%" nowrap>
	            <?php
	            
	            $voContratoFiltro = new vocontrato();
	            $voContratoFiltro->tipo = $tipo;
	            $voContratoFiltro->cdContrato = $cdContrato;
	            $voContratoFiltro->anoContrato = $anoContrato;
	            $voContratoFiltro->cdEspecie = $filtro->cdEspecie;
	            $voContratoFiltro->sqEspecie = $filtro->sqEspecie;
	            
	            //var_dump($filtro->cdEspecie);
	            
	            $arrayComplementoHTML = null;
	            $arrayComplementoHTML = array (
	            		" ",
	            		" ",
	            		" ",
	            		" multiple ",
	            		" ",
	            );
	             
	            $pNmCampoCdContrato = vocontrato::$nmAtrCdContrato;
	            $pNmCampoAnoContrato = vocontrato::$nmAtrAnoContrato;
	            $pNmCampoTipoContrato = vocontrato::$nmAtrTipoContrato;
	            $pNmCampoCdEspecieContrato = vocontrato::$nmAtrCdEspecieContrato . "[]";
	            $pNmCampoSqEspecieContrato = vocontrato::$nmAtrSqEspecieContrato;
	            $nmCampoDivPessoaContratada = vopessoa::$nmAtrNome;
	             
	            $arrayNmCamposFormularioContrato[0] = $pNmCampoCdContrato;
	            $arrayNmCamposFormularioContrato[1] = $pNmCampoAnoContrato;
	            $arrayNmCamposFormularioContrato[2] = $pNmCampoTipoContrato;
	            $arrayNmCamposFormularioContrato[3] = $pNmCampoCdEspecieContrato;
	            $arrayNmCamposFormularioContrato[4] = $pNmCampoSqEspecieContrato;
	            $arrayNmCamposFormularioContrato[5] = $nmCampoDivPessoaContratada;
	             
	            $pArray = array($voContratoFiltro,
	            		constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,
	            		false,
	            		true,
	            		false,
	            		null,
	            		$arrayNmCamposFormularioContrato,
	            		$arrayComplementoHTML,
	            		true,
	            );
	            
	            getContratoEntradaArrayGenerico($pArray);
	            ?>
	            </TD>
	            <TD class="campoformulario">
	                <TABLE class="filtro" cellpadding="0" cellspacing="0">
        			<TBODY>
						<TR>
			                 <TH class="campoformulario" nowrap>Gestor:</TH>
			                 <TD class="campoformulario">				                 
				            <?php 
				            include_once(caminho_util. "dominioSimNao.php");
				            $comboSimNao = new select(dominioSimNao::getColecao());
				             
				            echo "E-mail? " . $comboSimNao->getHtmlCombo(
				            		filtroManterContratoInfo::$ID_REQ_InGestor,
				            		filtroManterContratoInfo::$ID_REQ_InGestor, 
				            		$filtro->inGestor, true, "camponaoobrigatorio", false,
				            	"");
				            ?>
							|Nome: <INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="camponaoobrigatorio" size="20" >	            
							</TD>
						</TR>
						<TR>
							<?php
							require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");				
							$nmCheckAutorizacaoArray = vocontrato::$nmAtrCdAutorizacaoContrato . "[]";
							$colecaoAutorizacao = $filtro->cdAutorizacao;
											
							require_once (caminho_util . "/selectOR_AND.php");
							$comboOuE = new selectOR_AND();
							?>
				            <TH class="campoformulario" width="1%" nowrap>Autoriza��o:</TH>
				            <TD class="campoformulario">
				            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
				            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
				            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
				            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
				            <?php echo $comboOuE->getHtmlSelect(filtroManterContrato::$NmAtrInOR_AND,filtroManterContrato::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>
							</TD>
						</TR>
						<TR>
			               <TH class="campoformulario" width="1%" nowrap>Empenho:</TH>
			               <TD class="campoformulario"><?=getInputText(vocontrato::$nmAtrNumEmpenhoContrato, vocontrato::$nmAtrNumEmpenhoContrato, $filtro->empenho)?>
			               </TD>	            
			            </TR>
						<TR>
				            <TH class="campoformulario" width="1%" nowrap>Licon:</TH>
				            <TD class="campoformulario">
				            <?php 	            
				            echo $comboSimNao->getHtmlCombo(vocontrato::$nmAtrInLicomContrato,vocontrato::$nmAtrInLicomContrato, $filtro->licon, true, "camponaoobrigatorio", false,"");
				            ?>
						</TR>        			
        			</TBODY>
        			</TABLE>	            
	            </TD>	            	        	
	        	</TR>
	        	</TBODY>
	        	</TABLE>
            </TD>	            
 	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitat�rio:</TH>
	            <TD class="campoformulario" colspan=3><?php getCampoDadosProcLicitatorio($filtro->voproclic);?>
	            |Extenso: 
	            <INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($filtro->dsproclic);?>"  class="camponaoobrigatorio" size="15" >
	            </TD>
	        </TR>	        
			<TR>
                <TH class="campoformulario" nowrap >Contratada:</TH>
                <TD class="campoformulario" width="1%">
                Nome: 
                <INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="20" <?=$requiredArquivo?>>
                |CPF/CNPJ: 
                <INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18">
                </TD>
				<TH class="campoformulario" width="1%">Objeto:</TH>
				<TD class="campoformulario" >
				<INPUT type="text" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>"  value="<?php echo($dsObjeto);?>"  class="camponaoobrigatorio" size="30">
				</TD>
            </TR>
			<TR>
				<TH class="campoformulario" nowrap>Intervalo:</TH>
				<TD class="campoformulario" colspan=3>
                          	Data Inicial: <INPUT type="text" 
                        	       id="dtInicio1" 
                        	       name="dtInicio1" 
                        			value="<?php echo($dtInicio1);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="dtInicio2" 
                        	       name="dtInicio2" 
                        			value="<?php echo($dtInicio2);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                        	| Data Final:
                        	<INPUT type="text" 
                        	       id="dtFim1" 
                        	       name="dtFim1" 
                        			value="<?php echo($dtFim1);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="dtFim2" 
                        	       name="dtFim2" 
                        			value="<?php echo($dtFim2);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                        	| Vl.Global:
							<INPUT type="text" id="<?=filtroManterContrato::$NmAtrVlGlobalInicial?>" name="<?=filtroManterContrato::$NmAtrVlGlobalInicial?>"  value="<?php echo($filtro->vlGlobalInicial);?>"
	            					onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            				a <INPUT type="text" id="<?=filtroManterContrato::$NmAtrVlGlobalFinal?>" name="<?=filtroManterContrato::$NmAtrVlGlobalFinal?>"  value="<?php echo($filtro->vlGlobalFinal);?>"
	            					onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
				</TD>
         </TR>	    		
			<TR>
				<TH class="campoformulario" nowrap>Assinatura:</TH>
				<TD class="campoformulario" colspan=3>
                          	De <INPUT type="text" 
                        	       id="<?=filtroManterContrato::$ID_REQ_DtAssinaturaInicial?>" 
                        	       name="<?=filtroManterContrato::$ID_REQ_DtAssinaturaInicial?>" 
                        			value="<?php echo($filtro->dtAssinaturaInicial);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="<?=filtroManterContrato::$ID_REQ_DtAssinaturaFinal?>" 
                        	       name="<?=filtroManterContrato::$ID_REQ_DtAssinaturaFinal?>"
                        			value="<?php echo($filtro->dtAssinaturaFinal);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                        	| Publicado? 
                        	<?php
                        	echo $comboSimNao->getHtmlCombo(filtroManterContrato::$ID_REQ_InPublicado,
                        			filtroManterContrato::$ID_REQ_InPublicado, 
                        			$filtro->inPublicado, true, "camponaoobrigatorio", false,"");
                        	?>
				</TD>
         </TR>	
         		<TR>
			<TH class="campoformulario" nowrap>Tp.Vig�ncia:</TH>
			<?php
			include_once(caminho_util."dominioTpVigencia.php");
			$comboVigencia = new select(dominioTpVigencia::getColecao());						
			?>
            <TD class="campoformulario" nowrap colspan=3>
            <?php echo $comboVigencia->getHtmlOpcao($filtro::$nmAtrTpVigencia,$filtro::$nmAtrTpVigencia, $filtro->tpVigencia, false);?>
			| Vigente na Data:
			<INPUT type="text" 
                        	       id="dtVigencia" 
                        	       name="dtVigencia" 
                        			value="<?php echo($dtVigencia);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
			| Vigente no intervalo: 
			<INPUT type="text" 
                        	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
                        	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
                        			value="<?php echo($dtVigenciaInicial);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
                        	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
                        			value="<?php echo($dtVigenciaFinal);?>"
                        			onkeyup="formatarCampoData(this, event, false);"
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > 
                        	| Sq.Max? 
                        	<?php
                        	//$filtro->isTpVigenciaMAxSq = true;
                        	echo $comboSimNao->getHtmlCombo(filtroManterContrato::$nmAtrIsTpVigenciaMAxSq,
                        			filtroManterContrato::$nmAtrIsTpVigenciaMAxSq, 
                        			dominioSimNao::getString($filtro->isTpVigenciaMAxSq), true, "camponaoobrigatorio", false,"");
                        	?>                        			           
            </TD>
	    </TR>	
	    <script>
	    <?php 
	    $colecaoespecieLAI = dominioEspeciesContrato::getColecaoTermosQuePodemAlterarVigencia();
	    $nmVariavelJSEspecieContratosLAI = "varNmVariavelJSEspecieContratosLAI";
	    echo getColecaoComoVariavelJS($colecaoespecieLAI, $nmVariavelJSEspecieContratosLAI);
	    ?>
	    var pArrayIdCamposFiltroPortalTransparencia = new Array(
	    		'<?=filtroManterContrato::$nmAtrTpVigencia?>',
	    		'<?=filtroManterContrato::$nmAtrIsTpVigenciaMAxSq?>',
	    		'<?=filtroManterContrato::$ID_REQ_InPublicado?>',
	    		'<?=$pNmCampoCdEspecieContrato?>',
	    		'<?=filtroManter::$nmAtrQtdRegistrosPorPag?>'
	    		);

	    var pArrayIdCamposFiltroLicon = new Array(
	    		'<?=filtroManterContrato::$ID_REQ_InPublicado?>',
	    		'<?=vocontrato::$nmAtrInLicomContrato?>',	    		
	    		'<?=$pNmCampoCdEspecieContrato?>',
	    		'<?=filtroManterContrato::$nmAtrIsTpVigenciaMAxSq?>',	    		
	    		);

	    //alert(pArrayIdCamposFiltroPortalTransparencia);
	    </script>				
		<?php
		$linkMontaFiltroPortalTransparencia = "&nbsp;" 
		. getTextoLink("|FiltroPortalTransparencia", "#", "onClick='setFiltroContratosPortalTransparencia(pArrayIdCamposFiltroPortalTransparencia, varNmVariavelJSEspecieContratosLAI);'");
		
		$linkMontaFiltroLicon = "&nbsp;"
				. getTextoLink("|FiltroLicon", "#", "onClick='setFiltroContratosLicon(pArrayIdCamposFiltroLicon, varNmVariavelJSEspecieContratosLAI);'");
		
				/*$pArrayFiltroConsulta = array(
						$filtro->getComboOrdenacao(),
						$filtro->cdAtrOrdenacao,
						$filtro->cdOrdenacao,
						$filtro->TemPaginacao,
						$filtro->qtdRegistrosPorPag,
						$voContrato->temTabHistorico,
						$filtro->cdHistorico,
						$colecao,
						$linkMontaFiltroPortalTransparencia . $linkMontaFiltroLicon
				);*/
				
				$pArrayFiltroConsulta = array(
						$filtro,
						$voContrato->temTabHistorico,
						true,
						$linkMontaFiltroPortalTransparencia . $linkMontaFiltroLicon,
				);
				
				//echo getComponenteConsultaFiltro($voContrato->temTabHistorico, $filtro, true, $colecao);
				echo getComponenteConsultaPaginacaoArray($pArrayFiltroConsulta);
				?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<?php 

/*if($filtro->cdConsultarArquivo != "N"){
	include("grid_arquivo.php");
}else{*/
	include("grid_contrato.php");
//}

?>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);	
}
