<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_funcoes . "alertas/Biblioteca_alertas.php");

class voMensageriaRegistro extends voentidade {	
	
	static $nmAtrSq = "sq";
	static $nmAtrSqMensageria = "msg_sq";
			
	var $sq;
	var $sqMensageria;
	
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
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
		<br><br><br><b>Esta é uma mensagem automática</b> gerada pelo sistema de automação da Unidade de Contratos (UNCT/SAFI),
		solicitando informações referentes à <b>prorrogação</b> do contrato <b>$codigoContrato</b>, que em breve se encerrará.
		<br>Havendo interesse da SEFAZ pela prorrogação, requere-se provocação tempestiva, via SEI, à SAFI, junto com as cotações de preços e a anuência da Contratada.
	
		<br><br><b>Não sendo possível nova prorrogação, e persistindo a necessidade da contratação, o gestor deverá solicitar novo processo licitatório
		em tempo hábil, sob pena de encerramento da prestação do serviço.
	
		<br><br>Informamos ainda que o envio da garantia contratual atualizada, sendo este o caso, é necessária à instrução da renovação contratual.</b>
	
		<br><br>A resposta deve ser enviada para o seguinte correio eletrônico: <b><u>$emailPrincipal</u></b>, com cópia para <u>$emailCopia</u> .
		<br><br><b>Sem prejuízo quanto à responsabilidade referente à gestão contratual própria do setor demandante,
		é imprescindível a resposta deste email, ainda que inexista interesse na prorrogação, para fins de controle e registro desta unidade</b>.
	
		<br><br>Caso o pedido de prorrogação já tenha sido formalizado, <b>favor informar o número do SEI que trata da presente questão</b>.
		<br><br>Na ausência de manifestação, este e-mail será reenviado a cada <b>$numFrequencia dia(s)</b>.";
	
		return $retorno;
	}
	 
	static function getMensagemGestorContratoImprorrogavel($codigoContrato, $numFrequencia){

		$emailPrincipal = email_sefaz::$REMETENTE_PRINCIPAL;
		$emailCopia = email_sefaz::$REMETENTE_COPIA;
		 
		$retorno = "<br>Prezado gestor,
	
		<br><br><br><b>Esta é uma mensagem automática</b> gerada pelo sistema de automação da Unidade de Contratos (UNCT/SAFI),
		comunicando a <b>improrrogabilidade</b> do contrato <b>$codigoContrato</b>, que em breve se encerrará.
		<br>Havendo interesse da SEFAZ pela manutenção do serviço contratado, requere-se provocação tempestiva, via SEI, à SAFI,
		pleiteando a abertura de novo processo licitatório.
	
		<br><br>Excepcionalmente, permite-se a análise extraordinária de uma nova prorrogação, desde que atendidos os requisitos legais.</b>
		 
		<br><br>A resposta deve ser enviada para o seguinte correio eletrônico: <b><u>$emailPrincipal</u></b>, com cópia para <u>$emailCopia</u> .
		<br><br><b>Sem prejuízo quanto à responsabilidade referente à gestão contratual própria do setor demandante,
		é imprescindível a resposta deste email, ainda que inexista interesse na prorrogação, para fins de controle e registro desta unidade</b>.
		 
		<br><br>Caso esta solicitação já tenha sido respondida, <b>favor informar o número do SEI que trata da presente questão</b>.
	
		<br><br>Na ausência de manifestação, este e-mail será reenviado a cada <b>$numFrequencia dia(s)</b>.";
		 
		return $retorno;
	}
}
?>