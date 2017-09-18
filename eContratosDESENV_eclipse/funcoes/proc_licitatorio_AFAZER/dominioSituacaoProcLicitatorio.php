<?php
include_once(caminho_util."dominio.class.php");

  Class dominioSituacaoProcLicitatorio extends dominio{
  	static $CD_SITUACAO_PROCLIC_CONCLUIDO = 1;
  	static $CD_SITUACAO_PROCLIC_EM_ANDAMENTO = 2;
  	static $CD_SITUACAO_PROCLIC_FRACASSADO = 3;
  	static $CD_SITUACAO_PROCLIC_DESERTO = 4;
  	static $CD_SITUACAO_PA_CANCELADO = 5;

  	static $DS_SITUACAO_PROCLIC_CONCLUIDO = "Concluído";
  	static $DS_SITUACAO_PROCLIC_EM_ANDAMENTO = "Em andamento";
  	static $DS_SITUACAO_PROCLIC_FRACASSADO = "Fracassado";
  	static $DS_SITUACAO_PROCLIC_DESERTO = "Deserto";
  	static $DS_SITUACAO_PA_CANCELADO = "Cancelado";
  	 

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// Funcoes ( Propriedades e metodos da classe )

	static function getColecao(){
		return array(
				constantes::$CD_SITUACAO_PROCLIC_CONCLUIDO => constantes::$DS_SITUACAO_PROCLIC_CONCLUIDO,
				constantes::$CD_SITUACAO_PROCLIC_EM_ANDAMENTO => constantes::$DS_SITUACAO_PROCLIC_EM_ANDAMENTO,
				constantes::$CD_SITUACAO_PROCLIC_FRACASSADO => constantes::$DS_SITUACAO_PROCLIC_FRACASSADO,
				constantes::$CD_SITUACAO_PROCLIC_DESERTO => constantes::$DS_SITUACAO_PROCLIC_DESERTO,
				constantes::$CD_SITUACAO_PA_CANCELADO => constantes::$DS_SITUACAO_PA_CANCELADO,
		);
	}
	
	
}
?>