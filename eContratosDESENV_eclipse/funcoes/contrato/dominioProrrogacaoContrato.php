<?php
include_once(caminho_util."dominio.class.php");

Class dominioProrrogacaoContrato extends dominio{
	static $CD_ART57_II = 2;
	static $CD_ART57_IV = 4;
	static $CD_ART57_V = 5;	
	static $CD_NAO_SEAPLICA = 99;

	static $DS_ART57_II = "Art.57, II - serv.contнnuos em geral";

	// https://www.zenite.blog.br/nos-contratos-de-locacao-de-equipamentos-com-fornecimento-de-insumos-o-prazo-e-a-possibilidade-de-prorrogacao-devem-ser-fundamentados-no-art-57-inc-ii-ou-no-inc-iv/
	static $DS_ART57_IV = "Art.57, IV - aluguel de equipamentos ou utilizaзгo de softwares";
	static $DS_ART57_V = "Art.57, V - casos de seguranca nacional";
	static $DS_NAO_SEAPLICA = "Nгo se aplica";
	
	// ...............................................................
	// Construtor
	// ...............................................................
	// FunГ§Гµes ( Propriedades e mГ©todos da classe )

	static function getColecao(){
		return array(				
				self::$CD_ART57_II => self::$DS_ART57_II,
				self::$CD_ART57_IV => self::$DS_ART57_IV,
				self::$CD_ART57_V => self::$DS_ART57_V,
				self::$CD_NAO_SEAPLICA  => self::$DS_NAO_SEAPLICA,
		);
	}

	/**
	 * serve apenas para o filtro consolidacao
	 * @return string[]
	 */
	static function getColecaoValidacaoSQL(){
		return array(
				self::$CD_ART57_II => self::$DS_ART57_II,
				self::$CD_ART57_IV => self::$DS_ART57_IV,
				self::$CD_ART57_V => self::$DS_ART57_V,
		);
	}
	
	static function getPrazoProrrogacao($cd){
		if($cd == static::$CD_ART57_II)
			$retorno = 5;
		else if($cd == static::$CD_ART57_IV)
			$retorno = 4;
		else if($cd == static::$CD_ART57_V)
				$retorno = 10;
					
		return $retorno;
	}
	
}
?>