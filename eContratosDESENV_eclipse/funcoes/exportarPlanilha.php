<!DOCTYPE html>
<HTML>

<?php 
include_once("../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_lib. "filtroManter.php");
include_once(caminho_vos . "dbcontrato.php");

try{
	inicio();

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"nome_arquivo.xls\"" );
header ("Content-Description: PHP Generated Data" );
	
	$nmFiltroExportar = @$_GET [filtroManter::$ID_REQ_NmFiltroExportarPlanilha];
	$filtro = getObjetoSessao($nmFiltroExportar);	
	$colecaoPlanilha = $filtro->consultarExportarPlanilha();
				
	$titulo = "EXPORTAR PLANILHA";
?>
<head>
 <style type="text/css">
 TABLE.conteiner {
	height: 98%;
	width: 100%;
	text-align: left;
	border-width: 0;
	vertical-align: top;
}

TABLE.conteiner table {
	border-collapse: collapse
}

TABLE.tabeladados {
	width: 100%;
	background-color: #ffffff;
	text-align: left;
	vertical-align: top;
	border-collapse: collapse;
}

TH.headertabeladados,TH.headertabeladadosalinhadodireita,TH.headertabeladadosalinhadocentro
	{	
	font-weight: bold;
	color: #fff;
	text-shadow: 0 1px 1px #194b7e;
	background: #006CA9;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	text-align: top;
	padding: 0.2em 4px;
	border: 1px solid #FFF;
	border-collapse: collapse;
}
	TH.headertabeladados,TH.headertabeladadosalinhadodireita,TH.headertabeladadosalinhadocentro {
		text-shadow: none;
		color: #000;
		background: #e5e8f0;
		border: solid 1px #777;
	}

TD.conteinertabeladados {
	height: 100%;
	vertical-align: top;
	background-color: #F8F8F8;
}

TD.tabeladados,TD.tabeladadosalinhadodireita,TD.tabeladadosalinhadocentro,TD.tabeladadosdestacado,TD.tabeladadosdestacadoamarelo, TD.tabeladadosdestacadoverde,TD.tabeladadosdestacadovermelho,TD.tabeladadosdestacadoazulclaro,TD.tabeladadosdestacadonegrito, TD.tabeladadosdestacadomarrom
	{
	color: #222;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 13px;
	/*font-weight: bolder;*/
	border-right: 1px solid #D3D3D5;
	border-bottom: 1px solid #D3D3D5;
	padding: 4px;
	vertical-align: top;
	color: #000;
	border: 1px solid #aaa;	
}
  
 </style>
</head>
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
	<TR>
       <TD class="conteinertabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                <?php
                $arrayAtributos = $filtro->getArrayColunasExportarPlanilha();
                $colecao = $colecaoPlanilha;
                
                $tamAtributos = sizeof($arrayAtributos);
                for ($j=0;$j<$tamAtributos;$j++) {
                	$colunaPlanilhaAtual = $arrayAtributos[$j];
                	//$colunaPlanilhaAtual = new colunaPlanilha();
                	
                	$tituloAtributo = $colunaPlanilhaAtual->titulo;
				?>                
                    <TH class="headertabeladados" width="1%"><?=$tituloAtributo?></TH>
                <?php								
                }
				?>                                    
                </TR>
                <?php								
                for ($i=0;$i<sizeof($colecao);$i++) {
                	$registro = $colecao[$i];                                        
                ?>
                <TR>
	                <?php								
	                for ($k=0;$k<$tamAtributos;$k++) { 
	                	$colunaPlanilhaAtual = $arrayAtributos[$k];
	                	//$colunaPlanilhaAtual = new colunaPlanilha();	                	
	                	$valorAtributo = $colunaPlanilhaAtual->getValorCampoRegistro($registro);
	                ?>                
                    <TD class="tabeladados"><?=$valorAtributo?></TD>
	                <?php								
	                }	                ?>
                </TR>					
                <?php
				}				
                ?>
            </TBODY>
            <SCRIPT language="JavaScript" type="text/javascript">
				window.close();
			</script>
        </TABLE>
       </TD>
       </TR>
    </TBODY>
</TABLE>
</FORM>

</HTML>
<?php 
}catch(Exception $ex){
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex, $vo, "mensagemErro.php");	
}
?>
