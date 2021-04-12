<?php
//include_once ("../../config_lib.php");
include_once (caminho_util . "multiplosConstrutores.php");
// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require_once ("class.phpmailer.php");
require_once ("class.smtp.php");

class email_sefaz extends multiplosConstrutores{
	
	static $FLAG_ENVIAR_EMAIL = true;
	static $CD_IMAGEM_SEFAZLOGO = "SEFAZ_LOGO";
	static $REMETENTE_ATJA = "atja@sefaz.pe.gov.br";
	static $REMETENTE_UNCT = "unct@sefaz.pe.gov.br";
	static $REMETENTE_DILC = "carlos.pereira@sefaz.pe.gov.br";
	
	static $REMETENTE_COPIA = "eduardo.s-goncalves@sefaz.pe.gov.br";
	static $REMETENTE_PRINCIPAL = "unct@sefaz.pe.gov.br";
	
	private $Host;
	private $Port;
	private $Username;
	private $Password;
	private $FromName;
	
	var $mail;
	// ...............................................................
	// Construtor
	function __construct0() {
		$this->__construct1("");
		//$this->getConfigEmailSefaz("correio.sefaz.pe.gov.br", "25", 'daniel.ribeiro@sefaz.pe.gov.br', 'C@rbeiro03', constantes::$nomeSistema);
	}
		
	function __construct1($texto) {
		$str = $this->getNomeAExibir($texto);
		//$this->getConfigEmailSefaz("correio.sefaz.pe.gov.br", "25", 'daniel.ribeiro@sefaz.pe.gov.br', 'C@rbeiro03', $str);
		$this->getConfigEmailSefaz("correio.sefaz.pe.gov.br", "25", static::$REMETENTE_ATJA, '', $str);
	}
	function __construct2($texto, $remetente) {
		$str = $this->getNomeAExibir($texto);
		$this->getConfigEmailSefaz("correio.sefaz.pe.gov.br", "25", $remetente, '', $str);
	}
	
	/**
	 * 
	 * @param unknown $texto
	 */
	function getNomeAExibir($texto) {
		$str = constantes::$nomeSistema;
		if($texto != ""){
			$str .= " - $texto";
		}
		
		return $str;
	}
	
	private function getConfigEmailSefaz($host, $port, $user, $pwd, $nomeAExibir) {
		$this->Host = $host;
		$this->Port = $port;
		$this->Username = $user;		
		$this->Password = $pwd;
		$this->FromName = $nomeAExibir;
	}
	
	function setEmailRemetente($user) {
		$this->Username = $user;
	}
	
	static function getListaEmailJuridico(){
		return array(static::$REMETENTE_ATJA,
				//"patricia.farias@sefaz.pe.gov.br",
				"frederico.britto@sefaz.pe.gov.br",
				//"alfredo.carvalho@sefaz.pe.gov.br",
				"margarida.vasconcelos@sefaz.pe.gov.br",
				//"rogerio.f-carvalho@sefaz.pe.gov.br"
		);	
	}
	
	static function getListaEmailAvisoGestorContrato(){
		return array(
				static::$REMETENTE_PRINCIPAL,
				static::$REMETENTE_COPIA,
				static::$REMETENTE_DILC,
				//"rogerio.f-carvalho@sefaz.pe.gov.br",
				//"eduardo.s-goncalves@sefaz.pe.gov.br",
				//"daniel.ribeiro@sefaz.pe.gov.br",				
				//"margarida.vasconcelos@sefaz.pe.gov.br"
		);
	}
	
	static function getListaEmailLogAlertasGestor(){
		return array(
				"eduardo.s-goncalves@sefaz.pe.gov.br",
				"daniel.ribeiro@sefaz.pe.gov.br",
		);
	}
	
	static function getListaEmailContratosAVencer(){
		return array(
				"eduardo.s-goncalves@sefaz.pe.gov.br",
				"daniel.ribeiro@sefaz.pe.gov.br",
				static::$REMETENTE_DILC,
				//"rogerio.f-carvalho@sefaz.pe.gov.br",
		);
	}
	
	static function getListaEmailUNCT(){
		return array(
				"eduardo.s-goncalves@sefaz.pe.gov.br",
				"daniel.ribeiro@sefaz.pe.gov.br",
				//"rogerio.f-carvalho@sefaz.pe.gov.br",
				"juliene.paiva@sefaz.pe.gov.br",
				"Andrea.c-oliveira@sefaz.pe.gov.br",
				"andrielle.rodrigues@sefaz.pe.gov.br",
		);
	}
	
	protected static function setListaEmail($mail, $listaDestinatarios){
		foreach ($listaDestinatarios as $destinatario){
			$mail->AddAddress($destinatario, '');
			//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
			//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
		}
	}
	
	function criarEmail($listaDestinatarios){
		$mail = new PHPMailer();
		// Define os dados do servidor e tipo de conexão
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->Host = $this->Host; // Endereço do servidor SMTP
		$mail->Port= $this->Port; // Endereço do servidor SMTP
		//$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
		//$mail->SMTPSecure = 'ssl';
		$mail->Username = $this->Username; // Usuário do servidor SMTP
		$mail->Password = $this->Password; // Senha do servidor SMTP
		// Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->From = $this->Username; // Seu e-mail
		$mail->FromName = $this->FromName; // Seu nome	
		// Define os destinatário(s)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		static::setListaEmail($mail, $listaDestinatarios);	
		// Define os dados técnicos da Mensagem
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
		
		$this->mail = $mail; 
	}
	
	function addImagem($imagem, $cid){
		$this->mail->AddEmbeddedImage($imagem, $cid, $imagem);		
	}
	
	function enviarMensagem($mensagem, $assunto = null, $listaDestinatarios = null){
		//$mail = $this->criarEmail($listaDestinatarios);		
		$mail = $this->mail;
		// Define a mensagem (Texto e Assunto)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		if($assunto == null){
			$assunto = "AVISO";
		}
		
		$assunto .= " " . constantes::$nomeSistema . " - ALERTA AUTOMÁTICO";
		
		$mail->Subject  = $assunto; // Assunto da mensagem
		
		$mail->Body = $mensagem;
		$mail->AltBody = "Mensagem em texto plano (não html)! \r\n $mensagem :)";
		
		// Envia o e-mail
		$enviado = $mail->Send();
		// Limpa os destinatários e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		
		$this->mail =$mail;
		
		return $enviado;		 
	}
}
?>