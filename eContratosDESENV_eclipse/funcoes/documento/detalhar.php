<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_util."dominioSetor.php");
include_once(caminho_vos."dbDocumento.php");

//inicia os parametros
inicio();

$vo = new voDocumento();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);
$vo->getDadosBanco($colecao);
putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";

$titulo = $vo::getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
	$isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);

?>

<!DOCTYPE html>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
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
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

/*function mostrarpasta(){
	pasta= document.frm_principal.<?=voDocumento::$nmAtrLink?>.value;	
	//pasta = "c:"; 
    url = "../abrir_windowsexplorer.php?comando=" + pasta;
    //abrirJanelaAuxiliar(url, true, false, false);    

    window.open(url,'_blank');
    
}*/

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
        <TR>
            <TD class="conteinerfiltro">
            <DIV id="div_filtro" class="div_filtro">
            <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
            <TBODY>
	        <?php
	            $selectExercicio = new selectExercicio();
	            $domSetor = new dominioSetor();
	            $comboSetor = new select($domSetor->colecao);
	            $domTp = new dominioTpDocumento();
	            $comboTp= new select($domTp->colecao);
	             
			  ?>			            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
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
                <TD class="campoformulario"><INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS);?>"  class="camporeadonly" size="7" <?=$readonlyChaves?>></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Endereço:</TH>
                <?php
                	$endereco = $vo->getEnderecoTpDocumento();
                ?>                
                <TD class="campoformulario" colspan=3><textarea id="<?=voDocumento::$nmAtrLink?>" name="<?=voDocumento::$nmAtrLink?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $endereco;?></textarea>
                <?php 
                echo getBotaoAbrirDocumento(voDocumento::$nmAtrLink);
				?>
                </TD>
            </TR>	        
            <?php 
	            echo incluirUsuarioDataHoraDetalhamento($vo);	        	
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