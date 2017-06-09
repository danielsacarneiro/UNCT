<?php
include_once (caminho_util . "multiplosConstrutores.php");
include_once (caminho_util . "dominio.class.php");

// .................................................................................................................
// Classe select
// cria um combo select html
class select extends multiplosConstrutores {
	var $colecao;
	// ...............................................................
	// Construtor
	// recebe uma colecao Cd x Descricao
	function __construct0() {
		self::__construct1 ( array () );
	}
	function __construct1($colecao) {
		$this->colecao = $colecao;
		reset ( $this->colecao );
	}
	function __construct3($recordSet, $nmColCD, $nmColDS) {
		$colecao = array();
		
		for ($i=0; $i<count($recordSet);$i++){			
			$cd = $recordSet[$i][$nmColCD];
			$ds = $recordSet[$i][$nmColDS];			
			$colecao[$cd]=$ds;
		}

		self::__construct1($colecao);	
	}
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function getHtml($idSelect, $nmSelect, $opcaoSelecionada) {
		return $this->getHtmlOpcao ( $idSelect, $nmSelect, $opcaoSelecionada, true );
	}
	function getHtmlComObrigatorio($idSelect, $nmSelect, $opcaoSelecionada, $isTrazerValuenoOption, $isCampoObrigatorio) {
		$html = "";
		if ($isCampoObrigatorio)
			$html = " required";
		
		return $this->getHtmlCombo ( $idSelect, $nmSelect, $opcaoSelecionada, true, "camponaoobrigatorio", $isTrazerValuenoOption, $html );
	}
	function getHtmlOpcao($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione) {
		return $this->getHtmlOpcaoClass ( $idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, "camponaoobrigatorio" );
	}
	function getHtmlOpcaoClass($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class) {
		return $this->getHtmlSelect ( $idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, false );
	}
	function getHtmlSelect($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption) {
		return $this->getHtmlCombo ( $idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption, "" );
	}
	function getHtmlCombo($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption, $TagEJavaScript) {
		$html = "";
		$html = "<select id='$idSelect' name='$nmSelect' class='$class' $TagEJavaScript>\n";
		
		// inclui opcao vazio
		if ($comOpcaoSelecione) {
			$html .= $this->getOpcao ( "", "-- Selecione --", null );
			// $html .= $this->getOpcao(constantes::$CD_OPCAO_VAZIO, "-- Selecione --", null);
		}
		
		// var_dump( $this->colecao);
		// echo $idSelect .":". $opcaoSelecionada;
		
		$totalResultado = count ( $this->colecao );
		$chaves = array_keys ( $this->colecao );
		
		for($i = 0; $i < $totalResultado; $i ++) {
			$cd = $chaves [$i];
			$ds = $this->colecao [$cd];
			
			$html .= $this->getOpcaoValue ( $cd, $ds, $opcaoSelecionada, $isTrazerValuenoOption );
		}
		
		$html .= "</select>";
		return $html;
	}
	function getOpcao($cd, $ds, $opcaoSelecionada) {
		return $this->getOpcaoValue ( $cd, $ds, $opcaoSelecionada, false );
	}
	function getOpcaoValue($cd, $ds, $opcaoSelecionada, $isTrazerValuenoOption) {
		$selected = "";
		if ($cd!="" && $this->selected ( $cd, $opcaoSelecionada )){
			$selected = "selected";
			
			//echo "selecionado:".$cd;
		}
		
		$descricao = $ds;
		if ($isTrazerValuenoOption)
			$descricao = $cd . " - " . $ds;
		
		return "<option value='" . $cd . "' " . $selected . ">" . $descricao . "</option>\n";
	}
	function selected($cd, $opcaoSelecionada) {
		if (! is_array ( $opcaoSelecionada )) {
			return $cd == $opcaoSelecionada;
		} else {
			return in_array ( $cd, $opcaoSelecionada );
		}
	}
	function getRecordSetComoColecaoSelect($nmColunaCd, $nmColunaDs, $recordSet) {
		$totalResultado = count ( $recordSet );
		
		// var_dump($recordSet);
		// echo "qtd" . $totalResultado;
		$retorno = array ();
		if ($recordSet != "") {
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $recordSet [$i] [$nmColunaCd] . "";
				$ds = $recordSet [$i] [$nmColunaDs];
				
				$retorno [$cd] = $ds;
				// $atual = array($cd => $ds);
				// $retorno = array_merge($retorno, $atual);
			}
			// var_dump($retorno);
		}
		$this->colecao = $retorno;
		// reset($this->colecao);
	}
	function getHtmlComboMultiplo($pNmSelectOrigem, $pNmSelectDestino, $pColecaoSelecionada, $classDestino, $size, $javaScript) {
		
		$dominio = new dominio ();
		$colecaoOrigem = $this->colecao;
		if (! isColecaoVazia ( $pColecaoSelecionada )) {
			$colecaoOrigem = dominio::getColecaoComElementosARemover ( $pColecaoSelecionada, $this->colecao );
		}
		
		$colecaoDestino = dominio::getColecaoApenasComElementos ( $pColecaoSelecionada, $this->colecao );
		
		$comboOrigem = new select ( $colecaoOrigem );
		$comboDestino = new select ( $colecaoDestino );
		
		$html .= $this->getJQueryComboMultiplo($pNmSelectOrigem, $pNmSelectDestino);
		$html .= "<TABLE cellpadding='0' cellspacing='0'>";
		$html .= "<TBODY>";
		$html .= "<TR>";
		$html .= "<TD class='campoformulario'>";
		$html .= $comboOrigem->getHtmlCombo ( $pNmSelectOrigem, $pNmSelectOrigem, "", false, "camponaoobrigatorio", false, " size=$size multiple " );
		$html .= "</TD>";
		$html .= "<TD class='campoformulario'>";
		$html .= "	<input id='pAdd' type='button' value='>' ><br>";
		$html .= "	<input id='pAddAll' type='button' value='>>' > <br>";
		$html .= "	<input id='pRemove' type='button' value='<' ><br>";
		$html .= "	<input id='pRemoveAll' type='button' value='<<' >";
		$html .= "</TD>";
		$html .= "<TD class='campoformulario'>";
		$html .= $comboDestino->getHtmlCombo ( $pNmSelectDestino, $pNmSelectDestino . "[]", $pColecaoSelecionada, false, "$classDestino", false, " size=$size multiple $javaScript " );
		$html .= "</TD>";
		$html .= "</TR>";
		$html .= "</TBODY>";
		$html .= "</TABLE>";
		
		return $html;
	}
	function getJQueryComboMultiplo($ID_REQ_CDSETOR_ORIGEM, $ID_REQ_CDSETOR_DESTINO) {
		
		$html .= "<SCRIPT language='JavaScript' type='text/javascript' src='".caminho_js."jquery.js'></SCRIPT>";
		$html .= "<SCRIPT language='JavaScript' type='text/javascript'>";
		// jquery
		$html .= "$( document ).ready(function() {";
		
		$html .= "$('#pAdd').on('click', function() {";
		$html .= "var p = $('#$ID_REQ_CDSETOR_ORIGEM option:selected');";
		$html .= "p.clone().appendTo('#$ID_REQ_CDSETOR_DESTINO');";
		$html .= "p.remove();";
		
		$html .= "$('#$ID_REQ_CDSETOR_DESTINO option').prop('selected', true);";
		
		$html .= "});";
		
		$html .= "$('#pAddAll').on('click', function() {";
		$html .= "var p = $('#$ID_REQ_CDSETOR_ORIGEM option');";
		$html .= "p.clone().appendTo('#$ID_REQ_CDSETOR_DESTINO');";
		$html .= "p.remove();";
		
		$html .= "$('#$ID_REQ_CDSETOR_DESTINO option').prop('selected', true);";
		$html .= "});";
		
		$html .= "$('#pRemove').on('click', function() {";
		$html .= "var p = $('#$ID_REQ_CDSETOR_DESTINO option:selected');";
		$html .= "p.clone().appendTo('#$ID_REQ_CDSETOR_ORIGEM');";
		$html .= "p.remove();";
		
		$html .= "$('#$ID_REQ_CDSETOR_DESTINO option').prop('selected', true);";
		$html .= "});";
		
		$html .= "$('#pRemoveAll').on('click', function() {";
		$html .= "var p = $('#$ID_REQ_CDSETOR_DESTINO option');";
		$html .= "p.clone().appendTo('#$ID_REQ_CDSETOR_ORIGEM');";
		$html .= "p.remove();";
		
		$html .= "$('#$ID_REQ_CDSETOR_DESTINO option').prop('selected', true);";
		$html .= "});";
		
		$html .= "});";
		
		$html .= "</SCRIPT>";
		
		return $html;
	}
}
?>