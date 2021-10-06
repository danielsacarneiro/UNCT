<?php
include_once (caminho_util . "paginacao.php");
include_once (caminho_util . "multiplosConstrutores.php");
include_once (caminho_util . "dominioTpVigencia.php");
require_once (caminho_util . "/selectOR_AND.php");
class filtroManter extends multiplosConstrutores {
	
	static $IN_FILTRO_OCULTO_PADRAO = true;
	static $NM_DIV_FILTRO_EXPANSIVEL = "NM_DIV_FILTRO_EXPANSIVEL";	
	static $ID_SESSAO_COUNT_FILTRO_SESSAO = "ID_SESSAO_COUNT_FILTRO_SESSAO";
	static $ID_REQ_NmFiltroExportarPlanilha = "ID_REQ_NmFiltroExportarPlanilha";
	static $CD_CAMPO_SUBSTITUICAO = "[[[[QEEWER_CD_CAMPO_SUBSTITUICAO_EREWFDSFS]]]]";
	static $NmColCOUNTFiltroManter = "NmColCOUNTFiltroManter";
	// ...............................................................

	static $nmAtrCdConsultarArquivo = "cdConsultarArquivo";
	static $nmAtrCdAtrOrdenacao = "cdAtrOrdenacao";
	static $nmAtrCdOrdenacao = "cdOrdenacao";
	static $nmAtrInTrazerVigenciaFutura = "InTrazerVigenciaFutura";
	static $nmAtrDtVigencia = "dtVigencia";
	static $nmAtrTpVigencia = "tpVigencia";
	static $nmAtrCdHistorico = "cdHistorico";
	static $nmAtrQtdRegistrosPorPag = "qtdRegistrosPorPag";
	static $nmAtrNumTotalRegistros = "numTotalRegistros";
	static $nmAtrCdUtilizarSessao = "utilizarSessao";
	static $nmAtrCdConsultar = "consultar";
	static $nmAtrIsTpVigenciaMAxSq = "nmAtrIsTpVigenciaMAxSq";
	static $nmAtrInFiltroOculto = "nmAtrInFiltroOculto";
	
	private $nmMetodoExportarPlanilha = "";
	private $nmVOEntidadeExportarPlanilha = "";
	var $cdAtrOrdenacao;
	var $cdOrdenacao;
	var $inTrazerVigenciaFutura;
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
	var $inFiltroOculto;
	//implementar quando trouxer uma solucao para exibicao de registros considerando o historico
	//var $inTrazerComHistorico = false;
	
	var $isIncluirFiltroNaSessao = false;
	var $isFazerControleSessao = false;
	
	var $sqlFiltrosASubstituir;
	private $temSQLFiltrosASubstituir = false;
	
	private $inConsultaRealizada = false;
	private $inConsultaFiltroPlanilhaExportarRealizada = false;
	private $QUERY_SELECT;
	private $QUERY_FROM;
	
	private $QUERY_FILTRO;
	
	var $isRetornarQueryCompleta;
	//quando acionada, guarda a query efetivamente consultada
	private $QUERY_COMPLETA;
	
	var $voPrincipal;
	private $colecaoTotalizadores;

	// construtor
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
		$this->__construct3 ( $temPaginacao, $pegarFiltrosDaTela );
	}
	function __construct3($temPaginacao, $pegarFiltrosDaTela, $isFazerControleSessao=false) {
		//o isFazerControleSessao serve para setar o nome do filtro na sessao com sequenciais
		//para que permita mais de um mesmo filtro na sessao
		//ver metodo: setNomeFiltroControleSessao
		$this->isFazerControleSessao = $isFazerControleSessao;
		
		$this->cdConsultarArquivo = constantes::$CD_NAO;
		//$this->tpVigencia = constantes::$CD_OPCAO_TODOS;
		
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
		}else{
			$this->setaFiltroConsultaSemLimiteRegistro ();
		}
		
		$this->isHistorico = "S" == $this->cdHistorico;
		//echoo ("historico:" . $this->cdHistorico);
		// para o caso de ser necessario setar um filtro default para nao trazer todos os registros
		$this->temValorDefaultSetado = false;
		$this->inDesativado = null;
		//$this->isTpVigenciaMAxSq = false;
		//$this->inTrazerComHistorico = false;
	}
	
	/**
	 * funcao que dá um identificador para que o filtro nao seja sobrescrito na sessao
	 */
	function setNomeFiltroControleSessao(){		
		$count = null;

		if($this->isFazerControleSessao){
			if(existeObjetoSessao(static::$ID_SESSAO_COUNT_FILTRO_SESSAO)){
				//o controle de sessao de filtro eh feito por filtro
				$arraySessaoFiltro = getObjetoSessao(filtroManter::$ID_SESSAO_COUNT_FILTRO_SESSAO);
				$count = $arraySessaoFiltro[get_class($this)];			
			}
			
			$count = $count + 1;
			$arraySessaoFiltro[get_class($this)] = $count;
			putObjetoSessao(static::$ID_SESSAO_COUNT_FILTRO_SESSAO, $arraySessaoFiltro);		
		}
		
		$this->nmFiltroOriginal = $this->nmFiltro = get_class($this) . $count ;
		
		//echo "DEU IDENTIFICADOR " . $count ;
	}
	
	function zerarFiltroControleSessao(){
		if(existeObjetoSessao(static::$ID_SESSAO_COUNT_FILTRO_SESSAO)){
			//o controle de sessao de filtro eh feito por filtro
			$arraySessaoFiltro = getObjetoSessao(filtroManter::$ID_SESSAO_COUNT_FILTRO_SESSAO);
			$arraySessaoFiltro[get_class($this)] = null;
			putObjetoSessao(static::$ID_SESSAO_COUNT_FILTRO_SESSAO, $arraySessaoFiltro);
		}
	}
	
	function getNmFiltroOriginal(){
		return $this->nmFiltroOriginal;
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
		$this->inFiltroOculto = @$_POST [self::$nmAtrInFiltroOculto];
		/*if(!isAtributoValido($this->inFiltroOculto)){
			$this->inFiltroOculto = "N";
		}*/
		
		//se o filtro vem da tela, deve por na sessao
		$this->isIncluirFiltroNaSessao = true;
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
	
	/**
	 * VErifica se a flag de consultar esta ativada
	 * @return boolean
	 */
	static function isConsultarHTML() {
		$consultar = @$_GET [constantes::$ID_REQ_CD_CONSULTAR];
		if ($consultar == null || $consultar == "") {
			$consultar = @$_POST [constantes::$ID_REQ_CD_CONSULTAR];
		}
		//echo "consultar == $consultar";
	
		return $consultar == "S";
	}
	
	/**
	 * VErifica se tem filtro na sessao
	 * @return boolean
	 */
	static function isPegarFiltroSessaoHTML($filtro) {
		$utilizarSessao = @$_POST ["utilizarSessao"];
		$isUtilizarSessao = $utilizarSessao != "N";
		$isConsultar = static::isConsultarHTML();		
		$existeFiltroSessao = existeObjetoSessao ( $filtro->nmFiltro );
		
		//$cliqueBotaoConsulta = existeStr1NaStr2("index.php", $_SERVER['REQUEST_URI']);
		
		/*echoo ("$filtro->nmFiltro|isUtilizarSessao: " . dominioSimNao::getDescricao($isUtilizarSessao) 
		. " isConsultar: " . dominioSimNao::getDescricao($isConsultar)
		. " existeFiltroSessao: " . dominioSimNao::getDescricao($existeFiltroSessao));*/
		
		return ($isConsultar && $isUtilizarSessao  && $existeFiltroSessao); 
	}
	
	static function verificaFiltroSessao($filtro) {		
		//echoo("FILTRO: " . $filtro->nmFiltro);
		//session_start ();
		// echo "nome filtro". $filtro->nmFiltro;
		if (static::isPegarFiltroSessaoHTML($filtro)) {
			//echoo("pegou filtro sessao");
			$filtro = getObjetoSessao ( $filtro->nmFiltro );
			$paginaAtual = @$_GET ['paginaAtual'];
			
			if ($paginaAtual != null)
				$filtro->paginacao->paginaAtual = $paginaAtual;
		} /*else {
			//echoo("incluiu filtro sessao");
			putObjetoSessao ( $filtro->nmFiltro, $filtro );
		}	*/	 
		
		return $filtro;
	}
	function getSQLWhere($comAtributoOrdenacao) {
		return $this->getFiltroConsultaSQL ( $comAtributoOrdenacao );
	}

	/**
	 * retorna o filtro preenchido pelo usuario
	 * @param unknown $comAtributoOrdenacao
	 * @return unknown
	 */
	function getSQLFiltroPreenchido($comAtributoOrdenacao=true) {
		$this->getFiltroConsultaSQL ( $comAtributoOrdenacao );
		return $this->getSQLFiltro();
	}
	
	/**
	 * substitui os campos de substituicao pelo filtro consultado
	 * @return unknown
	 */
	function getSQLQueryComFiltroSubstituicao($query, $sqlFiltro, $comClausulaWHERE = true) {
		
		$substituirPorVazio = false;
		if($sqlFiltro == null || $sqlFiltro == ""){
			$substituirPorVazio = true;
		}
		
		$complemento = "";		
		if($comClausulaWHERE){
			$complemento = " WHERE";
		}
				
		if(!is_array($sqlFiltro)){
			$substituicao = "$complemento $sqlFiltro";
			if($substituirPorVazio){
				$substituicao = "";
			}
			$retorno = str_replace(STATIC::$CD_CAMPO_SUBSTITUICAO, $substituicao, $query);
			
		}else{
			//echo "aqui";
			$chaves = array_keys($sqlFiltro);
			$retorno = $query;
			for ($i=0; $i < sizeof($sqlFiltro); $i++){
				$subst = $chaves[$i];
				$conteudo = $sqlFiltro[$subst];
				
				if($conteudo == null || $conteudo == ""){
					$substituirPorVazio = true;
					//echoo("vazio");
				}else{
					$substituirPorVazio = false;
					//echoo("vazio nao");
				}				
				
				$substituicao = "$complemento $conteudo";
				if($substituirPorVazio){
					$substituicao = "";
					//echo "vazio";
				}
				
				$retorno = str_replace( $subst, $substituicao, $retorno);
			}
		}
		
		return $retorno;
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
		//GUARDA O FILTRO USADO NA CONSULTA
		$this->setSQLFiltro($strFiltro);
		
		//providencia o complemento do filtro para possibilitar a consulta final
		//incluindo validacao de registros desativados ou n
		return $this->getFiltroSQLCompleto($strFiltro, null, $comAtributoOrdenacao);		
	}
	
	function getFiltroSQLCompleto($strFiltro, $voEntidadePrincipal = null, $comAtributoOrdenacao = true) {
		
		if($voEntidadePrincipal != null){
			$this->formataCampoOrdenacao($voEntidadePrincipal);
		}
		
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
			//echoo ("ok");
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
			//remove a ordenacao por historico se a consulta NAO for por historico
			//MARRETA ORDER BY
			if(!$this->isHistorico() && existeStr1NaStr2(voentidade::$nmAtrSqHist, $atributoOrdenacao)){
				$atributoOrdenacao = "";				
			}else{
				$ordenacaoFinal = $atributoOrdenacao;
				$conectorOrdem = ",";
			}
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
			if(method_exists($this, "getAtributosOrdenacao")){
				$comboOrdenacao = new select ( $this->getAtributosOrdenacao () );
			}			
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
		$retorno = "";
		//filtro de historico na tela, para facilitar a implementacao, so permitira ordenar pelo historico. Ver alteracao futura!
		/*if($this->isHistorico() && $this->pegarFiltroDaTela()){
			//$retorno = voentidade::$nmAtrSqHist;
			$this->cdAtrOrdenacao = voentidade::$nmAtrSqHist; 
		}*/
		return $retorno;
	}
	function setaFiltroConsultaSemLimiteRegistro() {
		$this->qtdRegistrosPorPag = null;
	}
	function isHistorico() {
		return $this->isHistorico;
	}	
	static function isAtributoArrayVazio($colecao) {		
		return isAtributoArrayVazio($colecao);		
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
		
	/**
	 * serve para guardar o filtro usado na consulta: sem WHERE ou ORDER BY
	 * @param unknown $strFiltro
	 * @return boolean
	 */
	function setSQLFiltro($strFiltro){
		$this->QUERY_FILTRO = $strFiltro;
	}
	
	function getSQLFiltro(){
		return $this->QUERY_FILTRO;
	}
	
	/**
	 * meetodo correto para consultar se eh caso de historico ou nao
	 * eh preciso passar o voprincipal como parametro para que o sql saiba em que tabela validarah se eh caso de historico
	 * @param unknown $cdHistorico
	 * @param unknown $voPrincipal
	 */
	function setCdHistorico($cdHistorico, $voPrincipal){
		$this->voPrincipal = $voPrincipal;
		$this->cdHistorico = $cdHistorico;
	}
	
	/**
	 * utilizada somente quando selecionado o retorno apenas da query completa
	 */
	function setSQL_QUERY_COMPLETA($query){
		$this->QUERY_COMPLETA = $query;
	}
	function getSQL_QUERY_COMPLETA(){
		return $this->QUERY_COMPLETA;
	}
	
	function setFiltroOrdenacaoComplemento(&$varAtributos){
		if($this->isHistorico()){
			$atributo = array(voentidade::$nmAtrSqHist => voentidade::$DS_HISTORICO);
			$varAtributos = putElementoArray2NoArray1ComChaves ($atributo, $varAtributos);
		}
	}
	
	/**
	 * retorna o necessario para o caso da consulta possuir valores totalizados
	 */
	function getStringSQLAtributosTotalizados($arrayAtributos=null){
		$retorno = "";

		$nmMetodo = "getAtributosValoresTotalizados";
		if(method_exists($this, $nmMetodo)){
			if($arrayAtributos == null){
				$arrayAtributos = $this->$nmMetodo();
			}
			
			//var_dump($arrayParam);
			$retorno = getStringDoArrayComSeparador($arrayAtributos, ",");
		}
		
		return $retorno;		
	}
	
	function getAtributosTotalizadoresOperacionados($operacao="SUM", $removeNomeTabelaAtributo=true){
		$retorno = "";
		$nmMetodo = "getAtributosValoresTotalizados";
		if(method_exists($this, $nmMetodo)){
			$array = $this->$nmMetodo();
			foreach ($array as $atributo){
				if($removeNomeTabelaAtributo){
					$atributo = removeNomeTabelaDoAtributo($atributo);
				}
				$retorno[] = "$operacao($atributo) AS $atributo";				
			}
		}
		
		return $retorno;
	}
	
	function temTotalizadoresFiltro(){
		return isAtributoValido($this->getStringSQLAtributosTotalizados());
	}
	
	function setColecaoTotalizadores($colecao){
		$this->colecaoTotalizadores = $colecao;		
	}

	function getColecaoTotalizadores(){
		return $this->colecaoTotalizadores;
	}
	
	function temColecaoTotalizadores(){
		return !isColecaoVazia($this->getColecaoTotalizadores());
	}
	
	function temSQLFiltrosASubstituir(){
		return $this->temSQLFiltrosASubstituir;
	}
	
	function setSQLFiltrosASubstituir($param){
		$this->sqlFiltrosASubstituir = $param;
		$this->temSQLFiltrosASubstituir = true;
	}
	
	function getSQLFiltrosASubstituir(){
		return $this->sqlFiltrosASubstituir;
	}
	
	function setArrayObjetosExportarPlanilha($array){
		$this->setNmVOEntidadeExportarPlanilha(get_class($array[0]));
		//var_dump($this->voEntidadeExportarPlanilha);
		$this->setNmMetodoExportarPlanilha($array[1]);
	}
	
	function setNmMetodoExportarPlanilha($nmMetodo){
		$this->nmMetodoExportarPlanilha = $nmMetodo;
	}

	function getNmMetodoExportarPlanilha(){
		return $this->nmMetodoExportarPlanilha;
	}
	
	function setNmVOEntidadeExportarPlanilha($nmvo){
		$this->nmVOEntidadeExportarPlanilha = $nmvo;
	}
	
	function getNmVOEntidadeExportarPlanilha(){
		return $this->nmVOEntidadeExportarPlanilha;
	}
	
	function isFiltroPlanilhaExportar(){
		return $this->nmMetodoExportarPlanilha != "";
	}
	
	function isConsultaFiltroPlanilhaExportarRealizada(){
		return $this->inConsultaFiltroPlanilhaExportarRealizada;
	}
	
	function consultarExportarPlanilha(){
		$nmVo = $this->getNmVOEntidadeExportarPlanilha();
		$voExportar = new $nmVo();
		//var_dump($voExportar);
		$nmClasseDbProcesso = $voExportar->getNmClassProcesso();
		$dbprocesso = new $nmClasseDbProcesso();
		$nmMetodoExportarPlanilha = $this->getNmMetodoExportarPlanilha();
		
		//$colecaoPlanilha = getObjetoSessao(constantes::$ID_REQ_COLECAO_EXPORTAR_PLANILHA);
		$this->isValidarConsulta = false;
		$this->setaFiltroConsultaSemLimiteRegistro();
		
		$this->inConsultaFiltroPlanilhaExportarRealizada = true;
		$colecaoPlanilha = $dbprocesso->$nmMetodoExportarPlanilha($this);
		
		if(isColecaoVazia($colecaoPlanilha)){
			throw new excecaoConsultaVazia("Erro ao exportar.");
		}
		
		return $colecaoPlanilha;		
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