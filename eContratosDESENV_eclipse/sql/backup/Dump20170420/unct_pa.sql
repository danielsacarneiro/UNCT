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
-- Table structure for table `pa`
--

DROP TABLE IF EXISTS `pa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pa` (
  `pa_cd` int(11) NOT NULL,
  `pa_ex` int(11) NOT NULL,
  `ct_exercicio` int(11) DEFAULT NULL,
  `ct_numero` int(11) DEFAULT NULL,
  `ct_tipo` char(1) DEFAULT NULL,
  `pa_cd_responsavel` int(11) DEFAULT NULL,
  `pa_proc_licitatorio` varchar(300) DEFAULT NULL,
  `pa_observacao` longtext,
  `pa_dt_abertura` date NOT NULL,
  `pa_dt_inicio_prazo` date DEFAULT NULL,
  `pa_si` int(11) NOT NULL,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dh_ultima_alt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  `cd_usuario_ultalt` int(11) DEFAULT NULL,
  PRIMARY KEY (`pa_ex`,`pa_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pa`
--

LOCK TABLES `pa` WRITE;
/*!40000 ALTER TABLE `pa` DISABLE KEYS */;
INSERT INTO `pa` VALUES (11,2014,2010,66,'C',509,NULL,'teste com 2 tramitacoes','2017-03-02',NULL,2,'2017-03-02 15:15:54','2017-03-03 16:12:25',1,1),(16,2016,2015,25,'C',510,NULL,NULL,'2017-03-03',NULL,2,'2017-03-03 17:56:59','2017-03-03 17:56:59',1,1),(1,2017,2015,25,'C',69,NULL,NULL,'2017-02-17',NULL,2,'2017-02-17 20:23:32','2017-02-20 17:48:10',1,1);
/*!40000 ALTER TABLE `pa` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:16
