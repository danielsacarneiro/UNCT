<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."vocontrato.php");
require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");

//inicia os parametros
inicioComValidacaoUsuario(true);
$vo = new vocontrato();
setCabecalho($titulo);

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

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {

	campoSetorDestino = document.frm_principal.<?=voDemandaTramitacao::$nmAtrCdSetorDestino?>;

	if(campoSetorDestino.value != "" && !isCampoTextoValido(document.frm_principal.<?=voDemandaTramitacao::$nmAtrTexto?>, true))	
		return false;		
	
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

function habilitaContrato() {	

	<?php
	$dominioTipoDemanda = new dominioTipoDemanda(dominioTipoDemanda::getColecaoTipoDemandaContrato());
	echo $dominioTipoDemanda->getArrayHTMLChaves("colecaoTpDemandaContrato");	
	?>

	cdTpDemanda = document.frm_principal.<?=voDemanda::$nmAtrTipo?>.value;
	
	pCampoCdContrato = document.frm_principal.<?=vocontrato::$nmAtrCdContrato;?>;
	pCampoAnoContrato = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato;?>;
	pCampoTipoContrato = document.frm_principal.<?=vocontrato::$nmAtrTipoContrato;?>;

	flag = false;
	if(colecaoTpDemandaContrato.indexOf(cdTpDemanda) != -1)
		flag = true;

	pCampoCdContrato.required = flag;
	pCampoAnoContrato.required = flag;
	pCampoTipoContrato.required = flag;
}


function validaFormulario() {
	habilitaContrato();
}

function transferirDadosDocumento(sq, cdSetor, ano, tpDoc){
	chave = ano
	+ CD_CAMPO_SEPARADOR +  cdSetor
	+ CD_CAMPO_SEPARADOR +  tpDoc
	+ CD_CAMPO_SEPARADOR +  sq;

	document.getElementsByName("<?=voDocumento::getNmTabela()?>").item(0).value = chave;
	document.getElementsByName("<?=voDocumento::$nmAtrSq?>").item(0).value = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc);

	//alert(document.frm_principal.<?=voDocumento::getNmTabela()?>.value);
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
 
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
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3><?php echo getCampoDadosContratoMultiplos();?>	            
	            </TD>
	        </TR>	        
            </TBODY>
            </TABLE>
            </DIV>
            </TD>
        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
