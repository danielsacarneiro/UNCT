<?php
include_once ("select.php");

// .................................................................................................................
  Class radiobutton extends select{

// ...............................................................
// Construtor

// ...............................................................
// Funções ( Propriedades e métodos da classe )    
    function getHtmlRadio($idRadio, $nmRadio, $opcaoSelecionada, $comOpcaoTodos, $disabled) {
		$html = "";
		
        if($opcaoSelecionada == null)
            $opcaoSelecionada = "N";
        
		$totalResultado = count($this->colecao);				 		
		$chaves = array_keys($this->colecao);

		for ($i=0; $i<$totalResultado; $i++) {
			$cd = $chaves[$i];
			$ds = $this->colecao[$cd];
			
			$html .= $this->getRadioValue($idRadio, $nmRadio, $cd, $ds, $opcaoSelecionada);		
		}	
		//inclui opcao vazio
		if($comOpcaoTodos)
			$html .= $this->getRadioValue($idRadio, $nmRadio,"", "Todos", null);

		return $html;
	}
	    
    function getRadioValue($idRadio, $nmRadio, $cd, $ds, $opcaoSelecionada) {
		$selected = "";
        
		if($this->selected($cd, $opcaoSelecionada)){			
            $selected = "checked";
		}
		
		return $html = "<input type='radio' id='$idRadio' name='$nmRadio' value='$cd' $selected>$ds</input>";
	}
		
}
?>