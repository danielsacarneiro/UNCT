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
-- Table structure for table `demanda_doc`
--

DROP TABLE IF EXISTS `demanda_doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demanda_doc` (
  `dtm_sq` int(11) NOT NULL,
  `dem_ex` int(11) NOT NULL,
  `dem_cd` int(11) NOT NULL,
  `doc_sq` int(11) NOT NULL,
  `doc_cd_setor` int(11) NOT NULL,
  `doc_ex` int(11) NOT NULL,
  `doc_tp` char(2) NOT NULL,
  `dh_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_usuario_incl` int(11) DEFAULT NULL,
  PRIMARY KEY (`dem_ex`,`dem_cd`,`dtm_sq`,`doc_ex`,`doc_cd_setor`,`doc_tp`,`doc_sq`),
  KEY `fk_demanda_doc` (`doc_ex`,`doc_cd_setor`,`doc_tp`,`doc_sq`),
  CONSTRAINT `fk_demanda_doc` FOREIGN KEY (`doc_ex`, `doc_cd_setor`, `doc_tp`, `doc_sq`) REFERENCES `documento` (`doc_ex`, `doc_cd_setor`, `doc_tp`, `sq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demanda_doc`
--

LOCK TABLES `demanda_doc` WRITE;
/*!40000 ALTER TABLE `demanda_doc` DISABLE KEYS */;
INSERT INTO `demanda_doc` VALUES (2,2016,2,12,3,2016,'NT','2017-03-27 18:47:27',10),(2,2016,3,5,3,2016,'NT','2017-03-28 19:37:41',10),(1,2016,4,92,3,2016,'NT','2017-04-10 17:33:06',1),(3,2017,4,77,2,2017,'OF','2017-03-29 18:03:52',9),(2,2017,13,14,3,2017,'NT','2017-03-27 18:41:48',10),(2,2017,14,13,3,2017,'NT','2017-03-28 18:49:46',10),(2,2017,15,13,3,2017,'NT','2017-03-28 18:55:07',10),(2,2017,16,13,3,2017,'NT','2017-03-28 18:59:35',10),(2,2017,17,10,3,2017,'NT','2017-03-28 19:13:28',10),(2,2017,18,7,3,2017,'NT','2017-03-28 19:46:01',10),(3,2017,21,80,2,2017,'OF','2017-04-03 12:18:33',11),(3,2017,22,78,2,2017,'OF','2017-03-31 13:46:57',11),(3,2017,23,79,2,2017,'OF','2017-03-31 14:46:34',11),(3,2017,24,87,2,2017,'OF','2017-04-05 11:44:44',11),(2,2017,25,34,3,2016,'NT','2017-04-07 17:23:13',1),(3,2017,26,88,2,2017,'OF','2017-04-07 11:45:07',11),(3,2017,27,16,3,2017,'NT','2017-03-30 19:25:08',1),(2,2017,34,17,3,2017,'NT','2017-04-06 18:37:07',1),(6,2017,34,1,3,2017,'OF','2017-04-19 13:44:18',1),(2,2017,36,18,3,2017,'NT','2017-04-10 17:33:19',10),(2,2017,38,21,3,2017,'NT','2017-04-18 16:26:15',10),(2,2017,39,23,3,2017,'NT','2017-04-18 18:11:50',10),(2,2017,40,22,3,2017,'NT','2017-04-18 17:54:51',10),(2,2017,41,19,3,2017,'NT','2017-04-11 16:12:02',1),(3,2017,47,97,2,2017,'OF','2017-04-19 11:23:37',11);
/*!40000 ALTER TABLE `demanda_doc` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:20
