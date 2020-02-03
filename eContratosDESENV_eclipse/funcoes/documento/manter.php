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

function isNomeDocumentoValido(){
	var naoencontrado = "<?=constantes::$DS_CAMPO_NAO_ENCONTRADO?>";
	var nomeDoc = document.frm_principal.<?=voDocumento::$nmAtrLink?>.value;

	if(nomeDoc.indexOf(naoencontrado) == -1){
		return true;
	}else{
		exibirMensagem("altere o nome n�o encontrado para o nome do contratado.");
		return false;
	}	
}

<?php
//guarda os setores do econti
$varColecaoGlobalSetor = "_globalColecaoSetor";
echo getColecaoComoVariavelJS(dominioSetor::getColecao(), $varColecaoGlobalSetor);
?>

function getDSPAAP(){

	anoPAAP = document.frm_principal.<?=voPA::$nmAtrAnoPA?>.value;
	cdPAAP = document.frm_principal.<?=voPA::$nmAtrCdPA?>.value;

	retorno = "";
	if(anoPAAP != "" && cdPAAP != ""){
		retorno = "PAAP." + formatarCodigoDocumento(cdPAAP, null, anoPAAP, null, null, ".");
	}
	return retorno;
}


function criarNomeDocumento(campoChamada){
	//formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento);
	
	var ano = document.frm_principal.<?=voDocumento::$nmAtrAno?>.value;
	var cdSetor = document.frm_principal.<?=voDocumento::$nmAtrCdSetor?>.value;	
	var sq = document.frm_principal.<?=voDocumento::$nmAtrSq?>.value;
	var tpDoc = "";

	try{
		tpDoc = document.frm_principal.<?=voDocumento::$nmAtrTp?>.value;
	}catch(e){
		tpDoc = "";
	}

	anoContrato = document.frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value;
	cdContrato = document.frm_principal.<?=vocontrato::$nmAtrCdContrato?>.value;
	tpContrato = document.frm_principal.<?=vocontrato::$nmAtrTipoContrato?>.value;
	tpContrato = getDescricaoTipoContrato(tpContrato);

	anoProcLic = document.frm_principal.<?=voProcLicitatorio::$nmAtrAno?>.value;
	cdProcLic = document.frm_principal.<?=voProcLicitatorio::$nmAtrCd?>.value;	
	cdModProcLic = document.frm_principal.<?=voProcLicitatorio::$nmAtrCdModalidade?>.value;
	
	anoARP = document.frm_principal.<?=voDocumento::$nmAtrAnoARP?>.value;
	cdARP = document.frm_principal.<?=voDocumento::$nmAtrCdARP?>.value;	

	complemento = "";
	isContrato = (cdContrato != "" && anoContrato != "" && tpContrato != "");
	isEdital = (anoProcLic != "" && cdProcLic != "");
	isARP = (anoARP != "" && cdARP != "");
	
	colecaoSetor=<?=$varColecaoGlobalSetor?>;
	nome = "";
	
	if(isContrato){
		nome = getNomePessoaContratada('<?=vopessoa::$ID_NOME_DADOS_CONTRATADA?>');
		nome = "_" + nome  + "_";
		//se nao eh parecer, eh contrato
		//pega o contrato
		complemento = formatarCodigoDocumento(cdContrato, "", anoContrato, tpContrato, colecaoSetor);		
	}else{ 
		if(isARP){
			nome = nome + "_AQUISICAO_ARP-" + formatarCodigoDocumento(cdARP, null, anoARP, null, colecaoSetor);
		}

		if(isEdital){
			var cdModDs = ""; 
			if(cdModProcLic != null){
				cdModDs = cdModProcLic;
			}

			//alert(cdModDs);
			nome = nome + "_PL-" + formatarCodigoDocumento(cdProcLic, null, anoProcLic, null, colecaoSetor) + "." + cdModDs;
		}
	}	
	
	textoComplemento = "";
	conectorLocal = "";
	//paap = "PAAP001";
	paap = getDSPAAP();	
	if(paap != null && paap != ""){
		textoComplemento = conectorLocal + paap;
		conectorLocal = ".";
	}		

	textoComplementoHTML = document.frm_principal.<?=voDocumento::$nmAtrComplemento?>.value;
	if(textoComplementoHTML != null && textoComplementoHTML != ""){
		textoComplemento = textoComplemento + conectorLocal + textoComplementoHTML;
	}
	
	if(textoComplemento != null && textoComplemento != ""){
		nome = "_" + textoComplemento.toUpperCase() + nome;
	}

	complemento = nome + complemento;
	//complemento = complemento + ".doc";
	complemento = complemento + getExtensaoDocumento(tpDoc);	
	
	document.frm_principal.<?=voDocumento::$nmAtrLink?>.value = formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento, colecaoSetor);

	isCampoChaveDoc = false;
	//isChavesPreenchidas = ano != "" && cdSetor != "" && tpDoc != "" && sq != "";
				
	if(campoChamada != null){
		isCampoChaveDoc = campoChamada.name == "<?=vodocumento::$nmAtrAno?>" || campoChamada.name == "<?=vodocumento::$nmAtrCdSetor?>" || campoChamada.name == "<?=vodocumento::$nmAtrTp?>";
		//alert(isCampoChaveDoc);		
	} 

	if(isCampoChaveDoc){
		getSqAtual();	
	}
	
}

/*function carregaDadosContratada(){
	str = "";

	cdDemanda = document.frm_principal.<?=voDemanda::$nmAtrCd?>.value;
	anoDemanda = document.frm_principal.<?=voDemanda::$nmAtrAno?>.value;
	
	if(cdDemanda != "" && anoDemanda != ""){
		str = anoDemanda + '<?=CAMPO_SEPARADOR?>' + cdDemanda;
		//vai no ajax
		getDadosContratadaPorDemanda(str, '<?=vopessoa::$nmAtrNome?>');
	}
}*/

function getSqAtual(){
	var pNmCampoDiv = "<?=voDocumento::$ID_REQ_DIV_SQATUAL?>";
	var pIDCampoAno = "<?=voDocumento::$nmAtrAno?>";
	var pIDCampoSetor = "<?=voDocumento::$nmAtrCdSetor?>";
	var pIDCampoTp = "<?=voDocumento::$nmAtrTp?>";	
	
	//alert(inDiasUteis);
	var ano = document.getElementById(pIDCampoAno).value;
	var setor = document.getElementById(pIDCampoSetor).value;
	var tipo = "";
	try{
		tipo = document.getElementById(pIDCampoTp).value;
	}catch(e){
		tipo = "";
	}
	//alert(tipo);	
	if(ano != "" && setor != "" && tipo != ""){
		chave = ano + '<?=CAMPO_SEPARADOR?>' + setor + '<?=CAMPO_SEPARADOR?>' + tipo;			
		getSqDocumentoAtual(chave, pNmCampoDiv) ;		
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}	
}

function getTpDocumentoSetor(){
	
	var pNmCampoDiv = "<?=voDocumento::$ID_REQ_DIV_TpDocumentoSetor?>";
	var pIDCampoSetor = "<?=voDocumento::$nmAtrCdSetor?>";
	var setor = document.getElementById(pIDCampoSetor).value;	
	
	if(setor != ""){
		var chave = setor;			
		//chave = ano + '<?=CAMPO_SEPARADOR?>' + setor + '<?=CAMPO_SEPARADOR?>' + tipo;
		getTpDocumentoPorSetorAjax(chave, pNmCampoDiv) ;		
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
	        <?php
	        $selectExercicio = selectExercicio::getSelectColecaoAnoInicio();
	        $domSetor = new dominioSetor();
	        $domTp = new dominioTpDocumento();
	        $comboTipoDocMinuta = new select(dominioEspeciesContrato::getColecao());
	        
	        require_once (caminho_funcoes . voPA::getNmTabela() . "/biblioteca_htmlPA.php");
	        
	        if($isInclusao){	            
	            $comboSetor = new select($domSetor->colecao);

	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        
	        
	        $nmClass = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;
	        
	        $js_procLic = " onChange='criarNomeDocumento();' ";
	        $arrayComplementoHTML = array($js_procLic, $js_procLic, $js_procLic);
	        ?>
	        <!--  <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $vo->anoDemanda, true, "camponaoobrigatorio", false, " onChange='carregaDadosContratada();'");?>
			  N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($vo->cdDemanda, "0", 5));?>"  class="camponaoobrigatorio" size="6" maxlength="5" onBlur='carregaDadosContratada();'>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>-->	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" width="1%" NOWRAP>
	            <?php
	            	$pArray = array(null,constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,true,FALSE,false,true,"criarNomeDocumento();");
	            	getContratoEntradaArray($pArray);	             
	            	//getCampoDadosContratoSimples("camponaoobrigatorio", "criarNomeDocumento();");
	            ?></TD>
	            <TH class="campoformulario" nowrap width="1%">Limpar:</TH>
	            <TD class="campoformulario">
	            <?php	            
	            $nmCampos = array(vocontrato::$nmAtrAnoContrato,
	            		vocontrato::$nmAtrCdContrato,
	            		vocontrato::$nmAtrTipoContrato,
	            		voProcLicitatorio::$nmAtrAno,
	            		voProcLicitatorio::$nmAtrCd,
	            		voProcLicitatorio::$nmAtrCdModalidade,
	            		voDocumento::$nmAtrAnoARP,
	            		voDocumento::$nmAtrCdARP,
	            		voPA::$nmAtrAnoPA,
	            		voPA::$nmAtrCdPA,	            		 
	            );
	            echo "<br>".getBorracha($nmCampos, "criarNomeDocumento();");
	            ?>
	            </TD>	            
	            
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitat�rio:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            $arrayCssClass = array($nmClass,$nmClass);
	            //getProcLicitatorioEntradaDados("", "", $arrayCssClass, $arrayComplementoHTML);
	            $voProcLicitatorio = new voProcLicitatorio();
	            getCampoDadosProcLicitatorio($voProcLicitatorio, "camponaoobrigatorio", $arrayComplementoHTML);
	            ?>
	            </TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">ARP (aquisi��o):</TH>
	            <TD class="campoformulario" colspan=3><?php getEntradaDadosCdAno("", "", voDocumento::$nmAtrCdARP, voDocumento::$nmAtrAnoARP, $arrayCssClass, $arrayComplementoHTML);
	            ?></TD>	            
	        </TR>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
				<TD class="campoformulario" colspan=3><?php getCampoDadosPAAP($voPAAP, "camponaoobrigatorio", $arrayComplementoHTML);?></TD>			                                           
	        </TR>			            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario" width="1%"><?php echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $vo->cdSetor, true, $classChaves, false, "onChange='getTpDocumentoSetor();criarNomeDocumento(this);' $disabledChaves $required");?></TD>
                <TH class="campoformulario" nowrap width="1%">Ano.Doc.:</TH>
                <TD class="campoformulario" nowrap><?php echo $selectExercicio->getHtmlCombo(voDocumento::$nmAtrAno,voDocumento::$nmAtrAno, $vo->ano, true, $classChaves, false, " onChange='criarNomeDocumento(this);' $disabledChaves $required");?></TD>                
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Tp.Documento:</TH>
                <TD class="campoformulario">
                <div id="<?=voDocumento::$ID_REQ_DIV_TpDocumentoSetor?>">
                	<?php 
                	$comboTp= new select(array());
                	echo $comboTp->getHtmlCombo(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $vo->tp, true, $classChaves, true, " onChange='criarNomeDocumento(this);' $disabledChaves $required");
                	?>
                	</TD>
				</div>			
                <TH class="campoformulario" nowrap>N�mero:</TH>
                <TD class="campoformulario">
                <INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" onkeyup="validarCampoNumericoPositivo(this);" name="<?=voDocumento::$nmAtrSq?>" onBlur='criarNomeDocumento();' value="<?php echo complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" required>
                <div id="<?=voDocumento::$ID_REQ_DIV_SQATUAL?>">				  
		        </div>
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap>Complemento:</TH>
                <TD class="campoformulario" colspan=3>
                <INPUT type="text" id="<?=voDocumento::$nmAtrComplemento?>" name="<?=voDocumento::$nmAtrComplemento?>" onBlur='criarNomeDocumento();' class="camponaoobrigatorio" size="30" maxlength=50>                
                </TD>
            </TR>
            <?php 
	        }else{	        	
	        ?>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Exerc�cio:</TH>
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
                <TH class="campoformulario" nowrap width="1%">N�mero:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS);?>" class="<?=$classChaves?>" size="7" <?=$readonlyChaves?>></TD>
            </TR>
	        <?php 
	        }?>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Arquivo:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voDocumento::$nmAtrLink?>" name="<?=voDocumento::$nmAtrLink?>" value="<?php echo $vo->link;?>"  class="camponaoobrigatorio" size="80" required></TD>
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
