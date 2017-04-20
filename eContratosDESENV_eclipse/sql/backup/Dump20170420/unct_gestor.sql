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
-- Table structure for table `gestor`
--

DROP TABLE IF EXISTS `gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gestor` (
  `gt_cd` int(11) NOT NULL AUTO_INCREMENT,
  `gt_descricao` varchar(300) DEFAULT NULL,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dh_ultima_alt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  `cd_usuario_ultalt` int(11) DEFAULT NULL,
  PRIMARY KEY (`gt_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gestor`
--

LOCK TABLES `gestor` WRITE;
/*!40000 ALTER TABLE `gestor` DISABLE KEYS */;
INSERT INTO `gestor` VALUES (2,'CGE - Contadoria Geral do Estado','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(3,'Coord.Geral da Campanha Todos c/a Nota','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(4,'DAS - Dir.Geral de Antecipação e Sistemas Tributários','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(5,'DAS - GERT - Gerência','2017-01-17 20:18:28','2017-01-23 12:59:52',1,1),(6,'DIENG - Diretoria de Engenharia','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(7,'DIF- Diretoria de Inteligência Fiscal','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(8,'DILOG','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(9,'DILOG/GEBES','2017-01-17 20:18:28','2017-01-17 20:18:28',1,1),(10,'DOE','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(11,'DOE - Diretoria de Operações Estratégicas','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(12,'GEBES','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(13,'Gerente do Segmento Econômico do IPVA','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(14,'NAPA -  Caruaru','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(15,'NAPA - Caruaru','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(16,'NAPA - Petrolina Núcleo de Apoio Adm-DRR III Região Fiscal','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(17,'NAPA Caruaru','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(18,'SETE','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(19,'STI','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(20,'STI-GEAT - Ger.de Atend.a Usuários','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(21,'STI-GEPS-Ger.de Processos de Suporte','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(22,'STI-GOCT-Ger.de Oper.e Controle de TIC','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(23,'STI-GSUT - Gerência de Suporte Técnico','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(24,'UADI - Unidade de Atendimento Digital','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(25,'UNSG/SAFI','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(26,'UNTG','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1),(27,'UNTG - Unidade de Transporte e Garagem Supervisor de Apoio e Garagens','2017-01-17 20:18:29','2017-01-17 20:18:29',1,1);
/*!40000 ALTER TABLE `gestor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:19
