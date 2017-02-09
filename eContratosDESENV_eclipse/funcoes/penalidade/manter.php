<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."vopenalidade.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new vopenalidade();
//var_dump($vo->varAtributos);

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
	$chave = @$_GET["chave"];
	$array = explode("*",$chave);
	
	$vo->cd = $array[0];
    $vo->cdHistorico = $array[1];
    $isHistorico = ("S" == $vo->cdHistorico);        
	
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao[0]);
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}

$titulo = "PENALIDADE";
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nome  = $vo->descricao;
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;

?>
<!DOCTYPE html>
<HTML lang="pt-BR">
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=vopenalidade::$nmAtrCd?>" name="<?=vopenalidade::$nmAtrCd?>" value="<?=$vo->cd?>">
 
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
	        <?php if(!$isInclusao){?>
				<TR>
	                <TH class="campoformulario" nowrap width=1%>Código:</TH>
	                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
	            </TR>                            
	        <?php }
	        	        
	        include_once("../contrato/dominioTipoContrato.php");
	        $dominioTipoContrato = new dominioTipoContrato();
	        ?>
	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato(Ano/Num/Tipo):</TH>
	            <TD class="campoformulario" >
		            <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vopenalidade::$nmAtrAnoContrato?>" name="<?=vopenalidade::$nmAtrAnoContrato?>"  value="<?php echo($voContrato->anoContrato);?>"  class="<?=$classChaves?>" size="6" maxlength="4" <?=$readonlyChaves?> required>
		            <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vopenalidade::$nmAtrCdContrato?>" name="<?=vopenalidade::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required>                                
	                    <?php
	                    if(!$isInclusao){
	                    ?>
	                        <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" value="<?php echo($dominioTipoContrato->getDescricao($voContrato->tipo));?>"  class="<?=$classChaves?>" size="7" <?=$readonlyChaves?>>
	                        <INPUT type="hidden" id="<?=vopenalidade::$nmAtrTipoContrato?>" name="<?=vopenalidade::$nmAtrTipoContrato?>"  value="<?=$voContrato->tipo;?>">
	                    <?php
	                    }else{
	                        $combo = new select($dominioTipoContrato->colecao);
	                        //cria o combo
	                        echo $combo->getHtmlComObrigatorio(vopenalidade::$nmAtrTipoContrato,vopenalidade::$nmAtrTipoContrato, "", false, true);
	                    }
	                    ?>
	            
	            <TD class="campoformularioalinhadodireita" colspan="2"><a href="javascript:limparFormulario();" ><img  title="Limpar" src="<?=caminho_imagens?>borracha.jpg" width="20" height="20"></a></TD>				
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Administrativo(Ano/Num):</TH>
	            <TD class="campoformulario" colspan=3>
		            <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vopenalidade::$nmAtrAnoPA?>" name="<?=vopenalidade::$nmAtrAnoPA?>"  value="<?php echo($voContrato->anoContrato);?>"  class="<?=$classChaves?>" size="6" maxlength="4" <?=$readonlyChaves?> required>
		            <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vopenalidade::$nmAtrCdPA?>" name="<?=vopenalidade::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required>                               
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vopenalidade::$nmAtrProcessoLicitatorio?>" name="<?=vopenalidade::$nmAtrProcessoLicitatorio?>"  value="<?php echo($procLic);?>"  class="camponaoobrigatorio" size="50" ></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=vopenalidade::$nmAtrObservacao?>" name="<?=vopenalidade::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($dsObjeto);?></textarea>
				</TD>
	        </TR>

	        <?php if(!$isInclusao){
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
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
