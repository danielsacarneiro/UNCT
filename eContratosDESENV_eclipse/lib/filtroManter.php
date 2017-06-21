<?php
include_once (caminho_util . "paginacao.php");
include_once (caminho_util . "multiplosConstrutores.php");
include_once (caminho_util . "dominioTpVigencia.php");
class filtroManter extends multiplosConstrutores {
	// ...............................................................
	// construtor
	static $nmAtrCdConsultarArquivo = "cdConsultarArquivo";
	static $nmAtrCdAtrOrdenacao = "cdAtrOrdenacao";
	static $nmAtrCdOrdenacao = "cdOrdenacao";
	static $nmAtrDtVigencia = "dtVigencia";
	static $nmAtrTpVigencia = "tpVigencia";
	static $nmAtrCdHistorico = "cdHistorico";
	static $nmAtrQtdRegistrosPorPag = "qtdRegistrosPorPag";
	static $nmAtrNumTotalRegistros = "numTotalRegistros";
	static $nmAtrCdUtilizarSessao = "utilizarSessao";
	static $nmAtrCdConsultar = "consultar";
	var $cdAtrOrdenacao;
	var $cdOrdenacao;
	var $dtVigencia;
	var $tpVigencia;
	var $cdHistorico;
	var $numTotalRegistros;
	var $TemPaginacao;
	var $qtdRegistrosPorPag;
	var $paginacao;
	var $nmEntidadePrincipal;
	var $isHistorico;
	var $cdConsultarArquivo;
	var $isValidarConsulta;
	var $inDesativado;
	var $groupby;
	
	private $QUERY_SELECT;
	private $QUERY_FROM;
	
	var $voPrincipal;
	function __construct0() {
		// echo "teste0";
		$this->__construct1 ( true );
	}
	function __construct1($pegarFiltrosDaTela) {
		// echo "teste" . $pegarFiltrosDaTela;
		// se nao pega dados da tela, nao tem paginacao por padrao
		$this->__construct2 ( $pegarFiltrosDaTela, $pegarFiltrosDaTela );
	}
	function __construct2($temPaginacao, $pegarFiltrosDaTela) {
		$this->cdConsultarArquivo = constantes::$CD_NAO;
		$this->tpVigencia = constantes::$CD_OPCAO_TODOS;
		
		if ($pegarFiltrosDaTela) {
			$this->pegarFiltroDaTela ();
			// chama o metodo do filho que pega os dados do filtro do formulario
			if (method_exists ( $this, "getFiltroFormulario" )) {
				$this->getFiltroFormulario ();
			}
			
			$this->isValidarConsulta = true;
		} else {
			$this->isValidarConsulta = false;
		}
		
		if ($this->numTotalRegistros == null) {
			$this->numTotalRegistros = 0;
		}
		if ($this->qtdRegistrosPorPag == null) {
			$this->qtdRegistrosPorPag = paginacao::$qtdRegistrosPorPag;
		}
		$this->paginacao = null;
		$this->TemPaginacao = $temPaginacao;
		if ($temPaginacao) {
			$this->paginacao = new paginacao ( $this->qtdRegistrosPorPag );
		}
		
		$this->isHistorico = "S" == $this->cdHistorico;
		// para o caso de ser necessario setar um filtro default para nao trazer todos os registros
		$this->temValorDefaultSetado = false;
		$this->inDesativado = null;
	}
	function pegarFiltroDaTela() {
		$this->cdAtrOrdenacao = @$_POST [self::$nmAtrCdAtrOrdenacao];
		$this->cdOrdenacao = @$_POST [self::$nmAtrCdOrdenacao];
		$this->tpVigencia = @$_POST [self::$nmAtrTpVigencia];
		$this->dtVigencia = @$_POST [self::$nmAtrDtVigencia];
		$this->cdHistorico = @$_POST [self::$nmAtrCdHistorico];
		$this->qtdRegistrosPorPag = @$_POST [self::$nmAtrQtdRegistrosPorPag];
		$this->numTotalRegistros = @$_POST [self::$nmAtrNumTotalRegistros];
		$this->cdConsultarArquivo = @$_POST [self::$nmAtrCdConsultarArquivo];
	}
	function setQueryFromJoin($query) {
		$this->QUERY_FROM = $query;
	}
	function getQueryFromJoin(){
		return $this->QUERY_FROM;
	}
	function setQuerySelect($query) {
		$this->QUERY_SELECT = $query;
	}
	function getQuerySelect() {
		return $this->QUERY_SELECT;
	}
	function temQueryPadrao() {
		return $this->QUERY_SELECT != null && $this->QUERY_SELECT != "";
	}
	function isSetaValorDefault() {
		$retorno = false;
	}
	static function verificaFiltroSessao($filtro) {
		// echo $filtro->nmFiltro;
		session_start ();
		$utilizarSessao = @$_POST ["utilizarSessao"];
		$isUtilizarSessao = $utilizarSessao != "N";
		
		$consultar = @$_GET ["consultar"];
		if ($consultar == null || $consultar == "") {
			$consultar = @$_POST ["consultar"];
		}
		
		$isConsultar = $consultar == "S";
		
		$pegarFiltroSessao = $isUtilizarSessao && $isConsultar;
		// echo "nome filtro". $filtro->nmFiltro;
		if (existeObjetoSessao ( $filtro->nmFiltro ) && $pegarFiltroSessao) {
			// echo "pegou filtro sessao";
			$filtro = getObjetoSessao ( $filtro->nmFiltro );
			$paginaAtual = @$_GET ['paginaAtual'];
			
			if ($paginaAtual != null)
				$filtro->paginacao->paginaAtual = $paginaAtual;
		} else {
			// echo "incluiu filtro sessao";
			putObjetoSessao ( $filtro->nmFiltro, $filtro );
		}
		
		return $filtro;
	}
	function getSQLWhere($comAtributoOrdenacao) {
		return $this->getFiltroConsultaSQL ( $comAtributoOrdenacao );
	}
	function getFiltroConsulta($filtro) {
		return $this->getFiltroSQL ( $filtro, true );
	}
	function getFiltroSQL($strFiltro, $comAtributoOrdenacao) {
		// ECHO "TESTE";
		$conector = "";
		
		// seta o conector
		if ($strFiltro != "") {
			$conector = "\n AND ";
		}
		
		// complementa com algum filtro do pai
		// se o inDesativado for null, eh porque nao tem desativacao
		//echo "desativado: ". $this->inDesativado;
		//so tem desativado quando a consulta NAO eh por historico
		if (!$this->isHistorico() && $this->voPrincipal != null) {
			
			if($this->voPrincipal->temTabHistorico() && $this->inDesativado == null){				
				//seta para nao, para nao trazer os desativados como default
				$this->inDesativado = constantes::$CD_NAO;
			}
				
			if($this->inDesativado != null && $this->inDesativado != constantes::$CD_OPCAO_TODOS){
				$strFiltro = $strFiltro . $conector . $this->voPrincipal->getNmTabela() . "." . voentidade::$nmAtrInDesativado . " = '" . $this->inDesativado . "'";
				$conector = "\n AND ";
			}
		}
		
		// agora sim inclui os valores de filtro
		if ($strFiltro != "") {
			$strFiltro = "\n WHERE $strFiltro";
		}
		
		// var_dump($this->groupby);
		if ($this->groupby != null && $this->groupby != "") {
			$str = $this->groupby;
			if (is_array ( $this->groupby )) {
				$str = getSQLStringFormatadaColecaoIN ( $this->groupby, false );
			}
			
			$strFiltro = $strFiltro . "\n GROUP BY " . $str;
		}
		
		// pega do filho, se existir
		$strOrdemDefault = "";
		if ($this->getAtributoOrdenacaoDefault ()) {
			$strOrdemDefault = $this->getAtributoOrdenacaoDefault ();
		}
		
		if ($this->cdAtrOrdenacao != null) {
			
			$atributoOrdenacao = $this->cdAtrOrdenacao;
			$ordem = $this->cdOrdenacao;			
			
			if ($this->cdAtrOrdenacaoConsulta != null) {
				// atributo que serve para formatar o atributo de ordenacao de acordo com a tabela que deve ser consultada
				// os campos dos combos de ordenacao geralmente nao vem identificados com a tabela que devem ordenar
				// o filtro filho pode formatar isso, e atribui a variavel cdAtrOrdenacaoConsulta
				$atributoOrdenacao = $this->cdAtrOrdenacaoConsulta;
			}
			
			$atributoOrdenacao = "$atributoOrdenacao $ordem";
		}
		
		$ordenacaoFinal = "";
		$conectorOrdem = "";
		//concatena as ordenacoes que existirem
		if($atributoOrdenacao != ""){
			$ordenacaoFinal = $atributoOrdenacao;
			$conectorOrdem = ",";
		}

		if($strOrdemDefault != ""){
			$ordenacaoFinal = $atributoOrdenacao . $conectorOrdem. $strOrdemDefault;
		}
		
		if ($comAtributoOrdenacao && $ordenacaoFinal != "") {
			$strFiltro = $strFiltro . "\n ORDER BY $ordenacaoFinal ";
		}
			
		//echo $strFiltro;
		
		return $strFiltro;
	}
	function getVOEntidadePrincipal() {
		$class = $this->nmEntidadePrincipal;
		$retorno = "";
		if ($class != null)
			$retorno = new $class ();
		return $retorno;
	}
	
	// NAO USAR MAIS
	/*
	 * function getAtributosOrdenacao(){
	 * $comboOrdenacao = null;
	 * if($this->nmEntidadePrincipal != null){
	 * $voentidade = $this->getVOEntidadePrincipal();
	 * $comboOrdenacao = new select($voentidade::getAtributosOrdenacao());
	 * }
	 * return $comboOrdenacao;
	 * }
	 */
	function getComboOrdenacao() {
		$comboOrdenacao = null;
		try {
			// $comboOrdenacao = new select(static::getAtributosOrdenacao());
			$comboOrdenacao = new select ( $this->getAtributosOrdenacao () );
			
			// }catch (Throwable $ex){
		} catch ( Error $ex ) {
			echo "FiltroManter:Error";
			$comboOrdenacao = null;
		} catch ( Throwable $ex ) {
			echo "FiltroManter:Throwable";
			$comboOrdenacao = null;
		}
		
		return $comboOrdenacao;
	}
	function formataCampoOrdenacao($voEntidade) {
		$nmTabela = $voEntidade->getNmTabelaStatic ( $this->isHistorico );
		
		if ($nmTabela != null && $this->cdAtrOrdenacao != null) {
			
			$jaEhFormatado = strpos ( $this->cdAtrOrdenacao, "." );
			
			// so formata se o atrordenacao escolhido pertencer a nmtabela em questao
			if ($jaEhFormatado === false && existeItemNoArray ( $this->cdAtrOrdenacao, $voEntidade->getTodosAtributos () )) {
				$this->cdAtrOrdenacaoConsulta = $nmTabela . "." . $this->cdAtrOrdenacao;
			}
		}
	}
	function toString() {
		$retorno .= "qtdRegistrosPorPag=" . $this->qtdRegistrosPorPag . "|";
		$retorno .= "paginaAtual=" . $this->paginacao->paginaAtual . "|";
		$retorno .= "numTotalRegistros=" . $this->numTotalRegistros;
		
		return $retorno;
	}
	function getAtributoOrdenacaoDefault() {
		return "";
	}
	function setaFiltroConsultaSemLimiteRegistro() {
		$this->qtdRegistrosPorPag = null;
	}
	function isHistorico() {
		return $this->isHistorico;
	}	
	static function isAtributoArrayVazio($colecao) {		
		return is_array($colecao) && count($colecao)==1 && $colecao[0]=="";
	}
	
}

/*
 * class filtroManterGUI extends filtroManter{
 * // ...............................................................
 * // construtor
 * function __construct($temPaginacao) {
 * parent::__construct1($temPaginacao, true);
 * }
 * }
 */
?>