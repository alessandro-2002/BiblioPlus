-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 23, 2021 alle 22:20
-- Versione del server: 10.4.14-MariaDB
-- Versione PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblio_plus`
--
CREATE DATABASE IF NOT EXISTS `biblio_plus` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblio_plus`;

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_session`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `admin_session`;
CREATE TABLE IF NOT EXISTS `admin_session` (
  `idSession` varchar(100) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day),
  PRIMARY KEY (`idSession`),
  KEY `fk2` (`idAdmin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `author`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE IF NOT EXISTS `author` (
  `idAuthor` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `book`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `ISBN` varchar(15) NOT NULL,
  `title` varchar(45) NOT NULL,
  `subtitle` varchar(45) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `cover` varchar(20) DEFAULT NULL,
  `idPublisher` int(11) NOT NULL,
  PRIMARY KEY (`ISBN`),
  KEY `fk3_idx` (`idPublisher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `borrow`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `borrow`;
CREATE TABLE IF NOT EXISTS `borrow` (
  `idLoan` int(11) NOT NULL,
  `idCopy` int(11) NOT NULL,
  PRIMARY KEY (`idLoan`,`idCopy`),
  KEY `fk7_idx` (`idCopy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `copy`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `copy`;
CREATE TABLE IF NOT EXISTS `copy` (
  `idCopy` int(11) NOT NULL AUTO_INCREMENT,
  `ISBN` varchar(15) NOT NULL,
  PRIMARY KEY (`idCopy`),
  KEY `fk4_idx` (`ISBN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `loan`
--
-- Creazione: Mag 23, 2021 alle 20:17
--

DROP TABLE IF EXISTS `loan`;
CREATE TABLE IF NOT EXISTS `loan` (
  `idLoan` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `loanDate` datetime NOT NULL DEFAULT current_timestamp(),
  `duration` int(11) NOT NULL,
  `returnDate` datetime DEFAULT NULL,
  PRIMARY KEY (`idLoan`),
  KEY `fk5` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `publisher`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE IF NOT EXISTS `publisher` (
  `idPublisher` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idPublisher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `user_session`
--
-- Creazione: Mag 23, 2021 alle 20:17
--

DROP TABLE IF EXISTS `user_session`;
CREATE TABLE IF NOT EXISTS `user_session` (
  `idSession` varchar(100) NOT NULL,
  `idUser` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day),
  PRIMARY KEY (`idSession`),
  KEY `fk1` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `write_book`
--
-- Creazione: Mag 23, 2021 alle 20:16
--

DROP TABLE IF EXISTS `write_book`;
CREATE TABLE IF NOT EXISTS `write_book` (
  `ISBN` varchar(15) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ISBN`,`idAuthor`),
  KEY `fk9_idx` (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `admin_session`
--
ALTER TABLE `admin_session`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`idAdmin`) REFERENCES `admin` (`idAdmin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `fk3` FOREIGN KEY (`idPublisher`) REFERENCES `publisher` (`idPublisher`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `fk6` FOREIGN KEY (`idLoan`) REFERENCES `loan` (`idLoan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk7` FOREIGN KEY (`idCopy`) REFERENCES `copy` (`idCopy`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `copy`
--
ALTER TABLE `copy`
  ADD CONSTRAINT `fk4` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk5` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `write_book`
--
ALTER TABLE `write_book`
  ADD CONSTRAINT `fk8` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk9` FOREIGN KEY (`idAuthor`) REFERENCES `author` (`idAuthor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
