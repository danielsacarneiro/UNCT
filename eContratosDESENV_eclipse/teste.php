<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."bibliotecaDataHora.php");

//inicia os parametros
inicio();

$titulo = "TESTE de Fun��es UNCT";
setCabecalho($titulo);

$vo = new voMensageria();

/*$filtro = getFiltroContratosAVencer(constantes::$CD_NAO);
$log .= "<br>In�cio de verifica��o dos contratos a vencer que gerar�o alertas - (". $filtro->qtdDiasParaVencimento . ") dias para o vencimento.";

$dbprocesso = new dbContratoInfo();
$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro);*/
$COUNT=0;
ECHO getMensagemDemandaIniciais($COUNT);

//var_dump($colecao);

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>

</HEAD>
<?=setTituloPagina($titulo)?>
<BODY CLASS="paginadados">
	<FORM name="frm_principal" method="post">
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
    			<TR>
          			<TD class="conteinerconteudodados">
					<TABLE id="table_conteudodados" class="conteudodados" cellpadding="0" cellspacing="0">
					<TR>
				        <?php
							$vodemanda = new voDemanda();
				        	//INCLUSAO
				        	$comboTipoEditado = new select(dominioTipoDemanda::getColecaoTipoDemanda(false));
				        	//var_dump($comboTipoEditado->colecao);
						  ?>			            
				        <TR>
				            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
				            <TD class="campoformulario">				            
				            <?php echo "Tipo: " . $comboTipoEditado->getHtmlCombo(voDemanda::$nmAtrTipo,voDemanda::$nmAtrTipo, "", true, "campoobrigatorio", false, " required onChange=\"formataFormTpDemanda('".voDemanda::$nmAtrTipo."', 'teste');\"");?>			  
				        </TR>					
		                <TH class="campoformulario" nowrap width=1% colspan=2>
							<?php
							echo dominioTipoDemanda::getHtmlChecksBox("teste", "4", dominioTipoDemanda::getColecaoTipoDemandaContratoValido(), 2, true);
							?>
							<input type="text" value = "" class="campoobrigatorioalinhadodireita">
						</TH>
		            </TR>							

					</TABLE>
            		</TD>
        		</TR>        			
    			</TBODY>
			</TABLE>
		</FORM>
</BODY>
</HTML>