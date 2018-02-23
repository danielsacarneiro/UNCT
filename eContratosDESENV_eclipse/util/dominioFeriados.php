<?php
include_once(caminho_util."dominio.class.php");

Class dominioFeriados extends dominio{
	static $CD_PRIMEIRO_JAN = 'primeiro_jan';
	static $CD_PASCOA = 'pascoa';
	static $CD_SEXTA_SANTA = 'sexta_santa';
	static $CD_CARNAVAL  = 'carnaval';
	static $CD_DATA_MAGNA = 'data.magna';
	static $CD_TIRADENTES = 'tiradentes';
	static $CD_TRABALHO = 'trabalho';
	static $CD_SAO_JOAO = 'sao_joao';
	static $CD_NOSSA_SRA_CARMO = 'CD_NOSSA_SRA_CARMO';
	static $CD_INDEPENDENCIA = 'CD_INDEPENDENCIA';

	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
		//ksort($this->colecao);
	}

	static function getColecao(){
		$ano =  getAnoHoje();
		$dia = 86400;
		$datas = array();
		$datas['pascoa'] = easter_date($ano);
		$datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
		$datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
		//$datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
		$retorno = array (
				'01/01',
				date('d/m',$datas['carnaval']),
				date('d/m',$datas['sexta_santa']),
				date('d/m',$datas['pascoa']),
				'06/03', //data magna
				'21/04', //tiradentes
				'01/05',//dia do trabalho
				'24/06', //SAO JOAO
				'16/07', //NOSSA SRA DO CARMO
				'07/09',
				'12/10', //NOSSA SRA APARECIDA
				'28/10', //SERVIDOR PUBLICO
				'02/11', //FINADOS
				'15/11', //PROCLAMACAO REPUBLICA
				'08/12',//NOSSA SRA DA CONCEICAO
				'25/12',
				
		);
		
		return $retorno;
	}

}