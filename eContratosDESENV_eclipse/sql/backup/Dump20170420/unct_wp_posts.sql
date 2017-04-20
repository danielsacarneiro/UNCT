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
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2016-12-23 09:12:23','2016-12-23 11:12:23','Bem-vindo ao WordPress. Esse é o seu primeiro post. Edite-o ou exclua-o, e então comece a escrever!','Olá, mundo!','','publish','open','open','','ola-mundo','','','2016-12-23 09:12:23','2016-12-23 11:12:23','',0,'http://localhost/wordpress/?p=1',0,'post','',1),(2,1,'2016-12-23 09:12:23','2016-12-23 11:12:23','Esta é uma página de exemplo. É diferente de um post porque ela ficará em um local e será exibida na navegação do seu site (na maioria dos temas). A maioria das pessoas começa com uma página de introdução aos potenciais visitantes do site. Ela pode ser assim:\n\n<blockquote>Olá! Eu sou um bike courrier de dia, ator amador à noite e este é meu blog. Eu moro em São Paulo, tenho um cachorro chamado Tonico e eu gosto de caipirinhas. (E de ser pego pela chuva.)</blockquote>\n\nou assim:\n\n<blockquote>A XYZ foi fundada em 1971 e desde então vem proporcionando produtos de qualidade a seus clientes. Localizada em Valinhos, XYZ emprega mais de 2.000 pessoas e faz várias contribuições para a comunidade local.</blockquote>\nComo um novo usuário do WordPress, você deve ir até o <a href=\"http://localhost/wordpress/wp-admin/\">seu painel</a> para excluir essa página e criar novas páginas com seu próprio conteúdo. Divirta-se!','Página de Exemplo','','publish','closed','open','','pagina-exemplo','','','2016-12-23 09:12:23','2016-12-23 11:12:23','',0,'http://localhost/wordpress/?page_id=2',0,'page','',0),(5,1,'2016-12-23 09:20:32','2016-12-23 11:20:32','','Cadastro de Contratos','','publish','closed','closed','','cadastrocontratosdesenv','','','2016-12-23 09:20:32','2016-12-23 11:20:32','',0,'http://localhost/wordpress/?page_id=5',0,'page','',0),(6,1,'2016-12-23 09:20:32','2016-12-23 11:20:32','','Cadastro de Contratos','','inherit','closed','closed','','5-revision-v1','','','2016-12-23 09:20:32','2016-12-23 11:20:32','',5,'http://localhost/wordpress/2016/12/23/5-revision-v1/',0,'revision','',0),(10,1,'2016-12-23 09:25:54','2016-12-23 11:25:54','','e-Contr@tos','','publish','closed','closed','','cadastro-de-contratos','','','2017-01-20 13:14:19','2017-01-20 15:14:19','',0,'http://localhost/wordpress/2016/12/23/cadastro-de-contratos/',1,'nav_menu_item','',0),(25,1,'2017-01-04 19:23:19','2017-01-04 21:23:19','','marca_sefaz','','inherit','open','closed','','marca_sefaz','','','2017-01-04 19:23:19','2017-01-04 21:23:19','',0,'http://sf300451/wordpress/wp-content/uploads/2017/01/marca_sefaz.png',0,'attachment','image/png',0),(34,1,'2017-01-13 11:34:06','2017-01-13 13:34:06','','Aplicação em desenvolvimento','','publish','closed','closed','','aplicacao-em-desenvolvimento','','','2017-01-13 11:34:06','2017-01-13 13:34:06','',0,'http://sf300451/wordpress/2017/01/13/aplicacao-em-desenvolvimento/',1,'nav_menu_item','',0),(46,1,'2017-01-20 13:17:19','2017-01-20 15:17:19','','Desenvolvimento','','publish','closed','closed','','desenvolvimento','','','2017-01-20 13:17:19','2017-01-20 15:17:19','',0,'http://sf300451/wordpress/2017/01/20/desenvolvimento/',2,'nav_menu_item','',0),(52,10,'2017-04-18 13:15:44','0000-00-00 00:00:00','','Rascunho automático','','auto-draft','open','open','','','','','2017-04-18 13:15:44','0000-00-00 00:00:00','',0,'http://sf300451/wordpress/?p=52',0,'post','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-20  8:34:55
