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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Alessandro','Toninelli','test@test.it','$2y$10$p7sXO5MY7xY/NWy.SFGFkOqW7JFs/TcCj9toKbhrWZnHXkJgsPavm','2021-08-31 20:21:34',1,1,1,1);
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
  CONSTRAINT `fk2` FOREIGN KEY (`idAdmin`) REFERENCES `admin` (`idAdmin`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_session`
--

LOCK TABLES `admin_session` WRITE;
/*!40000 ALTER TABLE `admin_session` DISABLE KEYS */;
INSERT INTO `admin_session` VALUES ('dadapgnd6cumg392a0vl26v38i',1,'2021-05-24 22:26:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Godfrey H. Hardy'),(2,'Richard Phillips Feynman'),(3,'Carlo Rovelli'),(4,'Stephen Hawking'),(5,'Walter Isaacson'),(6,'Primo Levi'),(7,'Italo Calvino'),(8,'Dante Alighieri'),(9,'Roberto Mercadini'),(10,'Joanne K. Rowling'),(11,'Paolo Nespoli');
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
INSERT INTO `book` VALUES ('9781473695993','Brief Answers to the Big Questions','The Final Book from Stephen Hawking','en',NULL,'9781473695993.jpg',3),('9788804598893','Il barone rampante',NULL,'it',2010,'9788804598893.jpg',6),('9788804614401','Dall\'alto i problemi sembrano più piccoli','lezioni di vita imparate dallo spazio','it',2012,'9788804614401.jpg',6),('9788804678397','Einstein. La sua vita, il suo universo',NULL,'it',2010,'9788804678397.jpg',4),('9788806219352','Se questo è un uomo',NULL,'it',2014,'9788806219352.jpg',5),('9788806219505','Il sistema periodico',NULL,'it',2014,'9788806219505.jpg',1),('9788811685272','Apologia di un matematico',NULL,'it',2002,'9788811685272.jpg',1),('9788817143509','Storia perfetta dell\'errore',NULL,'it',2019,'9788817143509.jpg',8),('9788817147347','Bomba atomica',NULL,'it',2020,'9788817147347.jpg',8),('9788831003384','Harry Potter e la pietra filosofale',NULL,'it',2020,'9788831003384.jpg',9),('9788831003391','Harry Potter e la camera dei segreti',NULL,'it',2020,'9788831003391.jpg',9),('9788831003407','Harry Potter e il prigioniero di Azkaban',NULL,'it',2020,'9788831003407.jpg',9),('9788831003414','Harry Potter e il calice di fuoco',NULL,'it',NULL,'9788831003414.jpg',9),('9788831003421','Harry Potter e l\'ordine della fenice',NULL,'it',2020,'9788831003421.jpg',9),('9788831003438','Harry Potter e il principe mezzosangue',NULL,'it',NULL,'9788831003438.jpg',9),('9788831003445','Harry Potter e i doni della morte',NULL,'it',2020,'9788831003445.jpg',9),('9788845927034','Il senso delle cose',NULL,'it',2012,'9788845927034.jpg',2),('9788845929250','Sette brevi lezioni di fisica',NULL,'it',2014,'9788845929250.jpg',2),('9788845931925','L\'ordine del tempo',NULL,'it',2017,'9788845931925.jpg',2),('9788845935053','Helgoland',NULL,'it',2020,'9788845935053.jpg',2),('9788854165069','Divina Commedia. Ediz. integrale',NULL,'it',2014,'9788854165069.jpg',7);
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
INSERT INTO `borrow` VALUES (1,15),(1,17),(1,34),(1,52),(1,72);
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
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copy`
--

LOCK TABLES `copy` WRITE;
/*!40000 ALTER TABLE `copy` DISABLE KEYS */;
INSERT INTO `copy` VALUES (21,'9781473695993'),(36,'9788804598893'),(37,'9788804598893'),(38,'9788804598893'),(39,'9788804598893'),(76,'9788804614401'),(77,'9788804614401'),(78,'9788804614401'),(22,'9788804678397'),(23,'9788804678397'),(24,'9788804678397'),(25,'9788804678397'),(26,'9788804678397'),(27,'9788806219352'),(28,'9788806219352'),(29,'9788806219352'),(30,'9788806219352'),(31,'9788806219352'),(32,'9788806219352'),(33,'9788806219352'),(34,'9788806219505'),(35,'9788806219505'),(1,'9788811685272'),(2,'9788811685272'),(3,'9788811685272'),(4,'9788811685272'),(5,'9788811685272'),(52,'9788817143509'),(53,'9788817143509'),(54,'9788817143509'),(50,'9788817147347'),(51,'9788817147347'),(55,'9788831003384'),(56,'9788831003384'),(57,'9788831003384'),(58,'9788831003391'),(59,'9788831003391'),(60,'9788831003391'),(61,'9788831003391'),(62,'9788831003407'),(63,'9788831003407'),(64,'9788831003407'),(65,'9788831003414'),(66,'9788831003421'),(67,'9788831003421'),(68,'9788831003421'),(69,'9788831003421'),(70,'9788831003421'),(71,'9788831003438'),(72,'9788831003445'),(73,'9788831003445'),(74,'9788831003445'),(75,'9788831003445'),(6,'9788845927034'),(7,'9788845927034'),(8,'9788845927034'),(17,'9788845929250'),(18,'9788845929250'),(19,'9788845929250'),(20,'9788845929250'),(15,'9788845931925'),(16,'9788845931925'),(9,'9788845935053'),(10,'9788845935053'),(11,'9788845935053'),(12,'9788845935053'),(13,'9788845935053'),(14,'9788845935053'),(40,'9788854165069'),(41,'9788854165069'),(42,'9788854165069'),(43,'9788854165069'),(44,'9788854165069'),(45,'9788854165069'),(46,'9788854165069'),(47,'9788854165069'),(48,'9788854165069'),(49,'9788854165069');
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
  CONSTRAINT `fk5` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan`
--

LOCK TABLES `loan` WRITE;
/*!40000 ALTER TABLE `loan` DISABLE KEYS */;
INSERT INTO `loan` VALUES (1,1,'2021-05-23 22:58:30',30,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher`
--

LOCK TABLES `publisher` WRITE;
/*!40000 ALTER TABLE `publisher` DISABLE KEYS */;
INSERT INTO `publisher` VALUES (1,'Garzanti'),(2,'Adelphi'),(3,'John Murray'),(4,'Oscar storia'),(5,'Einaudi'),(6,'Mondadori'),(7,'Zanichelli'),(8,'Rizzoli'),(9,'Salani');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Giuseppe','Conte','test@test.it','$2y$10$g..izYnW9SzCXO3oFpypuOuHxRivEQN9G05uAiGYVyAM7x2Xl2yVS','2021-08-31 22:25:41','Via Provinciale 33, Roma','1.jpeg',1);
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
  CONSTRAINT `fk1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_session`
--

LOCK TABLES `user_session` WRITE;
/*!40000 ALTER TABLE `user_session` DISABLE KEYS */;
INSERT INTO `user_session` VALUES ('dadapgnd6cumg392a0vl26v38i',1,'2021-05-24 22:25:51');
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
INSERT INTO `write_book` VALUES ('9781473695993',4,0),('9788804598893',7,0),('9788804614401',11,0),('9788804678397',5,0),('9788806219352',6,0),('9788806219505',6,0),('9788811685272',1,0),('9788817143509',9,0),('9788817147347',9,0),('9788831003384',10,0),('9788831003391',10,0),('9788831003407',10,0),('9788831003414',10,0),('9788831003421',10,0),('9788831003438',10,0),('9788831003445',10,0),('9788845927034',2,0),('9788845929250',3,0),('9788845931925',3,0),('9788845935053',3,0),('9788854165069',8,0);
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

-- Dump completed on 2021-05-23 23:00:27
