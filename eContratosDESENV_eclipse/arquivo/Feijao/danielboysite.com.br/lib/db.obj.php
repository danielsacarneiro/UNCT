<?php

// .................................................................................................................
// Classe db
// Faz a conexao com o banco: responsavel pelas tarefas realizadas atraves do banco de dados

  Class db {
  	var $id_conexao;
  	var $resultado;	

          // ...............................................................
         // Construtor

        function db () {
			$this->id_conexao = "";
			$this->resultado = "";			

        }


        // ...............................................................
        // Funções ( Propriedades e métodos da classe )

/************************************************************************/
//param:$db, $login, $senha, $odbc, $driver, $servidor
// return : $id_conexao
// retorna a identidade da conexao com o banco de dados especificado

        Function abrirConexao ($db, $login, $senha, $odbc, $driver, $servidor) {
				$this->id_conexao = mysqli_connect ($servidor, $login, $senha );
				if ($this->id_conexao){
         			mysqli_select_db ($this->id_conexao,$db);
					return $this->id_conexao;
				}
				else
					echo ("Erro ao conectar ao banco!");
        }



/************************************************************************/
// destroi a conexao com o bando de dados

        Function fecharConexao () {
        
                mysqli_close ($this->id_conexao);
        }


/************************************************************************/
// param:$query
// return: $resultado, a consulta realizada
// executa a query passada como parametro no banco, serve para as transacoes de consulta apenas

        Function consultar ($query) {
			  $this->limpaResultado();
              $dados = mysqli_query($this->id_conexao,$query);
              $numrows = mysqli_num_rows($dados);
              if ($numrows > 0) {

                    $nf = mysqli_num_fields($dados);	
                    for ($x = 0; $row = mysqli_fetch_row($dados); $x++) {  

                        for ($w = 0; $w < $nf; $w++){
                                $finfo = mysqli_fetch_field_direct($dados, $w);
                                $this->resultado[$x][$finfo->name] = $row[$w];
                        }	
                     }

                }
                    else
                            $this->resultado = "";
                
              return $this->resultado;

        }

/************************************************************************/
// param:$query
// return: $resultado, booleano que indica se a query foi realizada com sucesso ou nao
// executa a query de atualizacao,insercao ou delecao passada como parametro no banco

        Function atualizar ($query) {

		$this->limpaResultado();
                //$dados= mysqli_query ($query);
                $dados = mysqli_query($this->id_conexao,$query);
                // Verifica se ocorreu algum erro durante a execução da query.
                if ( strlen ( trim ( mysqli_error($this->id_conexao) ) ) ==  0 )
                        $resultado = 1; //operacao efetuada com sucesso
                else 
                        $resultado = 0;

                return $resultado;
        }
		

/************************************************************************/
// limpa o campo resultado 

        Function limpaResultado () {
		
				$this->resultado = "";
        }		



}


/************************************************************************/
//Função usada para retornar os campos da tabela pessoa
// param:$query
// return: $resultado, a consulta realizada
// executa a query passada como parametro no banco, serve para as transacoes de consulta apenas


        Function campos ($tabela) {
			  $this->limpaResultado(); 
              $res = mysqli_list_fields($this->id_conexao,$tabela); 
			  $numFields = mysqli_num_fields($res);
	        for ($i=0; $i < $numFields; $i++) {
                     $finfo = mysqli_fetch_field_direct($res, $i);
                     //$this->resultado[$x][$finfo->name] = $row[$w];
                     $this->resultado[$i] = [$finfo->name];
                    //$this->resultado[$i] = mysqli_field_name($res,$i);

                 }  
              return $this->resultado;

        }




/********************************* Fim da classe DB *******************************/
?>