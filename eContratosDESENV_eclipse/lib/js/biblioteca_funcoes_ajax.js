 /**
  * Função gestorPessoa
  */
 function getDadosGestorPessoa(idCampoGestor, idDivResultado) {
     // Declaração de Variáveis
     var cdGestor   = document.getElementById(idCampoGestor).value;
     var result = document.getElementById(idDivResultado);
     imprimeResultado(result, "../gestor_pessoa/comboGestorPessoaAjax.php?" + idCampoGestor + "=" + cdGestor);     
 }
 
/**
  * Função para criar um objeto XMLHTTPRequest
  */
 function CriaRequest() {
     try{
         request = new XMLHttpRequest();        
     }catch (IEAtual){
         
         try{
             request = new ActiveXObject("Msxml2.XMLHTTP");       
         }catch(IEAntigo){
         
             try{
                 request = new ActiveXObject("Microsoft.XMLHTTP");          
             }catch(falha){
                 request = false;
             }
         }
     }
     
     if (!request) 
         alert("Seu Navegador não suporta Ajax!");
     else
         return request;
 }

 /**
  * Função para enviar os dados
  */
 function imprimeResultado(objectResult, paginaAplicacao) {
     // Exibi a imagem de progresso
     objectResult.innerHTML = '<img src="../../imagens/loading/loading26.gif"/>';
     
     // Iniciar uma requisição
	 /*
		Primeiro argumento define qual método de envio deverá ser utilizado (GET ou POST).
		Segundo argumento especifica a URL do script no servidor.
		Terceiro argumento especifica que a requisição deverá ser assíncrona.
	  */
     var xmlreq = CriaRequest();
     xmlreq.open("GET", paginaAplicacao, true);     
     // Atribui uma função para ser executada sempre que houver uma mudança de ado
     xmlreq.onreadystatechange = function(){
         
         // Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
		 /*
		  A lista completa dos valores readyState é a seguinte:

0 (não inicializado)
1 (carregando)
2 (carregado)
3 (interativo)
4 (completo)
		  */
         if (xmlreq.readyState == 4) {
             
             // Verifica se o arquivo foi encontrado com sucesso
             if (xmlreq.status == 200) {
                 objectResult.innerHTML = xmlreq.responseText;
             }else{
				/*
			  // there was a problem with the request,
				// for example the response may contain a 404 (Not Found)
			    // or 500 (Internal Server Error) response code
				 */
				
                 objectResult.innerHTML = "Erro: " + xmlreq.statusText;
             }
         }
     };
     xmlreq.send(null);
 }