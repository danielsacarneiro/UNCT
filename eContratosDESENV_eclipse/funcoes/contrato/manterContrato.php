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
	
	/*if (!isNmArquivoValido()){
		return false;		
	}*/

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
	if(!isFormularioValido()){
		return false;
	}
	
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
		<TR>
            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
            <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="50" required></TD>
            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>"  value="<?php echo($docContratada);?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camponaoobrigatorio" size="20" maxlength="40" required></TD>
        </TR>	                	        
		
		<?php 
		}else{
			getContratoDet($voContrato, false, true);
		}
		?>
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
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($procLic);?>"  
            onKeyUp='formatarCampoProcLicitatorio(this, event)'class="campoobrigatorio" size="50" required></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Valor Mensal:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrVlMensalContrato?>" name="<?=vocontrato::$nmAtrVlMensalContrato?>"  value="<?php echo($vlMensal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
            <TH class="campoformulario" nowrap>Valor Global:</TH>
            <TD class="campoformulario"><INPUT type="text" id="<?=vocontrato::$nmAtrVlGlobalContrato?>" name="<?=vocontrato::$nmAtrVlGlobalContrato?>"  value="<?php echo($vlGlobal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
        </TR>
		
		<?php 
		
		if($dtVigenciaFinal == null){
			$dtVigenciaFinal = "31/12/" . anoDefault;
		}
		?>
		<TR>
            <TH class="campoformulario" nowrap>Periodo de Vigencia:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            			value="<?php echo($dtVigenciaInicial);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required> a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo($dtVigenciaFinal);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Assinatura:</TH>
            <TD class="campoformulario">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            			value="<?php echo($dtAssinatura);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" required>
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
    		</TD>
           </TR>
		<TR>
            <TH class="campoformulario" nowrap>Empenho:</TH>
            <TD class="campoformulario" colspan="3">
            <INPUT type="text" id="<?=vocontrato::$nmAtrNumEmpenhoContrato?>" name="<?=vocontrato::$nmAtrNumEmpenhoContrato?>"  value="<?php echo($empenho);?>"  class="camponaoobrigatorio" size="20" required></TD>
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
		        
		        $arrayRetorno = getHTMLDocumentosContrato($voContrato);
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
				        <textarea id="<?=vocontrato::$nmAtrLinkMinutaDoc?>" name="<?=vocontrato::$nmAtrLinkMinutaDoc?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $enderecoMinuta;?></textarea>
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
					        <textarea id="<?=vocontrato::$nmAtrLinkDoc?>" name="<?=vocontrato::$nmAtrLinkDoc?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $endereco;?></textarea>
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
