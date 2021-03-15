<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbUsuarioInfo extends dbprocesso {
	static $FLAG_PRINTAR_SQL = false;
	
	function consultarPorChaveTela($vo, $isHistorico) {
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );		
		$nmTabelaWPUsers = vousuario::getNmTabela ();
		$nmTabelaUsuInfo = voUsuarioInfo::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabelaWPUsers . "." . vousuario::$nmAtrID,
				$nmTabelaWPUsers . "." . vousuario::$nmAtrLogin,
				$nmTabelaWPUsers . "." . vousuario::$nmAtrName,				
/*				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrSetor,
				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrInCaracteristicas,
				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrCdUsuarioInclusao,
				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrCdUsuarioUltAlteracao,
				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrDhInclusao,
				$nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrDhUltAlteracao,	*/			
				$nmTabelaUsuInfo . ".*",
		)
		;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabela . "." . voUsuarioInfo::$nmAtrID;		
		
		$vouserwp = new vousuario();
		$vouserwp->id = $vo->id;		
		
		$queryWhere = " WHERE ";
		$queryWhere .= $vouserwp->getValoresWhereSQLChave ( $isHistorico );
		
		$nmTabelaACompararCdUsuario = $nmTabelaUsuInfo;
		return $this->consultarMontandoQueryUsuario($vouserwp, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false);

		//ATENCAO
		//QUANDO PASSAR A USAR A TABELA VOUSUARIOINFO, ALTERAR O METODO PARA O ABAIXO
		//return $this->consultarMontandoQuery ( $vouserwp, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false );
		//return $this->consultarPorChaveMontandoQuery ( $vouserwp, $arrayColunasRetornadas, $queryJoin, $isHistorico );
		
	}
	function consultarTelaConsulta($vo, $filtro) {
		$isHistorico = $filtro->isHistorico ();
		$nmTabela = $vo::getNmTabelaStatic ( $isHistorico );
		$nmTabelaWPUsers = vousuario::getNmTabela ();
		$nmTabelaUsuInfo = voUsuarioInfo::getNmTabela ();
		
		$arrayColunasRetornadas = array (
				$nmTabelaWPUsers . ".*" 
		)
		;
		
		$queryJoin .= "\n LEFT JOIN " . $nmTabela;
		$queryJoin .= "\n ON ";
		$queryJoin .= $nmTabelaWPUsers . "." . voUsuarioInfo::$nmAtrID . "=" . $nmTabela . "." . voUsuarioInfo::$nmAtrID;

		$queryJoin .= constantes::$CD_CAMPO_SUBSTITUICAO;
		
		if(!$filtro->temJoinFiltroASubstituir()){
			$queryJoin = str_replace(constantes::$CD_CAMPO_SUBSTITUICAO, "", $queryJoin);
		}
		
		$vouserwp = new vousuario();
		$nmTabelaACompararCdUsuario = $nmTabelaUsuInfo;
							
		//$filtro->groupby = array($vouserwp::getNmTabelaStatic($isHistorico) . "." . $vouserwp::$nmAtrID);
		$filtro->groupby = $vouserwp->getAtributosComIdentificacaoTabela($vouserwp->getAtributosChavePrimaria(), $isHistorico);
		
		//ATENCAO
		//QUANDO PASSAR A USAR A TABELA VOUSUARIOINFO, ALTERAR O METODO PARA O ABAIXO
		//return parent::consultarMontandoQueryTelaConsulta ( $vouserwp, $filtro, $arrayColunasRetornadas, $queryJoin );
		return parent::consultarMontandoQueryUsuarioFiltro ( $vouserwp, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $filtro, false );
		
	}
	
	/**
	 * verifica se o usuario está associado ao setor
	 * @param unknown $cdSetor
	 * @return boolean
	 */
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
		$nmTabelaUsuInfo = voUsuarioInfo::getNmTabelaStatic ( false);
		$nmTabelaWPUsers = vousuario::getNmTabelaStatic ( false);
			
		$querySelect = " SELECT * ";
		$queryFrom = " FROM " . $nmTabelaUsuInfo;
		$queryFrom .= " INNER JOIN " . $nmTabelaWPUsers;
		$queryFrom .= "\n ON ";
		$queryFrom .= $nmTabelaUsuInfo . "." . voUsuarioInfo::$nmAtrID . "=" . $nmTabelaWPUsers . "." . vousuario::$nmAtrID;
						
		$filtro->nmEntidadePrincipal = $nmTabelaUsuInfo;
				
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	/**
	 * metodo alterar funciona distinto dos outros VO´s 
	 * porque os usuarios sao mantidos pelo wordpress
	 * o econti apenas inclui novas informacoes ao usuario ja incluido, no caso apenas os setores
	 * permite incluir novas informacoes no futuro, mas NAO EXCLUI USUARIO, apenas mantem as novas informacoes do econti
	 * {@inheritDoc}
	 * @see dbprocesso::alterar()
	 */
	function alterar($vo) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			try {
				//primeiro tenta incluir o usuario na tabela de usuario_info
				//nem sempre existira, so existirá se algum dia alguem associou algum setor a ele
				$this->incluir($vo);
			} catch ( Exception $e ) {
				//se ja existir, tenta alterar
				parent::alterar($vo);
			}
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
		$retorno = "";
		$sqlConector = "";
	
		$retorno .= $sqlConector . voUsuarioInfo::$nmAtrSetor . " = " . $this->getVarComoString ( $vo->setor );
		$sqlConector = ",";		
		
		$retorno .= $sqlConector . voUsuarioInfo::$nmAtrInCaracteristicas . " = " . $this->getVarComoString ( $vo->inCaracteristicas );
		$sqlConector = ",";		
	
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
	
		return $retorno;
	}
}
?>