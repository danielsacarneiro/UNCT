<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
	//inicia os parametros
	inicioComValidacaoUsuario(true);

	$vo = new voUsuarioInfo();
	$funcao = @$_GET["funcao"];

	$readonly = "";
	$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

	$classChaves = "campoobrigatorio";
	$readonlyChaves = "";

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
		//pode vir mais de um registro porque pode ter mais de um setor associado
		$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
		$vo->getDadosBanco($colecao[0]);
		$vo->setColecaoSetorRegistroBanco($colecao);

		putObjetoSessao($vo->getNmTabela(), $vo);

		$nmFuncao = "ALTERAR ";
	}


	$titulo = voUsuarioInfo::getTituloJSP();
	$titulo = $nmFuncao . $titulo;
	setCabecalho($titulo);
	
	$ID_REQ_CDSETOR_ORIGEM = "cdSetorOrigem";
	//colecao
	$ID_REQ_CDSETOR_DESTINO = voUsuarioSetor::$nmAtrCdSetor;
	?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
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
	        <?php	        	        
	        $complementoHTML = "";
	        
	        $comboSetor = new select(dominioSetor::getColecao());	        
	         
	        if(!$isInclusao){
	        	$id = $vo->id;	        	        	
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">ID:</TH>
				<TD class="campoformulario" nowrap width="1%"><INPUT type="text" id="<?=voUsuarioInfo::$nmAtrID?>" name="<?=voUsuarioInfo::$nmAtrID?>" value="<?php echo complementarCharAEsquerda($vo->id, "0", TAMANHO_CODIGOS);?>"  class="camporeadonly" size="7" readonly></TD>
				<TH class="campoformulario" >Nome:</TH>
				<TD class="campoformulario"><INPUT type="text" id="<?=voUsuarioInfo::$nmAtrName?>" name="<?=voUsuarioInfo::$nmAtrName?>" value="<?php echo $vo->name;?>"  class="camporeadonly" size="30" readonly></TD>	            
					            
	        </TR>			
			<?php
	        }else{
	        	//INCLUSAO
				//por enquanto nao tem
				;
	       }	       	       
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?=$comboSetor->getHtmlComboMultiplo($ID_REQ_CDSETOR_ORIGEM, $ID_REQ_CDSETOR_DESTINO, $vo->colecaoSetor, "camponaoobrigatorio", 10, " ");?>
			    </TD>
	        </TR>	        
<TR>
	<TD halign="left" colspan="4">
	<DIV class="textoseparadorgrupocampos">&nbsp;</DIV>
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
	tratarExcecaoHTML($ex, $vo);	
}
?>
