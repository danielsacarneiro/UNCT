<?php
// .................................................................................................................
// Classe select
// cria um combo select html

  Class select {
  	var $colecao;

// ...............................................................
// Construtor
//recebe uma colecao Cd x Descricao	
	function __construct ($colecao) {		
		$this->colecao = $colecao;
		reset($this->colecao);
	}

// ...............................................................
// Funções ( Propriedades e métodos da classe )


	function getHtml($idSelect, $nmSelect, $opcaoSelecionada) {
		return $this->getHtmlOpcao($idSelect, $nmSelect, $opcaoSelecionada, true);
	}

	function getHtmlComObrigatorio($idSelect, $nmSelect, $opcaoSelecionada, $isTrazerValuenoOption, $isCampoObrigatorio) {
        $html = "";
        if($isCampoObrigatorio)
            $html = " required";
            
        return $this->getHtmlCombo($idSelect, $nmSelect, $opcaoSelecionada, true, "camponaoobrigatorio", $isTrazerValuenoOption, $html);
	}

	function getHtmlOpcao($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione) {
		return $this->getHtmlOpcaoClass($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, "camponaoobrigatorio");
	}

	function getHtmlOpcaoClass($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class) {
        return $this->getHtmlSelect($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, false);
    }
        
    function getHtmlSelect($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption) {
        return $this->getHtmlCombo($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption, "");
    }
    
    function getHtmlCombo($idSelect, $nmSelect, $opcaoSelecionada, $comOpcaoSelecione, $class, $isTrazerValuenoOption, $TagEJavaScript) {
		$html = "";
		$html = "<select id='$idSelect' name='$nmSelect' class='$class' $TagEJavaScript>\n";
			
		//inclui opcao vazio
		if($comOpcaoSelecione){
			$html .= $this->getOpcao("", "-- Selecione --", null);
			//$html .= $this->getOpcao(constantes::$CD_OPCAO_VAZIO, "-- Selecione --", null);
		}
			
		//var_dump( $this->colecao);
		
		$totalResultado = count($this->colecao);				 		
		$chaves = array_keys($this->colecao);

		for ($i=0; $i<$totalResultado; $i++) {
			$cd = $chaves[$i];
			$ds = $this->colecao[$cd];
			
			$html .= $this->getOpcaoValue($cd, $ds, $opcaoSelecionada, $isTrazerValuenoOption);		
		}
		
		
		$html .= "</select>";		
		return $html;
	}
	
	function getOpcao($cd, $ds, $opcaoSelecionada) {
        return $this->getOpcaoValue($cd, $ds, $opcaoSelecionada, false);
    }
    
    function getOpcaoValue($cd, $ds, $opcaoSelecionada, $isTrazerValuenoOption) {
		$selected = "";
		if($this->selected($cd, $opcaoSelecionada))
			$selected = "selected";
        
        $descricao =  $ds;
        if($isTrazerValuenoOption)
            $descricao = $cd . " - " . $ds;
		
		return "<option value='" . $cd . "' " . $selected . ">". $descricao . "</option>\n";
	}
	
	function selected($cd, $opcaoSelecionada) {
		
		//echo " $cd == $opcaoSelecionada ";
		if($cd == $opcaoSelecionada)
			return true;
		else
			return false;
	}
    
    function getRecordSetComoColecaoSelect($nmColunaCd, $nmColunaDs, $recordSet){			
		$totalResultado = count($recordSet);
        
        //var_dump($recordSet);
        //echo "qtd" . $totalResultado;
        $retorno = array();		
        if($recordSet !=""){            
            for ($i=0; $i<$totalResultado; $i++) {
                $cd = $recordSet[$i][$nmColunaCd]."";
                $ds = $recordSet[$i][$nmColunaDs];                        
                
                $retorno[$cd] = $ds;
                //$atual = array($cd => $ds);
                //$retorno = array_merge($retorno, $atual);
                           
            }
        //var_dump($retorno);
        }		
        $this->colecao = $retorno;
        //reset($this->colecao);
    }
	
}
?>