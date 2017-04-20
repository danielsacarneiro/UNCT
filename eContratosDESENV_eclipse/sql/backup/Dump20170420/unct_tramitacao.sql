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
-- Table structure for table `tramitacao`
--

DROP TABLE IF EXISTS `tramitacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tramitacao` (
  `sq_tram` bigint(20) NOT NULL,
  `tr_observacao` mediumtext,
  `tr_dtreferencia` date NOT NULL,
  `doc_sq` int(11) DEFAULT NULL,
  `doc_cd_setor` int(11) DEFAULT NULL,
  `doc_ex` int(11) DEFAULT NULL,
  `doc_tp` char(2) DEFAULT NULL,
  `dh_ultima_alt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cd_usuario_ultalt` int(11) DEFAULT NULL,
  PRIMARY KEY (`sq_tram`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tramitacao`
--

LOCK TABLES `tramitacao` WRITE;
/*!40000 ALTER TABLE `tramitacao` DISABLE KEYS */;
INSERT INTO `tramitacao` VALUES (1,'visto contrato','2017-03-10',NULL,NULL,NULL,NULL,'2017-03-10 16:58:08',1),(2,'visto contrato mater','2017-01-10',NULL,NULL,NULL,NULL,'2017-03-10 17:05:52',1),(3,'NT 010 - 17  REAJUSTE CONTRATO C-SAFI 028-2016 - ADLIM TERCEIRIZAÇÃO.doc','2017-03-10',10,3,2017,'NT','2017-03-10 17:36:24',1),(4,'visto contrato mater','2017-03-10',NULL,NULL,NULL,NULL,'2017-03-14 17:40:42',1),(5,'visto contrato mater','2017-03-13',NULL,NULL,NULL,NULL,'2017-03-14 17:40:42',1),(6,'visto 1 T.A. (primeiro termo aditivo)','2017-03-14',NULL,NULL,NULL,NULL,'2017-03-14 17:36:37',1),(7,'parecer sobre responsabilidade por danos a imovel locado.','2017-03-15',13,3,2017,'NT','2017-03-15 17:44:16',1),(8,'parecer sobre responsabilidade por danos a imovel locado.','2017-03-15',13,3,2017,'NT','2017-03-15 17:44:47',1),(9,'parecer sobre responsabilidade por danos a imovel locado.','2017-03-15',13,3,2017,'NT','2017-03-15 17:45:16',1),(10,'visto contrato mater','2017-03-16',NULL,NULL,NULL,NULL,'2017-03-16 17:09:09',1),(11,'envio nota tecnica para a SAD solicitando reajuste','2017-03-17',14,3,2017,'NT','2017-03-17 13:57:09',1),(12,'visto contrato mater','2017-03-17',NULL,NULL,NULL,NULL,'2017-03-17 17:57:21',1),(13,'nota técnica concessão de prorrogação excepcional','2017-02-20',7,3,2017,'NT','2017-03-17 19:14:49',1),(14,'ENCAMINHANDO PARA VISTO O CONTRATO MATER, CUJO OBJETO É A CONTRATAÇÃO DE EMPRESA ESPECIALIZADA NO CARREGAMENTO ELETRÔNICO DE CRÉDITOS DE VALE-TRANSPORTE.','2017-03-18',NULL,NULL,NULL,NULL,'2017-03-20 12:59:44',8),(15,'ENCAMINHANDO PARA VISTO DO 3° TERMO ADITIVO.','2017-03-20',NULL,NULL,NULL,NULL,'2017-03-20 13:08:19',8),(16,'nota tecnica concedendo prorrogação e reajuste','2017-03-20',12,3,2016,'NT','2017-03-20 13:19:41',1),(17,'visto contrato mater','2017-03-20',NULL,NULL,NULL,NULL,'2017-03-20 13:42:08',1),(18,'visto 3 termo aditivo','2017-03-20',NULL,NULL,NULL,NULL,'2017-03-20 13:42:56',1),(19,'SEGUE 4° TERMO ADITIVO PARA VISTO.','2017-03-20',NULL,NULL,NULL,NULL,'2017-03-20 16:34:47',8),(20,'visto 4 termo aditivo','2017-03-20',NULL,NULL,NULL,NULL,'2017-03-20 17:16:02',1),(21,'inclusao nota técnica a favor reajuste','2016-01-27',5,3,2016,'NT','2017-03-20 17:41:05',1);
/*!40000 ALTER TABLE `tramitacao` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:11
