<?php
include_once("../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
//define a classe do vo que chamou
$class = @$_GET["class"];
include_once(caminho_vos.$class.".php");

//inicio();
inicioComValidacaoUsuario(true);

setCabecalhoPorNivel(null,1);
$funcao = @$_POST["funcao"];
//echo $funcao;
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
$isExclusao = $funcao == constantes::$CD_FUNCAO_EXCLUIR;
$isAlteracao = $funcao == constantes::$CD_FUNCAO_ALTERAR;

$msgErro = "";
$nmFuncao = "";

try{
    
    session_start();
    if(!isset($_SESSION["vo"])){
        throw new Exception("Sessão expirada. Realize nova consulta.");        
    }
    
    $vo = $_SESSION["vo"];
    $dbprocesso = new $class(null);
    //session_destroy();
    
    if($isInclusao){
        $nmFuncao = "INCLUIR";        
        /*$metodo = 'incluir';
        $parametros = array($vo, false); 
        $resultado = call_user_func_array(array( $classe, $metodo), $parametros);*/                
        $resultado = $dbprocesso->incluir($vo);
                
    }else if($isExclusao){
        $nmFuncao = "EXCLUIR";
        $resultado = $dbprocesso->excluir($vo);
        
    }else if($isAlteracao){
        $nmFuncao = "ALTERAR";
        //$resultado = $dbprocesso->alterarContratoPorCima($vo);
        $resultado = $dbprocesso->alterar($vo);
    }
    
    $classMensagem = "campomensagemverde";
    $msg = "OPERACÃO $nmFuncao REALIZADA COM SUCESSO";
    
    putObjetoSessao($vo->getNmTabela(), $vo);
    
}catch(Exception $e) {
    $msgErro = $e->getMessage();
    $classMensagem = "campomensagemvermelho";
    $msg = "OPERACAO $nmFuncao FALHOU.$msgErro";
}


?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPaginaPorNivel(null,1)?>

<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	//history.back().back();
    //window.location.history.go(-2);
    //history.go(-2);
	//location.href="index.php";	
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="<?=$vo->getNmTabela()?>/index.php?consultar=S"> 
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
                    <TH class="<?=$classMensagem?>" nowrap width="1%"><?=$msg?></TH>			
                </TR>
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
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="submit" accesskey="o">OK</button></TD>
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
