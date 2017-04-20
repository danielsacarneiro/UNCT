-- MySQL dump 10.13  Distrib 5.7.12, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: unct
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.19-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contrato_hist`
--

DROP TABLE IF EXISTS `contrato_hist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contrato_hist` (
  `hist` int(11) NOT NULL AUTO_INCREMENT,
  `sq` int(11) NOT NULL,
  `ct_exercicio` int(11) DEFAULT NULL,
  `ct_numero` int(11) DEFAULT NULL,
  `ct_tipo` char(1) DEFAULT NULL,
  `ct_especie` varchar(50) DEFAULT NULL,
  `ct_sq_especie` int(11) DEFAULT NULL,
  `ct_cd_especie` char(2) DEFAULT NULL,
  `ct_cd_situacao` char(2) DEFAULT NULL,
  `ct_objeto` longtext,
  `ct_gestor_pessoa` varchar(300) DEFAULT NULL,
  `pe_cd_resp` int(11) DEFAULT NULL,
  `ct_gestor` varchar(200) DEFAULT NULL,
  `gt_cd` int(11) DEFAULT NULL,
  `ct_processo_lic` varchar(300) DEFAULT NULL,
  `ct_modalidade_lic` varchar(300) DEFAULT NULL,
  `ct_data_public` varchar(300) DEFAULT NULL,
  `ct_dt_public` date DEFAULT NULL,
  `ct_dt_assinatura` date DEFAULT NULL,
  `ct_dt_vigencia_inicio` date DEFAULT NULL,
  `ct_dt_vigencia_fim` date DEFAULT NULL,
  `ct_contratada` varchar(300) DEFAULT NULL,
  `pe_cd_contratada` int(11) DEFAULT NULL,
  `ct_doc_contratada` varchar(30) DEFAULT NULL,
  `ct_num_empenho` varchar(50) DEFAULT NULL,
  `ct_tp_autorizacao` varchar(15) DEFAULT NULL,
  `ct_cd_autorizacao` int(11) DEFAULT NULL,
  `ct_in_licom` char(1) DEFAULT NULL,
  `ct_in_importacao` char(1) DEFAULT NULL,
  `ct_observacao` longtext,
  `ct_valor_global` decimal(14,4) DEFAULT NULL,
  `ct_valor_mensal` decimal(14,4) DEFAULT NULL,
  `ct_doc_link` text,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dh_ultima_alt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  `cd_usuario_ultalt` int(11) DEFAULT NULL,
  `ct_dt_proposta` date DEFAULT NULL,
  PRIMARY KEY (`hist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contrato_hist`
--

LOCK TABLES `contrato_hist` WRITE;
/*!40000 ALTER TABLE `contrato_hist` DISABLE KEYS */;
/*!40000 ALTER TABLE `contrato_hist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:05
