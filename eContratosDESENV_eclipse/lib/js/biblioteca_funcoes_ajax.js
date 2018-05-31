 /**
  * Função abrirPasta
  */
 function abrirPasta(idCampoGestor, idDivResultado) {
     // Declaração de Variáveis
     var cdGestor   = "1";//document.getElementById(idCampoGestor).value;
     var result = document.getElementById(idDivResultado);
     imprimeResultado(result, "../../util/funcaoAbrirPastaAjax.php?" + idCampoGestor + "=" + cdGestor);     
 }

 
 /**
  * Função gestorPessoa
  */
 function getDadosGestorPessoa(idCampoGestor, idDivResultado) {
     // Declaração de Variáveis
     var cdGestor   = document.getElementById(idCampoGestor).value;
     var result = document.getElementById(idDivResultado);
     imprimeResultado(result, "../gestor_pessoa/comboGestorPessoaAjax.php?" + idCampoGestor + "=" + cdGestor);     
 }
 
 function getDataFimPrazo(chave, idDivResultado) {
     var result = document.getElementById(idDivResultado);
     link = "../pa/campoDataFimPrazoAjax.php?chave=" + chave;
     //alert(link);
     imprimeResultado(result, link);     
 }
 
 function getSqDocumentoAtual(chave, idDivResultado) {
     var result = document.getElementById(idDivResultado);
     link = "../documento/campoSqDocumentoAtual.php?chave=" + chave;
     //alert(link);
     imprimeResultado(result, link);     
 }

 function getTpDocumentoPorSetorAjax(chave, idDivResultado) {
     var result = document.getElementById(idDivResultado);
     link = "../documento/campoTpDocumentoAtual.php?chave=" + chave;
     //alert(link);
     imprimeResultado(result, link);     
 }

 /**
  * consulta os gestores para serem atribuidos a um responsavel
  * @param idCampoGestor
  * @param idDivResultado
  * @returns
  */
 function getDadosResponsavel(idCampoGestor, idDivResultado) {
     // Declaração de Variáveis	 
	 campoGestor = document.getElementById(idCampoGestor);
	 var cdGestor   = "";
	 if(campoGestor != null)
		 cdGestor   = document.getElementById(idCampoGestor).value;     
     
     var result = document.getElementById(idDivResultado);
     
     imprimeResultado(result, "../pessoa/campoResponsavelAjax.php?" + idCampoGestor + "=" + cdGestor);     
     
 }
 
 function getDadosContratadaPorContrato(chaveContrato, idDivResultado) {
     var result = document.getElementById(idDivResultado);          
     imprimeResultado(result, "../pessoa/campoDadosContratadaAjax.php?chave=" + chaveContrato);     
 }
 
 function getDadosContratadaPorDemanda(chaveDemanda, idDivResultado) {
     var result = document.getElementById(idDivResultado);          
     imprimeResultado(result, "../pessoa/campoDadosContratadaAjax.php?voentidade=vodemanda&chave=" + chaveDemanda);     
 }

 function getDadosContratoLicon(chave, idDivResultado) {
     var result = document.getElementById(idDivResultado);          
     imprimeResultado(result, "../contrato_licon/campoDadosContratoLicon.php?chave=" + chave);     
 }

 function getDadosContratadaPorPAAP(chavePAAP, idDivResultado) {
     var result = document.getElementById(idDivResultado);          
     imprimeResultado(result, "../pessoa/campoDadosContratadaAjax.php?voentidade=voPA&chave=" + chavePAAP);     
 }

 function getNovoCampoDadosContratoAjax(idDivResultado, pIndiceContratoAtual, pIsLimpar) {
     var result = document.getElementById(idDivResultado);
     var limpar = (pIsLimpar != null && pIsLimpar);
     if(limpar)
    	 retorno = "S";
     else
    	 retorno = "N";
     
     pIndiceContratoAtual++;
      
     imprimeResultado(result, "../contrato/campoDadosContratoAjax.php?limpar="+retorno + "&indice=" +pIndiceContratoAtual);     
 }

 function manterDadosTramitacaoPA(textofase, docfase, idDivResultado, funcao, indice) {     
     var result = document.getElementById(idDivResultado);          
     imprimeResultado(result, "../pa/gridTramitacaoAjax.php?funcao=" + funcao + "&textoTramitacao=" + textofase+ "&indice=" + indice+"&docFase=" + docfase);     
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