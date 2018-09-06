<?php
include_once (caminho_util . "multiplosConstrutores.php");
class dominio extends multiplosConstrutores {
	var $colecao;
	
	// ...............................................................
	// Construtor
	// herda do pai
	
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function getDescricao($chave) {
		return self::getDescricaoStatic ( $chave, $this->colecao );
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
	
	static function getHtmlDetalhamento($id, $nm, $opcaoSelecionada, $isTrazerValuenoOption) {
		$html = "";
		$value = self::getDescricaoStatic($opcaoSelecionada);
		if($isTrazerValuenoOption){
			$value = select::getDescricaoComValueNoOption($opcaoSelecionada, $value);
		}
		if($value == null || $value == ""){
			$value = " ";			
		}
		
		$html = getInputText($id, $nm, $value, constantes::$CD_CLASS_CAMPO_READONLY)."\n";
		return $html;
	}
	
	static function getHtmlChecksBox($nm, $opcaoSelecionada, $colecao=null, $qtdItensPorColuna=4, $comOpcaoMarcarTodos=false, $javascriptadicional=null) {
		if($colecao==null){
			$colecao = static::getColecao ();
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
		foreach ($colecaoChave as $chave){
			$novaTD = $i%$qtdItensPorColuna==0;				
			if($novaTD){
				$conectorAntes = "</TD>\n<TD valign='top'>\n";
			}else{
				$conectorAntes = "";
			}
					
			$checked = stripos($opcaoSelecionada, "$chave", 0) !== false;
			$html .= "\n".$conectorAntes . getCheckBoxBoolean($chave, $nm, $chave, $checked, $javascript)." ". static::getDescricaoStatic($chave,$colecao) . "<br>";
			$i++;
		}
				
		$html.="\n</TD>";
		$html.="\n</TR>";
		$html.="\n</TBODY>";
		$html.="\n</TABLE>";		
		
		return $html;
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