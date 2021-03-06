-- MariaDB dump 10.17  Distrib 10.4.14-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: biblio_plus
-- ------------------------------------------------------
-- Server version	10.4.14-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 100 day),
  `ACLcatalogue` tinyint(1) NOT NULL DEFAULT 0,
  `ACLloan` tinyint(1) NOT NULL DEFAULT 0,
  `ACLuser` tinyint(1) NOT NULL DEFAULT 0,
  `ACLadmin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAdmin`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Alessandro','Toninelli','toninelli.alessandro00@gmail.com','$2y$10$Va3zSWkXc/GJ2eDYzObVcOV9ClnmfpaPU/LtWjJrWpF7ExCTYkdsa','2021-08-09 21:56:50',1,1,1,1),(2,'Wade','Baisini','sheduxerr@gmail.com','$2y$10$yHqwAOwMY8JC3qsX1om6XegR.d1GTbktJYmUqLpXZ8JD3LwGeqFp6','2021-08-11 19:44:44',1,1,1,0),(4,'Tata','Matilda','tata.matilda@mellino.it','$2y$10$QwGBbUyVTI1sGPoDZ0Y.0OPrhVn1X6zY3N63juteJEgqYPaeODP7G','2021-05-19 17:34:18',1,0,1,0);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_session`
--

DROP TABLE IF EXISTS `admin_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_session` (
  `idSession` varchar(100) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day),
  PRIMARY KEY (`idSession`),
  KEY `fk2` (`idAdmin`),
  CONSTRAINT `fk2` FOREIGN KEY (`idAdmin`) REFERENCES `admin` (`idAdmin`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_session`
--

LOCK TABLES `admin_session` WRITE;
/*!40000 ALTER TABLE `admin_session` DISABLE KEYS */;
INSERT INTO `admin_session` VALUES ('dadapgnd6cumg392a0vl26v38i',1,'2021-05-23 17:16:50');
/*!40000 ALTER TABLE `admin_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `idAuthor` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idAuthor`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Walter'),(2,'Leonardo'),(8,'Godfrey H. Hardy'),(17,'Fiorenzo Formichi'),(18,'Giorgio Meini'),(25,'Richard Phillips Feynman');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `ISBN` varchar(15) NOT NULL,
  `title` varchar(45) NOT NULL,
  `subtitle` varchar(45) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `cover` varchar(20) DEFAULT NULL,
  `idPublisher` int(11) NOT NULL,
  PRIMARY KEY (`ISBN`),
  KEY `fk3_idx` (`idPublisher`),
  CONSTRAINT `fk3` FOREIGN KEY (`idPublisher`) REFERENCES `publisher` (`idPublisher`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES ('9788804678397','Einstein','La sua vita, il suo universo','Italiano',2019,'9788804678397.jpg',1),('9788808389770','Corso di informatica 3','Seconda edizione per Informatica','It',2018,'9788808389770.jpg',10),('9788811685272','Apologia di un matematico',NULL,'It',NULL,'9788811685272.jpg',5),('9788845927034','Il senso delle cose',NULL,'It',2012,'9788845927034.jpg',5);
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrow`
--

DROP TABLE IF EXISTS `borrow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borrow` (
  `idLoan` int(11) NOT NULL,
  `idCopy` int(11) NOT NULL,
  PRIMARY KEY (`idLoan`,`idCopy`),
  KEY `fk7_idx` (`idCopy`),
  CONSTRAINT `fk6` FOREIGN KEY (`idLoan`) REFERENCES `loan` (`idLoan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk7` FOREIGN KEY (`idCopy`) REFERENCES `copy` (`idCopy`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrow`
--

LOCK TABLES `borrow` WRITE;
/*!40000 ALTER TABLE `borrow` DISABLE KEYS */;
INSERT INTO `borrow` VALUES (1,1),(4,1),(4,2),(10,1),(15,1),(15,2),(17,1),(17,2),(18,1),(19,2),(21,47);
/*!40000 ALTER TABLE `borrow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `copy`
--

DROP TABLE IF EXISTS `copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `copy` (
  `idCopy` int(11) NOT NULL AUTO_INCREMENT,
  `ISBN` varchar(15) NOT NULL,
  PRIMARY KEY (`idCopy`),
  KEY `fk4_idx` (`ISBN`),
  CONSTRAINT `fk4` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copy`
--

LOCK TABLES `copy` WRITE;
/*!40000 ALTER TABLE `copy` DISABLE KEYS */;
INSERT INTO `copy` VALUES (1,'9788804678397'),(2,'9788804678397'),(30,'9788808389770'),(23,'9788811685272'),(24,'9788811685272'),(25,'9788811685272'),(26,'9788811685272'),(27,'9788811685272'),(46,'9788845927034'),(47,'9788845927034'),(48,'9788845927034');
/*!40000 ALTER TABLE `copy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan` (
  `idLoan` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `loanDate` datetime NOT NULL DEFAULT current_timestamp(),
  `duration` int(11) NOT NULL,
  `returnDate` datetime DEFAULT NULL,
  PRIMARY KEY (`idLoan`),
  KEY `fk5` (`idUser`),
  CONSTRAINT `fk5` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan`
--

LOCK TABLES `loan` WRITE;
/*!40000 ALTER TABLE `loan` DISABLE KEYS */;
INSERT INTO `loan` VALUES (1,5,'2021-04-03 22:38:19',4,'2021-05-10 18:51:06'),(4,1,'2021-05-10 21:03:38',10,'2021-05-12 22:59:11'),(10,23,'2021-05-10 22:11:42',33,'2021-05-12 23:03:56'),(15,27,'2021-05-12 23:04:45',3,'2021-05-16 21:01:23'),(17,69,'2021-05-16 21:09:32',30,'2021-05-18 15:52:45'),(18,1,'2021-05-18 15:53:53',30,'2021-05-20 22:24:42'),(19,1,'2021-05-20 22:24:10',30,NULL),(21,27,'2021-05-22 23:16:43',30,NULL);
/*!40000 ALTER TABLE `loan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publisher`
--

DROP TABLE IF EXISTS `publisher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publisher` (
  `idPublisher` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idPublisher`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher`
--

LOCK TABLES `publisher` WRITE;
/*!40000 ALTER TABLE `publisher` DISABLE KEYS */;
INSERT INTO `publisher` VALUES (1,'Mondadori'),(2,'DeA Scuola'),(4,'Garzanti'),(5,'Gli Adelphi'),(10,'Zanichelli');
/*!40000 ALTER TABLE `publisher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 100 day),
  `address` varchar(100) DEFAULT NULL,
  `avatar` varchar(20) DEFAULT NULL,
  `isEnabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Ethan Aldino','Cominelli','toninelli.alessandro02@gmail.com','$2y$10$eAV3IwNjUmCe9q3nayTXPuorlEOh0vWyXck96N5q5qzbxMWOkrXC2','2021-08-20 19:41:23','Via Pisani 11, 25040 Esine (BS)','1.PNG',1),(5,'Gabriele Molto bene Test nome assai prolisso','Baiguini cognome Assai cognomoso maciaone????','toninelli.alessandro00@gmail.com','$2y$10$kCCidRxchyT87HD8u.XiqOeAVU0pIZWcaArgGJ7iDHRWSEpeU0Of6','2021-07-24 23:23:24',NULL,'5.png',1),(23,'Wade Giovanni Erba','Nepero Baisini','sheduxerr@gmail.com','$2y$10$/.YcPX2op9vWPzArrbaXoeGfMcBzVgjlcIw7Vx56qnevOBkQeNXge','2021-07-26 10:41:04',NULL,'23.PNG',1),(26,'Manuel','Bon??','manuel.bonu02@gmail.com','$2y$10$CngVNb0BVqtVqasHeKwmxOcKJoPAPUhOp/khZpbfkbiEtbeMUdXfO','2021-08-16 09:22:20','Via Porte 12, Angolo Terme','26.jpeg',0),(27,'Simone','Mariolini','simone.mariolini42@gmail.com','$2y$10$oqLgY6iKjHaFslyMS.lhl.fpwP8C7THMSK.KecwWzO9EK0GCBJs7S','2021-08-30 23:19:00','Via Archi 8, 25040 Angolo Terme (BS)','27.PNG',1),(69,'Alberto','Pezzotti','alberto.pezzotti@gmail.com','$2y$10$pKwPIg0oFi4EVYyMWgDB4uipdAZLGiqEpjM1aM.EyC.H/9LB4E5xm','2021-08-24 20:54:48','Via Ravellini 4A, 25040 Angolo Terme (BS)','69.jpg',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_session`
--

DROP TABLE IF EXISTS `user_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_session` (
  `idSession` varchar(100) NOT NULL,
  `idUser` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day),
  PRIMARY KEY (`idSession`),
  KEY `fk1` (`idUser`),
  CONSTRAINT `fk1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_session`
--

LOCK TABLES `user_session` WRITE;
/*!40000 ALTER TABLE `user_session` DISABLE KEYS */;
INSERT INTO `user_session` VALUES ('dadapgnd6cumg392a0vl26v38i',27,'2021-05-23 23:19:14');
/*!40000 ALTER TABLE `user_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `write_book`
--

DROP TABLE IF EXISTS `write_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `write_book` (
  `ISBN` varchar(15) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ISBN`,`idAuthor`),
  KEY `fk9_idx` (`idAuthor`),
  CONSTRAINT `fk8` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk9` FOREIGN KEY (`idAuthor`) REFERENCES `author` (`idAuthor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `write_book`
--

LOCK TABLES `write_book` WRITE;
/*!40000 ALTER TABLE `write_book` DISABLE KEYS */;
INSERT INTO `write_book` VALUES ('9788804678397',1,1),('9788808389770',17,0),('9788808389770',18,1),('9788811685272',8,0),('9788845927034',25,0);
/*!40000 ALTER TABLE `write_book` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-22 23:24:14
