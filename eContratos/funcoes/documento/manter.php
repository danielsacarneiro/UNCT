<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_util."dominioSetor.php");
include_once(caminho_vos."voDocumento.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDocumento();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "campoobrigatorio";
$readonlyChaves = "";
$disabledChaves = "";
$required = "";

session_start();

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
	$required = "required";
}else{
	$classChaves = "camporeadonly";
	$readonlyChaves = "readonly";
	$disabledChaves = "disabled";	
	
    $vo->getVOExplodeChave();
    $isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");
	
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);
	
    $nmFuncao = "ALTERAR ";
}
	
$titulo = $vo::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>

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
	/*if(!isFormularioValido())
		return false;*/
	
	return confirm("Confirmar Alteracoes?");    
}

function carregaContratada() {
	<?php 
	$nmCampoDiv = vopessoa::$nmAtrNome;
	?>
	//ta na biblioteca_funcoes_pessoa.js
	NmCampoCdContrato = '<?=vocontrato::$nmAtrCdContrato;?>';
	NmCampoAnoContrato = '<?=vocontrato::$nmAtrAnoContrato;?>';
	NmCampoTipoContrato = '<?=vocontrato::$nmAtrTipoContrato;?>';
	NmCampoDiv = '<?=$nmCampoDiv;?>';
	
	carregaDadosContratada(NmCampoAnoContrato, NmCampoTipoContrato, NmCampoCdContrato, NmCampoDiv);    
}

function criarNomeDocumento(){
	//formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento);
	
	ano = document.frm_principal.<?=voDocumento::$nmAtrAno?>.value;
	cdSetor = document.frm_principal.<?=voDocumento::$nmAtrCdSetor?>.value;
	tpDoc = document.frm_principal.<?=voDocumento::$nmAtrTp?>.value;
	sq = document.frm_principal.<?=voDocumento::$nmAtrSq?>.value;;

	anoContrato = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value;
	cdContrato = document.frm_principal.<?=vocontrato::$nmAtrCdContrato?>.value;
	tpContrato = document.frm_principal.<?=vocontrato::$nmAtrTipoContrato?>.value;
	tpContrato = getDescricaoTipoContrato(tpContrato);

	try{
		nmContrata = document.frm_principal.<?=vopessoa::$ID_NOME_DADOS_CONTRATADA?>.value;		
		nmContrata = truncarTexto(nmContrata, 20, "");
	}catch(ex){
		nmContrata = "";
	}
	nmContrata = "_" + nmContrata  + "_";
	
	complemento = formatarCodigoDocumento(cdContrato, "", anoContrato, tpContrato);
	complemento = nmContrata + complemento;
	complemento = complemento + ".doc";
		
	document.frm_principal.<?=voDocumento::$nmAtrLink?>.value = formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento);
}


</SCRIPT>

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
	        $domSetor = new dominioSetor();
	        $domTp = new dominioTpDocumento();
	        $selectExercicio = new selectExercicio();
	        if($isInclusao){	            
	            $comboSetor = new select($domSetor->colecao);	            
	            $comboTp= new select($domTp->colecao);
			  ?>			            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
                <TD class="campoformulario" nowrap width="1%"><?php echo $selectExercicio->getHtmlCombo(voDocumento::$nmAtrAno,voDocumento::$nmAtrAno, $vo->ano, true, $classChaves, false, " onChange='criarNomeDocumento();' $disabledChaves $required");?></TD>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario"><?php echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $vo->cdSetor, true, $classChaves, true, "onChange='criarNomeDocumento();' $disabledChaves $required");?></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Tp.Documento:</TH>
                <TD class="campoformulario"><?php echo $comboTp->getHtmlCombo(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $vo->tp, true, $classChaves, true, " onChange='criarNomeDocumento();' $disabledChaves $required");?></TD>			
                <TH class="campoformulario" nowrap>Número:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>" onBlur='criarNomeDocumento();' value="<?php echo complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS);?>"  class="<?=$classChaves?>" size="7" <?=$readonlyChaves?>></TD>
            </TR>
            <?php 
	        }else{	        	
	        ?>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Exercício:</TH>
                <TD class="campoformulario" nowrap width="1%"><INPUT type="text" id="<?=voDocumento::$nmAtrAno?>" name="<?=voDocumento::$nmAtrAno?>"  value="<?php echo $vo->ano;?>"  class="camporeadonly" size="5" readonly></TD>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario" nowrap >
                		<INPUT type="text" value="<?php echo $domSetor->getDescricao($vo->cdSetor);?>"  class="camporeadonly" size="7" readonly>
                		<INPUT type="hidden" id="<?=voDocumento::$nmAtrCdSetor?>" name="<?=voDocumento::$nmAtrCdSetor?>"  value="<?php echo $vo->cdSetor;?>">
                </TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Tp.Documento:</TH>
                <TD class="campoformulario" nowrap width="1%">
                		<INPUT type="text" value="<?php echo $domTp->getDescricao($vo->tp);?>"  class="camporeadonly" size="20" readonly>
                		<INPUT type="hidden" id="<?=voDocumento::$nmAtrTp?>" name="<?=voDocumento::$nmAtrTp?>"  value="<?php echo $vo->tp;?>">			
                </TD>
                <TH class="campoformulario" nowrap width="1%">Número:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS);?>" class="<?=$classChaves?>" size="7" <?=$readonlyChaves?>></TD>
            </TR>
	        <?php 
	        }?>
	        <?php 
	        	require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioTipoContrato.php");	        	
	        	$combo = new select(dominioTipoContrato::getColecao());
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	          <?php echo "Ano: " . $selectExercicio->getHtmlCombo(vocontrato::$nmAtrAnoContrato,vocontrato::$nmAtrAnoContrato, $vo->anoContrato, true, "camporeadonly", false, "  onChange='carregaContratada();criarNomeDocumento();'");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camporeadonly" size="4" maxlength="3"  onBlur='carregaContratada();criarNomeDocumento();'>
			  <?php echo $combo->getHtmlCombo(vocontrato::$nmAtrTipoContrato,vocontrato::$nmAtrTipoContrato, "", true, "camporeadonly", false, " onChange='carregaContratada();criarNomeDocumento();' ");
			  ?>
			  <div id="<?=$nmCampoDiv?>">
	          </div>
	        </TR>	        

			<TR>
                <TH class="campoformulario" nowrap width="1%">Arquivo:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voDocumento::$nmAtrLink?>" name="<?=voDocumento::$nmAtrLink?>" onClick='criarNomeDocumento();' value="<?php echo $vo->link;?>"  class="camponaoobrigatorio" size="80" ></TD>
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
