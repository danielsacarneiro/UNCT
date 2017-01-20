<?php
include("../util/select.php");
include("../util/dominioEspeciesContrato.php");

// Verifica se existe a variável txtnome
//if (isset($_GET["txtnome"])) {
	$testeNome = @$_GET["txtnome"];
	//funcao p dar tempo da imagem de carregamento	
	//sleep(3);
	
	if($testeNome == "ok"){
		$especiesContrato = new dominioEspeciesContrato();
		$combo = new select($especiesContrato->getColecao());
		
		echo $combo->getHtml("cdEspecie","cdEspecie", "");
	}
	else{
		echo "palavra errada";
	}
	

//}
?>
