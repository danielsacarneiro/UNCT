<?php
include_once ("select.php");

// .................................................................................................................
  Class radiobutton extends select{

// ...............................................................
// Construtor

// ...............................................................
// Funcoes ( Propriedades e metodos da classe )    
  	function getHtmlRadio($idRadio, $nmRadio, $opcaoSelecionada, $comOpcaoTodos, $disabled) {
  		return $this->getHtmlRadioButton($idRadio, $nmRadio, $opcaoSelecionada, $comOpcaoTodos, "");	
  	}
  	
  	function getHtmlRadioButton($idRadio, $nmRadio, $opcaoSelecionada, $comOpcaoTodos, $javaScript) {
		$html = "";
		
        /*if($opcaoSelecionada == null)
            $opcaoSelecionada = "N";*/
        
		$totalResultado = count($this->colecao);				 		
		$chaves = array_keys($this->colecao);		

		for ($i=0; $i<$totalResultado; $i++) {
			$cd = $chaves[$i];
			$ds = $this->colecao[$cd];
			
			$id = $idRadio.complementarCharAEsquerda("$i", "0", 2);
			$html .= $this->getRadioValue($id, $nmRadio, $cd, $ds, $opcaoSelecionada, $javaScript);		
		}	
		//inclui opcao vazio
		if($comOpcaoTodos){
			$id = $idRadio.complementarCharAEsquerda("$i", "0", 2);
			$html .= $this->getRadioValue($id, $nmRadio,"", "Todos", null, $javaScript);
		}

		return $html;
	}
	    
    function getRadioValue($idRadio, $nmRadio, $cd, $ds, $opcaoSelecionada, $javaScript) {
		$selected = "";
        
		if($this->selected($cd, $opcaoSelecionada)){			
            $selected = "checked";
		}
		
		return $html = "<input type='radio' id='$idRadio' name='$nmRadio' value='$cd' $javaScript $selected>$ds</input>";
	}
		
}
?>