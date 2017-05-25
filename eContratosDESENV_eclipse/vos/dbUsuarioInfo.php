<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbUsuarioInfo extends dbprocesso {
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*" 
		);
		
		$queryJoin .= "";
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico ();
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaWPUsers = vousuario::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabelaWPUsers . ".*" 
		)
		;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voUsuarioInfo::$nmAtrID;
				
		return parent::consultarMontandoQueryTelaConsulta ( new vousuario(), $filtro, $arrayColunasRetornadas, $queryJoin );
	}
	function getSQLValuesInsert($vo) {
		throw new excecaoGenerica ( "método não implementado" );
		// $vo = new voContratoInfo();
		/*
		 * $retorno = "";
		 * $retorno .= $this->getVarComoNumero ( $vo->id );
		 *
		 * $retorno .= $vo->getSQLValuesInsertEntidade ();
		 *
		 * return $retorno;
		 */
	}
	function getSQLValuesUpdate($vo) {
		throw new excecaoGenerica ( "método não implementado" );
		// $vo = new voContratoInfo();
		/*
		 * $retorno = "";
		 * $sqlConector = "";
		 *
		 * if ($vo->cdAutorizacao != null) {
		 * $retorno .= $sqlConector . voContratoInfo::$nmAtrCdAutorizacaoContrato . " = " . $this->getVarComoString( $vo->cdAutorizacao);
		 * $sqlConector = ",";
		 * }
		 *
		 * $retorno = $retorno . $vo->getSQLValuesEntidadeUpdate ();
		 *
		 * return $retorno;
		 */
	}
}
?>