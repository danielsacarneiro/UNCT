<?php

class multiplosConstrutores{
	
	function __construct(){
		 
		$a = func_get_args();
		$i = func_num_args();
		
		/*echo "num argumentos:".$i; 
		echo "<br>argumentos:".$a;
		
		var_dump($a);*/
		if (method_exists($this,$f='__construct'.$i)) {
			call_user_func_array(array($this,$f),$a);
		}
	}

}

?>