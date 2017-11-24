<?php
include_once(caminho_util."dominio.class.php");

Class dominioClassificacaoContrato extends dominio{
	static $CD_SERVICOS = 1;
	static $CD_FORNECIMENTO_AQUISICAO = 2;
	static $CD_MAO_OBRA = 3;
	static $CD_LOCACAO_IMOVEL = 4;

	static $DS_SERVICOS = 'Servi�os';
	static $DS_FORNECIMENTO_AQUISICAO = "Fornec. e Aquisi��o";
	static $DS_MAO_OBRA = "Terceiriza��o M�o de Obra";
	static $DS_LOCACAO_IMOVEL = "Loca��o im�vel";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				self::$CD_SERVICOS => self::$DS_SERVICOS,
				self::$CD_FORNECIMENTO_AQUISICAO => self::$DS_FORNECIMENTO_AQUISICAO,
				self::$CD_MAO_OBRA => self::$DS_MAO_OBRA,
				self::$CD_LOCACAO_IMOVEL => self::$DS_LOCACAO_IMOVEL,
		);
	}

	static function isClassificaoMaoDeObra($opcao){
		return self::$CD_MAO_OBRA == $opcao;
	}	
}
?>