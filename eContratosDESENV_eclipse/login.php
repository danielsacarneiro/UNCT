<?php
include_once ("../../wp-config.php");

$funcao = @$_GET["funcao"];    

//redireciona o user para o login se n tiver logado
if($funcao == "O"){
    wp_logout(); 
}else{
    if(!is_user_logged_in()){
        echo "usuario nao logado <br>";
        auth_redirect();
    }else{
        echo "usuario logado <br>"; 
    }
}

wp_safe_redirect("index.php");

?>