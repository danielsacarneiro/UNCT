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
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','Daniel Sá Carneiro'),(2,1,'first_name','Daniel Sá Carneiro'),(3,1,'last_name','Ribeiro'),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'comment_shortcuts','false'),(7,1,'admin_color','fresh'),(8,1,'use_ssl','0'),(9,1,'show_admin_bar_front','true'),(10,1,'locale',''),(11,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(12,1,'wp_user_level','10'),(13,1,'dismissed_wp_pointers',''),(14,1,'show_welcome_panel','1'),(16,1,'wp_dashboard_quick_press_last_post_id','51'),(17,2,'nickname','rogerio.f-carvalho'),(18,2,'first_name','Rogerio'),(19,2,'last_name','Carvalho'),(20,2,'description',''),(21,2,'rich_editing','true'),(22,2,'comment_shortcuts','false'),(23,2,'admin_color','fresh'),(24,2,'use_ssl','0'),(25,2,'show_admin_bar_front','true'),(26,2,'locale',''),(27,2,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(28,2,'wp_user_level','1'),(29,2,'dismissed_wp_pointers',''),(31,2,'wp_user-settings','mfold=f'),(32,2,'wp_user-settings-time','1482500777'),(47,4,'nickname','cynnara.faria'),(48,4,'first_name','Cynnara Faria'),(49,4,'last_name',''),(50,4,'description',''),(51,4,'rich_editing','true'),(52,4,'comment_shortcuts','false'),(53,4,'admin_color','fresh'),(54,4,'use_ssl','0'),(55,4,'show_admin_bar_front','true'),(56,4,'locale',''),(57,4,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(58,4,'wp_user_level','1'),(59,4,'dismissed_wp_pointers',''),(61,4,'wp_dashboard_quick_press_last_post_id','40'),(62,2,'wp_dashboard_quick_press_last_post_id','21'),(63,5,'nickname','petronio.santiago'),(64,5,'first_name','Petronio'),(65,5,'last_name','Santiago'),(66,5,'description',''),(67,5,'rich_editing','true'),(68,5,'comment_shortcuts','false'),(69,5,'admin_color','fresh'),(70,5,'use_ssl','0'),(71,5,'show_admin_bar_front','true'),(72,5,'locale',''),(73,5,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(74,5,'wp_user_level','1'),(75,5,'dismissed_wp_pointers',''),(103,8,'nickname','jessica.andrade'),(104,8,'first_name','Jessica'),(105,8,'last_name','Andrade'),(106,8,'description',''),(107,8,'rich_editing','true'),(108,8,'comment_shortcuts','false'),(109,8,'admin_color','fresh'),(110,8,'use_ssl','0'),(111,8,'show_admin_bar_front','true'),(112,8,'locale',''),(113,8,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(114,8,'wp_user_level','1'),(115,8,'dismissed_wp_pointers',''),(116,1,'wp_user-settings','libraryContent=browse'),(117,1,'wp_user-settings-time','1483565003'),(118,4,'session_tokens','a:1:{s:64:\"fc0f4271dfd2f58ada339658d1958ab4203eed7c9de677ffac498c40999278f2\";a:4:{s:10:\"expiration\";i:1491663498;s:2:\"ip\";s:25:\"fe80::10dc:207c:66f5:e88d\";s:2:\"ua\";s:72:\"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0\";s:5:\"login\";i:1491490698;}}'),(119,1,'session_tokens','a:2:{s:64:\"eff7a480ea7d0b57e7bbc08c99ad936b3fe7ccede17b8dd820cd7174facc08f6\";a:4:{s:10:\"expiration\";i:1492695484;s:2:\"ip\";s:11:\"10.8.13.142\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492522684;}s:64:\"404bae430c4024ab210c2afeff1f9b211689f7618ae198bdec1f997cfeda8491\";a:4:{s:10:\"expiration\";i:1492777881;s:2:\"ip\";s:11:\"10.8.13.142\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492605081;}}'),(120,2,'session_tokens','a:4:{s:64:\"7bfc832f35af344e5b09d4dd0c935463e97fc129f5d08c8f0619251567bedbb7\";a:4:{s:10:\"expiration\";i:1491482922;s:2:\"ip\";s:10:\"10.8.13.86\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1491310122;}s:64:\"46dda4ff65412c7ac3f029c3c5aa2e7db5a590c856cf2a80266d3e25a6303e68\";a:4:{s:10:\"expiration\";i:1491482923;s:2:\"ip\";s:10:\"10.8.13.86\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1491310123;}s:64:\"57eb204353ffc1db31d9c760332922259fec94e438727d6cee4c41402a053071\";a:4:{s:10:\"expiration\";i:1491482924;s:2:\"ip\";s:10:\"10.8.13.86\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1491310124;}s:64:\"bad385a820c15a19345e58a5e87b75e2091dcdc7c161c14ee2acaf5564b80d87\";a:4:{s:10:\"expiration\";i:1491482925;s:2:\"ip\";s:10:\"10.8.13.86\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1491310125;}}'),(121,9,'nickname','cinthia.bonfim'),(122,9,'first_name','Cinthia'),(123,9,'last_name','Bonfim'),(124,9,'description',''),(125,9,'rich_editing','true'),(126,9,'comment_shortcuts','false'),(127,9,'admin_color','fresh'),(128,9,'use_ssl','0'),(129,9,'show_admin_bar_front','true'),(130,9,'locale',''),(131,9,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(132,9,'wp_user_level','1'),(133,9,'dismissed_wp_pointers',''),(135,9,'session_tokens','a:1:{s:64:\"87149301cada1e2f0b969fd59c16c08f6bab4c1ae33b54ce28792bbf5addea7e\";a:4:{s:10:\"expiration\";i:1492019385;s:2:\"ip\";s:11:\"10.8.13.108\";s:2:\"ua\";s:108:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\";s:5:\"login\";i:1490809785;}}'),(136,8,'session_tokens','a:1:{s:64:\"e3ce3f79a85959fd82aace744272aec7fa92f50f347b6bb385df152ab5d06013\";a:4:{s:10:\"expiration\";i:1492785544;s:2:\"ip\";s:11:\"10.8.13.129\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492612744;}}'),(137,10,'nickname','miguel.carvalho'),(138,10,'first_name','Miguel'),(139,10,'last_name','Amaral'),(140,10,'description',''),(141,10,'rich_editing','true'),(142,10,'comment_shortcuts','false'),(143,10,'admin_color','fresh'),(144,10,'use_ssl','0'),(145,10,'show_admin_bar_front','true'),(146,10,'locale',''),(147,10,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(148,10,'wp_user_level','1'),(149,10,'dismissed_wp_pointers',''),(150,10,'session_tokens','a:4:{s:64:\"4cbaddbd4b8a0ebcbdffef9dbd9331bafe20a793227083981410c9402d5fc489\";a:4:{s:10:\"expiration\";i:1492704941;s:2:\"ip\";s:11:\"10.8.13.178\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492532141;}s:64:\"ada27cb4028c4dee17ee0e26d98765c29ef4f692d13616da1171c077b414661e\";a:4:{s:10:\"expiration\";i:1492705007;s:2:\"ip\";s:11:\"10.8.13.178\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492532207;}s:64:\"48e37f1c9696f760576cf0e4849db2212cb0a0dc21c68c1f41164ef8d0fd9663\";a:4:{s:10:\"expiration\";i:1492705113;s:2:\"ip\";s:11:\"10.8.13.178\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492532313;}s:64:\"47c2f8347ebec06be32506247f78ab0d7f3e66781f58ccf00d5bd27166bdafd2\";a:4:{s:10:\"expiration\";i:1492710649;s:2:\"ip\";s:11:\"10.8.13.178\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492537849;}}'),(151,10,'wp_dashboard_quick_press_last_post_id','52'),(152,11,'nickname','andrea.c-oliveira'),(153,11,'first_name','Andrea'),(154,11,'last_name','Lucena'),(155,11,'description',''),(156,11,'rich_editing','true'),(157,11,'comment_shortcuts','false'),(158,11,'admin_color','fresh'),(159,11,'use_ssl','0'),(160,11,'show_admin_bar_front','true'),(161,11,'locale',''),(162,11,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(163,11,'wp_user_level','1'),(164,11,'dismissed_wp_pointers',''),(165,11,'session_tokens','a:2:{s:64:\"ddcaa3c1a6dfb281c37a92d0d2c76472a38c1039a3c3f39dba321ebae6fb9630\";a:4:{s:10:\"expiration\";i:1492772390;s:2:\"ip\";s:10:\"10.8.13.73\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492599590;}s:64:\"e3ef465944e977a06c3131638e79460259d3975cdc22e3c03005090567a0553a\";a:4:{s:10:\"expiration\";i:1492773568;s:2:\"ip\";s:10:\"10.8.13.73\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1492600768;}}'),(166,12,'nickname','sergio.lins'),(167,12,'first_name','Sergio'),(168,12,'last_name','Lins'),(169,12,'description',''),(170,12,'rich_editing','true'),(171,12,'comment_shortcuts','false'),(172,12,'admin_color','fresh'),(173,12,'use_ssl','0'),(174,12,'show_admin_bar_front','true'),(175,12,'locale',''),(176,12,'wp_capabilities','a:1:{s:10:\"subscriber\";b:1;}'),(177,12,'wp_user_level','0'),(178,12,'dismissed_wp_pointers',''),(179,12,'session_tokens','a:1:{s:64:\"1291fda8e30fc018ff23945a0fdd9055e4d4b5090ff557503568d9607b5b7d03\";a:4:{s:10:\"expiration\";i:1492699674;s:2:\"ip\";s:11:\"10.8.13.122\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36\";s:5:\"login\";i:1491490074;}}'),(180,13,'nickname','margarida.vasconcelos'),(181,13,'first_name','Margarida'),(182,13,'last_name','Vasconcelos'),(183,13,'description',''),(184,13,'rich_editing','true'),(185,13,'comment_shortcuts','false'),(186,13,'admin_color','fresh'),(187,13,'use_ssl','0'),(188,13,'show_admin_bar_front','true'),(189,13,'locale',''),(190,13,'wp_capabilities','a:1:{s:11:\"contributor\";b:1;}'),(191,13,'wp_user_level','1'),(192,13,'dismissed_wp_pointers',''),(193,13,'session_tokens','a:1:{s:64:\"49b79ad5b2c264cdefc2605061915dbf122ab0aa3656ad530315aa801619e5a1\";a:4:{s:10:\"expiration\";i:1492173314;s:2:\"ip\";s:11:\"10.8.12.139\";s:2:\"ua\";s:72:\"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0\";s:5:\"login\";i:1492000514;}}');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:35:02
