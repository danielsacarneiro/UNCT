<?php
class funcoes {

        function formatCPF($cpf) {
                $com = substr($cpf,0,3);
                $mei = substr($cpf,3,3);
                $fin = substr($cpf,6,3);
                $dig = substr($cpf,9,3);
                return $cpf = $com.".".$mei.".".$fin."-".$dig; 
        }
    
	function formatDate($data, $formato, $separador) {
		switch ($formato){
			case "ymd":
				$dia = substr($data,0,2);
				$mes = substr($data,3,2);
				$ano = substr($data,6,4);
				return $data = $ano.$separador.$mes.$separador.$dia; 
				break;
			case "dmy":
				$dia = substr($data,8,2);
				$mes = substr($data,5,2);
				$ano = substr($data,0,4);
				return $data = $dia.$separador.$mes.$separador.$ano;	
				break;
			case "my":
				$mes = substr($data,5,2);
				$ano = substr($data,0,4);
				return $data = $mes.$separador.$ano;	
				break;
			case "ym":
				$mes = substr($data,0,2);
				$ano = substr($data,3,4);
				return $data = $ano.$separador.$mes;	
				break;	
		}		
	}
	
	function mesReferenteExtenso($referente){
		$meses[0] = "01-Janeiro";
		$meses[1] = "02-Fevereiro";
		$meses[2] = "03-Março";
		$meses[3] = "04-Abril";
		$meses[4] = "05-Maio";
		$meses[5] = "06-Junho";
		$meses[6] = "07-Julho";
		$meses[7] = "08-Agosto";
		$meses[8] = "09-Setembro";
		$meses[9] = "10-Outubro";
		$meses[10] = "11-Novembro";
		$meses[11] = "12-Dezembro";

		$vReferente = explode("-",$referente);
		for ($j=0;$j<=11;$j++) {
			$vMeses = explode("-",$meses[$j]);
			if ($vMeses[0] == $vReferente[1]) { 
				$vMesProcurado = $vMeses[1]; 
				$vAnoProcurado = $vReferente[0];
			}
		}
		return $vMesProcurado."/".$vAnoProcurado;
	}
	
	function mesExtenso($referente){
		$meses[0] = "01-Janeiro";
		$meses[1] = "02-Fevereiro";
		$meses[2] = "03-Março";
		$meses[3] = "04-Abril";
		$meses[4] = "05-Maio";
		$meses[5] = "06-Junho";
		$meses[6] = "07-Julho";
		$meses[7] = "08-Agosto";
		$meses[8] = "09-Setembro";
		$meses[9] = "10-Outubro";
		$meses[10] = "11-Novembro";
		$meses[11] = "12-Dezembro";

		$vReferente = explode("-",$referente);
		for ($j=0;$j<=11;$j++) {
			$vMeses = explode("-",$meses[$j]);
			if ($vMeses[0] == $vReferente[1]) { 
				$vMesProcurado = $vMeses[1]; 
				$vAnoProcurado = $vReferente[0];
			}
		}
		return $vMesProcurado;
	}
	
	function geraSenha($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
        }
        return $code;
    }	

	// Retorna o nome do mês de acordo com o respectivo número
	function NomeMes($numero) {
		$mes = "";
		switch ($numero) {
		    case 1: $mes = "Janeiro"; break;
		    case 2: $mes = "Fevereiro"; break;
		    case 3: $mes = "Março"; break;
		    case 4: $mes = "Abril"; break;
		    case 5: $mes = "Maio"; break;
		    case 6: $mes = "Junho"; break;
		    case 7: $mes = "Julho"; break;
		    case 8: $mes = "Agosto"; break;
		    case 9: $mes = "Setembro"; break;
		    case 10: $mes = "Outubro"; break;
		    case 11: $mes = "Novembro"; break;
		    case 12: $mes = "Dezembro"; break;
		}
		return $mes;
	}

	function somarData($data, $dias, $meses, $ano)
	{
	   //passe a data no formato dd/mm/yyyy 
	   $data = explode("/", $data);
	   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
	     $data[0] + $dias, $data[2] + $ano) );
	   return $newData;
	}
	
	function subtrData($data, $dias, $meses, $ano)
	{
	   //passe a data no formato dd/mm/yyyy 
	   $data = explode("/", $data);
	   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] - $meses,
	     $data[0] - $dias, $data[2] - $ano) );
	   return $newData;
	}

        

}	

?>