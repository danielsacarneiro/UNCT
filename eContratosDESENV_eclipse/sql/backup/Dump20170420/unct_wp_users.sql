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
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'daniel.ribeiro','$P$B9U12AJF161swBuQ45JsjFZ7a3yj8O/','daniel-ribeiro','daniel.ribeiro@sefaz.pe.gov.br','','2016-12-23 11:12:22','',0,'Daniel Sá Carneiro'),(2,'rogerio.f-carvalho','$P$Bre5uHTRoDT9BdR.RdKViyKaMdV4NV/','rogerio-f-carvalho','rogerio.f-carvalho@sefaz.pe.gov.br','','2016-12-23 12:15:46','',0,'Rogerio'),(4,'cynnara.faria','$P$BKtWY.4Jk9dEjtEniy5fHIY1j5XHSW1','cynnara-faria','cynnara.faria@sefaz.pe.gov.br','','2016-12-26 15:04:24','1482764666:$P$B/r5SgaScZhGVPXSqu1ArlyzEzdOIF/',0,'Cynnara Faria'),(5,'petronio.santiago','$P$BfPFTnGEhpnwN3znnfRgIyJ8YbAb3z0','petronio-santiago','petronio.santiago@sefaz.pe.gov.br','','2017-01-03 12:53:03','',0,'Petronio Santiago'),(8,'jessica.andrade','$P$BfrHNiociYhTmBbwtDYBQfrFSJTYmM/','jessica-andrade','jessica.andrade@sefaz.pe.gov.br','','2017-01-03 13:32:11','',0,'Jessica Andrade'),(9,'cinthia.bonfim','$P$BMSRQpLqKRuhPS3607Va/lPHcnromh.','cinthia-bonfim','cinthia.bonfim@sefaz.pe.gov.br','','2017-03-17 14:00:11','1489759212:$P$BVkbI7kv1viXODl3Pz6PTPy25K39FR/',0,'Cinthia Bonfim'),(10,'miguel.carvalho','$P$BKQ14XimibDkKb4kyeZlVFLK/KGeBK1','miguel-carvalho','miguel.cavalho@hotmail.com','','2017-03-27 16:23:44','',0,'Miguel Amaral'),(11,'andrea.c-oliveira','$P$BtBVGek0dr9n9Y/2HR5j5V05fQL80a1','andrea-c-oliveira','andrea.c-oliveira@sefaz.pe.gov.br','','2017-03-31 13:28:39','',0,'Andrea Lucena'),(12,'sergio.lins','$P$B9SSclTmNaRUMWvnqS7ESxnERXvWOq0','sergio-lins','sergio.lins@sefaz.pe.gov.br','','2017-03-31 17:29:46','',0,'Sergio Lins'),(13,'margarida.vasconcelos','$P$B9VggPyLosJSg9UDu1jRK0/.km.Kkk.','margarida-vasconcelos','margarida.vasconcelos@sad.pe.gov.br','','2017-04-12 12:34:37','',0,'Margarida Vasconcelos');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:12
