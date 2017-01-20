------------------------------------------------
-- DDL Statements for Table "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"
------------------------------------------------
 

CREATE TABLE "ADMFIN.GFE_PREVISAO_DESEMBOLSO"  (
		  "CTBEXERC_DT_ANO" SMALLINT NOT NULL , 
		  "UG_CD" VARCHAR(6) NOT NULL , 
		  "GFUGESTAO_CD" VARCHAR(5) NOT NULL , 
		  "PREVDESB_SQ" INTEGER NOT NULL , 
		  "CTBEXERC_DT_ANO_REFERENCIA" SMALLINT , 
		  "UGGESTAO_SQ" SMALLINT NOT NULL , 
		  "EMPENHO_SQ" INTEGER , 
		  "TIPODOCCTB_CD" VARCHAR(2) NOT NULL , 
		  "PROT_NU" CHAR(20) , 
		  "FICHAFINAN_ID" INTEGER , 
		  "GFUGRCRED_CD" SMALLINT , 
		  "UG_CD_RECB" VARCHAR(6) , 
		  "GFUGESTAO_CD_RECB" VARCHAR(5) , 
		  "UGGESTAO_SQ_RECB" SMALLINT , 
		  "PESSOA_CD_RECB" INTEGER , 
		  "TPVINC_CD_RECB" SMALLINT , 
		  "BANAGNTARR_CD_RECB" SMALLINT , 
		  "PTOATEND_CD_RECB" INTEGER , 
		  "CONTABAN_NU_RECB" DECIMAL(12,0) , 
		  "PESSOA_CD_PAGADORA" INTEGER NOT NULL , 
		  "TPVINC_CD_PAGADORA" SMALLINT NOT NULL , 
		  "BANAGNTARR_CD_PAGADORA" SMALLINT , 
		  "PTOATEND_CD_PAGADORA" INTEGER , 
		  "CONTABAN_NU_PAGADORA" DECIMAL(12,0) , 
		  "TIPOOB_CD" CHAR(5) NOT NULL , 
		  "TIPOOB_SQ" INTEGER NOT NULL , 
		  "USUARIO_CD" VARCHAR(20) NOT NULL , 
		  "PREVDESB_DT" DATE NOT NULL , 
		  "PREVDESB_IN_GERACAO_OB" CHAR(1) NOT NULL , 
		  "PREVDESB_IN_RECOL_REPS" CHAR(1) , 
		  "PREVDESB_IN_REMESSA" CHAR(1) NOT NULL , 
		  "PREVDESB_VL_RETIDO" DECIMAL(14,2) , 
		  "PREVDESB_VL_BRUTO" DECIMAL(14,2) , 
		  "PREVDESB_TX_OBS" VARCHAR(300) , 
		  "PREVDESB_IN_GERADA" CHAR(1) NOT NULL , 
		  "GFEPARMVIN_CD" VARCHAR(2) NOT NULL , 
		  "SNCTRS_DH_ULT_ALTR" TIMESTAMP NOT NULL WITH DEFAULT CURRENT TIMESTAMP , 
		  "PREVDESB_NU_CHEQUE" INTEGER , 
		  "USUARIO_CD_ALTR_PD" VARCHAR(20) , 
		  "PREVDESB_NU_REMESSA_OT" INTEGER , 
		  "GFUGRCRED_SQ" SMALLINT , 
		  "PREVDESB_IN_RESTOS_PAGAR" CHAR(1) , 
		  "USUARIO_CD_INCL" VARCHAR(20) NOT NULL , 
		  "PREVDESB_DH_INCL" TIMESTAMP NOT NULL , 
		  "PREVDESB_IN_BORDERO" CHAR(1) NOT NULL , 
		  "PREVDESB_DH_ALTR" TIMESTAMP , 
		  "GFUFUNDEB_CD" SMALLINT , 
		  "GFUFUNDEB_SQ" INTEGER , 
		  "PREVDESB_DT_VCTO_PGM" DATE , 
		  "PLFEXERC_DT_ANO" SMALLINT , 
		  "DESPGERN_CD" SMALLINT , 
		  "GPOPACTO_SQ" INTEGER , 
		  "PREVDESB_NU_COMPET_DESP" CHAR(6) , 
		  "GFEPARMVIN_SQ" SMALLINT NOT NULL )   
		 IN "USERSPACE1" NOT LOGGED INITIALLY  
		 ORGANIZE BY ROW@ 

COMMENT ON TABLE "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO" IS 'Programação ou previsão de desembolso (PD) de um Empenho ou Liquidação efetuada. A programação de desembolso é um agendamento da previsão para o efetivo pagamento da despesa. Pode não estar relacionada a um empenho, nos casos de despesas extra-o'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."BANAGNTARR_CD_PAGADORA" IS 'Código do Banco no padrão FEBRABAN. Obs: Atributo validado, via trigger, contra o atributo BANAGNTARR_CD da entidade TGE_BANCO_AGENTE_ARREC'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."BANAGNTARR_CD_RECB" IS 'Código do Banco no padrão FEBRABAN. Obs: Atributo validado, via trigger, contra o atributo BANAGNTARR_CD da entidade TGE_BANCO_AGENTE_ARREC'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."CONTABAN_NU_PAGADORA" IS 'Número da conta bancária pagadora'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."CONTABAN_NU_RECB" IS 'Número da conta bancária na qual será efetuado o crédito do valor da restituição'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."CTBEXERC_DT_ANO" IS 'Ano da PREVISÃO de desembolso. Faz parte da identificação da PD.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."CTBEXERC_DT_ANO_REFERENCIA" IS 'Ano/Exercício a que se refere o empenho. Faz parte da chave de identificação do empenho.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."DESPGERN_CD" IS 'Informar a despesa gerencial orçamentária compatível com o pagamento feito com ficha Extra Orçamentária.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."EMPENHO_SQ" IS 'Número seqüencial de identificação do empenho, gerado automaticamente pelo sistema. Parte da chave da tabela de Empenho.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."FICHAFINAN_ID" IS 'Identificação da ficha financeira. Este atributo somente será exigido quando a PD for referente a pagamentos sem empenho - despesas extra-orçamentárias'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFEPARMVIN_CD" IS 'Código do Parametro do Vínculo da Previsão de Desembolso. Exemplos: E - Pagamento por Empenho R - Repasse/Pagto sem Empenho T - Transfência entre Contas da UG P - Restos a Pagar Processado N - Restos a Pagar Não-Processado D - Despesa a Regula'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFEPARMVIN_SQ" IS 'Sequencial Identificador da Vigência do Parametro do Vínculo da Previsão de Desembolso.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFUFUNDEB_CD" IS 'Código do FUNDEB atribuído às PD´s que pagarem com essa Fonte de Recurso.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFUFUNDEB_SQ" IS 'Sequencial do FUNDEB atribuído às PD´s que pagarem com essa Fonte de Recurso.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFUGESTAO_CD" IS 'Número seqüencial de identificação da Gestão, gerado de forma automática pelo sistema'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFUGESTAO_CD_RECB" IS 'Número seqüencial de identificação da Gestão, identificando a UG recebedora, no caso de OB-10'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GFUGRCRED_CD" IS 'Código de identificação do grupo de credores'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."GPOPACTO_SQ" IS 'Seqüencial da Célula de Pactuação da Despesa.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PESSOA_CD_PAGADORA" IS 'Identificação de uma Pessoa Física ou Jurídica junto a SEFAZ'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PESSOA_CD_RECB" IS 'Identificação de uma Pessoa Física ou Jurídica junto a SEFAZ'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PLFEXERC_DT_ANO" IS 'Ano da Despesa Gerencial compativel com o pagamento feito com ficha extra orçamentaria.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_DH_ALTR" IS 'Guarda a data de alteração da Previsão de Desembolso (esta coluna difere da data de sincronismo)'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_DH_INCL" IS 'Data e hora de inclusão do registro na tabela de previsão de desembolso.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_DT" IS 'Data original da previsão de desembolso, ou seja, é a data em que se pretende efetivar o respectivo pagamento. Esta data é informada pelo usuário quando da confecção da PD'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_DT_VCTO_PGM" IS 'Data de Vencimento do Pagamento. Essa Informação será utilizada no Momento de fazer o Repasse financeiro para selecionar as PD''s que estão próximas do Vencimento (CELPE, TELEFONE e Outras Faturas) para serem priorizadas no Repasse/Pagamento.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_BORDERO" IS 'Indica se a PD é do tipo Borderô, Dominio: N = não é borderô F = é PD gerada a partir de uma borderô (FILHO) P = é a borderô original (PAI)'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_GERACAO_OB" IS 'Indica se a OB foi gerada automaticamente pelo Sistema na Data prevista ou se houve Intervenção manual para sua Geração. S-Aqueles gerados automaticamente que NÃO são Repasses de Pagamento Automático. N-Aqueles NÃO gerados automaticamente que NÃO'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_GERADA" IS 'Ele indica se existe uma Ordem Bancária gerada para a Previsão Desembolso em questão. Domínios possíveis: S - Sim N - Não '@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_RECOL_REPS" IS 'Indicação se a PD se refere a um recolhimento das retenções efetuadas ou de repasse aos municípios. Essas duas formas de pagamento (quando for efetivado) terão procedimentos específicos. Domínios possíveis: R=Recolhimento das Retenções M=Repass'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_REMESSA" IS 'Indicação se o pagamento (ordem bancária) possuirá remessa, ou seja, se irá para o banco uma remessa assinada. Domínios possíveis: S - Sim N - Não'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_IN_RESTOS_PAGAR" IS 'Dirá se a Previsão de Desembolso possui RESTOS A PAGAR. Domínio fixo: E - EXERCÍCIO, P - Restos a Pagar PROCESSADO, N - Restos a Pagar NÃO PROCESSADO, S - SEM Empenho. '@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_NU_CHEQUE" IS 'Numero do cheque, com 6 digitos.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_NU_COMPET_DESP" IS 'Esse campo visa armazenar a competência da despesa (AAAAMM) que a Previsão de Desembolso foi executada.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_NU_REMESSA_OT" IS 'Número que identifica a Remessa de Outras Contas.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_SQ" IS 'Número seqüencial de identificação da previsão de desembolso, gerado automaticamente pelo sistema'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_TX_OBS" IS 'Observações gerais sobre a PD'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_VL_BRUTO" IS 'Valor bruto da programação de desembolso, sem ao abatimento das retenções (taxas ou impostos)'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PREVDESB_VL_RETIDO" IS 'Valor retido da PD'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PROT_NU" IS 'Número do processo do protocolo, que identifica o processo de restituição de tributos.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PTOATEND_CD_PAGADORA" IS 'Código da Agência Bancária Obs: Atributo validado, via trigger, contra o atributo PTOATEND_CD da entidade TGE_PONTO_ATENDIMENTO, considerando o BANAGNTARR_CD, o qual deve ser validado, via trigger, contra o atributo BANAGNTARR_CD da entidade TGE_BANCO'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."PTOATEND_CD_RECB" IS 'Código da Agência Bancária Obs: Atributo validado, via trigger, contra o atributo PTOATEND_CD da entidade TGE_PONTO_ATENDIMENTO, considerando o BANAGNTARR_CD, o qual deve ser validado, via trigger, contra o atributo BANAGNTARR_CD da entidade TGE_BANCO'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."SNCTRS_DH_ULT_ALTR" IS 'Data e hora da última alteração do registro. Uso interno pela aplicação'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."TIPODOCCTB_CD" IS 'Tipo do documento contábil. Neste caso trata-se de uma Previsão de Desembolso (PD) Exemplos: NE - Nota de Empenho NA - Nota de Anulação NL - Nota de Liberação PD - Previsão de Desembolso OB - Ordem Bancária GR - Guia de Recebimento RE - Repasse'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."TIPOOB_CD" IS 'Código de identificação do tipo da OB, informado pelo usuário Exemplo: OBN => Ordem Bancária Normal OBP => Ordem Bancária ao Portador OBB => Ordem Bancária ao Banco.... '@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."TIPOOB_SQ" IS 'Número seqüencial de identificação do tipo de OB, gerado de forma automática pelo sistema'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."TPVINC_CD_PAGADORA" IS 'Tipo do vínculo entre uma Pessoa (física ou jurídica) e a SEFAZ. Exemplos: - Contribuinte de ICMS - Fornecedor - Servidor Estadual - Autor de emendas - Contador - Credor - Ordenador de Despesas - Órgão da Administração Pública - Fiel Depositá'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."TPVINC_CD_RECB" IS 'Tipo do vínculo entre uma Pessoa (física ou jurídica) e a SEFAZ. Exemplos: - Contribuinte de ICMS - Fornecedor - Servidor Estadual - Autor de emendas - Contador - Credor - Ordenador de Despesas - Órgão da Administração Pública - Fiel Depositá'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."UG_CD" IS 'Código de identificação da Unidade Gestora (UG), referente à gestão'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."UG_CD_RECB" IS 'Código de identificação da Unidade Gestora (UG), identificando a UG recebedora, no caso de OB-10 '@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."UGGESTAO_SQ" IS 'Número seqüencial de identificação da UG/Gestão, gerado pelo sistema'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."UGGESTAO_SQ_RECB" IS 'Número seqüencial de identificação da UG/Gestão, identificando a UG recebedora, no caso de OB-10'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."USUARIO_CD" IS 'Código de identificação do usuário que gerou a PD.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."USUARIO_CD_ALTR_PD" IS 'Código de Identificação do Último Usuário que alterou a Previsão de Desembolso antes que ela se transformasse em uma OB.'@

COMMENT ON COLUMN "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"."USUARIO_CD_INCL" IS 'Código de identificação do usuário que incluiu o registro.'@


-- DDL Statements for Primary Key on Table "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO"

ALTER TABLE "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO" 
	ADD CONSTRAINT "PK_PREVDESB" PRIMARY KEY
		("CTBEXERC_DT_ANO",
		 "UG_CD",
		 "GFUGESTAO_CD",
		 "PREVDESB_SQ")@

ALTER TABLE `clientes` ADD CONSTRAINT `fk_cidade` FOREIGN KEY ( `codcidade` ) REFERENCES `cidade` ( `codcidade` ) ;

ALTER TABLE "ADMFIN  "."GFE_PREVISAO_DESEMBOLSO" 
	ADD CONSTRAINT "FK_CONTAPESS_RECB" FOREIGN KEY
		("PESSOA_CD_RECB",
		 "TPVINC_CD_RECB",
		 "BANAGNTARR_CD_RECB",
		 "PTOATEND_CD_RECB",
		 "CONTABAN_NU_RECB")
	REFERENCES "ADMTRB  "."ACG_PESSOA_CONTA_BANCARIA"
		("PESSOA_CD",
		 "TPVINC_CD",
		 "BANAGNTARR_CD",
		 "PTOATEND_CD",
		 "CONTABAN_NU")
	ON DELETE RESTRICT
	ON UPDATE RESTRICT
	;