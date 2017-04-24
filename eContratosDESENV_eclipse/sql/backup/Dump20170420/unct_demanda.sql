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
-- Table structure for table `demanda`
--

DROP TABLE IF EXISTS `demanda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demanda` (
  `dem_ex` int(11) NOT NULL,
  `dem_cd` int(11) NOT NULL,
  `dem_tipo` int(11) NOT NULL,
  `dem_situacao` int(11) NOT NULL,
  `dem_cd_setor` int(11) NOT NULL,
  `dem_texto` mediumtext NOT NULL,
  `dem_prioridade` int(11) NOT NULL DEFAULT '3',
  `dem_dtreferencia` date DEFAULT NULL,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dh_ultima_alt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  `cd_usuario_ultalt` int(11) DEFAULT NULL,
  PRIMARY KEY (`dem_ex`,`dem_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demanda`
--

LOCK TABLES `demanda` WRITE;
/*!40000 ALTER TABLE `demanda` DISABLE KEYS */;
INSERT INTO `demanda` VALUES (2016,2,1,1,2,'ANALISE PRORROGACAO C-SAFI 12-2014 (INCLUSAO ESTIMATIVA)',3,'2017-02-03','2017-03-27 18:46:51','2017-03-27 18:46:51',10,10),(2016,3,1,2,1,'REAJUSTE CONTRATO C-SAFI 016-2015',1,'2016-01-27','2017-03-28 19:37:11','2017-03-29 12:02:31',10,1),(2016,4,1,1,3,'4 T.A. AO C-SAFI 047/13',1,'2016-12-01','2017-04-10 17:33:06','2017-04-10 17:33:06',1,1),(2017,1,1,1,2,'SOLICITACAO 3º T.A. C-SAFI-028/15',2,'2017-03-10','2017-03-27 14:15:08','2017-03-27 14:15:08',1,1),(2017,2,1,1,2,'SOLICITACAO 1º T.A. C-SAFI-041/16',2,'2017-03-21','2017-03-27 14:24:55','2017-03-27 14:24:55',1,1),(2017,3,1,1,2,'SOLICITACAO 2º T.A. C-SAFI-041/16',2,'2017-03-21','2017-03-27 14:25:35','2017-03-27 14:25:35',1,1),(2017,4,1,1,2,'SOLICITACAO 12º T.A. C-SAFI-035/13',1,'2017-03-22','2017-03-27 15:02:49','2017-03-27 15:02:49',1,1),(2017,6,1,2,2,'SOLICITACAO 4 T.A. AO C-SAFI 012/14',3,'2017-03-20','2017-03-27 16:39:10','2017-03-29 12:02:48',1,1),(2017,7,1,1,3,'SOLICITACAO 3 T.A. AO C-SAFI 030/14',3,'2017-03-20','2017-03-27 16:51:19','2017-03-27 16:51:19',1,1),(2017,8,1,1,2,'CONTRATO MATER C-SAFI - 016/17',3,'2017-03-16','2017-03-27 17:34:31','2017-03-27 17:34:31',10,10),(2017,9,1,1,2,'SOLICITAÇÃO 1 T.A AO C-SAFI - 016/16',3,'2017-03-14','2017-03-27 17:49:53','2017-03-27 17:49:53',10,10),(2017,10,1,1,2,'CONTRATO MATER C-SAFI - 002/17',3,'2017-03-13','2017-03-27 17:58:02','2017-03-27 17:58:02',10,10),(2017,11,1,1,2,'CONTRATO MATER C-SAFI - 007/17',3,'2017-03-10','2017-03-27 18:01:27','2017-03-27 18:01:27',10,10),(2017,12,1,2,2,'CONTRATO MATER C-SAFI - 001/17',3,'2017-01-10','2017-03-27 18:06:52','2017-03-29 14:53:05',10,1),(2017,13,1,1,2,'ANALISE PRORROGACAO C-SAFI 055/16 (INCLUSAO ESTIMATIVA)',3,'2017-03-17','2017-03-27 18:38:47','2017-03-27 18:38:47',10,10),(2017,14,1,2,1,'PARECER SOBRE RESPONSABILIDADE POR DANOS A IMÓVEL LOCADO',1,'2017-03-15','2017-03-28 18:46:12','2017-04-05 12:00:36',10,1),(2017,15,1,2,1,'PARECER SOBRE RESPONSABILIDADE POR DANOS A IMÓVEL LOCADO',1,'2017-03-15','2017-03-28 18:53:21','2017-04-05 12:00:23',10,1),(2017,16,1,2,1,'PARECER SOBRE RESPONSABILIDADE POR DANOS A IMOVEL LOCADO.',1,'2017-03-15','2017-03-28 18:58:46','2017-04-05 12:00:11',10,1),(2017,17,1,2,1,'REAJUSTE CONTRATO C-SAFI 028-2016',1,'2017-03-10','2017-03-28 19:11:14','2017-04-11 18:36:33',10,1),(2017,18,1,2,1,'ANALISE PRORROGACAO EXCEPCIONAL C-SAFI 080-2011',1,'2017-02-20','2017-03-28 19:44:39','2017-03-29 12:01:34',10,1),(2017,21,1,1,2,'SOLICITAÇÃO 2 T.A AO C-SAFI - 020/16',1,'2017-03-15','2017-03-30 18:13:18','2017-03-30 18:13:18',10,10),(2017,22,1,1,2,'SOLICITAÇÃO 2 T.A AO C-SAFI - 033/15',1,'2017-03-07','2017-03-30 18:15:44','2017-03-30 18:15:44',10,10),(2017,23,1,1,2,'SOLICITAÇÃO 1 T.A AO C-SAFI - 014/17',1,'2017-03-21','2017-03-30 18:17:41','2017-03-30 18:17:41',10,10),(2017,24,1,1,2,'SOLICITAÇÃO 2 T.A AO C-SAFI - 009/15',1,'2017-03-16','2017-03-30 18:19:25','2017-04-04 18:50:55',10,10),(2017,25,1,1,2,'SOLICITAÇÃO 7 T.A AO C-SAFI - 008/13',1,'2017-03-08','2017-03-30 18:20:58','2017-03-30 18:20:58',10,10),(2017,26,1,1,2,'SOLICITAÇÃO 1 T.A AO CV-SAFI - 002/16',1,'2017-03-27','2017-03-30 18:24:58','2017-03-30 18:24:58',10,10),(2017,27,1,1,1,'ANALISE REAJUSTE C-SAFI 033/2016',1,'2017-03-07','2017-03-30 19:23:58','2017-03-30 19:26:28',1,1),(2017,29,1,2,2,'4º T.A C-SAFI Nº 072/13',1,'2017-04-03','2017-04-03 14:52:13','2017-04-03 15:12:05',9,1),(2017,30,1,2,2,'CONTRATO MATER C-SAFI Nº 019/17',1,'2017-04-04','2017-04-04 18:27:57','2017-04-04 18:29:03',9,9),(2017,31,1,2,2,'7º T.A',1,'2017-04-04','2017-04-04 18:35:35','2017-04-04 18:36:22',9,9),(2017,32,1,2,2,'CONTRATO MATER C-SAFI Nº 017/17',1,'2017-04-04','2017-04-04 18:40:38','2017-04-04 18:40:53',9,9),(2017,33,1,1,2,'CI Nº 027/17 SOLICITAÇÃO DE PRORROGAÇÃO CONTRATUAL',1,'2017-04-04','2017-04-04 18:47:41','2017-04-04 18:47:41',9,9),(2017,34,1,1,1,'ANALISE REAJUSTE C-SAFI 044/2016',1,'2017-03-03','2017-04-05 16:26:34','2017-04-05 16:26:34',1,1),(2017,35,1,1,4,'1 T.A. AO C-SAFI 012/17',3,'2017-04-06','2017-04-06 13:59:12','2017-04-06 13:59:12',1,1),(2017,36,1,1,4,'SOLICITAçãO DE REAJUSTE AO CONTRATO C-SAFI 060/2016',3,'2017-04-06','2017-04-06 15:01:46','2017-04-06 15:01:46',12,12),(2017,37,1,1,2,'BAKER TILLY C-SAFI Nº 072/13',3,'2017-04-06','2017-04-06 17:14:56','2017-04-06 17:14:56',12,12),(2017,38,1,1,4,'SOLICITAçãO DE REAJUSTE AO CONTRATO C-SAFI 061/2013',3,'2017-04-06','2017-04-06 18:52:35','2017-04-06 18:52:35',12,12),(2017,39,1,1,4,'LEMON TERCEIRIZAçãO C-SAFI Nº 014/2017',3,'2017-04-06','2017-04-06 18:59:28','2017-04-06 18:59:28',12,12),(2017,40,1,1,4,'LANLINK - REPACTUAçãO DO CONTRATO C-SAFI Nº 031/2011',3,'2017-04-06','2017-04-06 19:14:34','2017-04-06 19:14:34',12,12),(2017,41,1,1,4,'REAJUSTE DE PREçOS MONTANTE B',3,'2017-04-06','2017-04-06 19:20:28','2017-04-11 18:36:01',12,1),(2017,42,1,1,4,'REAJUSTE DE PREçOS DO CONTRATO C-SAFI Nº 001/2017 - BBC VIGILÂNCIA & SERVIÇOS',3,'2017-04-06','2017-04-06 19:27:36','2017-04-06 19:27:36',12,12),(2017,43,99,1,4,'SOLICITAÇÃO DE AQUISIÇÃO SWITCHS CORE ENTERASYS STI',1,'2017-03-31','2017-04-07 15:44:29','2017-04-11 16:48:04',1,1),(2017,46,3,1,8,'PL Nº 08/2017, PE Nº 05/2017',3,'2017-04-11','2017-04-11 16:55:27','2017-04-11 16:55:27',12,12),(2017,47,1,1,2,'CONTRATO MATER SERASA',3,'2017-04-11','2017-04-11 17:03:19','2017-04-11 17:03:19',8,8),(2017,48,1,1,2,'CONTRATO MATER GR INDUSTRIAL',2,'2017-04-11','2017-04-11 17:08:46','2017-04-11 17:10:24',8,1),(2017,49,1,1,2,'SOLICITAÇÃO 2 T.A AO C-SAFI - 060/16',1,'2017-03-28','2017-04-11 18:37:55','2017-04-11 18:37:55',10,10),(2017,50,1,1,4,'REAJUSTE DO VALOR DA LOCAçãO',2,'2017-04-12','2017-04-12 19:31:23','2017-04-12 19:31:23',12,12),(2017,51,1,1,2,'1° T.A AO C-SAFI N° 003/2016',3,'2017-04-19','2017-04-19 14:43:03','2017-04-19 14:43:03',8,8),(2017,52,2,1,4,'ABERTURA DE PENALIDADE CONFORME CI 030',3,'2017-04-19','2017-04-19 15:58:04','2017-04-19 16:02:46',12,1),(2017,53,99,1,2,'PARECER SOBRE CONVENIO SEFAZ VIRTUAL SEFAZ/RS-12/2015',1,'2017-03-20','2017-04-19 17:23:20','2017-04-19 17:23:20',1,1),(2017,54,1,1,4,'NOTIFICAÇÃO DE PROBLEMAS PENDENTES DA EMPRESA J.ATAÍDE EIRELI',2,'2017-04-19','2017-04-19 19:37:28','2017-04-19 19:37:28',12,12);
/*!40000 ALTER TABLE `demanda` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:04