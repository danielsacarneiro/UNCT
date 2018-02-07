<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."bibliotecaDataHora.php");

//inicia os parametros
inicio();

$titulo = "TESTE de Funções UNCT";
setCabecalho($titulo);

$dtinical = "25/01/2018";
$qtddias = 10;

echo "resultado " . somarOuSubtrairDiasUteisNaData($dtinical, $qtddias, "+");

/*$dbpenalidade = new dbPenalidadePA();
$filtro = new filtroManterPenalidade();
$filtro->voPrincipal = new voPenalidadePA();
$filtro->isValidarConsulta = false;
$filtro->inDesativado = "N";
$filtro->anoPA = 2017;
$filtro->cdPA = 3;
$colecao = $dbpenalidade->consultarPenalidadeTelaConsulta(new voPenalidadePA(), $filtro);
echoo("imprimindo");
var_dump($colecao);*/

$unidadeEstudo = 2; //2 horas
$chaveMateria = "cd";
$chavePeso = "peso";

$array = array(
		array( $chaveMateria => 'A4', $chavePeso => 4 ),
		array( $chaveMateria => 'B3', $chavePeso => 3 ),
		array( $chaveMateria => 'C5', $chavePeso => 5 ),
		array( $chaveMateria => 'D3', $chavePeso => 3 ),
		array( $chaveMateria => 'E2', $chavePeso => 2 ),
		array( $chaveMateria => 'F1', $chavePeso => 1 ),
		array( $chaveMateria => 'G1', $chavePeso => 1 ),
);

// Compara se $a é maior que $b
function ordenarMaior($a, $b) {
	return $a["peso"] < $b["peso"];
}

function ordenarMenor($a, $b) {
	return $a["peso"] > $b["peso"];
}

print_r( $array );
// Ordena
usort($array, 'ordenarMaior');

// Mostra os valores
/*echo '<pre>';
print_r( $array );
echo '</pre>';*/

echo imprimeOrdem($array, 5);

function imprimeOrdem($colecao, $qtdCasas){
	$retorno = "";
	$i=0;
	while(count($colecao) != 0 && $i<$qtdCasas){			
			/*if($i%2==0){
				usort($colecao, 'ordenarMaior');				
			}else{
				usort($colecao, 'ordenarMenor');
			}*/
			
			$retorno .= $colecao[0]["cd"] . ",";
			array_shift($colecao);
			//unset($colecao[$i]);
			$i++;		
	}
	return $retorno;
}

function getMateriaReOrdenando(&$colecao){
	if($colecao == null){
		throw new excecaoGenerica("colecao vazia");
	}	
}

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
				        	$comboTipoEditado = new select(dominioTipoDemanda::getColecaoTipoDemanda());
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