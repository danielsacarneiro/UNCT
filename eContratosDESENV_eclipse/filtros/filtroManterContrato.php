<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "voentidade.php");
include_once (caminho_lib . "filtroManter.php");
include_once (caminho_util . "bibliotecaHTML.php");
class filtroManterContrato extends filtroManter {
	public static $nmFiltro = "filtroManterContrato";
	public static $nmAtrInTrazerConsolidadoPorVigencia = "nmAtrInTrazerConsolidadoPorVigencia";
		
	var $cdContrato;
	var $anoContrato;
	var $tipo;
	var $especie;
	var $cdEspecie;
	var $modalidade;
	
	var $contratada;
	var $docContratada;
	var $gestor;
	
	var $objeto;
	var $dtVigenciaInicial;
	var $dtVigenciaFinal;
	
	var $dtInicio1;
	var $dtInicio2;
	var $dtFim1;
	var $dtFim2;
	var $dtInclusao;
	
	var $cdConsultarArquivo;
	var $inTrazerConsolidadoVigencia;
	
	// ...............................................................
	// construtor
	
	function __construct1($pegarFiltrosDaTela) {
		parent::__construct1($pegarFiltrosDaTela);
	
		if($pegarFiltrosDaTela){
			$this->getFiltroFormulario();		
		}	
	}
	
	function getFiltroFormulario(){
		$this->cdContrato = @$_POST [vocontrato::$nmAtrCdContrato];
		$this->anoContrato = @$_POST [vocontrato::$nmAtrAnoContrato];
		$this->tipo = @$_POST [vocontrato::$nmAtrTipoContrato];
		$this->especie = @$_POST [vocontrato::$nmAtrEspecieContrato];
		$this->cdEspecie = @$_POST [vocontrato::$nmAtrCdEspecieContrato];
		$this->modalidade = @$_POST [vocontrato::$nmAtrModalidadeContrato];
		
		$this->contratada = @$_POST [vocontrato::$nmAtrContratadaContrato];
		$this->docContratada = @$_POST [vocontrato::$nmAtrDocContratadaContrato];
		$this->gestor = @$_POST [vocontrato::$nmAtrGestorContrato];
		
		$this->objeto = @$_POST [vocontrato::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = @$_POST [vocontrato::$nmAtrDtVigenciaInicialContrato];
		$this->dtVigenciaFinal = @$_POST [vocontrato::$nmAtrDtVigenciaFinalContrato];
		
		$this->dtInicio1 = @$_POST ["dtInicio1"];
		$this->dtInicio2 = @$_POST ["dtInicio2"];
		$this->dtFim1 = @$_POST ["dtFim1"];
		$this->dtFim2 = @$_POST ["dtFim2"];
		$this->dtInclusao = @$_POST [voentidade::$nmAtrDhInclusao];
		
		$this->cdConsultarArquivo = @$_POST ["cdConsultarArquivo"];
		$this->inTrazerConsolidadoVigencia = @$_POST [self::$nmAtrInTrazerConsolidadoPorVigencia];		
	}
		
	function isSetaValorDefault() {
		$retorno = false;
		// verifica os filtros obrigatorios
		if (! isUsuarioAdmin() && $this->contratada == null && $this->docContratada == null && $this->anoContrato == null 
				&& $this->dtVigenciaInicial == null && $this->dtVigenciaFinal == null && $this->objeto == null && $this->dtVigencia == null) {
			$retorno = true;
			$this->temValorDefaultSetado = true;			
		}		
		return $retorno;		
	}
	function getFiltroConsultaSQL() {
		$filtro = "";
		$conector = "";
		$voContrato = new vocontrato ();
		
		/*
		 * $nmTabela = vocontrato::getNmTabela();
		 * if($isHistorico)
		 * $nmTabela = vocontrato::getNmTabelaHistorico();
		 */
		$isHistorico = $this->isHistorico;
		$nmTabela = $voContrato->getNmTabelaEntidade ( $this->isHistorico );
		
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			$this->anoContrato = anoDefault;
		}
		
		if ($this->cdContrato != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $this->cdContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->anoContrato != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $this->anoContrato;
			
			$conector = "\n AND ";
		}
		
		if ($this->tipo != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "='" . $this->tipo . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->modalidade != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrModalidadeContrato . " LIKE '%" . utf8_encode ( $this->modalidade ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->especie != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrEspecieContrato . " LIKE '%" . utf8_encode ( $this->especie ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->cdEspecie != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrCdEspecieContrato . " = '" . $this->cdEspecie . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->contratada != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrContratadaContrato . " LIKE '%" . utf8_encode ( $this->contratada ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->docContratada != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDocContratadaContrato . "='" . $this->docContratada . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->gestor != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrGestorContrato . " LIKE '%" . utf8_encode ( $this->gestor ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->objeto != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrObjetoContrato . " LIKE '%" . utf8_encode ( $this->objeto ) . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->dtVigenciaInicial != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtVigenciaInicialContrato . ">='" . getDataSQL ( $this->dtVigenciaInicial ) . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->dtVigenciaFinal != null) {
			$filtro = $filtro . $conector . $nmTabela . "." . vocontrato::$nmAtrDtVigenciaFinalContrato . "<='" . getDataSQL ( $this->dtVigenciaFinal ) . "'";
			
			/*
			 * $filtro = $filtro . $conector
			 * . "("
			 * . $nmTabela. "." .vocontrato::$nmAtrDtVigenciaFinalContrato
			 * . "<='"
			 * . getDataSQL($this->voContrato->dtVigenciaFinal)
			 * . "' OR ("
			 * . $nmTabela. "." .vocontrato::$nmAtrDtVigenciaInicialContrato
			 * . "<='"
			 * . getDataSQL($this->voContrato->dtVigenciaFinal)
			 * . "' AND "
			 * . $nmTabela
			 * . "."
			 * .vocontrato::$nmAtrDtVigenciaFinalContrato
			 * . " IS NULL)) ";
			 */
			
			$conector = "\n AND ";
		}
		
		if ($this->dtVigencia != null) {
			$pChaveTuplaComparacaoSemSequencial = $nmTabela . "." . vocontrato::$nmAtrCdContrato . "," . $nmTabela . "." . vocontrato::$nmAtrAnoContrato;
			
			$filtro = $filtro . $conector . getSQLDataVigente ( $nmTabela, vocontrato::$nmAtrSqContrato, $pChaveTuplaComparacaoSemSequencial, $pChaveTuplaComparacaoSemSequencial, $this->dtVigencia, vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaFinalContrato );
			
			$conector = "\n AND ";
		}
		
		if ($this->dtInicio1 != null || $this->dtInicio2 != null) {
			$filtro = $filtro . $conector . getSQLIntervaloDatas ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, $this->dtInicio1, $this->dtInicio2 );
			
			$conector = "\n AND ";
		}
		
		if ($this->dtFim1 != null || $this->dtFim2 != null) {
			$filtro = $filtro . $conector . getSQLIntervaloDatas ( $nmTabela, vocontrato::$nmAtrDtVigenciaFinalContrato, $this->dtFim1, $this->dtFim2 );
			
			$conector = "\n AND ";
		}
		
		if ($this->dtInclusao != null) {
			$filtro = $filtro . $conector . "DATE($nmTabela" . "." . vocontrato::$nmAtrDhInclusao . ")='" . getDataSQL ( $this->dtInclusao ) . "'";
			
			$conector = "\n AND ";
		}
		
		if ($this->tpVigencia != null && $this->tpVigencia != constantes::$CD_OPCAO_TODOS) {
			if ($this->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES) {
				$filtro = $filtro . $conector . getSQLDataVigenteSqSimples ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaFinalContrato );
			} else {
				$filtro = $filtro . $conector . getSQLDataNaoVigenteSqSimples ( $nmTabela, vocontrato::$nmAtrDtVigenciaInicialContrato, vocontrato::$nmAtrDtVigenciaFinalContrato );
			}
			
			$conector = "\n AND ";
		}
		
		// finaliza o filtro
		$filtro = parent::getFiltroConsulta ( $filtro );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributoOrdenacaoDefault() {
		return vocontrato::getNmTabelaStatic ( $this->isHistorico ) . "." . vocontrato::$nmAtrSqContrato . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				"ct_exercicio" => "Ano",
				"ct_numero" => "Numero",
				"ct_tipo" => "Tipo",
				vocontrato::$nmAtrCdEspecieContrato => "Especie",
				"ct_contratada" => "Contratada",
				"ct_dt_vigencia_inicio" => "Dt.Inicio",
				"ct_dt_vigencia_fim" => "Dt.Fim",
				"ct_valor_global" => "Vl.Global" 
		);
		return $varAtributos;
	}
}

?>