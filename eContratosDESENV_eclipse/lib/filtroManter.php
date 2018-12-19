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
	var $isConsultarHTML;
	var $inDesativado;
	var $groupby;
	var $isConsultaTela;
	var $isTpVigenciaMAxSq;
	
	private $inConsultaRealizada = false;
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
			$this->isConsultaTela = true;
		} else {
			$this->isValidarConsulta = false;
			$this->isConsultaTela = false;
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
		$this->isTpVigenciaMAxSq = false;
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
	
	static function isConsultarHTML() {
		$consultar = @$_GET ["consultar"];
		if ($consultar == null || $consultar == "") {
			$consultar = @$_POST ["consultar"];
		}
		
		return $consultar == "S"; 
	}
	static function verificaFiltroSessao($filtro) {		
		//echoo("FILTRO: " . $filtro->nmFiltro);
		session_start ();
		$utilizarSessao = @$_POST ["utilizarSessao"];
		$isUtilizarSessao = $utilizarSessao != "N";
		
		$isConsultar = static::isConsultarHTML();		
		
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
	
	/**
	 * 
	 * @param unknown $filtro
	 * @return string
	 * @ deprecated
	 * 
	 * o metodo correto eh o getFiltroSQLCompleto($strFiltro, $voEntidadePrincipal = null, $comAtributoOrdenacao = null) {
	 */
	/*function getFiltroConsulta($filtro) {
		//serve para formatar o atributo de ordenacao caso ele nao esteja referenciando a tabela correta(de historico ou nao)
		$this->formataCampoOrdenacao(new voProcLicitatorio());
		//$this->formataCampoOrdenacao($this->voPrincipal);
		
		return $this->getFiltroSQL ( $filtro, true );
	}*/
	
	
	function getFiltroSQL($strFiltro, $comAtributoOrdenacao = true) {
		return $this->getFiltroSQLCompleto($strFiltro, null, $comAtributoOrdenacao);		
	}
	
	function getFiltroSQLCompleto($strFiltro, $voEntidadePrincipal = null, $comAtributoOrdenacao = true) {
		
		if($voEntidadePrincipal != null){
			$this->formataCampoOrdenacao($voEntidadePrincipal);
		}
		
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
			
			/*if($this->inDesativado != null && $this->inDesativado != constantes::$CD_OPCAO_TODOS){				
				$atributoComparar = $this->voPrincipal->getNmTabela() . "." . voentidade::$nmAtrInDesativado;
				
				//MARRETA: so esta com a condicao de IS NULL NA UNICA OPCAO de que ha um JOIN com outra tabela que nao utilize o in_desativado
				//como por exemplo a consulta da tela de vocontratoinfo, em que se busca na tabela principal vocontrato, e ai nesse caso
				//o in_desativado, que tem o vocontratoinfo como voprincipal, pode ser nulo
				//
				$strFiltro = $strFiltro . $conector . "($atributoComparar IS NULL OR ($atributoComparar IS NOT NULL AND $atributoComparar = '" . $this->inDesativado . "')) ";
				$conector = "\n AND ";
			}*/
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
		$strOrdemAnteriorDefault = "";
		//so se vier da tela
		if($this->isConsultaTela){
			if ($this->getAtributoOrdenacaoDefault ()) {
				$strOrdemDefault = $this->getAtributoOrdenacaoDefault ();
			}
			
			if ($this->getAtributoOrdenacaoAnteriorDefault ()) {
				$strOrdemAnteriorDefault = $this->getAtributoOrdenacaoAnteriorDefault ();
			}
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

		if($strOrdemAnteriorDefault != ""){
			$ordenacaoFinal = $strOrdemAnteriorDefault . $conectorOrdem.  $ordenacaoFinal;
			$conectorOrdem = ",";
		}
		
		if($strOrdemDefault != ""){
			$ordenacaoFinal = $ordenacaoFinal . $conectorOrdem. $strOrdemDefault;
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
	
	/**  
	 * serve para formatar o atributo de ordenacao caso ele nao esteja 
	 * referenciando a tabela correta(de historico ou nao)
	 * se a tabela ja for a correta, ele nao faz nada, ou seja, permite a indicacao forcada da tabela que se deseja
	 * sem alterar a funcionalidade
	 */
	function formataCampoOrdenacao($voEntidade) {
		
		$nmTabela = $voEntidade->getNmTabelaStatic ( $this->isHistorico );
					
		if ($nmTabela != null && $this->cdAtrOrdenacao != null) {
			//echo "<br>ordenacao original: " . $this->cdAtrOrdenacao;
			$jaEhFormatado = strpos ( $this->cdAtrOrdenacao, "." );
							
			// so formata se o atrordenacao escolhido pertencer a nmtabela em questao			
			if ($jaEhFormatado === false && existeItemNoArray ( $this->cdAtrOrdenacao, $voEntidade->getTodosAtributos () )) {					
				$this->cdAtrOrdenacaoConsulta = $nmTabela . "." . $this->cdAtrOrdenacao;
				//echo "<br>ordenacao formatado: " . $this->cdAtrOrdenacaoConsulta;
			}
		}
	}
	function toString() {
		$retorno .= "qtdRegistrosPorPag=" . $this->qtdRegistrosPorPag . "|";
		$retorno .= "paginaAtual=" . $this->paginacao->paginaAtual . "|";
		$retorno .= "numTotalRegistros=" . $this->numTotalRegistros;
		
		return $retorno;
	}
	/**
	 * Usado quando se deseja que ocorra uma ordenacao configurada combinada ou apos a selecao do atributo ordenacao
	 * @return string
	 */
	function getAtributoOrdenacaoDefault() {
		return "";
	}
	/**
	 * Usado quando se deseja que SEMPRE ocorra uma ordenacao configurada, independente do atributo ordenacao
	 * selecionado pelo usuario 
	 * @return string
	 */	 
	function getAtributoOrdenacaoAnteriorDefault() {
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
	
	function getNmFiltro(){
		return $this->nmFiltro;
	}
		
	function isConsultaRealizada(){
		return $this->inConsultaRealizada;
	}
	
	function setConsultaRealizada(){
		return $this->inConsultaRealizada = true;
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

/** 
 * usado para consultas genericas, sem tela 
 * @author daniel.ribeiro
 *
 */
class filtroConsultar extends filtroManter {
	function __construct($temPaginacao=false) {
		parent::__construct2($temPaginacao, false);
		$this->isValidarConsulta = false;
		//$this->inDesativado = "N";
	}
}
	
?>