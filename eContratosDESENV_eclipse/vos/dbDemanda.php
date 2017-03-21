<?php
include_once(caminho_lib. "dbprocesso.obj.php");

Class dbDemanda extends dbprocesso{

	function consultarTelaConsulta($vo, $filtro){
		$isHistorico = $filtro->isHistorico;
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		/*$nmTabelaTramitacao = voTramitacao::getNmTabela();
		$nmTabelaContrato = vocontrato::getNmTabela();
		$nmTabelaPessoa = vopessoa::getNmTabela();*/
		$nmTabelaUsuario = vousuario::getNmTabela();
	
		$querySelect = "SELECT ";
		$querySelect .= $nmTabela.".*";
		//$querySelect .= "," . $nmTabelaTramitacao.".*";
		$querySelect .= "," . $nmTabelaUsuario . "." . vousuario::$nmAtrName;
		$querySelect .= "  AS " . voDemanda::$nmAtrNmUsuarioInclusao;
		$queryFrom = " FROM ".$nmTabela;
	
		/*$queryFrom.= "\n INNER JOIN ". $nmTabelaTramitacao;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrSq. "=".$nmTabelaTramitacao . "." . voTramitacao::$nmAtrSq;
		$queryFrom.= "\n INNER JOIN ". $nmTabelaContrato;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrAnoContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
		$queryFrom.= "\n AND ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrTipoContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
		$queryFrom.= "\n AND ";
		$queryFrom.= $nmTabela. ".".voContratoTramitacao::$nmAtrCdContrato. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
		$queryFrom.= "\n INNER JOIN ". $nmTabelaPessoa;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaPessoa. ".".vopessoa::$nmAtrCd. "=".$nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;*/
		$queryFrom.= "\n INNER JOIN ". $nmTabelaUsuario;
		$queryFrom.= "\n ON ";
		$queryFrom.= $nmTabelaUsuario. ".".vousuario::$nmAtrID. "=".$nmTabela . "." . voDemanda::$nmAtrCdUsuarioInclusao;	
		
		//echo $query;
		return parent::consultarTelaConsulta($filtro, $querySelect, $queryFrom);
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
		$retorno.= $this-> getVarComoString($vo->texto);

		$retorno.= $vo->getSQLValuesInsertEntidade();

		return $retorno;
	}
	 
}
?>