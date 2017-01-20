<?php
// .................................................................................................................
// Classe classConfig

  Class config
  {
        var $db;
        var $login;
        var $senha;
        var $odbc;
        var $driver;
        var $servidor;
		var $cDb;
          // ...............................................................
         // Construtor

        Function config ()
        {
                 $this->db        = "nome do banco de dados";
                 $this->login     = "root";
                 $this->senha     = "";
                 $this->odbc      = "";
                 $this->driver    = "";
                 $this->servidor  = "localhost";
        }
		
  }

?>