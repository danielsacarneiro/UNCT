<?php
//include_once ("../../config_lib.php");
include_once (caminho_util . "multiplosConstrutores.php");
// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require_once ("class.phpmailer.php");
require_once ("class.smtp.php");

class email_sefaz extends multiplosConstrutores{
	
	static $FLAG_ENVIAR_EMAIL = true;
	
	private $Host;
	private $Port;
	private $Username;
	private $Password;
	private $FromName;
	
	var $mail;
	// ...............................................................
	// Construtor
	Function __construct0() {
		$this->getConfigEmailSefaz("correio.sefaz.pe.gov.br", "25", 'daniel.ribeiro@sefaz.pe.gov.br', 'C@rbeiro01', "e-Conti" );
	}
	private function getConfigEmailSefaz($host, $port, $user, $pwd, $remetente) {
		$this->Host = $host;
		$this->Port = $port;
		$this->Username = $user;		
		$this->Password = $pwd;
		$this->FromName = $remetente;
	}
	
	static function getListaEmailJuridico(){
		return array("daniel.ribeiro@sefaz.pe.gov.br",
				"patricia.farias@sefaz.pe.gov.br",
				"rogerio.f-carvalho@sefaz.pe.gov.br"
		);
	}
	
	protected static function setListaEmail($mail, $listaDestinatarios){
		foreach ($listaDestinatarios as $destinatario){
			$mail->AddAddress($destinatario, '');
			//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
			//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // C�pia Oculta
		}
	}
	
	protected function criarEmail($listaDestinatarios){
		$mail = new PHPMailer();
		// Define os dados do servidor e tipo de conex�o
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsSMTP(); // Define que a mensagem ser� SMTP
		$mail->Host = $this->Host; // Endere�o do servidor SMTP
		$mail->Port= $this->Port; // Endere�o do servidor SMTP
		//$mail->SMTPAuth = true; // Usa autentica��o SMTP? (opcional)
		//$mail->SMTPSecure = 'ssl';
		$mail->Username = $this->Username; // Usu�rio do servidor SMTP
		$mail->Password = $this->Password; // Senha do servidor SMTP
		// Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->From = $this->Username; // Seu e-mail
		$mail->FromName = $this->FromName; // Seu nome	
		// Define os destinat�rio(s)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		static::setListaEmail($mail, $listaDestinatarios);	
		// Define os dados t�cnicos da Mensagem
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
		$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
		
		return $mail; 
	}
	
	function enviarMensagem($listaDestinatarios, $mensagem, $assunto = null){
		$mail = $this->criarEmail($listaDestinatarios);		
		// Define a mensagem (Texto e Assunto)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		if($assunto == null){
			$assunto = "AVISO";
		}
		$mail->Subject  = $assunto; // Assunto da mensagem
		
		$mail->Body = $mensagem;
		$mail->AltBody = "Mensagem em texto plano (n�o html)! \r\n $mensagem :)";
		
		// Envia o e-mail
		$enviado = $mail->Send();
		// Limpa os destinat�rios e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		
		$this->mail =$mail;
		
		return $enviado;		 
	}
}
?>