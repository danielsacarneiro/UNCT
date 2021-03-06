<?php

    function existeStr1NaStr2ComSeparador($str2, $str1comseparador){
        $array = explode("*",$str1comseparador);
        $tamanho = count($array);
        $retorno = false;

        //echo "<br> IMPRIMINDO OPCOES-----";
        //var_dump($array) . "<br>";
        
        for($i=0; $i<$tamanho;$i++){                        
            $especie = $array[$i];            
            //verifica se eh o tipo da especie em questao
            $existe = mb_stripos($str2, $especie);
            //echo "<br>$str2 x $especie";

            if($existe !== false){
            //if($existe){
                //echo "<br>EXISTE<br>";
                $retorno = true;
                break;
            }
        }        
        return $retorno;        
    }
    
    function getIndicePosteriorAoUltimoNumeroAPartirDoComeco($param){
        $tamanho = strlen($param);
        $retorno = null;
        
        //echo $tamanho;
        for($i=0; $i<$tamanho;$i++){
            $char = substr($param,$i,1);
            //echo "<br>$char<br>";
            
            if(!isNumero($char)){
                $retorno = $i;
                break;
            }
        }
        
        return $retorno;
    }

    function getIndiceBarraOuPonto($param){
        $tamanho = strlen($param);
        $retorno = null;
        
        for($i=$tamanho-1; $i>=0;$i--){
            $char = substr($param,$i,1);
            
            if($char == "." || $char == "/"){
                $retorno = $i;
                break;
            }
        }
        
        return $retorno;
    }
    
    function isNumero($param){
        return isNumeroComDecimal($param, true);
    }
    
    function isNumeroComDecimal($param, $isDecimal){
        $referencia = "0123456789";

        if($isDecimal)        
            $referencia = $referencia . ".";
            
        $retorno = false;
        
        $tam = strlen($param."");
        //echo "tamanho da string do numero $param :" . $tam . "<br>";
        
        for($i=0; $i < $tam;$i++){
            $digito = substr($param, $i, 1);
            $val = "$digito";
            
            if(strpos($referencia, "$val") || $val == "0"){
                //echo $val. " é numero <br>";
                $retorno = true;
            }else{
                //echo $val. " nao é numero <br>";
                $retorno = false;
                break;
            }
        }
        
        return $retorno;
    }
    
    function removeColecaoAtributos($colecaoAtributos, $arrayAtribRemover) {
            $retorno = $colecaoAtributos;
            
            if($arrayAtribRemover != null){
                $totalResultado = count($arrayAtribRemover);
                //echo "<br> qtd elementos a remover: " . $totalResultado;
                               
                for ($i=0; $i<$totalResultado; $i++) {
                    $atrib = $arrayAtribRemover[$i];
                    $retorno = removeElementoArray($retorno, $atrib);
                }
            }
            //echo "<br>"; var_dump($retorno);
            return $retorno;
    }
    
    function removeElementoArray($input, $elem){
        $key = array_search($elem, $input);
        if($key){
            //echo "<br> removendo elemento: " . $input[$key];
            //unset($input[$key]);
            $input[$key] = null;
        }
        
        return $input;
    }    

    function getColecaoEntreSeparador($colecaoAtributos, $separador) {
            $retorno = "";
            if($colecaoAtributos != null){
                $tamanho = count($colecaoAtributos);
                //echo "<br> qtd registros: " . $tamanho;
                               
                for ($i=1; $i<=$tamanho; $i++) {
                    $atrib = $colecaoAtributos[$i];
                    if($atrib != null)
                        $retorno .= $atrib . $separador;
                   //echo "$retorno<br>";
                }
                $retorno = substr($retorno, 0, count($retorno)-2);
            }
            //echo $retorno;
            return $retorno;
    }
    
    function getNomeArquivoPHP(){
        $link = getLinkChamadaPHP();
        return basename($link,'.php');        
    }

    function isPastaRaiz(){
        $retorno = false;
        
        $pastaRaiz = "/wordpress";        
        $nmPasta = getNomePastaArquivoPHP();
        
        //echo $nmPasta;
        $indice = getIndiceBarraOuPonto($nmPasta);        
        $nmPasta = substr($nmPasta, 0, $indice);                
        //echo "|$nmPasta";
        
        if($pastaRaiz == $nmPasta)
            $retorno = true;        
                
        return $retorno;
    }
    
    function subirNivelPasta($pasta, $qtdNiveis){
        $retorno = $pasta;
        $strARemover = "../"; //3 digitos
        $fator = strlen($strARemover); //3 digitos        
        if ($qtdNiveis != null){            
            //posicao inicial = $fator*$qtdNiveis
            //se sao 2 niveis, sao 6 digitos a apagar, por ex
            $indice = $fator*$qtdNiveis;
            $retorno = substr($retorno, $indice);
            
            /*for ($i=1; $i<=$qtdNiveis; $i++) {            
                $retorno .= "../";
            }*/
        }
        return $retorno;
    }

    function getNomePastaArquivoPHP(){
        $link = getLinkChamadaPHP();
        return dirname($link); 
    }

    function getLinkChamadaPHP(){        
        return $_SERVER['PHP_SELF'];
    }    

?>