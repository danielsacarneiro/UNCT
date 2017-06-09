<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbUsuarioInfo extends dbprocesso {
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );		
		$nmTabelaWPUsers = vousuario::getNmTabela ();
		$nmTabelaUsuSetor = voUsuarioSetor::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabelaWPUsers . "." . vousuario::$nmAtrID,
				$nmTabelaWPUsers . "." . vousuario::$nmAtrLogin,
				$nmTabelaWPUsers . "." . vousuario::$nmAtrName,				
				$nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrCdSetor,
				$nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrCdUsuarioInclusao,
				$nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrDhInclusao,
		)
		;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voUsuarioInfo::$nmAtrID;		
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaUsuSetor;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrID;
		
		$vouserwp = new vousuario();
		$vouserwp->id = $vo->id;		
		
		$queryWhere = " WHERE ";
		$queryWhere .= $vouserwp->getValoresWhereSQLChave ( $isHistorico );
		
		$nmTabelaACompararCdUsuario = $nmTabelaUsuSetor;
		return $this->consultarMontandoQueryUsuario($vouserwp, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false);

		//ATENCAO
		//QUANDO PASSAR A USAR A TABELA VOUSUARIOINFO, ALTERAR O METODO PARA O ABAIXO
		//return $this->consultarMontandoQuery ( $vouserwp, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false );		
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico ();
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaWPUsers = vousuario::getNmTabela ();
		$nmTabelaUsuSetor = voUsuarioSetor::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabelaWPUsers . ".*" 
		)
		;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voUsuarioInfo::$nmAtrID;
		$queryJoin .= "\n LEFT JOIN " . $nmTabelaUsuSetor;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrID;				
		
		$vouserwp = new vousuario();
		$nmTabelaACompararCdUsuario = $nmTabelaUsuSetor;
							
		//$filtro->groupby = array($vouserwp::getNmTabelaStatic($isHistorico) . "." . $vouserwp::$nmAtrID);
		$filtro->groupby = $vouserwp->getAtributosComIdentificacaoTabela($vouserwp->getAtributosChavePrimaria(), $isHistorico);
		
		//echo "teste";
		return $this->consultarMontandoQueryUsuarioFiltro($vouserwp, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, false);		
		
		//ATENCAO
		//QUANDO PASSAR A USAR A TABELA VOUSUARIOINFO, ALTERAR O METODO PARA O ABAIXO
		//return parent::consultarMontandoQueryTelaConsulta ( $vouserwp, $filtro, $arrayColunasRetornadas, $queryJoin );
		
	}
	
	function isUsuarioPertenceAoSetor($cdSetor) {
		$retorno = true;
		
		if($cdSetor != null){
			$filtro = new filtroManterUsuario(false);
			$filtro->id = get_current_user_id ();
			$filtro->cdSetor = $cdSetor;
			$colecao = $this->consultarSetorUsuario($filtro);
			$retorno = !isColecaoVazia($colecao);
		}
		
		return $retorno;
		
	}
	
	function consultarSetorUsuario($filtro) {
		$isHistorico = $filtro->isHistorico ();
		$nmTabelaUsuSetor = voUsuarioSetor::getNmTabelaStatic ( false);
	
		/*$arrayColunasRetornadas = array (
				$nmTabelaUsuSetor . ".*"
		)
		;*/
		
		$querySelect = " SELECT * ";
		$queryFrom = " FROM " . $nmTabelaUsuSetor;
				
		$filtro->nmEntidadePrincipal = $nmTabelaUsuSetor;
				
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	function incluirColecaoUsuarioSetor($vo) {
		$colecao = $vo->colecaoSetor;
		if(!isColecaoVazia($colecao)){
			foreach ($colecao as $cdSetor) {
				$voUsuarioSetor = new voUsuarioSetor();
				$voUsuarioSetor->cdSetor = $cdSetor; 
				$voUsuarioSetor->id = $vo->id;
				$voUsuarioSetor->dbprocesso->cDb = $this->cDb;
				$voUsuarioSetor->dbprocesso->incluir($voUsuarioSetor);
			}
		}
	}
	
	function excluirSetores($vo) {
		$nmTabela = voUsuarioSetor::getNmTabelaStatic ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . voUsuarioSetor::$nmAtrID . " = " . $vo->id;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	
	function alterar($vo) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->excluirSetores($vo);
			$this->incluirColecaoUsuarioSetor($vo);

			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
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