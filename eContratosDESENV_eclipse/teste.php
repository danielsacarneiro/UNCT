<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."bibliotecaDataHora.php");

//inicia os parametros
inicio();

$titulo = "TESTE de Funções UNCT";
setCabecalho($titulo);

$filtro = new filtroManterMensageria ( false );
$filtro->isValidarConsulta = false;
$filtro->inHabilitado = constantes::$CD_SIM;
$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
//pega somente os alertas para os contratos que serao prorrogados
$filtro->inSeraProrrogado = constantes::$CD_SIM;
//$filtro->inVerificarFrequencia = constantes::$CD_NAO;
$filtro->inVerificarFrequencia = voMensageria::$IN_VERIFICAR_FREQUENCIA;
$filtro->cdHistorico = 'N';
$filtro->tipo = dominioTipoMensageria::getColecaoTipoAlertaGestor();
//echoo("Verificador de Frequência do email: '$filtro->inVerificarFrequencia'.");
$log .= getLogComFlagImpressao("<br>Verificador de Frequência do email: '$filtro->inVerificarFrequencia'.");

$filtro->setaFiltroConsultaSemLimiteRegistro ();

$dbMensageria = new dbMensageria ();
$colecao = $dbMensageria->consultarTelaConsulta ( new voMensageria (), $filtro );

var_dump($colecao);

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