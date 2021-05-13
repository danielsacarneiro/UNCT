<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_funcoes . "alertas/Biblioteca_alertas.php");

class voMensageriaRegistro extends voentidade {	
	
	static $nmAtrSq = "sq";
	static $nmAtrSqMensageria = "msg_sq";
			
	var $sq;
	var $sqMensageria;
	
	// ...............................................................
	// Funcoes ( Propriedades e métodos da classe )
	function __construct($arrayChave = null) {
		parent::__construct1 ($arrayChave);
		$this->temTabHistorico = false;
		
		// retira os atributos padrao que nao possui
		// remove tambem os que o banco deve incluir default
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrCdUsuarioInclusao,
				self::$nmAtrDhUltAlteracao,
		);
		$this->removeAtributos ( $arrayAtribRemover );
		$this->varAtributosARemover = $arrayAtribRemover;
		
		$this->vocontratoinfo = new voContratoInfo();
	}
	public static function getTituloJSP() {
		return "MENSAGERIA REGISTRO";
	}
	public static function getNmTabela() {
		return "msg_registro";
	}
	public static function getNmClassProcesso() {
		return "dbMensageriaRegistro";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrSqMensageria . "=" . $this->sqMensageria;
		
		return $query;
	}
	static function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrSqMensageria,
		);
		
		return $retorno;
	}
	static function getAtributosFilho() {
		$retorno = static::getAtributosChavePrimaria();		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->sqMensageria = $registrobanco[self::$nmAtrSqMensageria];
	}
	function getDadosFormulario() {
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->sqMensageria = @$_POST[self::$nmAtrSqMensageria];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();
	}

	function toString() {
		$retorno .= "Registro" . complementarCharAEsquerda($this->sq, "0", constantes::$TAMANHO_CODIGOS) 
		. ". Alerta:" . complementarCharAEsquerda($this->sqMensageria, "0", constantes::$TAMANHO_CODIGOS);
		
		return $retorno;
	}
	function getValorChavePrimaria() {
		$separador = CAMPO_SEPARADOR;
		$chave = $this->sq
		. $separador
		. $this->sqMensageria;
		// $separador = "b";
		return $chave;
	}
	function getChavePrimariaVOExplode($array) {		
		$this->sq = $array[0];
		$this->sqMensageria = $array[1];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Registro Mensageria: " . $this->toString();
		return $retorno;
	}
	
	static function getMensagemGestor($codigoContrato, $numFrequencia){

		$emailPrincipal = email_sefaz::$REMETENTE_PRINCIPAL;
		$emailCopia = email_sefaz::$REMETENTE_COPIA;
	
		$retorno = "<br>Prezado gestor,
		<br><br><br><b>Esta � uma mensagem autom�tica</b> gerada pelo sistema de automa��o da Unidade de Contratos (UNCT/SAFI),
		solicitando informa��es referentes � <b>prorroga��o</b> do contrato <b>$codigoContrato</b>, que em breve se encerrar�.
		<br>Havendo interesse da SEFAZ pela prorroga��o, requere-se provoca��o tempestiva, via SEI, � SAFI, junto com as cota��es de pre�os e a anu�ncia da Contratada.
	
		<br><br><b>N�o sendo poss�vel nova prorroga��o, e persistindo a necessidade da contrata��o, o gestor dever� solicitar novo processo licitat�rio
		em tempo h�bil, sob pena de encerramento da presta��o do servi�o.
	
		<br><br>Informamos ainda que o envio da garantia contratual atualizada, sendo este o caso, � necess�ria � instru��o da renova��o contratual.</b>
	
		<br><br>A resposta deve ser enviada para o seguinte correio eletr�nico: <b><u>$emailPrincipal</u></b>, com c�pia para <u>$emailCopia</u> .
		<br><br><b>Sem preju�zo quanto � responsabilidade referente � gest�o contratual pr�pria do setor demandante,
		� imprescind�vel a resposta deste email, ainda que inexista interesse na prorroga��o, para fins de controle e registro desta unidade</b>.
	
		<br><br>Caso o pedido de prorroga��o j� tenha sido formalizado, <b>favor informar o n�mero do SEI que trata da presente quest�o</b>.
		<br><br>Na aus�ncia de manifesta��o, este e-mail ser� reenviado a cada <b>$numFrequencia dia(s)</b>.";
	
		return $retorno;
	}
	 
	static function getMensagemGestorContratoImprorrogavel($codigoContrato, $numFrequencia){

		$emailPrincipal = email_sefaz::$REMETENTE_PRINCIPAL;
		$emailCopia = email_sefaz::$REMETENTE_COPIA;
		 
		$retorno = "<br>Prezado gestor,
	
		<br><br><br><b>Esta � uma mensagem autom�tica</b> gerada pelo sistema de automa��o da Unidade de Contratos (UNCT/SAFI),
		comunicando a <b>improrrogabilidade</b> do contrato <b>$codigoContrato</b>, que em breve se encerrar�.
		<br>Havendo interesse da SEFAZ pela manuten��o do servi�o contratado, requere-se provoca��o tempestiva, via SEI, � SAFI,
		pleiteando a abertura de novo processo licitat�rio.
	
		<br><br>Excepcionalmente, permite-se a an�lise extraordin�ria de uma nova prorroga��o, desde que atendidos os requisitos legais.</b>
		 
		<br><br>A resposta deve ser enviada para o seguinte correio eletr�nico: <b><u>$emailPrincipal</u></b>, com c�pia para <u>$emailCopia</u> .
		<br><br><b>Sem preju�zo quanto � responsabilidade referente � gest�o contratual pr�pria do setor demandante,
		� imprescind�vel a resposta deste email, ainda que inexista interesse na prorroga��o, para fins de controle e registro desta unidade</b>.
		 
		<br><br>Caso esta solicita��o j� tenha sido respondida, <b>favor informar o n�mero do SEI que trata da presente quest�o</b>.
	
		<br><br>Na aus�ncia de manifesta��o, este e-mail ser� reenviado a cada <b>$numFrequencia dia(s)</b>.";
		 
		return $retorno;
	}
}
?>