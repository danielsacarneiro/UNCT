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

$voContrato = new voContrato();
//var_dump($voContrato->varAtributos);

$funcao = @$_GET["funcao"];

$classChaves = "";
$readonlyChaves = "";

$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

if($isInclusao){
    $classChaves = "campoobrigatorio";    	
	$titulo = "INCLUIR CONTRATO";
	
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

    $titulo = "ALTERAR CONTRATO";        
}

setCabecalho($titulo);

	$nmGestor  = $voContrato->gestor;
	$nmGestorPessoa  = $voContrato->nmGestorPessoa;
	$nmContratada  = $voContrato->contratada;
	$docContratada  = $voContrato->docContratada;	
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancela() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

function carregaGestorPessoa(){    
    <?php    
    $idDiv = vocontrato::$nmAtrCdPessoaGestorContrato."DIV";
    $idCampoGestor = vocontrato::$nmAtrCdGestorContrato. "";
    ?>
    getDadosGestorPessoa('<?=$idCampoGestor?>', '<?=$idDiv?>');     
}

</SCRIPT>
<?=setTituloPagina(null)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarManterContrato.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
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
            <TH class="campoformulario" nowrap width="1%">Ano Contrato:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>"  value="<?php echo($voContrato->anoContrato);?>"  class="<?=$classChaves?>" size="6" maxlength="4" <?=$readonlyChaves?> required></TD>
            <TD class="campoformularioalinhadodireita" colspan="2"><a href="javascript:limparFormulario();" ><img  title="Limpar" src="<?=caminho_imagens?>borracha.jpg" width="20" height="20"></a></TD>
			
        </TR>
        <?php                    
        $dominioTipoContrato = new dominioTipoContrato();
        ?>
        <TR>
            <TH class="campoformulario" nowrap>Número Contrato/Tipo:</TH>
            <TD class="campoformulario" colspan="3">
                    <INPUT type="text" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required>                                
                    <?php
                    if(!$isInclusao){
                    ?>
                        <INPUT type="text" value="<?php echo($dominioTipoContrato->getDescricao($voContrato->tipo));?>"  class="<?=$classChaves?>" size="7" <?=$readonlyChaves?>>
                        <INPUT type="hidden" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>"  value="<?=$voContrato->tipo;?>">
                    <?php
                    }else{
                        $combo = new select($dominioTipoContrato->colecao);
                        //cria o combo
                        echo $combo->getHtmlComObrigatorio(vocontrato::$nmAtrTipoContrato,voContrato::$nmAtrTipoContrato, "", false, true);
                    }
                    ?>
            </TD>
        </TR>					
		<TR>
            <TH class="campoformulario" nowrap>Espécie:</TH>
            <TD class="campoformulario" colspan="3">
                    <?php
                    $dominioEspeciesContrato = new dominioEspeciesContrato();
                    if(!$isInclusao){
                        $dsEspecie = getDsEspecie($voContrato);                        
                    ?>
                        <INPUT type="text" value="<?php echo(strtoupper($dsEspecie));?>"  class="camporeadonlydestacado" size="30" <?=$readonlyChaves?>>
                        <INPUT type="hidden" id="<?=vocontrato::$nmAtrEspecieContrato?>" name="<?=vocontrato::$nmAtrEspecieContrato?>"  value="<?=$voContrato->especie;?>">
                    <?php
                    }else{                        
                        $combo = new select($dominioEspeciesContrato->colecao);                        
                        //cria o combo
                        echo $combo->getHtmlComObrigatorio(vocontrato::$nmAtrCdEspecieContrato,voContrato::$nmAtrCdEspecieContrato, "", false, true);
                    }
                    ?>                        
                    Ordem: <INPUT type="text" id="<?=vocontrato::$nmAtrSqEspecieContrato?>" name="<?=vocontrato::$nmAtrSqEspecieContrato?>" value="<?=$voContrato->sqEspecie;?>"  class="camponaoobrigatorio" size="3" maxlength=2 >
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Gestor:</TH>
            <TD class="campoformulario" colspan="3">
                 <?php
                include_once(caminho_vos . "dbgestor.php");
                $dbgestor = new dbgestor(null);
                $recordSet = $dbgestor->consultarSelect();
                $gestorSelect = new select(array());                                
                $gestorSelect->getRecordSetComoColecaoSelect(vogestor::$nmAtrCd, vogestor::$nmAtrDescricao, $recordSet);
                echo $gestorSelect->getHtmlCombo($idCampoGestor, $idCampoGestor, $voContrato->cdGestor, true, "camponaoobrigatorio", true, " onChange=carregaGestorPessoa();");                               
                ?>
                <!--<INPUT type="text" id="<?=vocontrato::$nmAtrCdGestorContrato?>" name="<?=vocontrato::$nmAtrCdGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="camponaoobrigatorio" size="50"></TD>-->
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Responsável:</TH>
            <TD class="campoformulario" colspan="3">
                <div id="<?=$idDiv?>">
                <?php
                 include_once(caminho_funcoes. "pessoa/biblioteca_htmlPessoa.php");
                 //echo getComboGestorPessoa(new dbgestorpessoa(), vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $voContrato->cdGestor, $voContrato->cdGestorPessoa);
                 getComboGestorResponsavel(null);
                 ?>
                </div>
                <!--<INPUT type="text" id="<?=vocontrato::$nmAtrGestorPessoaContrato?>" name="<?=vocontrato::$nmAtrGestorPessoaContrato?>"  value="<?php echo($nmGestorPessoa);?>"  class="camponaoobrigatorio" size="50" ></TD>-->
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
            <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="50" ></TD>
            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>"  value="<?php echo($docContratada);?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camponaoobrigatorio" size="20" maxlength="40" ></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Objeto:</TH>
            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>" class="camponaoobrigatorio" ><?php echo($dsObjeto);?></textarea>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($procLic);?>"  class="camponaoobrigatorio" size="50" ></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Modalidade:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrModalidadeContrato?>" name="<?=vocontrato::$nmAtrModalidadeContrato?>"  value="<?php echo($modalidade);?>"  class="camponaoobrigatorio" size="50" ></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Valor Mensal:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrVlMensalContrato?>" name="<?=vocontrato::$nmAtrVlMensalContrato?>"  value="<?php echo($vlMensal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" ></TD>
        </TR>					
		<TR>
            <TH class="campoformulario" nowrap>Valor Global:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrVlGlobalContrato?>" name="<?=vocontrato::$nmAtrVlGlobalContrato?>"  value="<?php echo($vlGlobal);?>"
            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" ></TD>
        </TR>
		
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
            			maxlength="10" > a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo($dtVigenciaFinal);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" >
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Assinatura:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            			value="<?php echo($dtAssinatura);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" >
			</TD>
        </TR>
    	<TR>
               <TH class="campoformulario" nowrap>Data Publicacao:</TH>
               <TD class="campoformulario">
                    	<INPUT type="text" 
                    	       id="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
                    	       name="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
                    			value="<?php echo($dtPublicacao);?>"
                    			onkeyup="formatarCampoData(this, event, false);" 
                    			class="camponaoobrigatorio" 
                    			size="10" 
                    			maxlength="10">
					</TD>
                    <TH class="campoformulario" nowrap>Campo Extenso Data Publicacao:</TH>
                    <TD class="campoformulario">
                    	<INPUT type="text" 
                    			value="<?php echo($dataPublicacao);?>"
                    			class="camporeadonly" 
                    			size="50" 
                    			readonly>
    		</TD>
           </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Proposta:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtProposta?>" 
            	       name="<?=vocontrato::$nmAtrDtProposta?>" 
            			value="<?php echo($dtProposta);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camponaoobrigatorio" 
            			size="10" 
            			maxlength="10" >
			</TD>
        </TR>                
		<TR>
            <TH class="campoformulario" nowrap>Empenho:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrNumEmpenhoContrato?>" name="<?=vocontrato::$nmAtrNumEmpenhoContrato?>"  value="<?php echo($empenho);?>"  class="camponaoobrigatorio" size="20" ></TD>
        </TR>
		<TR>
			<?php
			include_once("dominioAutorizacao.php");
			$combo = new select(dominioAutorizacao::getColecao());						
			?>
            <TH class="campoformulario" nowrap>Autorização Prévia:</TH>
            <TD class="campoformulario" colspan="3"><?php echo $combo->getHtmlSelect(vocontrato::$nmAtrCdAutorizacaoContrato,vocontrato::$nmAtrCdAutorizacaoContrato, $voContrato->cdAutorizacao, true, "camponaoobrigatorio", true);?>
            <INPUT type="text" id="<?=vocontrato::$nmAtrTipoAutorizacaoContrato?>" name="<?=vocontrato::$nmAtrTipoAutorizacaoContrato?>"  value="<?php echo($tpAutorizacao);?>"  class="camponaoobrigatorio" size="10" ></TD>
        </TR>
        <?php                    
        $combo = new select((new dominioSimNao())->colecao);        
        $comboLicom = $combo->getHtmlComObrigatorio(vocontrato::$nmAtrInLicomContrato,voContrato::$nmAtrInLicomContrato, $licom, false, false);
        ?>        
		<TR>
            <TH class="campoformulario" nowrap>LICON:</TH>
            <TD class="campoformulario" colspan="3"><?php echo utf8_decode($comboLicom);?></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Observação:</TH>
            <TD class="campoformulario" colspan="3">
				<textarea rows=5 cols="80" id="<?=vocontrato::$nmAtrObservacaoContrato?>" name="<?=vocontrato::$nmAtrObservacaoContrato?>" class="camponaoobrigatorio" ><?php echo($obs);?></textarea>  
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
								<?php
								if($funcao == "I" || $funcao == "A"){
								?>
                                    <TD class="botaofuncao"><?=getBotaoConfirmar()?></TD>
								<?php
								}?>
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="button" accesskey="c">Cancelar</button></TD>                                
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
