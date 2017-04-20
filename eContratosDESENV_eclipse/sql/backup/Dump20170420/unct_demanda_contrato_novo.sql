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
-- Table structure for table `demanda_contrato_novo`
--

DROP TABLE IF EXISTS `demanda_contrato_novo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demanda_contrato_novo` (
  `dem_ex` int(11) NOT NULL,
  `dem_cd` int(11) NOT NULL,
  `ct_exercicio` int(11) NOT NULL,
  `ct_numero` int(11) NOT NULL,
  `ct_tipo` char(1) NOT NULL,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  PRIMARY KEY (`dem_ex`,`dem_cd`,`ct_exercicio`,`ct_numero`,`ct_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demanda_contrato_novo`
--

LOCK TABLES `demanda_contrato_novo` WRITE;
/*!40000 ALTER TABLE `demanda_contrato_novo` DISABLE KEYS */;
INSERT INTO `demanda_contrato_novo` VALUES (2017,2,2015,16,'C','2017-03-24 17:11:31',NULL),(2017,3,2011,80,'C','2017-03-24 17:11:31',NULL),(2017,4,2015,16,'C','2017-03-24 17:11:31',NULL),(2017,5,2017,1,'C','2017-03-24 17:11:31',NULL),(2017,6,2011,80,'C','2017-03-24 17:11:31',NULL),(2017,7,2016,28,'C','2017-03-24 17:11:31',NULL),(2017,8,2017,7,'C','2017-03-24 17:11:31',NULL),(2017,9,2017,2,'C','2017-03-24 17:11:31',NULL),(2017,10,2016,16,'C','2017-03-24 17:11:31',NULL),(2017,11,2004,7,'C','2017-03-24 17:11:31',NULL),(2017,12,2006,27,'C','2017-03-24 17:11:31',NULL),(2017,13,2012,18,'C','2017-03-24 17:11:31',NULL),(2017,14,2017,16,'C','2017-03-24 17:11:31',NULL),(2017,15,2016,55,'C','2017-03-24 17:11:31',NULL),(2017,16,2014,12,'C','2017-03-24 17:11:31',NULL),(2017,17,2014,30,'C','2017-03-24 17:11:31',NULL);
/*!40000 ALTER TABLE `demanda_contrato_novo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:07
