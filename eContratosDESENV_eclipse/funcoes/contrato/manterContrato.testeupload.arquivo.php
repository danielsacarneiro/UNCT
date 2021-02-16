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

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	var documento = document.frm_principal;
	var campoFormularioValido = documento.<?=voentidade::$ID_REQ_IN_FORMULARIO_VALIDO?>;
	if (campoFormularioValido != null && campoFormularioValido.value == 'N'){
		exibirMensagem("Para continuar, corriga os ALERTAS no topo da página.");
		return false;		
	}

	if (!isCampoProcLicitatorioSEFAZValido(documento.<?=vocontrato::$nmAtrProcessoLicContrato?>, true)){
		return false;		
	}
	
	if (!isNmArquivoValido()){
		return false;		
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
	location.href="index.php?consultar=S";	
}

function confirmar() {
	/*if(!isFormularioValido()){
		return false;
	}*/
	
	return confirm("Confirmar Alteracoes?");    
}

function carregaGestorPessoa(){    
    <?php    
    $idDiv = vocontrato::$nmAtrCdPessoaGestorContrato."DIV";
    $idCampoGestor = vocontrato::$nmAtrCdGestorContrato. "";
    ?>
    getDadosGestorPessoa('<?=$idCampoGestor?>', '<?=$idDiv?>');     
}

function carregaDadosContrato(){
	str = "";

	cdContrato = document.frm_principal.<?=vocontrato::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value;
	tipoContrato = document.frm_principal.<?=vocontrato::$nmAtrTipoContrato?>.value;
	cdEspecie = document.frm_principal.<?=vocontrato::$nmAtrCdEspecieContrato?>.value;
	sqEspecie = document.frm_principal.<?=vocontrato::$nmAtrSqEspecieContrato?>.value;
		
	if(cdContrato != "" && anoContrato != "" && tipoContrato != "" && cdEspecie != ""){

		if(cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>')
			sqEspecie = 1;

		if(sqEspecie != ""){		
			str = "null"+ '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + cdContrato + '<?=CAMPO_SEPARADOR?>' 
			+ tipoContrato + '<?=CAMPO_SEPARADOR?>' + cdEspecie
			+ '<?=CAMPO_SEPARADOR?>' + sqEspecie;
			//vai no ajax
			getDadosPorChaveGenerica(str, 'campoDadosManterContrato.php', '<?=vocontrato::$ID_REQ_DIV_DADOS_MANTER_CONTRATO?>');
			
			limpaDadosContrato();
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

 <?php
		 $varNomesAlternativos = "varJSNomesAlternativos";
		 $pArrayAtributos = array(vocontrato::$nmAtrGestorContrato,
		 		vocontrato::$nmAtrProcessoLicContrato,
		 		vocontrato::$nmAtrContratadaContrato,
		 		vocontrato::$nmAtrDocContratadaContrato,
		 		vocontrato::$nmAtrDtVigenciaInicialContrato,
		 );
		 		 	
		 echo montarHashNomeAlternativo($pArrayAtributos, $varNomesAlternativos, true);
		 ?>
 /**
  * o nome dessa funcao deve ser igual ao da constantes::$NM_FUNCAO_JS_COPIADADOS_TERMO_ANTERIOR
  */
function copiarDadosTermoAnterior(){
	setValorCamposNomesAlternativos(<?=$varNomesAlternativos?>);
	var documento = document.frm_principal;
	var procLic = documento.<?=vocontrato::$nmAtrProcessoLicContrato?>.value;
	documento.<?=vocontrato::$nmAtrProcessoLicContrato?>.value =  procLic.replace(" ", "");	
}

function limpaDadosContrato(){
	var documento = document.frm_principal;
	/*var campos = [documento.<?=vocontrato::$nmAtrGestorContrato?>];
	limparCamposColecaoDeCamposFormulario(campos);*/	

	 <?php
			 $varCamposExcecao = "varJSCamposExcecao";		
			 echo getColecaoComoVariavelJS(vocontrato::getAtributosChaveLogica(), $varCamposExcecao);
			 ?>
	limparFormularioGeral(<?=$varCamposExcecao?>);
}

function testeArquivo(){
	var documento = document.frm_principal;
	var campo = documento.<?=vocontrato::$nmAtrLinkDoc?>;
	var chave = campo.value;

	chave = chave.replace("C:\\fakepath\\", "");

	getDadosPorChaveGenerica(chave, 'campoDadosArquivo.php', '<?=vocontrato::$ID_REQ_DIV_DADOS_MANTER_CONTRATO?>');
	
}

</SCRIPT>
<?=setTituloPagina($titulo)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="campoDadosArquivo.php" enctype="multipart/form-data" onSubmit="return confirmar();">

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

            <TH class="campoformulario" nowrap>Doc.Assinado:</TH>
            <TD class="campoformulario" colspan="3">
            <?php 
            //echo " |PDF: " .  getInputFile(vocontrato::$nmAtrLinkDoc, vocontrato::$nmAtrLinkDoc, " required onChange='testeArquivo();'");
            echo " |PDF: " .  getInputFile(vocontrato::$nmAtrLinkDoc, vocontrato::$nmAtrLinkDoc, " required ");
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
