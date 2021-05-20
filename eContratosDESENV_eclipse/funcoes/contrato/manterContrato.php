<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once("dominioTipoContrato.php");
include_once("dominioEspeciesContrato.php");
include_once(caminho_util."dominioSimNao.php");
include_once(caminho_util."select.php");
include_once(caminho_vos."vousuario.php");
include_once(caminho_vos."dbcontrato.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo=$voContrato = new vocontrato();
//var_dump($voContrato->varAtributos);

$funcao = @$_GET["funcao"];

$classChaves = "";
$readonlyChaves = "";

$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
$isAlteracao = $funcao == constantes::$CD_FUNCAO_ALTERAR;

if($isInclusao){
    $classChaves = "campoobrigatorio";    	
    $titComplemento = "INCLUIR";
	
}else{
    $classChaves = "camporeadonly";
    $readonlyChaves = "readonly";
	
	$voContrato->getVOExplodeChave($chave);
	$isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");
	
	$dbprocesso = new dbcontrato(null);				
	$colecao = $dbprocesso->limpaResultado();
	$colecao = $dbprocesso->consultarContratoPorChave($voContrato, $isHistorico);	
	$voContrato->getDadosBanco($colecao[0]);
	//var_dump($voContrato);
	$vopessoacontratada = new vopessoa();
	$vopessoacontratada->getDadosBanco($colecao[0]);
	//var_dump($vopessoacontratada);
	
	putObjetoSessao($voContrato->getNmTabela(), $voContrato);

	$titComplemento = "ALTERAR";        
}

$titulo = $vo->getTituloJSP();
$titulo = "$titComplemento $titulo";
setCabecalho($titulo);

	$nmGestor  = $voContrato->gestor;
	$nmGestorPessoa  = $voContrato->nmGestorPessoa;
	$nmContratada  = $voContrato->contratada;
	$docContratada  = documentoPessoa::getNumeroDocFormatado($voContrato->docContratada);	
	$dsObjeto  = $voContrato->objeto;
	$dtVigenciaInicial  = $voContrato->dtVigenciaInicial;
	$dtVigenciaFinal  = $voContrato->dtVigenciaFinal;	
	$vlMensal  = $voContrato->vlMensal;
	$vlGlobal  = $voContrato->vlGlobal;	
	$procLic = $voContrato->procLic;
	$modalidade = $voContrato->modalidade;
	$dtAssinatura = $voContrato->dtAssinatura;
	$dtPublicacao = $voContrato->dtPublicacao;
    $dtProposta = $voContrato->dtProposta;
    $dataPublicacao = $voContrato->dataPublicacao;
	$empenho = $voContrato->empenho;
	$tpAutorizacao = $voContrato->tpAutorizacao;
	$licom = $voContrato->licom;
    $obs = $voContrato->obs;
    
    $dhInclusao = $voContrato->dhInclusao;
    $dhUltAlteracao = $voContrato->dhUltAlteracao;
    $cdUsuarioInclusao = $voContrato->cdUsuarioInclusao;
    $cdUsuarioUltAlteracao = $voContrato->cdUsuarioUltAlteracao;
	
    $idCampoCarac = vocontrato::$nmAtrInCaracteristicas;
    $nmCampoCarac = $idCampoCarac ."[]";
?>
<!DOCTYPE html>

<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">
<?php
		 $varNomesAlternativos = "varJSNomesAlternativos";
		 $pArrayAtributos = array(vocontrato::$nmAtrGestorContrato,
		 		vocontrato::$nmAtrProcessoLicContrato,
		 		vocontrato::$nmAtrContratadaContrato,
		 		vocontrato::$nmAtrDocContratadaContrato,
		 		vocontrato::$nmAtrDtVigenciaInicialContrato,
		 		vocontrato::$nmAtrDtVigenciaFinalContrato,
		 		vocontrato::$nmAtrVlGlobalContrato,
		 		vocontrato::$nmAtrVlMensalContrato,
		 );
		 		 	
		 echo montarHashNomeAlternativo($pArrayAtributos, $varNomesAlternativos, true);
?>

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	var documento = document.frm_principal;
	var campoFormularioValido = documento.<?=voentidade::$ID_REQ_IN_FORMULARIO_VALIDO?>;
	var campoEspecieContrato = documento.<?=vocontrato::$nmAtrCdEspecieContrato?>;
	var campoCaracteristicas = documento.<?=$idCampoCarac?>;
	var nmCampoCaracteristicas = "<?=$nmCampoCarac?>";
	var isTA = campoEspecieContrato.value == "<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO?>";
	
	if (campoFormularioValido != null && campoFormularioValido.value == 'N'){
		exibirMensagem("Para continuar, corriga os ALERTAS no topo da página.");
		return false;		
	}

	if (!isCampoProcLicitatorioSEFAZValido(documento.<?=vocontrato::$nmAtrProcessoLicContrato?>, true)){
		return false;		
	}

	var campoDoc = documento.<?=vocontrato::$nmAtrDocContratadaContrato?>;
	if (campoDoc != null && !isCampoCNPFouCNPJValido(campoDoc, true)){
		return false;		
	}	

	var campoDataInicial = documento.<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>;
	var campoDataFinal = documento.<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>;
	var campoDataAssinatura = documento.<?=vocontrato::$nmAtrDtAssinaturaContrato?>;
	//function isPeriodoValido(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
	if(!isPeriodoValido(campoDataInicial, campoDataFinal, true, true)){
		return false;
	}

	if(!isPeriodoValido(campoDataAssinatura, campoDataInicial, false, true, false, true, isTA)){
		//(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
		exibirMensagem("A data de assinatura deve ser anterior ao início da vigência.");
		return false;
	}

	var funcao = document.frm_principal.funcao.value;
	var isInclusao = funcao == "<?=constantes::$CD_FUNCAO_INCLUIR?>";
	
	//if(isCheckBoxConsultaSelecionado("<?=$nmCampoCarac?>", true)){
	if(!isCheckBoxSelecionado(nmCampoCaracteristicas, true)){
		exibirMensagem("Selecione pelo menos um item 'Características'.");		
		return false;		
	}else if(!(isItemCheckBoxSelecionado(nmCampoCaracteristicas, "<?=constantes::$CD_OPCAO_NENHUM?>")
			|| isItemCheckBoxSelecionado(nmCampoCaracteristicas, "<?=dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO?>"))){

		var pArrayNomeCamposOriginais = [
			"<?=vocontrato::$nmAtrVlMensalContrato?>",
			"<?=vocontrato::$nmAtrVlGlobalContrato?>"];

		//so valida na inclusao
		if(isInclusao && !isValoresCamposAlternativosAlterados(pArrayNomeCamposOriginais, <?=$varNomesAlternativos?>, true)){
			exibirMensagem("O termo em questão altera o valor do contrato, revise o valor inserido.");
			var campoValorGlobal = document.getElementById("<?=vocontrato::$nmAtrVlGlobalContrato?>");
			campoValorGlobal.focus();
			return false;	
		}
		
	}

	/*if (!isNmArquivoValido()){
		return false;		
	}*/

	return true;
}

function isValoresCamposAlternativosAlterados(pArrayNomeCamposOriginais, pArrayValoresAlternativos, pSemMensagem, pNaoSetarFoco){
		
	var i=0;
	for(i=0;i<pArrayNomeCamposOriginais.length;i++){
		var nome = pArrayNomeCamposOriginais[i];
		var campoOriginal = document.getElementById(nome);

		var nomeAlternatico = pArrayValoresAlternativos[nome];
		var campoAlternativo = document.getElementById(nomeAlternatico);

		if(campoAlternativo.value == campoOriginal.value){
			if(!pSemMensagem){
				exibirMensagem("Valor deve sofrer alteração.");
				pNaoSetarFoco = false;
			}

			if(!pNaoSetarFoco){
				campoOriginal.focus();
			}
			return false;
		}
		
	}

	return true;
}
	
function isNmArquivoValido(){
	var documento = document.frm_principal;
	var campo = documento.<?=vocontrato::$nmAtrLinkDoc?>;
	var chave = campo.value;

	chave = chave.replace("C:\\fakepath\\", "");
	var tamMaximo = <?=CONSTANTES::$TAMANHO_MAXIMO_NM_ARQUIVO?>;

	if(!isExtensaoArquivoValida(chave, ".pdf")){
		exibirMensagem("Selecione um arquivo 'pdf'!");
		return false;
	}
	if(chave != null && chave.length > tamMaximo){
		exibirMensagem("Reescreva o nome do arquivo com no máximo <?=CONSTANTES::$TAMANHO_MAXIMO_NM_ARQUIVO?> caracteres.");
		campo.focus();
		return false;
	}

	//alert(chave);
	//getDadosPorChaveGenerica(chave, 'campoDadosArquivo.php', '<?=vocontrato::$ID_REQ_DIV_DADOS_MANTER_CONTRATO?>');
	return true;
}


function cancelar() {
	//history.back();
	//location.href="index.php?consultar=S";
	location.href="<?=getLinkRetornoConsulta()?>";
}

function confirmar() {
	
	try{
		if(!isFormularioValido()){
			return false;
		}
	}catch(ex){
		alert(ex.message);
		return false;
	}
	
	return confirm("Confirmar Alteracoes?\n*Se a data de assinatura é incerta, deixe-a em branco, para incluí-la posteriormente.");    
}

function carregaGestorPessoa(){    
    <?php    
    $idDiv = vocontrato::$nmAtrCdPessoaGestorContrato."DIV";
    $idCampoGestor = vocontrato::$nmAtrCdGestorContrato. "";
    ?>
    getDadosGestorPessoa('<?=$idCampoGestor?>', '<?=$idDiv?>');     
}

function carregaDadosContrato(pCampoChamada=null){
	var str = "";

	var cdContrato = document.frm_principal.<?=vocontrato::$nmAtrCdContrato?>.value;
	var anoContrato = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value;
	var tipoContrato = document.frm_principal.<?=vocontrato::$nmAtrTipoContrato?>.value;
	var cdEspecie = document.frm_principal.<?=vocontrato::$nmAtrCdEspecieContrato?>.value;
	var sqEspecie = document.frm_principal.<?=vocontrato::$nmAtrSqEspecieContrato?>.value;
	var dataVigencia = document.frm_principal.<?=vocontrato::$nmAtrDtAssinaturaContrato?>.value;

	var funcao = document.frm_principal.funcao.value;
	var isAlteracao = funcao == "<?=constantes::$CD_FUNCAO_ALTERAR?>";
		
	if(!isAlteracao && cdContrato != "" && anoContrato != "" && tipoContrato != "" && cdEspecie != ""){
		var carregarDados = (sqEspecie != null && sqEspecie != "");

		if(cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>'){
			sqEspecie = 1;
		/*}else if(cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_APOSTILAMENTO?>'
			&& dataVigencia == ""){*/
		}
		
		if(carregarDados){

			/*var msg = "Para carregar dados anteriores, insira a data de assinatura.";
			msg = msg + "\nSe desconhecida, utilize a data de hoje para carregar os dados, lembrando de alterar posteriormente.";*/					

			/*if(dataVigencia == null || dataVigencia == ""){	
				var msg = "Data Assinatura em branco, serão carregados os dados do termo mais atual.";
				dataVigencia = "<?=getDataHoje()?>";
				exibirMensagem(msg);
			}*/
					
			var recarregar = confirm("Deseja recarregar os dados?");
			if(!recarregar){
				return;
			}
				
			str = "null"+ '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + cdContrato + '<?=CAMPO_SEPARADOR?>' 
			+ tipoContrato + '<?=CAMPO_SEPARADOR?>' + cdEspecie
			+ '<?=CAMPO_SEPARADOR?>' + sqEspecie
			+ '<?=CAMPO_SEPARADOR?>' + dataVigencia;
			//vai no ajax
			getDadosPorChaveGenerica(str, 'campoDadosManterContrato.php', '<?=vocontrato::$ID_REQ_DIV_DADOS_MANTER_CONTRATO?>');
			
			limpaDadosContrato(pCampoChamada);
		}
	}
}

/**
 * copia o valor do campo da CHAVE do hash para o campo do VALOR do hash pArrayNomes
 */
 function setValorCamposNomesAlternativos(pArrayNomes){
		var colecao = pArrayNomes;
		var colecaoChaves = Object.keys(colecao);
		var i=0;
		var nmCampoTela = "";
		var nmCampoalternativo = "";
		//alert(1);
		for(i=0;i<colecaoChaves.length;i++){
			nmCampoTela = colecaoChaves[i];
			nmCampoAlternativo = colecao[nmCampoTela];
			//alert(nmCampoAlternativo);
			var campoTela = document.getElementById(nmCampoTela);
			var campoAlternativo = document.getElementById(nmCampoAlternativo);
			campoTela.value = campoAlternativo.value;				
		}
	}

 /**
  * o nome dessa funcao deve ser igual ao da constantes::$NM_FUNCAO_JS_COPIADADOS_TERMO_ANTERIOR
  */
function copiarDadosTermoAnterior(){
	setValorCamposNomesAlternativos(<?=$varNomesAlternativos?>);
	var documento = document.frm_principal;
	var procLic = documento.<?=vocontrato::$nmAtrProcessoLicContrato?>.value;
	documento.<?=vocontrato::$nmAtrProcessoLicContrato?>.value =  procLic.replace(" ", "");	
}

function limpaDadosContrato(pCampoChamada=null){
	var documento = document.frm_principal;
	campoEspecie = documento.<?=vocontrato::$nmAtrCdEspecieContrato?>;
	cdEspecie = campoEspecie.value;

	var isCampoChamadaDataAssinatura = false;
	if(pCampoChamada != null){
		isCampoChamadaDataAssinatura = pCampoChamada.id = "<?=vocontrato::$nmAtrDtAssinaturaContrato?>";
	}
	
	<?php
			 $varCamposExcecao = "varJSCamposExcecao";
			 $colecaoExcecao = vocontrato::getAtributosChaveLogica();
			 echo getColecaoComoVariavelJS($colecaoExcecao, $varCamposExcecao);
			 ?>
	/*if(cdEspecie == "<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_APOSTILAMENTO?>"){
		<?=$varCamposExcecao?>.push("<?=vocontrato::$nmAtrDtAssinaturaContrato?>");
	}*/

	if(isCampoChamadaDataAssinatura){
		//nao apaga os campos com name='', posto que servem apenas pra detalhamento
		<?=$varCamposExcecao?>.push("");
	}

	//para nunca apagar a data de assinatura;
	<?=$varCamposExcecao?>.push("<?=vocontrato::$nmAtrDtAssinaturaContrato?>");
	limparFormularioGeral(<?=$varCamposExcecao?>);
}

function transferirDadosPessoa(cd, nm, doc) {
	campoDoc = document.getElementById("<?=vocontrato::$nmAtrDocContratadaContrato?>"); 
	campoDoc.value = doc;
	document.getElementById("<?=vocontrato::$nmAtrContratadaContrato?>").value = nm;
	formatarCampoCNPFouCNPJ(campoDoc);

	//formatarCampoCNPFouCNPJ(this, event);
}

function getDiferencaDiasVigencia(){
	campoDataIni = document.getElementById("<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>");
	campoDataFim = document.getElementById("<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>");
	campoNumDias = document.getElementById("<?=vocontrato::$ID_REQ_NumDias?>");

	pDataInicial = campoDataIni.value;
	pDataFinal = campoDataFim.value;
	campoNumDias.value = getQtDias(pDataInicial, pDataFinal);
}

function formatarEmpenho(pCampo){
	var valor = pCampo.value;
	var primeira = null;
	if(valor != null && valor != ""){
		primeira = valor[0];
		//var numero = eval(primeira);

		if (isNaN(primeira)) {
			removerCaracterer(pCampo, primeira, '');
			exibirMensagem("Verifique o formato correto.");
			focarCampo(pCampo);
		}

		/*var filtro = "/^([0-9])*$";
		if (!filtro.test(primeira)) {
			removerCaracterer(pCampo, primeira, '');
			exibirMensagem("Formato incorreto.");
			focarCampo(pCampo);
		}*/
			
	}

	removerCaracterer(pCampo, ' ', '');
}

</SCRIPT>
<?=setTituloPagina($titulo)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarManterContrato.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqContrato?>" name="<?=vocontrato::$nmAtrSqContrato?>" value="<?=$voContrato->sq?>">
 
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
		<TR>
            <TH class="campoformulario" nowrap>Data Assinatura:</TH>
            <TD class="campoformulario"  colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            			value="<?php echo($dtAssinatura);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
                		onChange="carregaDadosContrato(this);"
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required><font size=2><b>*preencha para carregar os dados atualizados. Se incerta, utilize o último dia de vigência do termo atual.</b></font>
			</TD>
           </TR>                		
		<?php 
		if($isInclusao){
		?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 	            
	            //getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, "carregaDadosContrato()", false);	            
	            $pArray = array(null,constantes::$CD_CLASS_CAMPO_OBRIGATORIO,true,true,false,true,"carregaDadosContrato();");
	            getContratoEntradaArray($pArray);	            	 
	            ?>
				<div id="<?=vocontrato::$ID_REQ_DIV_DADOS_MANTER_CONTRATO?>">
				</div>	            
	            </TD>
	        </TR>			
		<?php 
		}else{
			//getContratoDet($voContrato, false, true);
			
			$arrayParametro[0] = $voContrato;
			$arrayParametro[1] = null;
			$arrayParametro[2] = false;
			$arrayParametro[3] = true;
			$arrayParametro[4] = null;
			$arrayParametro[5] = true;			
			//var_dump($voContrato);			
			getContratoDetalhamentoParam($arrayParametro);
				
		}
		?>
		<TR>
            <TH class="campoformulario" width="1%" nowrap>Contratada:</TH>
            <TD class="campoformulario" colspan=3>
            <INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  
            value="<?php echo($nmContratada);?>"  class="camporeadonly" size="50" readonly required>
            | CNPJ/CNPF (em caso de alteração):
            <INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>"  
            value="<?php echo(documentoPessoa::getNumeroDocFormatado($vopessoacontratada->doc));?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camporeadonly" size="20" maxlength="40"  readonly required>
                    <?php 
                    echo getLinkPesquisa("../pessoa");                    
                    $nmCamposDocApagar = array(
                    		vocontrato::$nmAtrContratadaContrato,
                    		vocontrato::$nmAtrDocContratadaContrato,
                    );
                    echo getBorracha($nmCamposDocApagar, "");                    
                    ?>            
            </TD>
        </TR>	                	        
		<TR>
            <TH class="campoformulario" nowrap>Unid.Demandante:</TH>
            <TD class="campoformulario" colspan="3">
                 <?php
                 /*
                include_once(caminho_vos . "dbgestor.php");
                $dbgestor = new dbgestor(null);
                $recordSet = $dbgestor->consultarSelect();
                $gestorSelect = new select(array());                                
                $gestorSelect->getRecordSetComoColecaoSelect(vogestor::$nmAtrCd, vogestor::$nmAtrDescricao, $recordSet);
                echo $gestorSelect->getHtmlCombo($idCampoGestor, $idCampoGestor, $voContrato->cdGestor, true, "camponaoobrigatorio", true, " onChange=carregaGestorPessoa();");                               
                */?>
                <INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="campoobrigatorio" size="50" required></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Objeto:</TH>
            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>" class="camponaoobrigatorio" required><?php echo($dsObjeto);?></textarea>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap><?=getTextoHTMLTagMouseOver("Proc.Licitatorio", "Para os PLs da SEFAZ, o formato deve seguir o exemplo 0013.2018.CPLI.PE.0009.SEFAZ-PE")?>:</TH>
            <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($procLic);?>"  
            onKeyUp='formatarCampoProcLicitatorio(this, event)'class="campoobrigatorio" size="50" required>
            </TD>
	            <TH class="campoformulario" nowrap width="1%">Características:</TH>
	            <TD class="campoformulario" colspan=1>
	            <?php 
	            $itemNenhum = constantes::$CD_OPCAO_NENHUM;
	            $javaScript = "onClick=marcarCheckBoxesExcludentes(this, '$nmCampoCarac', '$itemNenhum');";
	            $arrayParamCarac = array($nmCampoCarac, $vo->inCaracteristicas, dominioTipoDemandaContrato::getColecaoCaracteristicasContrato(), 2, false, $javaScript, false, " ");
	            $arrayParamCarac[12] = true;
	            echo dominioAutorizacao::getHtmlChecksBoxArray($arrayParamCarac);
	             ?>
	            </TD>            
        </TR>		
		<?php 
		
		if($dtVigenciaFinal == null){
			$dtVigenciaFinal = "31/12/" . anoDefault;
		}
		?>
		<TR>
            <TH class="campoformulario" nowrap>Periodo de Vigencia:</TH>
            <TD class="campoformulario">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            			value="<?php echo($dtVigenciaInicial);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			onBlur='getDiferencaDiasVigencia();'
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required > a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo($dtVigenciaFinal);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			onBlur='getDiferencaDiasVigencia();'
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required>
            	<?php
            	//$numDias = getQtdDiasEntreDatas(getDataSQL($dtVigenciaInicial), getDataSQL($dtVigenciaFinal));
            	$numDias = getQtdDiasEntreDatas($dtVigenciaInicial, $dtVigenciaFinal);
            	?>		
            	<INPUT type="text" 
            	       id="<?=vocontrato::$ID_REQ_NumDias?>" 
            	       name="<?=vocontrato::$ID_REQ_NumDias?>" 
            			value="<?php echo($numDias);?>" 
            			class="camporeadonly" 
            			size="4" 
            			readonly> (dias aprox.)
            			
			</TD>
			<TH class="campoformulario" nowrap>Data Publicacao:</TH>
               <TD class="campoformulario">
                    	<INPUT type="text" 
                    	       id="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
                    	       name="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
                    			value="<?php echo($dtPublicacao);?>"
                    			onkeyup="formatarCampoData(this, event, false);" 
                    			class="camponaoobrigatorio" 
                    			size="10" 
                    			maxlength="10" required>
    		</TD>
        </TR>
		<TR>
<SCRIPT language="JavaScript" type="text/javascript">
	var pArrayCalcularValorMensal = new Array();
	pArrayCalcularValorMensal[0] = "<?=vocontrato::$nmAtrVlMensalContrato?>"; 
	pArrayCalcularValorMensal[1] = "<?=vocontrato::$nmAtrVlGlobalContrato?>";
	pArrayCalcularValorMensal[2] = "<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>";
	pArrayCalcularValorMensal[3] = "<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>";
	pArrayCalcularValorMensal[4] = "<?=vocontrato::$ID_REQ_NumMesesUltimaProrrogacao?>";
	pArrayCalcularValorMensal[5] = "<?=$nmCampoCarac?>";
	pArrayCalcularValorMensal[6] = "<?=dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO?>";
	pArrayCalcularValorMensal[7] = null;
	pArrayCalcularValorMensal[8] = "*";

	var pArrayCalcularValorGlobal = new Array();
	pArrayCalcularValorGlobal[0] = "<?=vocontrato::$nmAtrVlGlobalContrato?>"; 
	pArrayCalcularValorGlobal[1] = "<?=vocontrato::$nmAtrVlMensalContrato?>";
	pArrayCalcularValorGlobal[2] = "<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>";
	pArrayCalcularValorGlobal[3] = "<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>";
	pArrayCalcularValorGlobal[4] = "<?=vocontrato::$ID_REQ_NumMesesUltimaProrrogacao?>";
	pArrayCalcularValorGlobal[5] = "<?=$nmCampoCarac?>";
	pArrayCalcularValorGlobal[6] = "<?=dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO?>";
	pArrayCalcularValorGlobal[7] = null;
	pArrayCalcularValorGlobal[8] = "/";
	
</SCRIPT>
	
	<TH class="campoformulario" nowrap>Valor Mensal:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrVlMensalContrato?>" name="<?=vocontrato::$nmAtrVlMensalContrato?>"  value="<?php echo($vlMensal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" 
            onBlur="setaValorCampoPorFator(pArrayCalcularValorMensal);"
            class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
            <TH class="campoformulario" nowrap>Valor Global:</TH>
            <TD class="campoformulario"><INPUT type="text" id="<?=vocontrato::$nmAtrVlGlobalContrato?>" name="<?=vocontrato::$nmAtrVlGlobalContrato?>"  value="<?php echo($vlGlobal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" 
           	onChange="setaValorCampoPorFator(pArrayCalcularValorGlobal);"
            class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
        </TR>
           
		<TR>
            <TH class="campoformulario" nowrap><?=getTextoHTMLTagMouseOver("Empenho", "Separar por ; em caso de múltiplos empenhos.")?>:</TH>
            <TD class="campoformulario" colspan="3">
            <INPUT type="text" <?=getMsgPadraoInputText();?> id="<?=vocontrato::$nmAtrNumEmpenhoContrato?>" onKeyUp="formatarEmpenho(this);" name="<?=vocontrato::$nmAtrNumEmpenhoContrato?>"  value="<?php echo($empenho);?>"  class="camponaoobrigatorio" size="40" required></TD>
        </TR>
        <?php
        if(!$isInclusao){
        ?>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">Documento:</TH>
		        <?php
		        $endereco = $voContrato->getLinkDocumento();
		        $enderecoMinuta = $voContrato->getEnredeçoDocumento($voContrato->linkMinutaDoc);
		        $isContratoPlanilha = $voContrato->importacao != constantes::$CD_NAO;
		        
		        $arrayRetorno = getHTMLDocumentosContrato($voContrato, false, true);
		        $temDocsAExibir = $arrayRetorno[0];
		        $docsAexibir = $arrayRetorno[1];
		        ?>
				<TD class="campoformulario" colspan=3>
				<?php
				if($isContratoPlanilha && !$temDocsAExibir){
				?>
				<TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
					<TR>						
						<TH class="campoformulario">
						Minuta:
						</TH>											
						<TD class="campoformulario">
				        <textarea id="<?=vocontrato::$nmAtrLinkMinutaDoc?>" name="<?=vocontrato::$nmAtrLinkMinutaDoc?>" rows="2" cols="80" class="camponaoobrigatorio"><?php echo  $enderecoMinuta;?></textarea>
					    	<?php    	
					    	echo getBotaoAbrirDocumento(vocontrato::$nmAtrLinkMinutaDoc);
					    	?>					        
				        </TD>
						</TR>
						<TR>
							<TH class="campoformulario">
							Assinado:
							</TH>			
							<TD class="campoformulario">
					        <textarea id="<?=vocontrato::$nmAtrLinkDoc?>" name="<?=vocontrato::$nmAtrLinkDoc?>" rows="2" cols="80" class="camponaoobrigatorio"><?php echo  $endereco;?></textarea>
					    	<?php    	
					    	echo getBotaoAbrirDocumento(vocontrato::$nmAtrLinkDoc);
					    	?>				
							</TD>						
					</TR>
			    </TABLE>
			   <?php
				}else{				
				?>
				<TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
					<TR>
				<?php
				echo $docsAexibir;
				?>					
					</TR>
			    </TABLE>
				<?php 
				}
				?>	
				</TD>								
			</TR>	
        <?php
        }
        ?>					
			<TR>
            <TH class="campoformulario" nowrap>Observação:</TH>
            <TD class="campoformulario" colspan="3">
				<textarea rows=5 cols="80" id="<?=vocontrato::$nmAtrObservacaoContrato?>" name="<?=vocontrato::$nmAtrObservacaoContrato?>" class="camponaoobrigatorio" ><?php echo($obs);?></textarea>
				<?php 
				$pArrayResponsabilidade = array(vocontrato::$nmAtrDtAssinaturaContrato,
						vocontrato::$nmAtrDtPublicacaoContrato,
						vocontrato::$nmAtrDtVigenciaInicialContrato,
						vocontrato::$nmAtrDtVigenciaFinalContrato,
						vocontrato::$nmAtrNumEmpenhoContrato,
						vocontrato::$nmAtrVlMensalContrato,
						vocontrato::$nmAtrVlGlobalContrato,
						vocontrato::$nmAtrLinkDoc,
				);
				
				echo "<br>".getInputCampoCheckResponsabilidade($pArrayResponsabilidade);
				?>  
			</TD>
        </TR>
        <?php if(!$isInclusao){
            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($voContrato) .  "</TR>";
        }?>
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
