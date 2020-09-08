<?php
/**
 * Define uma classe de exceção
 */
class excecaoGenerica extends Exception
{	
	static $CD_EXCECAO_SUCESSO = 1;
	static $CD_EXCECAO_OBJETO_SESSAO_INEXISTENTE = 2;
	static $CD_EXCECAO_CLASS_NAO_ENCONTRADA = 3;
	static $CD_EXCECAO_NUM_PROC_LIC_INVALIDO = 4;
	static $CD_EXCECAO_SISTEMA_MANUTENCAO = 5;
	static $CD_EXCECAO_CHAVE_INEXISTENTE = 6;
	static $CD_EXCECAO_METODO_NAO_IMPLEMENTADO = 7;
	static $CD_EXCECAO_CHAVE_DUPLICADA = 1062;	
	
    // Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message, $code = 0, Exception $previous = null) {
    	if($message == null || $message == ""){
    		$message = "Exceção Genérica.";
    	}
    
        // garante que tudo está corretamente inicializado
        parent::__construct(get_class($this). "->". $message, $code, $previous);
    }
    // personaliza a apresentação do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}