<?php
include_once (caminho_util . "multiplosConstrutores.php");
class dominio extends multiplosConstrutores {
	var $colecao;
	
	// ...............................................................
	// Construtor
	// herda do pai
	
	function __construct0 () {
		$this->colecao = static::getColecao();
	}
	
	function __construct1 ($colecao) {
		$this->colecao = $colecao;
	}	
	
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	static function getDescricao($chave) {
		//return self::getDescricaoStatic ( $chave, $this->colecao );
		return self::getDescricaoStatic ( $chave, static::getColecao ());
	}
	
	/**
	 *
	 * @param unknown $chave        	
	 * @deprecated
	 *
	 */
	static function getDescricaoStaticTeste($chave) {
		return static::getDescricaoStatic ( $chave );
	}
	static function getDescricaoStatic($chave, $colecao = null, $isDescricaoMaiuscula=false) {
		$retorno = $chave;
		if ($colecao == null) {
			$colecao = static::getColecao ();
		}
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			// echo "chave selecionada: ". $chave. "<br>";
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				// echo "chave: ". $cd . "<br>";
				
				if ($cd == $chave) {
					$retorno = $colecao [$cd];
					break;
				}
			}
		}
		
		if($isDescricaoMaiuscula){
			$retorno = strtoupper($retorno);
		}
		
		return $retorno;
	}
	/**
	 * 
	 * @param unknown $colecaochave
	 * @param string $isDescricaoMaiuscula
	 * @return string
	 */
	static function getDescricaoColecaoChave($colecaochave, $isDescricaoMaiuscula=false, $pColecaoDominioOpcional=null) {
		$tamMaximo = 15;
		
		if($colecaochave != null){
			if(!is_array($colecaochave)){
				$colecaochave = voentidade::getStringCampoSeparadorComoArray($colecaochave);
			}		
			
			$strSeparador = ".";
			foreach ($colecaochave as $chave){
				if(strlen($retorno) >= $tamMaximo){
					$strSeparador = "<br>";
				}				
				$retorno = $retorno . $strSeparador . static::getDescricaoStatic($chave, $pColecaoDominioOpcional, $isDescricaoMaiuscula);
			}
			//retira o primeiro ponto;
			$retorno = substr($retorno, 1);
		
			if($isDescricaoMaiuscula){
				$retorno = strtoupper($retorno);
			}
		}
	
		return $retorno;
	}
	
	//$colecao tem que ser ou string separada por CAMPO_SEPARADOR
	//ou colecao do tipo dominio
	//NAO PODE SER COLECAO UNIDIMENSIONAL
	static function existeItemArrayOuStrCampoSeparador($chave, $colecao) {
		$retorno = false;
		if($colecao==null){
			$colecao = static::getColecao();
		}
		
		if(!is_array($colecao)){
			$colecao = voentidade::getStringCampoSeparadorComoArray($colecao);
			$retorno = in_array($chave, $colecao);
		}else{
			$retorno = array_key_exists($chave, $colecao);
		}		
		
		return $retorno;
	}
	static function existeItem($chave, $colecao=null) {
		if($colecao==null){
			$colecao = static::getColecao();
		}		
		return array_key_exists($chave, $colecao);		
	}
	static function removeElementoStatic($chave, $colecao) {
		$retorno = array ();
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			// echo "chave selecionada: ". $chave. "<br>";
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				if ($cd != $chave) {
					// echo "chave$i: ". $cd . "<br>";
					$array2 = array (
							$cd => $colecao [$cd] 
					);
					$retorno = putElementoArray2NoArray1ComChaves ( $retorno, $array2 );
				}
			}
		}
		
		return $retorno;
	}
	static function getColecaoComElementosARemover($chaveARemover, $colecao = null) {
		// usado para o caso de um dominio que tenha a colecao chamar sem o 2 argumento
		if ($colecao == null) {
			$colecao = static::getColecao ();
		}
		
		// var_dump($colecao);
		$retorno = $colecao;
		if ($chaveARemover != null) {
			
			if (! is_array ( $chaveARemover )) {
				
				$retorno = self::removeElementoStatic ( $chaveARemover, $colecao );
			} else {
				
				foreach ( $chaveARemover as $chave ) {
					
					$retorno = self::removeElementoStatic ( $chave, $retorno );
				}
			}
			// echo "remove";
		}
		return $retorno;
	}
	
	static function getColecaoApenasComElementos($chaves, $colecao = null) {
		// usado para o caso de um dominio que tenha a colecao chamar sem o 2 argumento
		if ($colecao == null) {
			$colecao = static::getColecao ();
		}
	
		// var_dump($colecao);
		$retorno = $colecao;
		if ($chaves != null) {
				
			foreach ( array_keys ( $colecao ) as $chave ) {
	
				if (! in_array ( $chave, $chaves )) {
					$retorno = self::removeElementoStatic ( $chave, $retorno );
				}
			}
				
			// echo "remove";
		}
		return $retorno;
	}
	
	/**
	 * parametro $pStrChaveCodificada cria um codigo para chaves de dominios diferentes, mas que podem ter valores iguais
	 * por ex.: dominio01 = (01 => 'daniel') e dominio02 = (01=>'alice') . Usados no HTML contendo o id como chave, serao sobrescritos, 
	 * causando problemas no request e nas validacoes html
	 * @param unknown $chaves
	 * @param unknown $colecao
	 * @param unknown $pChaveCodificada
	 * @return string|unknown[]
	 */
	/*static function getColecaoApenasComElementos($chaves, $colecao = null, $pStrChaveCodificada=null) {
		// usado para o caso de um dominio que tenha a colecao chamar sem o 2 argumento
		if ($colecao == null) {
			$colecao = static::getColecao ();
		}
		
		if($chaves!=null && !is_array($chaves)){
			$chaves = array($chaves);
		}
		
		// var_dump($colecao);
		$retorno = array();
		if ($chaves != null) {
			
			foreach ( $chaves as $chave ) {
				$chaveTemp = $pStrChaveCodificada!=null?$pStrChaveCodificada.constantes::$CD_CAMPO_SEPARADOR. $chave:$chave;
				//echoo($chave);
				$retorno = array_merge_keys($retorno, array($chaveTemp=>$colecao[$chave]));
			}
			//var_dump($retorno);
			//echo ("chave codi" . $pStrChaveCodificada);
			// echo "remove";
		}
		return $retorno;
	}*/
	
	function getArrayHTMLChaves($nmVariavelHtml) {
		$retorno = getStrComPuloLinha ( "$nmVariavelHtml = new Array();" );
		$colecao = $this->colecao;
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				$retorno .= getStrComPuloLinha ( $nmVariavelHtml . "[" . $i . "] = '$cd';" );
			}
		}
		
		return $retorno;
	}
	
	static function getHtmlDetalhamento($id, $nm, $opcaoSelecionada, $isTrazerValuenoOption=false, $colecaoAlternativa=null) {
		$html = "";
		$value = self::getDescricaoStatic($opcaoSelecionada, $colecaoAlternativa);
		if($isTrazerValuenoOption){
			$value = select::getDescricaoComValueNoOption($opcaoSelecionada, $value);
		}
		if($value == null || $value == ""){
			$value = " ";			
		}
		
		$html = getInputText($id, $nm, $value, constantes::$CD_CLASS_CAMPO_READONLY)."\n";
		return $html;
	}
	
	static function getHtmlDetalhamentoRadio($id, $nm, $opcaoSelecionada, $colecaoAlternativa=null) {
		$html = "";

		if($colecaoAlternativa == null){
			$colecaoAlternativa = static::getColecao();
		}	
		
		if($opcaoSelecionada != null){
			$ds = self::getDescricaoStatic($opcaoSelecionada, $colecaoAlternativa);
		}else{
			$ds = constantes::$DS_OPCAO_NAO_INFORMADO;
		}
		return radiobutton::getHTMLRadioButtonStatic($id, $nm, $opcaoSelecionada, getTextoHTMLNegrito($ds), true);
	}
	
	/**
	 * o parametro $usarIdCodificado transforma o id, que geralmente eh usado somente como codigo (que pode se repetir), em um id unico
	 * conjugado com o $nm: esse uso impede que varios componentes htmls, de tipos diferentes, mas de mesmo codigo (e, portanto, mesmo id), 
	 * entrem em conflito
	 * 
	 * parametro $usarIdCodificado cria um codigo para chaves de dominios diferentes, mas que podem ter valores iguais
	 * por ex.: dominio01 = (01 => 'daniel') e dominio02 = (01=>'alice') . Usados no HTML contendo o id como chave, serao sobrescritos, 
	 * causando problemas no request e nas validacoes html
	 * @param unknown $nm
	 * @param unknown $opcaoSelecionada
	 * @param number $qtdItensPorColuna
	 * @param string $usarIdCodificado
	 * @return string
	 */
	static function getHtmlChecksBoxDetalhamento($nm, $opcaoSelecionada, $qtdItensPorColuna=4, $usarIdCodificado=false) {
		if(!is_array($opcaoSelecionada)){
			$opcaoSelecionada = getStringCampoSeparadorComoArray($opcaoSelecionada);
		}
		
		$colecao = static::getColecaoApenasComElementos($opcaoSelecionada);
		
		//return static::getHtmlChecksBox($nm, $opcaoSelecionada, $colecao, $qtdItensPorColuna, false, null, false, " disabled ");
		
		$pArray = array(
				$nm,
				$opcaoSelecionada,
				$colecao,
				$qtdItensPorColuna,
				false,
				null,
				false,
				"",
				null,
				null,
				null,
				$usarIdCodificado,
				false,
				false,
				true,
				);
		
		return static::getHtmlChecksBoxArray($pArray);
		
	}

	static function getHtmlChecksBox($nm, $opcaoSelecionada, $colecao=null, $qtdItensPorColuna=4, 
			$comOpcaoMarcarTodos=false, $javascriptadicional=null, $comBorracha = false, $htmlAdicional = null) {
				$pArray = array(
				$nm,
				$opcaoSelecionada,
				$colecao,
				$qtdItensPorColuna,
				$comOpcaoMarcarTodos,
				$javascriptadicional,
				$comBorracha,
				$htmlAdicional,				
		);
		
		return static::getHtmlChecksBoxArray($pArray);
	}
	static function getHtmlChecksBoxArray($pArray) {
		$nm = $pArray[0];
		$opcaoSelecionada = $pArray[1]; 
		$colecao = $pArray[2];
		$qtdItensPorColuna = $pArray[3];
		$comOpcaoMarcarTodos = $pArray[4];
		$javascriptadicional = $pArray[5];
		$comBorracha = $pArray[6];
		$htmlAdicional = $pArray[7];
		
		$comComboOR_And = $pArray[8]==null?false:$pArray[8];
		$nmComboOr_And = $pArray[9];
		$cdOpcaoSelecionadaComboOr_And = $pArray[10];
				
		$usarIdCodificado = $pArray[11];
		$comOpcaoNenhum = $pArray[12];
		$comOpcaoSimNao = $pArray[13];
		$isDetalhamento = $pArray[14];
		
		if($isDetalhamento == null){
			$isDetalhamento = false;
		}
		
		if($usarIdCodificado == null){
			$usarIdCodificado = false;
		}
		//a ordem eh importante porque o 'simnao' influencia no 'nenhum'
		if($comOpcaoSimNao == null){
			$comOpcaoSimNao = false;
		}			
		if($comOpcaoNenhum == null){
			$comOpcaoNenhum = false;
		}
		$comOpcaoNenhum = $comOpcaoNenhum && !$comOpcaoSimNao;
		$comOpcaoMarcarTodos = $comOpcaoMarcarTodos && !$comOpcaoSimNao;
		
		//var_dump($usarIdCodificado);
		
		if($colecao==null){
			$colecao = static::getColecao ();
			//echoo("coolecao NULA");
		}
		
		if($comOpcaoNenhum){
			$array2 = array (
					constantes::$CD_OPCAO_NENHUM => constantes::$DS_OPCAO_NENHUM,
			);
			$colecao = putElementoArray2NoArray1ComChaves ( $colecao, $array2);				
		}
		
		if($javascriptadicional != null){
			$javascript = " onClick=$javascriptadicional ";
		}
				
		$colecaoChave = array_keys($colecao);		
		
		$conector = "<br>";
		$i=0;	
		
		$html = "";
		$html.="<TABLE cellpadding='0' cellspacing='0'>";
		$html.="\n<TBODY>";
		$html.="\n<TR>";
		if($comOpcaoMarcarTodos){
			$html.="\n<TD>";
			$html .= getTextoLink("Todos", "javascript:if(!document.getElementsByName('$nm')[0].disabled){marcarTodosCheckBoxes('$nm');$javascriptadicional;}");
			$html.="\n</TD>";
			//$html .= getImagemLink("javascript:marcarTodosCheckBoxes('$nm');", "todos.gif");
		}
		
		$html.="\n<TD valign='top'>";
		$novaTD = false;
		
		//se $comOpcaoSimNao, a opcaoselecionada deve permanecer como array pra possibilitar achar o campo checked
		if(!$comOpcaoSimNao && is_array($opcaoSelecionada)){
			$opcaoSelecionada = getArrayComoStringCampoSeparador($opcaoSelecionada);
		}
		
		$isNenhumItemSelecionado = true;
		//var_dump($colecaoChave);
		
		foreach ($colecaoChave as $chave){
			$novaTD = $i%$qtdItensPorColuna==0;				
			if($novaTD){
				$conectorAntes = "</TD>\n<TD valign='top'>\n";
			}else{
				$conectorAntes = "";
			}
			
			$id = $usarIdCodificado?$nm . constantes::$CD_CAMPO_SEPARADOR. $chave :$chave;
			$descricao = static::getDescricaoStatic($chave,$colecao);
			if(!$comOpcaoSimNao){
				//sem o simnao, a validacao segue o rito normal
				$checked = stripos($opcaoSelecionada, "$chave", 0) !== false;
			}else if($opcaoSelecionada != null){
				// o checked aqui nao vai fazer efeito algum
				//ja que outros checks sao criados, com validacoes diferentes para o simnao
				//que sao feitas internamente no metodo abaixo getCheckBoxArray
				$chave = static::getChaveCheckSimNao($chave, $opcaoSelecionada);
				//echoo("chave $chave");
			}
			
			//para o caso de exibir pelo menos um item selecionado
			if($checked){
				$isNenhumItemSelecionado = false;
			}
			
			//var_dump($chave);
			//echoo("chave:$chave & selecao: $opcaoSelecionada");
			$arrayCheck = array($id, $nm, $chave, $checked, "$javascript $htmlAdicional", $descricao ,$comOpcaoSimNao, $isDetalhamento);
			if(!$isDetalhamento || ($isDetalhamento && $checked)){
				$html .= "\n".$conectorAntes 
					//. getCheckBoxBoolean($id, $nm, $chave, $checked, "$javascript $htmlAdicional", static::getDescricaoStatic($chave,$colecao))
					. getCheckBoxArray($arrayCheck)
					. "<br>";
			}
			
			$i++;
		}		

		//para o caso de ser detalhamento e nao haver nenhum selecionado
		if($isDetalhamento && $isNenhumItemSelecionado){
			//$html .= "Nenhum Item Selecionado";
			;
		}else{
			//artificio usado para tirar o ultimo <br>
			$html = removerUltimaString("<br>",$html);				
		}
		
		if($comBorracha){
			$nmCamposDoc = array(
					$nm,
			);				
			$borracha = getBorracha($nmCamposDoc, "");
		}
		
		if($comComboOR_And){
			require_once (caminho_util . "/selectOR_AND.php");
			$comboOuE = new selectOR_AND();			
			$comboOR_AND = $comboOuE->getHtmlSelect($nmComboOr_And,$nmComboOr_And, $cdOpcaoSelecionadaComboOr_And, false, "camponaoobrigatorio", false);
		}
		
		$html.="$borracha $comboOR_AND\n</TD>";
		$html.="\n</TR>";
		$html.="\n</TBODY>";
		$html.="\n</TABLE>";		
		
		return $html;
	}
	
	/**
	 * permite ao html reconhecer a existencia dos checks simnao e seleciona-los corretamente
	 * @param unknown $colecaoDominio
	 * @return string
	 */
	static function getChaveCheckSimNao($chave, $colecaoSelecionada){
		$retorno = $chave;
		if(!isColecaoVazia($colecaoSelecionada)){			
			foreach ($colecaoSelecionada as $item){
				$arrayAtrib = explode ( CAMPO_SEPARADOR, $item );
				$chaveAtual = $arrayAtrib[0];
				if($chaveAtual == $chave){
					$retorno = $item;
					break;
				}				
			}			
		}		
		return $retorno;
	}
	
	static function getColecaoComDescricao($colecaoChaveSemDescricao) {
		
		$array1 = array();
		foreach ($colecaoChaveSemDescricao as $chave){
			$array2 = array($chave => self::getDescricaoStatic($chave));
			$array1 = putElementoArray2NoArray1ComChaves($array1, $array2);	
		}
	
		return $array1;
	}
	
	static function getColecaoConsulta() {	
		return static::getColecao();
	}	
	
	static function getColecaoSimplesApenasDescricao($colecao=null) {
		if($colecao == null){
			$colecao = static::getColecaoConsulta();
		}
		
		$chaves = array_keys($colecao);
		foreach ($chaves as $chave){
			$retorno[] = self::getDescricao($chave);
		}
	
		return $retorno;
	}
	
	/**
	 * busca encontrar uma chave em $string (com separador ou nao), determinada pela ordem em que aparece em $colecao 
	 * @param unknown $string
	 * @param unknown $colecao
	 * @return mixed
	 */
	static function getChaveDeUmaStringPorExtenso($string, $colecao) {
		$tamanho = count ( $colecao );
		// echo $tamanho . "<br>";
		// var_dump($colecao) . "<br>";
		$chaves = array_keys ( $colecao );
		// echo "<br>especie:$string";
	
		for($i = 0; $i < $tamanho; $i ++) {
			$chave = $chaves [$i];
			$especie = $colecao [$chave];	
			$mystring = utf8_encode ( $especie );
			// $mystring = $especie;
			// echo "<br>$mystring X $string";
	
			// verifica se eh o tipo da especie em questao
			if (existeStr1NaStr2ComSeparador ( $string, $mystring, false )) {
				$retorno = $chave;
				break;
			}
		}
	
		if ($retorno != null) {
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echo "<br>EXISTE $string<br>";
			}
		} else {
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echo "<br>NAO EXISTE $string <br>";
			}
		}
		return $retorno;
	}
	
	static function getChaveDeUmaStringPorColecaoSimples($string, $colecao) {
		$tamanho = count ( $colecao );
	
		for($i = 0; $i < $tamanho; $i ++) {
			$especie = $colecao [$i];
			$mystring = utf8_encode ( $especie );
			// $mystring = $especie;
			// echo "<br>$mystring X $string";
	
			// verifica se eh o tipo da especie em questao
			if (existeStr1NaStr2($mystring, $string)) {
				$retorno = $especie;
				break;
			}
		}
	
		if ($retorno != null) {
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echo "<br>EXISTE $string SIMPLES <br>";
			}
		} else {
			if(dbcontrato::$FLAG_PRINTAR_LOG_IMPORTACAO){
				echo "<br>NAO EXISTE $string SIMPLES <br>";
			}
		}
		return $retorno;
	}
	
	/**
	 * retorna uma string no formato de colecao separada por separador e por aspas
	 * @param string $separador
	 * @param string $aspas
	 * @param unknown $colecao
	 * @return string|mixed
	 */
	static function getColecaoCdsSeparador($separador = ",", $aspas = "\"", $colecao = null) {
		if($colecao == null){
			$colecao = static::getColecao();
		}
		$tamanho = count ( $colecao );
		// echo $tamanho . "<br>";
		// var_dump($colecao) . "<br>";
		$chaves = array_keys ( $colecao );
		// echo "<br>especie:$string";		
		foreach($colecao as $chave => $descricao) {
			$retorno .= $aspas. $chave . $aspas . $separador;
		}
	
		if ($retorno != null) {
			$retorno = removerUltimaString($separador, $retorno);
		} 
		return $retorno;
	}
	
	
	/*
	 * function ordenaSetor( $a, $b ) {
	 * if ( $a['pontos'] == $b['pontos'] ) {
	 * return 0;
	 * }
	 * return ( $a['pontos'] < $b['pontos'] ) ? -1 : 1;
	 * }
	 * $ranking = array(
	 * 0 => array( 'nome' => 'Davi', 'pontos' => 2 ),
	 * 1 => array( 'nome' => 'Letícia', 'pontos' => 4 ),
	 * 2 => array( 'nome' => 'Francisco', 'pontos' => 1 ),
	 * 3 => array( 'nome' => 'Cecília', 'pontos' => 3 ),
	 * )
	 * usort( $ranking, 'ordenaPontos' );
	 */
}
?>