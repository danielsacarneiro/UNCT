<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_util."dominioSetor.php");
include_once(caminho_vos."voDocumento.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voRegistroLivro();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "campoobrigatorio";
$readonlyChaves = "";
$disabledChaves = "";
$required = "";

$nmFuncao = "";
    
$nmFuncao = "PRÓXIMO TERMO ";	
$required = "required";
	
$titulo = $vo::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isNomeDocumentoValido())
		return false;		
			
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	retorno = true;
	if(!isFormularioValido()){
		retorno = false;
	}

	if(retorno){
		retorno = confirm("Confirmar Alteracoes?"); 
	}

	return  retorno;	
}


function getSqAtual(){
	var pNmCampoDiv = "<?=voDocumento::$ID_REQ_DIV_SQATUAL?>";
	var pIDCampoAno = "<?=vocontrato::$nmAtrAnoContrato?>";
	var pIDCampoTp = "<?=vocontrato::$nmAtrTipoContrato?>";	
	
	//alert(inDiasUteis);
	var ano = document.getElementById(pIDCampoAno).value;
	var tipo = document.getElementById(pIDCampoTp).value;

	//alert(tipo);	
	if(ano != "" && tipo != ""){
		chave = ano + '<?=CAMPO_SEPARADOR?>' + tipo;
				
		link = "campoSqProximoTermo.php";
		//biblio ajax
		getDadosPorChaveGenerica(chave, link, pNmCampoDiv);
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}	
}

function iniciar(){
	//alert(getDescricaoChaveDS(11,<?=$varColecaoGlobalSetor?>));
}
</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
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
	            <TD class="campoformulario" width="1%" NOWRAP>
	            <?php
	            require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	            $pArray = array(null,constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,true,FALSE,false,true,"getSqAtual();");
	            getContratoEntradaArray($pArray);
	            ?>
                <div id="<?=voDocumento::$ID_REQ_DIV_SQATUAL?>">				  
		        </div>	            
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Limpar:</TH>
	            <TD class="campoformulario">
	            <?php	            
	            $nmCampos = array(vocontrato::$nmAtrAnoContrato,
	            		vocontrato::$nmAtrCdContrato,
	            		vocontrato::$nmAtrTipoContrato,
	            );
	            echo "<br>".getBorracha($nmCampos, "getSqAtual();");
	            ?>
	            </TD>	            
	            
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
							//=getBotoesRodape();
							?>
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoCancelar();
                            ?>
                            </TD>							
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
