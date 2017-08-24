<?php
include_once("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");

$mensagem = "Encaminhamento para a SAD";
$voDemanda = new voDemanda();
try{
	$filtro = new filtroManterDemanda(false);
	$dbprocesso = $voDemanda->dbprocesso;
	
	$filtro->isValidarConsulta = false;
	//$filtro->voPrincipal = $voDemanda;
	$filtro->setaFiltroConsultaSemLimiteRegistro();
	$filtro->vodemanda->situacao = array(dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA, dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO);
	$filtro->vodemanda->tipo = array_keys(dominioTipoDemanda::getColecaoTipoDemandaSAD());
	$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
	$filtro->vocontrato->cdAutorizacao = array(dominioAutorizacao::$CD_AUTORIZ_SAD);
	$colecao = $dbprocesso->consultarTelaConsulta($voDemanda, $filtro);
	
	if(!isColecaoVazia($colecao)){
		//enviar o email com os registros a serem analisados
		foreach ($colecao as $registro){
			$voAtual = new voDemanda();
			$voAtual->getDadosBanco($registro);				
				?>
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="2" cellspacing="2" BORDER=1>						
             <TBODY>				
				<TR>
				<TD class="tabeladadosalinhadodireita"><?php echo $voAtual->ano;?></TD>
			    <TD class="tabeladadosdestacadonegrito"><?php echo complementarCharAEsquerda($voAtual->cd, "0", TAMANHO_CODIGOS)?></TD>								
			    <TD class="tabeladados" ><?php echo $voAtual->texto;?></TD>
			    </TR>
            </TBODY>
        </TABLE>
			    					
			<?php 	

		}
		
	}
	
	echo "Alerta de $mensagem realizado com sucesso";

}catch(Exception $ex){
	tratarExcecaoHTML($ex, $voDemanda);
}
?>