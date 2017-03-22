<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemanda extends dbprocesso{

	function consultarPorChave($vo, $isHistorico){		
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);		
		$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		
		$arrayColunasRetornadas = array($nmTabela . ".*", 
				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato,
				$nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato
		);
				
		$queryJoin.= "\n LEFT JOIN ". $nmTabelaDemandaContrato;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabelaDemandaContrato. ".".voDemandaContrato::$nmAtrAnoDemanda. "=".$nmTabela . "." . voDemanda::$nmAtrAno;
		$queryJoin.= "\n AND " . $nmTabelaDemandaContrato. ".".voDemandaContrato::$nmAtrCdDemanda. "=".$nmTabela . "." . voDemanda::$nmAtrCd;

		$queryJoin.= "\n LEFT JOIN ". $nmTabelaContrato;
		$queryJoin.= "\n ON ";
		$queryJoin.= $nmTabelaDemandaContrato. ".".voDemandaContrato::$nmAtrSqContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato;
				
		return $this->consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $isHistorico);	
	}
	
	function consultarTelaConsulta($vo, $filtro){
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela();
		
		$colunaUsuHistorico = "";
		
		if($isHistorico){
			$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao; 
		}
		$arrayColunasRetornadas = array($nmTabela . ".*",
				static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
				$nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "  AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
				$colunaUsuHistorico
		);		
			
		$atributosGroup = voDemandaTramitacao::$nmAtrCd 
					. ",". voDemandaTramitacao::$nmAtrAno;
		
		$queryJoin = "";
		$queryJoin .= "\n LEFT JOIN (";
		$queryJoin .= " SELECT MAX("
					. voDemandaTramitacao::$nmAtrSq. ") AS " . voDemandaTramitacao::$nmAtrSq 
					. ",". $atributosGroup 
					. " FROM ". $nmTabelaTramitacao									
					." GROUP BY "
					. $atributosGroup;		
		$queryJoin .= ") TABELA_MAX";
		$queryJoin .= "\n ON ".$nmTabela.".".voDemandaTramitacao::$nmAtrAno." = TABELA_MAX.".voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND ".$nmTabela.".".voDemandaTramitacao::$nmAtrCd." = TABELA_MAX.".voDemandaTramitacao::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN ";
		$queryJoin .= $nmTabelaTramitacao;
		$queryJoin .= "\n ON ".$nmTabelaTramitacao.".".voDemandaTramitacao::$nmAtrAno." = TABELA_MAX.".voDemandaTramitacao::$nmAtrAno;
		$queryJoin .= "\n AND ".$nmTabelaTramitacao.".".voDemandaTramitacao::$nmAtrCd." = TABELA_MAX.".voDemandaTramitacao::$nmAtrCd;
		$queryJoin .= "\n AND ".$nmTabelaTramitacao.".".voDemandaTramitacao::$nmAtrSq." = TABELA_MAX.".voDemandaTramitacao::$nmAtrSq;
		
		//echo $query;
		return parent::consultarMontandoQueryTelaConsulta($vo, $filtro, $arrayColunasRetornadas, $queryJoin);		
	}
	
	function consultarDemandaTramitacao($vo){
		$isHistorico = $filtro->isHistorico;
		$nmTabela = voDemandaTramitacao::getNmTabelaStatic($isHistorico);
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($isHistorico);
		/*$nmTabelaTramitacao = voTramitacao::getNmTabela();
			$nmTabelaContrato = vocontrato::getNmTabela();
			$nmTabelaPessoa = vopessoa::getNmTabela();*/
		$nmTabelaUsuario = vousuario::getNmTabela();
	
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela.".*";

		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM ".$nmTabela;
	
		$queryFrom.= "\n INNER JOIN ". $nmTabelaDemanda;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaDemanda. ".".voDemanda::$nmAtrAno. "=".$nmTabela . "." . voDemandaTramitacao::$nmAtrAno;
		$queryFrom.= "\n AND " . $nmTabelaDemanda. ".".voDemanda::$nmAtrCd. "=".$nmTabela . "." . voDemandaTramitacao::$nmAtrCd;
		
		$queryFrom.= "\n INNER JOIN ". $nmTabelaUsuario;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaUsuario. ".".vousuario::$nmAtrID. "=".$nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;
		
		$filtro = new filtroManterDemanda();
		$filtro->vodemanda = $vo;
		$filtro->TemPaginacao = false;	
		//echo $query;
		return parent::consultarFiltro($filtro, $querySelect, $queryFrom, false);
	}
	
	//o excluir eh implementado para nao usar da voentidade
	//por ser mais complexo
	function excluir($vo){
		//Start transaction
		$this->cDb->retiraAutoCommit();
		try{
			$this->excluirDemandaTramitacao($vo);
			$this->excluirDemandaContrato($vo);
			$vo = parent::excluir($vo);
			//End transaction
			$this->cDb->commit();
		}catch(Exception $e){
			$this->cDb->rollback();
			throw new Exception($e->getMessage());
		}
	
		return $vo;
	}
	
	function excluirDemandaTramitacao($voDemanda){
		$vo = new voDemandaTramitacao();
		
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "DELETE FROM ".$nmTabela;
		$query.= "\n WHERE ". voDemandaTramitacao::$nmAtrAno. " = ". $voDemanda->ano;
		$query.= "\n AND ". voDemandaTramitacao::$nmAtrCd. " = ". $voDemanda->cd;
		//echo $query;
		return $this->atualizarEntidade($query);
	}
	
	function excluirDemandaContrato($voDemanda){
		$vo = new voDemandaContrato();
	
		$nmTabela = $vo->getNmTabelaEntidade(false);
		$query = "DELETE FROM ".$nmTabela;
		$query.= "\n WHERE ". voDemandaContrato::$nmAtrAnoDemanda. " = ". $voDemanda->ano;
		$query.= "\n AND ". voDemandaContrato::$nmAtrCdDemanda. " = ". $voDemanda->cd;
		//echo $query;
		return $this->atualizarEntidade($query);
	}
	
	function incluirSQL($vo){
		if($vo->cd == null || $vo->cd == ""){
			$vo->cd = $this->getProximoSequencialChaveComposta(voDemanda::$nmAtrCd, $vo);
		}
		return $this->incluirQueryVO($vo);
	}
	
	function getSQLValuesInsert($vo){
		$retorno = "";
		$retorno.= $this-> getVarComoNumero($vo->ano) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cd) . ",";
		$retorno.= $this-> getVarComoNumero($vo->tipo) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdSetor) . ",";
		//$retorno.= $this-> getVarComoNumero($vo->situacao);				
		$retorno.= $this-> getVarComoNumero(dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA). ",";
		$retorno.= $this-> getVarComoString($vo->texto). ",";
		$retorno.= $this-> getVarComoNumero($vo->prioridade);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	
	function getSQLValuesUpdate($vo){
		$retorno = "";
		$sqlConector = "";
	
		if($vo->prioridade != null){
			$retorno.= $sqlConector . voDemanda::$nmAtrPrioridade. " = " . $this->getVarComoNumero($vo->prioridade);
			$sqlConector = ",";
		}
		
		if($vo->situacao != null){
			$retorno.= $sqlConector . voDemanda::$nmAtrSituacao. " = " . $this->getVarComoNumero($vo->situacao);
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
	
		return $retorno;
	}	 
}
?>