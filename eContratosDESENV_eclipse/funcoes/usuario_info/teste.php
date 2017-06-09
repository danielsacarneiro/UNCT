<?php
include_once("../../config_lib.php");
include_once(caminho_util. "select.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
	
inicioComValidacaoUsuario(true);
	
$voUsuario = new voUsuarioInfo();
$filtroUsu = new filtroManterUsuario(false);
$colecaoUsu = $voUsuario->dbprocesso->consultarTelaConsulta($voUsuario, $filtroUsu);

$comboUsuTramitacao = new select($colecaoUsu, voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName);
?>
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Usuário:</TH>
				<TD class="campoformulario" width="1%" colspan=3><?php echo $comboUsuTramitacao->getHtmlSelect(filtroManterDemanda::$NmAtrCdUsuarioTramitacao,filtroManterDemanda::$NmAtrCdUsuarioTramitacao, $filtro->cdUsuarioTramitacao, true, "camponaoobrigatorio", true);?>				
			</TR>
			
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);
}
?>
			