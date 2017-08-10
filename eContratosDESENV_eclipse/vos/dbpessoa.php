<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_vos . "vopessoa.php");
include_once (caminho_vos . "vousuario.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
include_once (caminho_vos . "dbgestor.php");
include_once (caminho_vos . "dbpessoavinculo.php");
include_once (caminho_vos . "dbpessoagestor.php");

// .................................................................................................................
// Classe select
// cria um combo select html
class dbpessoa extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		
		$arrayColunasRetornadas = array($nmTabela . ".*",
				vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd,
				vogestor::getNmTabela () . "." . vogestor::$nmAtrCd,
				vogestor::getNmTabela () . "." . vogestor::$nmAtrDescricao
		);		

		$queryJoin .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryJoin .= "\n ON ";
		$queryJoin .= vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa . "=" . $nmTabela . "." . vopessoa::$nmAtrCd;
		
		$queryJoin .= "\n LEFT JOIN " . vopessoagestor::getNmTabela ();
		$queryJoin .= "\n ON ";
		$queryJoin .= vopessoagestor::getNmTabela () . "." . vopessoagestor::$nmAtrCdPessoa . "=" . $nmTabela . "." . vopessoa::$nmAtrCd;
		$queryJoin .= "\n LEFT JOIN " . vogestor::getNmTabela ();
		$queryJoin .= "\n ON ";
		$queryJoin .= vogestor::getNmTabela () . "." . vogestor::$nmAtrCd . "=" . vopessoagestor::getNmTabela () . "." . vopessoagestor::$nmAtrCdGestor;
		
		$recordset = $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, false );		
		
		$colecaoColunasATransformar = array (
				vopessoavinculo::$nmAtrCd,
				vogestor::$nmAtrDescricao 
		);
		
		$retorno = $this->getEntidadePorChavePrimariaComValoresDiversosEmColunas ( $recordset, $colecaoColunasATransformar );
		
		return $retorno;
	}
	
	/**
	 *
	 * @param unknown $voentidade        	
	 * @param unknown $filtro        	
	 * @return string
	 * @deprecated
	 *
	 */
	function consultarPessoa($voentidade, $filtro) {
		return $this->consultarPessoaManter ( $filtro, true );
	}	
	function consultarPessoaContratoFiltro($filtro) {
		$nmTabelaContrato = vocontrato::getNmTabela ();
		$nmTabela = vopessoa::getNmTabela ();
		$nmTabelaPessoaVinculo = vopessoavinculo::getNmTabela ();
	
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDoc;
		$atributosConsulta .= "," . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCd;
		//$atributosConsulta .= "," . $nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato;
	
		$querySelect = "SELECT " . $atributosConsulta;
	
		$queryFrom = "\n FROM " . $nmTabela;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaVinculo;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCdPessoa;
	
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		// echo $querySelect."<br>";
		// echo $queryFrom;
	
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function consultarPessoaManter($filtro, $validarConsulta) {		
		$nmTabela = vopessoa::getNmTabelaStatic($filtro->isHistorico());
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDoc;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrEmail;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrTel;
		$atributosConsulta .= "," . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd;
		
		if($filtro->isHistorico()){
			$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrSqHist;
		}		
		
		$nmTabelaContrato = vocontrato::getNmTabela ();		
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabela;
		$queryFrom .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
		// echo $querySelect."<br>";
		// echo $queryFrom;
		// $filtro = new filtroManterPessoa();
		$filtro->groupby = $atributosConsulta;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, $validarConsulta );
	}
	function consultarPessoaPorContrato($filtro) {
		$atributosConsulta = vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrDoc;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrEmail;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrTel;
		$atributosConsulta .= "," . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd;
		$atributosConsulta .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrSqContrato;
		
		// $atributoVinculo = "(SELECT )"
		
		$querySelect = "SELECT DISTINCT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . vopessoa::getNmTabela ();
		$queryFrom .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryFrom .= "\n ON " . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa;
		$queryFrom .= "\n INNER JOIN " . vocontrato::getNmTabela ();
		$queryFrom .= "\n ON ";
		$queryFrom .= vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd . "=" . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	public function consultarPessoaPorGestor($cdGestor) {
		$vo = new vopessoa ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "SELECT * FROM " . $nmTabela;
		
		if ($cdGestor != null)
			$query .= " WHERE " . vogestor::$nmAtrCd . "=" . $cdGestor;
			
			// echo $query;
		return $this->consultarEntidade ( $query, false );
	}
	public function consultarGestorPorParam($cdGestor) {
		$vo = new vogestor ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "SELECT * FROM " . $nmTabela;
		
		if ($cdGestor != null)
			$query .= " WHERE " . vogestor::$nmAtrDescricao . " LIKE '%" . $cdGestor . "%'";
			
			// echo $query;
		return $this->consultarEntidade ( $query, false );
	}
	
	// o incluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function incluir($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$vopessoa = $this->incluirPessoa ( $vopessoa );
			// echo "<br>incluiu pessoa:" . var_dump($vopessoa);
			$this->incluirPessoaVinculo ( $vopessoa );
			$this->incluirPessoaGestor ( $vopessoa );
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
	function incluirPessoaVinculo($vopessoa) {
		$vopvinculo = new vopessoavinculo ();
		$vopvinculo->cd = $vopessoa->cdVinculo;
		$vopvinculo->cdPessoa = $vopessoa->cd;
		$dbpvinculo = new dbpessoavinculo ();
		$dbpvinculo->cDb = $this->cDb;
		$dbpvinculo->incluir ( $vopvinculo );
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopvinculo);
	}
	function excluirPessoaVinculo($vopessoa) {
		$vo = new vopessoavinculo ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopessoavinculo::$nmAtrCdPessoa . " = " . $vopessoa->cd;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function incluirPessoaGestor($vopessoa) {
		$cdGestor = $vopessoa->cdGestor;
		if ($cdGestor != null) {
			$vopgestor = new vopessoagestor ();
			$vopgestor->cdGestor = $cdGestor;
			$vopgestor->cdPessoa = $vopessoa->cd;
			$dbpgestor = new dbpessoagestor ();
			$dbpgestor->cDb = $this->cDb;
			$dbpgestor->incluir ( $vopgestor );
		}
	}
	function excluirPessoaGestor($vopessoa) {
		$vo = new vopessoagestor ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopessoagestor::$nmAtrCdPessoa . " = " . $vopessoa->cd;
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function incluirPessoa($vopessoa) {
		$vopessoa->cd = $this->getProximoSequencial ( vopessoa::$nmAtrCd, $vopessoa );
		
		$arrayAtribRemover = array (
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao 
		);
		
		$query = $this->incluirQuery ( $vopessoa, $arrayAtribRemover );
		$retorno = $this->cDb->atualizar ( $query );
		
		return $vopessoa;
	}
	
	// o alterar eh implementado para nao usar da voentidade
	// por ser mais complexo
	function alterar($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$this->excluirPessoaVinculo ( $vopessoa );
			$this->incluirPessoaVinculo ( $vopessoa );
			
			$this->excluirPessoaGestor ( $vopessoa );
			$this->incluirPessoaGestor ( $vopessoa );
			
			$vopessoa = parent::alterar ( $vopessoa );
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
	
	// o excluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function excluir($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal($vopessoa);
			if($permiteExcluirPrincipal){
				//echo "excluiu";
				$this->excluirPessoaVinculo ( $vopessoa );
				$this->excluirPessoaGestor ( $vopessoa );			
			}
			
			$vopessoa = parent::excluir ( $vopessoa );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
	function getSQLValuesInsert($vopessoa) {
		$retorno = "";
		// $retorno.= $this-> getProximoSequencial(vopessoa::$nmAtrCd, $vopessoa) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoa->cd ) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoa->id ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->nome ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->doc ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->tel ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->email ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->endereco ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->obs );
		
		$retorno .= $vopessoa->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->id != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrIdUser . " = " . $this->getVarComoNumero ( $vo->id );
			$sqlConector = ",";
		}
		
		if ($vo->nome != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrNome . " = " . $this->getVarComoString ( $vo->nome );
			$sqlConector = ",";
		}
		
		if ($vo->doc != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrDoc . " = " . $this->getVarComoString ( $vo->doc );
			$sqlConector = ",";
		}
		
		if ($vo->email != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrEmail . " = " . $this->getVarComoString ( $vo->email );
			$sqlConector = ",";
		}
		
		if ($vo->tel != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrTel . " = " . $this->getVarComoString ( $vo->tel );
			$sqlConector = ",";
		}
		
		if ($vo->endereco != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrEndereco . " = " . $this->getVarComoString ( $vo->endereco );
			$sqlConector = ",";
		}
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrObservacao . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
	
	/**
	 * FUNCOES DE IMPORTACAO EXCLUSIVA
	 */
	function importar($linha) {
		$vo = new vopessoa ();
		
		$atributosInsert = $vo->getTodosAtributos ();
		$arrayAtribRemover = array (
				vopessoa::$nmAtrCd,
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao,
				vopessoa::$nmAtrCdUsuarioInclusao,
				vopessoa::$nmAtrCdUsuarioUltAlteracao 
		);
		// var_dump($arrayAtribRemover);
		$atributosInsert = removeColecaoAtributos ( $atributosInsert, $arrayAtribRemover );
		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, "," );
		
		$query = " INSERT INTO " . $vo->getNmTabela () . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .= ") ";
		
		$query .= " \nVALUES(";
		$query .= $this->getAtributosInsertImportacaoPlanilha ( $linha );
		$query .= ")";
		
		// echo $query;
		$retorno = $this->cDb->atualizarImportacao ( $query );
		return $retorno;
	}
	function getAtributosInsertImportacaoPlanilha($linha) {
		$nome = $linha ["B"];
		$tel = $linha ["D"];
		$email = $linha ["E"];
		$doc = null;
		$id = null;
		$endereco = null;
		
		// CUIDADO COM A ORDEM
		// DEVE ESTAR IGUAL A getAtributosFilho()
		$retorno = "";
		// $retorno.= $this-> getVarComoNumero($cd) . ",";
		$retorno .= $this->getVarComoNumero ( $id ) . ",";
		$retorno .= $this->getVarComoString ( $nome ) . ",";
		$retorno .= $this->getVarComoString ( $doc ) . ",";
		$retorno .= $this->getVarComoString ( $tel ) . ",";
		$retorno .= $this->getVarComoString ( $email ) . ",";
		$retorno .= $this->getVarComoString ( $endereco );
		
		return $retorno;
	}
}
?>