<?php
require_once ("alerta_demanda_geral.php");

$assunto = "CONTRATOS A VENCER.";

try {
	$filtro = new filtroConsultarContratoConsolidacao(false);
	$filtro->isValidarConsulta = false;
	// $filtro->voPrincipal = $voDemanda;
	$filtro->setaFiltroConsultaSemLimiteRegistro ();

	$filtro->tpVigencia = dominioTpVigencia::$CD_OPCAO_VIGENTES;	
	$filtro->qtdDiasParaVencimento = 120;
	$filtro->cdHistorico = constantes::$CD_NAO;
	$filtro->voPrincipal = new voContratoInfo();
	
	$dbprocesso = new dbContratoInfo();
	$colecao = $dbprocesso->consultarTelaConsultaConsolidacao($filtro );	
	
	$colunasAAcrescentar = null;
	$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Início.Vigência', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
	$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vigência', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
	$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);
	
	exibeAlertaContrato($colecao, false, $assunto, $colunasAAcrescentar);

} catch ( Exception $ex ) {
	$msg = $ex->getMessage ();
	echo $msg;
}

