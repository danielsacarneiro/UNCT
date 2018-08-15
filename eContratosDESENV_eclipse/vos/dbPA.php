<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes."pa/dominioSituacaoPA.php");
include_once (caminho_filtros."filtroManterPA.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbPA extends dbprocesso{
  	static $nmTabelaPublicacao = "nmTabelaPublicacao";
    
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
  		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaDemandaProcLic = voDemandaPL::getNmTabelaStatic ( false );
  		$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic ( false );  		
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato,
  				$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato,
  				$nmTabelaDemanda . "." . voDemanda::$nmAtrTexto,
  				self::$nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrDtReferencia,
  				$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrCd,
  				$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrAno,
  				
//  				$nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato,  
  		);
  	
  		$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabela . "." . voPA::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voPA::$nmAtrAnoDemanda;
  		
  		$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaContrato;
  		$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
  		$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
  		
  		$queryFrom .= "\n LEFT JOIN " . $nmTabelaDemandaProcLic;
  		$queryFrom .= "\n ON ";
  		$queryFrom .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
  		$queryFrom .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
  		
  		$queryFrom .= "\n LEFT JOIN " . $nmTabelaProcLic;
  		$queryFrom .= "\n ON ";
  		$queryFrom .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrAno;
  		$queryFrom .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCd;
  		
  		
  		$queryFrom .= $this->getSQLJoinDataPublicacaoPAAP($nmTabelaDemanda);
  		  		  		 		
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
  	}
  	
  	static function getSQLNmAtributoDtPublicacao(){
  		return self::$nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrDtReferencia;  		
  	}
  	
  	function getSQLJoinDataPublicacaoPAAP($nmTabelaDemanda, $nmTabelaTramDoc=null){
  		//se nao passar $nmTabelaTramDoc a tabela default sera a sem historico, por obvio
  		if($nmTabelaTramDoc == null){
  			$nmTabelaTramDoc = voDemandaTramDoc::getNmTabelaStatic ( false );
  		}
  		
  		$nmTabelaPublicacao = self::$nmTabelaPublicacao;
  		
  		//tudo para pegar a data da publicacao atraves do doc do tipo PUBLICACAO DE PENALIDADE anexado na demanda
  		$nmTabelaTramitacao = voDemandaTramitacao::getNmTabelaStatic ( false );
  		$atributosGroupDem = "$nmTabelaTramitacao.".voDemandaTramitacao::$nmAtrCd . ",$nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrAno;
  		$nmTABELA_MAX_TRAM_PUBLIC = "TABELA_MAX_TRAM_PUBLIC";
  		$queryFrom .= "\n LEFT JOIN (";
  		$queryFrom .= " SELECT MAX($nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq . ", ". $atributosGroupDem
  		. " FROM " . $nmTabelaTramitacao
  		. "\n INNER JOIN ". $nmTabelaTramDoc
  		. "\n ON ". $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd. "=" . $nmTabelaTramDoc . "." . voDemandaTramDoc::$nmAtrCdDemanda
  		. "\n AND ". $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . "=" . $nmTabelaTramDoc . "." . voDemandaTramDoc::$nmAtrAnoDemanda
  		. "\n AND ". $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . "=" . $nmTabelaTramDoc . "." . voDemandaTramDoc::$nmAtrSqDemandaTram
  		. "\n WHERE ". $nmTabelaTramDoc . "." . voDemandaTramDoc::$nmAtrTpDoc . "=" . getVarComoString(dominioTpDocumento::$CD_TP_DOC_PUBLICACAO_PAAP)
  		. " GROUP BY " . $atributosGroupDem;
  		$queryFrom .= ") $nmTABELA_MAX_TRAM_PUBLIC";
  		$queryFrom .= "\n ON ". "$nmTABELA_MAX_TRAM_PUBLIC." . voDemandaTramitacao::$nmAtrCd. "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
  		$queryFrom .= "\n AND ". "$nmTABELA_MAX_TRAM_PUBLIC." . voDemandaTramitacao::$nmAtrAno . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
  		$queryFrom .= "\n LEFT JOIN ". $nmTabelaTramitacao . " $nmTabelaPublicacao ";
  		$queryFrom .= "\n ON ". "$nmTABELA_MAX_TRAM_PUBLIC." . voDemandaTramitacao::$nmAtrCd. "=" . $nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrCd;
  		$queryFrom .= "\n AND ". "$nmTABELA_MAX_TRAM_PUBLIC." . voDemandaTramitacao::$nmAtrAno . "=" . $nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrAno;
  		$queryFrom .= "\n AND " . "$nmTABELA_MAX_TRAM_PUBLIC." . voDemandaTramitacao::$nmAtrSq . " = " . $nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrSq;
  		
  		return $queryFrom;
  	}
    
    function consultarPAAP($vo, $filtro){    	
    	$isHistorico = ("S" == $filtro->cdHistorico);
    	$nmTabela = $vo->getNmTabelaEntidade($isHistorico);    	
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic(false);
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
    	$nmTabelaPessoaContrato = $filtro->nmTabelaPessoaContrato;
    	$nmTabelaPessoaResponsavel = $filtro->nmTabelaPessoaResponsavel;
    	$nmTabelaDemandaProcLic = voDemandaPL::getNmTabelaStatic ( false );
    	$nmTabelaProcLic = voProcLicitatorio::getNmTabelaStatic ( false );
    	$nmTabelaPenalidade = voPenalidadePA::getNmTabelaStatic ( false );
    	
    	$colunaUsuHistorico = "";
    	
    	if ($isHistorico) {
    		$sqHist = $nmTabela . "." . voPA::$nmAtrSqHist;
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunasRetornadas = array (
    			//$nmTabela . ".*",
    			$nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao,    			
    			$nmTabela . "." . voPA::$nmAtrAnoPA,
    			$nmTabela . "." . voPA::$nmAtrCdPA,
    			$nmTabela . "." . voPA::$nmAtrAnoDemanda,
    			$nmTabela . "." . voPA::$nmAtrCdDemanda,
    			$nmTabela . "." . voPA::$nmAtrSituacao,
    			$nmTabela . "." . voPA::$nmAtrDtAbertura,
    			$nmTabela . "." . voPA::$nmAtrCdResponsavel,
    			$nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrNome . " AS " . $filtro->nmColNomePessoaResponsavel,    			 
    			$nmTabelaContrato. "." . vocontrato::$nmAtrTipoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrAnoContrato,
    			$nmTabelaContrato. "." . vocontrato::$nmAtrCdContrato,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd,
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
    			getSQLCOALESCE(array($nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome, $nmTabelaDemanda . "." . voDemanda::$nmAtrTexto), $filtro->nmColNomePessoaContrato),
    			$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrCd,
    			$nmTabelaDemandaProcLic . "." . voProcLicitatorio::$nmAtrAno,
    			 
    			$colunaUsuHistorico,
    			$sqHist
    	);

    	$queryFrom .= "\n INNER JOIN ". $nmTabelaDemanda;
    	$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabela . "." . voPA::$nmAtrCdDemanda;
    	$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabela . "." . voPA::$nmAtrAnoDemanda;
    	 
    	$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaContrato;
    	$queryFrom .= "\n ON ". $nmTabelaDemanda . "." . voDemanda::$nmAtrCd. "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    	$queryFrom .= "\n AND ". $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	
    	// o proximo join eh p pegar o registro de contrato mais atual na planilha
    	//faz o join apenas com os contratos de maximo sequencial (mais atual)
    	$nmTabelaMAXContrato = "TABELA_MAX_CONTRATO";
    	$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
    	$queryFrom .= "\n LEFT JOIN (";
    	$queryFrom .= " SELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
    	. "," . $atributosGroupContrato . " FROM " . $nmTabelaContrato
    	. " GROUP BY " . $atributosGroupContrato;
    	$queryFrom .= ") $nmTabelaMAXContrato";
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrCdContrato;
    	
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryFrom .= "\n LEFT JOIN ";
    	$queryFrom .= $nmTabelaContrato;
    	$queryFrom .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;    	 
    	 
        /*$queryFrom .= "\n INNER JOIN ". $nmTabelaContrato;
        $queryFrom .= "\n ON ". $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato;
        $queryFrom .= "\n AND ". $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato;
        $queryFrom .= "\n AND ". $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato;*/
        
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaContrato . " \n ON ". $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada. "=" . $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd;
        $queryFrom .= "\n LEFT JOIN ". vopessoa::getNmTabela();
        $queryFrom .= " ". $nmTabelaPessoaResponsavel . " \n ON ". $nmTabela . "." . voPA::$nmAtrCdResponsavel . "=" . $nmTabelaPessoaResponsavel . "." . vopessoa::$nmAtrCd;
        
        $queryFrom .= "\n LEFT JOIN " . $nmTabelaDemandaProcLic;
        $queryFrom .= "\n ON ";
        $queryFrom .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno;
        $queryFrom .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdDemanda . "=" . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd;
        
        $queryFrom .= "\n LEFT JOIN " . $nmTabelaProcLic;
        $queryFrom .= "\n ON ";
        $queryFrom .= $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrAnoProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrAno;
        $queryFrom .= "\n AND " . $nmTabelaDemandaProcLic . "." . voDemandaPL::$nmAtrCdProcLic . "=" . $nmTabelaProcLic . "." . voProcLicitatorio::$nmAtrCd;
                
        $queryFrom .= "\n LEFT JOIN " . $nmTabelaPenalidade;
        $queryFrom .= "\n ON ";
        $queryFrom .= $nmTabela . "." . voPA::$nmAtrAnoPA . "=" . $nmTabelaPenalidade . "." . voPenalidadePA::$nmAtrAnoPA;
        $queryFrom .= "\n AND " . $nmTabela . "." . voPA::$nmAtrCdPA . "=" . $nmTabelaPenalidade . "." . voPenalidadePA::$nmAtrCdPA;
        
        $groupbyPA = array("$nmTabela." .voPA::$nmAtrAnoPA,
        		"$nmTabela." . voPA::$nmAtrCdPA,        		
        );
        
        //$filtro->cdEspecieContrato = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
        $filtro->groupby = $groupbyPA;
        
        return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryFrom );        
    }
    
    function consultarDemandaPAAP($filtro) {
    	$isHistorico = $filtro->isHistorico;
    	$vo = new voDemanda();
    	$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
    	$nmTabelaTramitacao = voDemandaTramitacao::getNmTabela ();
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabela ();
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
    	$nmTabelaContratoInfo = voContratoInfo::getNmTabelaStatic ( false );
    	$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
    	$nmTabelaPA = voPA::getNmTabelaStatic ( false );
    	$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
    	$nmTabelaUsuarioResponsavelPAAP = filtroConsultarDemandaPAAP::$NM_TAB_USUARIO_RESP_PAAP;
    
    	$colunaUsuHistorico = "";
    
    	if ($isHistorico) {
    		$colunaUsuHistorico = static::$nmTabelaUsuarioOperacao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioOperacao;
    	}
    	$arrayColunaNomePessoaContrato = array(
    			$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
    			voDemanda::$nmAtrTexto
    	);
    	
    	$arrayColunasRetornadas = array (
    			$nmTabela . ".*",
    			getDataSQLDiferencaDias(voDemanda::$nmAtrDtReferencia,"NOW()") . " AS " . filtroManterDemanda::$NmColQtdDiasDataDtReferencia,
    			"COUNT(*)  AS " . filtroManterDemanda::$NmColQtdContratos,
    			static::$nmTabelaUsuarioInclusao . "." . vousuario::$nmAtrName . "  AS " . voDemanda::$nmAtrNmUsuarioInclusao,
    			"$nmTabelaUsuarioResponsavelPAAP." . vopessoa::$nmAtrNome . "  AS " . filtroConsultarDemandaPAAP::$NmColRESP_PAAP,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato,
    			$nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato,
    			getSQLCOALESCE($arrayColunaNomePessoaContrato,vopessoa::$nmAtrNome),
    			//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,    
    			$nmTabelaPA . "." . voPA::$nmAtrAnoPA,
    			$nmTabelaPA . "." . voPA::$nmAtrCdPA,
    			$nmTabelaPA . "." . voPA::$nmAtrDtUltNotificacaoParaManifestacao,
    			// $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . " AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
    			"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "," . $nmTabela . "." . voDemanda::$nmAtrCdSetor . ") AS " . voDemandaTramitacao::$nmAtrCdSetorDestino,
    			"COALESCE (" . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrDhInclusao . "," . $nmTabela . "." . voDemanda::$nmAtrDhUltAlteracao . ") AS " . filtroManterDemanda::$NmColDhUltimaMovimentacao,
    			self::$nmTabelaPublicacao . "." . voDemandaTramitacao::$nmAtrDtReferencia,
    			$colunaUsuHistorico,
    	);
    
    	$atributosGroup = voDemandaTramitacao::$nmAtrCd . "," . voDemandaTramitacao::$nmAtrAno;
    
    	// o proximo join eh p pegar a ultima tramitacao apenas, se houver
    	$nmTabelaMAXTramitacao = "TABELA_MAX";
    	$queryJoin = "";
    	$queryJoin .= "\n LEFT JOIN (";
    	$queryJoin .= " SELECT MAX(" . voDemandaTramitacao::$nmAtrSq . ") AS " . voDemandaTramitacao::$nmAtrSq
    	. "," . $atributosGroup . " FROM " . $nmTabelaTramitacao
    	. " GROUP BY " . $atributosGroup;
    	$queryJoin .= ") $nmTabelaMAXTramitacao";
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
    
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaTramitacao;
    	$queryJoin .= "\n ON " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrAno . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrAno;
    	$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrCd . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrCd;
    	$queryJoin .= "\n AND " . $nmTabelaTramitacao . "." . voDemandaTramitacao::$nmAtrSq . " = $nmTabelaMAXTramitacao." . voDemandaTramitacao::$nmAtrSq;
    
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaDemandaContrato;
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    
    	// o proximo join eh p pegar o registro de contrato mais atual na planilha
    	//faz o join apenas com os contratos de maximo sequencial (mais atual)
    	$nmTabelaMAXContrato = "TABELA_MAX_CONTRATO";
    	$atributosGroupContrato = vocontrato::$nmAtrAnoContrato . "," . vocontrato::$nmAtrTipoContrato . "," . vocontrato::$nmAtrCdContrato;
    	$queryJoin .= "\n LEFT JOIN (";
    	$queryJoin .= " SELECT MAX(" . vocontrato::$nmAtrSqContrato . ") AS " . vocontrato::$nmAtrSqContrato
    	. "," . $atributosGroupContrato . " FROM " . $nmTabelaContrato
    	. " GROUP BY " . $atributosGroupContrato;
    	$queryJoin .= ") $nmTabelaMAXContrato";
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaMAXContrato . "." . vocontrato::$nmAtrCdContrato;
    
    	// agora pega dos dados da ultima tramitacao, se houver
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaContrato;
    	$queryJoin .= "\n ON " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqContrato . " = $nmTabelaMAXContrato." . vocontrato::$nmAtrSqContrato;
        
    	$queryJoin .= "\n LEFT JOIN " . $nmTabelaContratoInfo;
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrAnoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrTipoContrato;
    	$queryJoin .= "\n AND ";
    	$queryJoin .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdContrato;
    
    	$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
    	$queryJoin .= "\n ON ";
    	$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
    	
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= $nmTabelaPA;
    	$queryJoin .= "\n ON " . $nmTabela . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaPA . "." . voPA::$nmAtrAnoDemanda;
    	$queryJoin .= "\n AND " . $nmTabela . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaPA . "." . voPA::$nmAtrCdDemanda;
    	
    	$queryJoin .= "\n LEFT JOIN ";
    	$queryJoin .= "$nmTabelaPessoa $nmTabelaUsuarioResponsavelPAAP";
    	$queryJoin .= "\n ON " . $nmTabelaUsuarioResponsavelPAAP . "." . vopessoa::$nmAtrCd . " = " . $nmTabelaPA . "." . voPA::$nmAtrCdResponsavel;
    	 
    	 //pega a data da publicacao para o caso de calcular quando deve emitir o alerta
    	$queryJoin .= $this->getSQLJoinDataPublicacaoPAAP($nmTabela);
    
    	$arrayGroupby = array (
    			$nmTabela . "." . voDemanda::$nmAtrAno,
    			$nmTabela . "." . voDemanda::$nmAtrCd
    	);
    
    	if ($isHistorico) {
    		$arrayGroupby [] = voentidade::$nmAtrSqHist;
    	}
    
    	$filtro->groupby = $arrayGroupby;
    
    	return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
    }
    
    function consultarContratoPAAP($vo) {
    	$isHistorico = $vo->isHistorico ();
    	
    	$nmTabelaPAAP = voPA::getNmTabelaStatic ( $isHistorico );
    	$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( $isHistorico );
    	$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic ( false );
    	$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
    	$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
    
    	$querySelect = "SELECT ";
    	$querySelect .= $nmTabelaPAAP . ".*,";
    	$querySelect .= $nmTabelaDemandaContrato . ".*";
    	$queryFrom = " FROM " . $nmTabelaPAAP;
    
    	$queryFrom .= "\n INNER JOIN ";
    	$queryFrom .= $nmTabelaDemanda;
    	$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaPAAP . "." . voPA::$nmAtrAnoDemanda;
    	$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaPAAP . "." . voPA::$nmAtrCdDemanda;
    
    	$queryFrom .= "\n INNER JOIN ";
    	$queryFrom .= $nmTabelaDemandaContrato;
    	$queryFrom .= "\n ON " . $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoDemanda;
    	$queryFrom .= "\n AND " . $nmTabelaDemanda . "." . voDemanda::$nmAtrCd . " = " . $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdDemanda;
    	 
    	$queryFrom .= "\n LEFT JOIN " . $nmTabelaContrato;
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrAnoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrTipoContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrCdEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato;
    	$queryFrom .= "\n AND ";
    	$queryFrom .= $nmTabelaDemandaContrato . "." . voDemandaContrato::$nmAtrSqEspecieContrato . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato;
    
    	$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
    	$queryFrom .= "\n ON ";
    	$queryFrom .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
    
    	$filtro = new filtroManterPA(false );
    	// var_dump($vo);
    	$filtro->anoPA = $vo->anoPA;
    	$filtro->cdPA = $vo->cdPA;
    	$filtro->sqHistPA = $vo->sqHist;
    	
    	$filtro->TemPaginacao = false;
    	$filtro->isHistorico = $isHistorico;
    
    	return parent::consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
    }
    
    function consultarPenalidade($vo) {
    	//$vo = new voPA();
    	$dbpenalidade = new dbPenalidadePA();    	
    	$filtro = new filtroManterPenalidade(false, false);
    	$vopenalidade = new voPenalidadePA();
    	$filtro->voPrincipal = $vopenalidade;
    	$filtro->inDesativado = "N";
    	//$filtro->TemPaginacao = false;
    	
    	$filtro->anoPA = $vo->anoPA;
    	$filtro->cdPA = $vo->cdPA;
    	$colecao = $dbpenalidade->consultarPenalidadeTelaConsulta($vopenalidade, $filtro);
    	return $colecao;
    }
    
    function consultarVODemanda($vo) {    	
    	//$vo = new voPA();
    	$vodemanda = new voDemanda(array($vo->anoDemanda, $vo->cdDemanda));
    	$vodemanda = $vodemanda->dbprocesso->consultarPorChaveVO($vodemanda, false);
    	return $vodemanda;
    }
    
    function temPenalidade($vo) {
    	$colecao = $this->consultarPenalidade($vo);
    	return !isColecaoVazia($colecao);
    }
    
    function validarAlteracao($vo) {
    	//$vo = new voPA();    	
    	//$retorno = true;    	
    	/*$vodemanda = $this->consultarVODemanda($vo);
    	//$vodemanda = new voDemanda();
    	$isDemandaFechada = $vodemanda->situacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA; 

    	if(dominioSituacaoPA::existeItem($vo->situacao, dominioSituacaoPA::getColecaoSituacaoTerminados())){    		
    		if(!$isDemandaFechada){
    			throw new excecaoGenerica("Só é permitido terminar o processo cuja demanda esteja concluída.");
    		}
    	}*/
    	
    	if($vo->situacao == dominioSituacaoPA::$CD_SITUACAO_PA_ENCERRADO){
    		$temPenalidade = $this->temPenalidade($vo);
    		if(!$temPenalidade){
    			throw new excecaoGenerica("Só é permitido encerrar o processo que possua penalidades cadastradas.");
    		}
    		    		
    		$registro = $this->consultarPorChaveTela($vo, false);
    		
    		$voBanco = new voPA();
    		$voBanco->getDadosBanco($registro);
    		if($voBanco->dtPublicacao == null){
    			throw new excecaoGenerica("Só é permitido encerrar o processo que tenha sido publicado.");
    		}
    	}     	
    	//return $retorno;
    }
    
    function alterar($vo) {
    	$this->validarAlteracao($vo);
    	return parent::alterar($vo);
    }    
    
    function getSQLValuesInsert($vo){
    	if($vo->cdPA == null){
    		$vo->cdPA = $this->getProximoSequencialChaveComposta (voPA::$nmAtrCdPA, $vo );
    	}
    	
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->cdPA) . ",";
		$retorno.= $this-> getVarComoNumero($vo->anoPA) . ",";
		
		$retorno.= $this-> getVarComoNumero($vo->anoDemanda) . ",";
		$retorno.= $this-> getVarComoNumero($vo->cdDemanda) . ",";

		$retorno.= $this-> getVarComoNumero($vo->cdResponsavel). ",";
		$retorno.= $this-> getVarComoString($vo->obs). ",";
		$retorno.= $this-> getVarComoString($vo->publicacao). ",";
		$retorno.= $this-> getVarComoData($vo->dtAbertura). ",";
		$retorno.= $this-> getVarComoData($vo->dtNotificacao). ",";
		$retorno.= $this-> getVarComoData($vo->dtUlNotificacaoParaManifestacao). ",";
		$retorno.= $this-> getVarComoData($vo->dtUlNotificacaoPrazoEncerrado). ",";
		$retorno.= $this-> getVarComoNumero($vo->numDiasPrazoUltNotificacao). ",";		
		$retorno.= $this-> getVarComoNumero($vo->situacao);		
	
		$retorno.= $vo->getSQLValuesInsertEntidade();
	
		return $retorno;
	}	
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->obs != null){
            $retorno.= $sqlConector . voPA::$nmAtrObservacao . " = " . $this->getVarComoString($vo->obs);
            $sqlConector = ",";
        }
        
        if($vo->publicacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrPublicacao . " = " . $this->getVarComoString($vo->publicacao);
        	$sqlConector = ",";
        }
        
        if($vo->dtAbertura != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtAbertura . " = " . $this->getVarComoData($vo->dtAbertura);
        	$sqlConector = ",";
        }
        
        if($vo->dtNotificacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtNotificacao . " = " . $this->getVarComoData($vo->dtNotificacao);
        	$sqlConector = ",";
        }
        
        if($vo->dtUlNotificacaoParaManifestacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtUltNotificacaoParaManifestacao . " = " . $this->getVarComoData($vo->dtUlNotificacaoParaManifestacao);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . voPA::$nmAtrDtUltNotificacaoParaManifestacao . " = NULL";
        	$sqlConector = ",";
        }
        
        if($vo->dtUlNotificacaoPrazoEncerrado != null){
        	$retorno.= $sqlConector . voPA::$nmAtrDtUltNotificacaoPrazoEncerrado . " = " . $this->getVarComoData($vo->dtUlNotificacaoPrazoEncerrado);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . voPA::$nmAtrDtUltNotificacaoPrazoEncerrado . " = NULL";
        	$sqlConector = ",";
        }        
        
        if($vo->numDiasPrazoUltNotificacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrNumDiasPrazoUltNotificacao . " = " . $this->getVarComoNumero($vo->numDiasPrazoUltNotificacao);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . voPA::$nmAtrNumDiasPrazoUltNotificacao . " = NULL";
        	$sqlConector = ",";
        }        	 
        
        if($vo->cdResponsavel != null){
        	$retorno.= $sqlConector . voPA::$nmAtrCdResponsavel. " = " . $this->getVarComoNumero($vo->cdResponsavel);
        	$sqlConector = ",";
        }
        
        if($vo->situacao != null){
        	$retorno.= $sqlConector . voPA::$nmAtrSituacao. " = " . $this->getVarComoNumero($vo->situacao);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>