<?php
// .................................................................................................................
// Classe classConfig

Class config {
        var $db;
        var $login;
        var $senha;
        var $odbc;
        var $driver;
        var $servidor;
				var $cDb;
          // ...............................................................
         // Construtor

      Function config (){
                 $this->db        = "unct";
                 $this->login     = "root";
                 $this->senha     = "carbeiro";
                 $this->odbc      = "";
                 $this->driver    = "";
                 $this->servidor  = "localhost";
      }
		
}

?>