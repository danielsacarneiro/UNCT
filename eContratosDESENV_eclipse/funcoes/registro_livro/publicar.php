<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voRegistroLivro();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$nmFuncao = "PUBLICAÇÃO";

if($vo->dtReferencia == null|| $vo->dtReferencia == "")
	$vo->dtReferencia = dtHoje;
	
$titulo = voRegistroLivro::getTituloJSP();
$titulo = $nmFuncao;// . $titulo;
setCabecalho($titulo);

$pIdCampoDivPublicacao = voRegistroLivro::$nmAtrObservacao;

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" ></script>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
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

//serve para guardar as chaves ja publicadas
var _globalChave = "";

function carregaPublicacaoContrato(){
	var pNmCampoDiv = "<?=$pIdCampoDivPublicacao?>";
	var pNmCampoCdContrato = "<?=vocontrato::$nmAtrCdContrato?>";
	var pNmCampoAnoContrato = "<?=vocontrato::$nmAtrAnoContrato?>";
	var pNmCampoTipoContrato = "<?=vocontrato::$nmAtrTipoContrato?>";
	var pNmCampoCdEspecieContrato = "<?=vocontrato::$nmAtrCdEspecieContrato?>";
	var pNmCampoSqEspecieContrato = "<?=vocontrato::$nmAtrSqEspecieContrato?>";
	
	//carregaPublicacaoContratoGenerico(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato);
	
	var existeCampo = true;
	var pIndice = 1;
	var chave = null;
	var campo_separador = "<?=constantes::$CD_CAMPO_SUBSTITUICAO?>";
	var existeContratoErrado = false;
	while(existeCampo){	
		pIdCampoCdContrato = pNmCampoCdContrato + pIndice;
		pIdCampoAnoContrato = pNmCampoAnoContrato  + pIndice; 
		pIdCampoTipoContrato = pNmCampoTipoContrato + pIndice;
		pIdCampoCdEspecieContrato = pNmCampoCdEspecieContrato + pIndice;
		pIdCampoSqEspecieContrato = pNmCampoSqEspecieContrato + pIndice;
				
		var chaveIndice = getChavePublicacao(pIdCampoAnoContrato, pIdCampoTipoContrato, pIdCampoCdContrato, 
				pIdCampoCdEspecieContrato, 
				pIdCampoSqEspecieContrato);
		
		//alert(chaveIndice + " " + pIdCampoCdContrato);
		if(chaveIndice != -1){
			chave = chave + chaveIndice + campo_separador;
			_globalChave = chave; 
		}else{
			existeContratoErrado = true;
			break;
		}		

		pIndice = pIndice +1;
		pIdCampoCdContrato = pNmCampoCdContrato + pIndice;
		existeCampo = document.getElementById(pIdCampoCdContrato) != null;
	}

	if(!existeContratoErrado && chave != null){
		getDadosPublicacaoContrato(chave, pNmCampoDiv);
	}else{
		limpaCampoDiv(pNmCampoDiv);
	}

	/*if(chave != null){
		getDadosPublicacaoContrato(chave, pNmCampoDiv);
	}*/

	//sinaliza se 
	_globalChave = "";
	
}

function getChavePublicacao(pIdCampoAnoContrato, pIdCampoTipoContrato, pIdCampoCdContrato, pIdCampoCdEspecieContrato, pIdCampoSqEspecieContrato){
	var str = "";

	//alert(pIdCampoCdContrato);
	var cdContrato = document.getElementById(pIdCampoCdContrato).value;
	var anoContrato = document.getElementById(pIdCampoAnoContrato).value;
	var tpContrato = document.getElementById(pIdCampoTipoContrato).value;	
	var cdEspecieContratoMater = 'CM';
	
	var campoSqEspecieContrato = document.getElementById(pIdCampoSqEspecieContrato);		
	cdEspecieContrato = document.getElementById(pIdCampoCdEspecieContrato).value;
	//alert(cdEspecieContrato);
	sqEspecieContrato = campoSqEspecieContrato.value;
	isChaveCompleta = true;
	
	if(sqEspecieContrato != null){
		if(cdEspecieContrato == cdEspecieContratoMater && sqEspecieContrato != 1){
			if(sqEspecieContrato != ""){
				exibirMensagem("Alteração não permitida para Contrato Mater.");
			}
			sqEspecieContrato = 1;
			campoSqEspecieContrato.value = sqEspecieContrato; 
		}		
	}
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);
		
	//fica assim por conta do formato da chave do vocontrato
	if(cdContrato != "" && anoContrato != "" && tpContrato != ""
		&& cdEspecieContrato != "" && sqEspecieContrato != ""){
		var str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + cdContrato + CD_CAMPO_SEPARADOR + tpContrato;
				
		str = str + CD_CAMPO_SEPARADOR + cdEspecieContrato;
		str = str + CD_CAMPO_SEPARADOR + sqEspecieContrato;		
		//alert(str);
	}else{
		return -1;
	}

	return str;
}

function carregaPublicacaoContratoGenerico(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato){
	var str = "";
	var pNmCampoDiv = "<?=$pIdCampoDivPublicacao?>";
		
	var cdContrato = document.getElementById(pNmCampoCdContrato).value;
	var anoContrato = document.getElementById(pNmCampoAnoContrato).value;
	var tpContrato = document.getElementById(pNmCampoTipoContrato).value;
	var isChaveCompleta = false;
	
	var cdEspecieContratoMater = 'CM';
	try{
		var campoSqEspecieContrato = document.getElementById(pNmCampoSqEspecieContrato);
		
		cdEspecieContrato = document.getElementById(pNmCampoCdEspecieContrato).value;
		//alert(cdEspecieContrato);
		sqEspecieContrato = campoSqEspecieContrato.value;
		isChaveCompleta = true;
		
		if(sqEspecieContrato != null){
			if(cdEspecieContrato == cdEspecieContratoMater && sqEspecieContrato != 1){
				if(sqEspecieContrato != ""){
					exibirMensagem("Alteração não permitida para Contrato Mater.");
				}
				sqEspecieContrato = 1;
				campoSqEspecieContrato.value = sqEspecieContrato; 
			}		
		}
	}catch(ex){		
		cdEspecieContrato = null;
		sqEspecieContrato = null;		
	}
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);
		
	var colecaoIDCamposRequired = [pNmCampoSqEspecieContrato];
	var required = cdEspecieContrato != cdEspecieContratoMater;	
	tornarRequiredCamposColecaoFormulario(colecaoIDCamposRequired, required);

	//fica assim por conta do formato da chave do vocontrato
	if(cdContrato != "" && anoContrato != "" && tpContrato != ""
		&& ((isChaveCompleta && cdEspecieContrato != "" && sqEspecieContrato != "") || !isChaveCompleta)){
		var str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + cdContrato + CD_CAMPO_SEPARADOR + tpContrato;
		
		if(cdEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + cdEspecieContrato;
		
		if(sqEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + sqEspecieContrato;

		//alert(str);
		//vai no ajax
		getDadosPublicacaoContrato(str, pNmCampoDiv);
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}
}

function getDadosPublicacaoContrato(chaveContrato, idDivResultado) {
    var result = document.getElementById(idDivResultado);          
    imprimeResultado(result, "campoDadosPublicacaoAjax.php?chave=" + chaveContrato);     
}

// funcao java script que aguarda qualquer evento definido
//para todos os componentes do documento, o evento onchange vai chamar a funcao carregaPublicacaoContrato
$(document).ready(function(){
	$(document).change(function (){
		/*alert(43);
		if($(this).val() != ""){
			alert(56);
		}*/
		carregaPublicacaoContrato();
	});

	/*$(document[name="LimparContrato"]).click(function(){
		//alert('ok');
		//limpaCampoDiv("<?=$pIdCampoDivPublicacao?>");
		setTimeout(carregaPublicacaoContrato, 1000);
		//carregaPublicacaoContrato();
		});	*/
})

</SCRIPT>
<?=setTituloPagina($titulo)?>
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
	            <TD class="campoformulario" colspan=3><?php getCampoDadosContratoMultiplos(true, true, "carregaPublicacaoContrato();");?>	            	            
	            </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Texto:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<div id="<?=voRegistroLivro::$nmAtrObservacao?>">
	            	</div>
				</TD>
	        </TR>

	        <?php
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
							<?php
	        				//=getBotoesRodapeManter();
							echo "<TD class='botaofuncao'>" . getBotaoCancelar () . "</TD>\n";
							?>
							
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
