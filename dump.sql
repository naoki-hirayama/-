-- MySQL dump 10.13  Distrib 5.5.61, for Linux (x86_64)
--
-- Host: localhost  Password: Naoki0820-  Database: bbs
-- ------------------------------------------------------
-- Server version	5.5.61

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
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `color` varchar(6) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:21:42'),(2,'naoki','hoge','black',NULL,NULL,1,'2019-04-05 03:22:52'),(3,'naoki','hoge2','black',NULL,NULL,1,'2019-04-05 03:23:12'),(4,'naoki','hoge','black',NULL,NULL,1,'2019-04-05 03:24:23'),(5,'naoki','hoge','black',NULL,NULL,1,'2019-04-05 03:24:30'),(6,'naoki','hoge','black',NULL,NULL,1,'2019-04-05 03:24:41'),(7,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:26'),(8,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:30'),(9,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:32'),(10,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:35'),(11,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:37'),(12,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:39'),(13,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:40'),(14,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:43'),(15,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:25:45'),(16,'naoki','hoge','black',NULL,NULL,1,'2019-04-05 03:26:27'),(17,'naoki','naoki','black',NULL,NULL,1,'2019-04-05 03:28:58'),(18,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-05 03:30:02'),(20,'naoki','llll','black',NULL,'9384387105ca6dfa3b6643.png',1,'2019-04-05 04:54:59'),(21,'hoge','ho','green',NULL,NULL,2,'2019-04-05 05:09:54'),(22,'sssssss','ssssssss','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-07 13:20:19'),(23,'sss','ssss','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-07 13:20:25'),(24,'再変更','hoge','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-07 13:20:31'),(25,'ssss','ssssss','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-07 13:20:38'),(26,'sssss','ssssss','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-07 13:20:49'),(27,'名前変更','hoge','red',NULL,NULL,1,'2019-04-08 04:02:19'),(28,'名前変更','hoge','blue',NULL,NULL,1,'2019-04-08 04:02:46'),(29,'テスト','hoge','black',NULL,'1572967615caac8352b2ba.jpeg',1,'2019-04-08 04:04:05'),(30,'name','hoge','black',NULL,NULL,1,'2019-04-08 10:17:51'),(31,'name','hoge','black',NULL,NULL,1,'2019-04-09 02:35:18'),(32,'name','hoge','black',NULL,NULL,1,'2019-04-09 02:35:25'),(33,'name','sss','black',NULL,NULL,1,'2019-04-09 02:35:30'),(34,'name','hoge','black',NULL,NULL,1,'2019-04-10 01:49:16'),(35,'name','hoge','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-10 01:49:31'),(36,'name','hoge','black',NULL,NULL,3,'2019-04-10 03:12:15'),(37,'name','hoge','black',NULL,NULL,3,'2019-04-10 03:13:11'),(38,'name','hoge','black',NULL,NULL,4,'2019-04-10 04:08:25'),(39,'name','hoge2','black',NULL,NULL,4,'2019-04-10 04:46:27'),(40,'name','hogehoge','blue',NULL,NULL,4,'2019-04-10 08:32:18'),(41,'name','hura','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-10 09:30:46'),(42,'日本語','漢字\nありがとう','black','7fKsK9esLRh9TtJ',NULL,NULL,'2019-04-10 15:05:37'),(43,'テスト','hoge','blue',NULL,'13916603715cae93494230f.png',5,'2019-04-11 01:07:21'),(44,'テスト','テスト','black',NULL,'10687031915cae952f2025a.jpeg',5,'2019-04-11 01:15:27'),(45,'テスト','hoogehoge','green',NULL,'6391599595cae976399980.png',5,'2019-04-11 01:24:51'),(46,'テスト','更新','green',NULL,NULL,5,'2019-04-11 02:06:25'),(47,'テスト','ddd','black',NULL,NULL,5,'2019-04-11 02:09:10'),(48,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:04'),(49,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:09'),(50,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:10'),(51,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:11'),(52,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:12'),(53,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:13'),(54,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:14'),(55,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:15'),(56,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 02:12:16'),(57,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:17'),(58,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 02:12:19'),(59,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:19'),(60,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:20'),(61,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:22'),(62,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:23'),(63,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:24'),(64,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:26'),(65,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:27'),(66,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 02:12:29'),(67,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:31'),(68,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:32'),(69,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:34'),(70,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:35'),(71,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:36'),(72,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:37'),(73,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:38'),(74,'hoge','hoge','yellow','hoge',NULL,NULL,'2019-04-11 02:12:39'),(75,'hoge','hoge','yellow','hoge',NULL,NULL,'2019-04-11 02:12:40'),(76,'hoge','hogehoge','red','hoge',NULL,NULL,'2019-04-11 02:12:41'),(77,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:41'),(78,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:42'),(79,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:12:43'),(80,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:01'),(81,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:03'),(82,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:04'),(83,'hogeほげ','hoge','blue','hoge',NULL,NULL,'2019-04-11 02:13:05'),(84,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:09'),(85,'hoge','hoge','yellow','hoge',NULL,NULL,'2019-04-11 02:13:11'),(87,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:13'),(88,'hoge','hoge','red','hoge',NULL,NULL,'2019-04-11 02:13:14'),(89,'テスト','hoge','green',NULL,NULL,5,'2019-04-11 08:23:43'),(90,'テスト','test','yellow',NULL,NULL,5,'2019-04-11 08:24:43'),(91,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:06'),(92,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:08'),(93,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:14'),(94,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:16'),(95,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:18'),(96,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:20'),(97,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:22'),(98,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:24'),(99,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:34'),(100,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:39'),(101,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:40'),(102,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:41'),(103,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:43'),(104,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:44'),(105,'hoge','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:38:47'),(106,'hoge','hoge','green','hoge',NULL,NULL,'2019-04-11 08:38:49'),(107,'ほげ','変更変更','yellow','hoge',NULL,NULL,'2019-04-11 08:39:07'),(109,'ほげ','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:39:13'),(110,'ほげ','hoge','blue','hoge',NULL,NULL,'2019-04-11 08:39:14'),(123,'ほげ','hoge','yellow','hoge',NULL,NULL,'2019-04-11 08:40:06'),(129,'ほげ','hoge','yellow','hoge',NULL,NULL,'2019-04-11 08:42:30'),(131,'テスト','ほげほげ','green',NULL,NULL,5,'2019-04-12 02:01:14'),(133,'テスト2','hoge','blue',NULL,'16608755435cb41f460be50.png',5,'2019-04-15 06:05:58'),(134,'hoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 09:59:11'),(135,'hoge','hogehoge','blue',NULL,NULL,NULL,'2019-04-15 10:00:02'),(136,'hoge','hogehoge','red',NULL,NULL,NULL,'2019-04-15 10:00:52'),(137,'hoge','hogehoge','blue',NULL,NULL,NULL,'2019-04-15 10:00:54'),(138,'hoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 10:01:08'),(139,'hogehogeh','hoge','blue',NULL,NULL,NULL,'2019-04-15 10:02:21'),(140,'hoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 10:04:47'),(141,'hoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 10:08:04'),(142,'hoge','hoge','green',NULL,NULL,NULL,'2019-04-15 10:12:26'),(143,'hoge','hoge','green',NULL,NULL,NULL,'2019-04-15 10:16:23'),(144,'hogehoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 13:44:09'),(145,'hogehoge','hoge','blue',NULL,NULL,NULL,'2019-04-15 13:45:48'),(146,'hogehoge','hoge','black',NULL,NULL,NULL,'2019-04-15 14:46:38'),(147,'hogehoge','hogehoge','black',NULL,NULL,NULL,'2019-04-15 14:46:47'),(148,'hogehoge','hogehoge','blue',NULL,NULL,NULL,'2019-04-15 14:46:59'),(149,'hogehoge','hogehoge','blue',NULL,NULL,NULL,'2019-04-15 14:48:37'),(150,'hogehoge','hogehoge','yellow',NULL,NULL,NULL,'2019-04-15 14:58:27'),(151,'hogehoge','hogehoge','red',NULL,NULL,NULL,'2019-04-15 14:59:39'),(152,'hoge','hoge','yellow',NULL,NULL,NULL,'2019-04-15 14:59:55'),(153,'hoge','hogehogehoge','blue',NULL,NULL,NULL,'2019-04-16 00:55:56'),(154,'hoge','hogehogehoge','blue',NULL,NULL,NULL,'2019-04-16 00:56:31'),(155,'hoge','hoghoge','blue',NULL,NULL,NULL,'2019-04-16 01:11:24'),(157,'テスト変更','hogesssss','red',NULL,NULL,5,'2019-04-16 02:23:12'),(158,'sss','編集24','black',NULL,'18117207655cb53c9b05ded.jpeg',5,'2019-04-16 02:23:23'),(159,'hoge','hoge','red',NULL,NULL,5,'2019-04-17 06:44:51');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `color` varchar(6) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

LOCK TABLES `replies` WRITE;
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
INSERT INTO `replies` VALUES (1,'naoki','hoge','black',NULL,NULL,1,1,'2019-04-05 03:23:20'),(2,'naoki','hoge','black',NULL,NULL,1,3,'2019-04-05 03:23:30'),(3,'naoki','hoge','black',NULL,NULL,1,15,'2019-04-05 03:26:35'),(4,'naoki','hoge','black',NULL,NULL,1,12,'2019-04-05 03:26:59'),(5,'naoki','hoge','black',NULL,NULL,1,12,'2019-04-05 03:27:03'),(6,'naoki','hoge','black',NULL,NULL,1,15,'2019-04-05 03:29:12'),(7,'hoge','ssssssssss','black',NULL,NULL,2,21,'2019-04-05 05:17:19'),(8,'hoge','sssssss','black',NULL,NULL,2,21,'2019-04-05 05:17:25'),(11,'tt','tt','black','7fKsK9esLRh9TtJ',NULL,NULL,20,'2019-04-07 10:33:24'),(12,'naokinaoki','hoge','black',NULL,NULL,1,20,'2019-04-07 13:56:35'),(13,'naokinaoki','hoge','black',NULL,NULL,1,15,'2019-04-07 14:06:33'),(14,'naokinaoki','hhh','black',NULL,NULL,1,15,'2019-04-07 14:06:45'),(15,'名前変更','hoge','green',NULL,NULL,1,28,'2019-04-08 04:03:03'),(16,'名前変更','hoge','yellow',NULL,'18979294885caac80619811.png',1,28,'2019-04-08 04:03:18'),(17,'名前変更','hoge','red',NULL,NULL,1,29,'2019-04-08 04:04:44'),(18,'name','hoge','black',NULL,NULL,4,39,'2019-04-10 07:11:07'),(20,'name','hoge','black',NULL,NULL,4,38,'2019-04-10 08:15:58'),(21,'name','hoge','black',NULL,NULL,4,37,'2019-04-10 08:35:57'),(22,'再変更','change','yellow',NULL,NULL,4,40,'2019-04-10 09:01:33'),(23,'再変更','ほげほげ','blue',NULL,NULL,4,40,'2019-04-10 09:01:38'),(24,'再変更','hoghog','green',NULL,NULL,4,40,'2019-04-10 09:02:07'),(27,'name','hoge','black','7fKsK9esLRh9TtJ',NULL,NULL,33,'2019-04-10 13:03:19'),(28,'なおき','こんにちは','black','7fKsK9esLRh9TtJ',NULL,NULL,33,'2019-04-10 13:47:58'),(29,'テスト','hoge','black',NULL,'17118985265cae984158e8f.jpeg',5,45,'2019-04-11 01:28:33'),(30,'テスト2','レス1','black',NULL,NULL,5,159,'2019-04-17 06:45:15'),(31,'テスト2','レス2','red',NULL,'5189142285cb6cb905e2f5.png',5,159,'2019-04-17 06:45:36'),(32,'テスト2','レス3','black',NULL,NULL,5,159,'2019-04-17 06:45:56'),(33,'テスト','hello','red',NULL,'17627163045cb6cbb051520.jpeg',5,159,'2019-04-17 06:46:08');
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `login_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `comment` varchar(60) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_id` (`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'name','naoki','$2y$10$yheTsVjK4Elqr/oh2CWj9.NHddSPVAoZfYzvounJ3/1TGkNAeORZS',NULL,'ほげ','2019-04-05 02:56:19'),(2,'hoge','hoge','$2y$10$dok1gwfyX8w2Tv4my0hiyuYoNN7aN889jXiU5zQ64rB5Qrkrdt/vK',NULL,NULL,'2019-04-05 05:09:41'),(3,'name','namename','$2y$10$2kA/eJhilsSdtkJxWU9HkeCJs3ENScVH4adrG2rPqH2MZ5ckK86vi',NULL,NULL,'2019-04-10 03:12:07'),(4,'再変更','higesssssss','$2y$10$OBev7pLPbh1H5Prr/fKLO.kzOOY32ao80i9uCABN/CDEiNf31pyBq',NULL,NULL,'2019-04-10 04:08:16'),(5,'テスト2','test','$2y$10$o8rYFFuDrzW8n6FmdGq.6O5m1c63sUMwAJ156bxrm5L62fI7z7BNm','8671390605cae9316e71f9.jpeg','test','2019-04-11 01:06:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-19  4:38:30
