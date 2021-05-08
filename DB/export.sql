-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 08, 2021 alle 22:38
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 8.0.3

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

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 100 day),
  `ACLcatalogue` tinyint(1) NOT NULL DEFAULT 0,
  `ACLloan` tinyint(1) NOT NULL DEFAULT 0,
  `ACLuser` tinyint(1) NOT NULL DEFAULT 0,
  `ACLadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`idAdmin`, `name`, `surname`, `mail`, `password`, `expiration`, `ACLcatalogue`, `ACLloan`, `ACLuser`, `ACLadmin`) VALUES
(1, 'Alessandro', 'Toninelli', 'toninelli.alessandro00@gmail.com', '$2y$10$Va3zSWkXc/GJ2eDYzObVcOV9ClnmfpaPU/LtWjJrWpF7ExCTYkdsa', '2021-08-09 21:56:50', 1, 1, 1, 1),
(2, 'Wade', 'Baisini', 'sheduxerr@gmail.com', '$2y$10$yHqwAOwMY8JC3qsX1om6XegR.d1GTbktJYmUqLpXZ8JD3LwGeqFp6', '2021-08-11 19:44:44', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_session`
--

CREATE TABLE `admin_session` (
  `idSession` varchar(100) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin_session`
--

INSERT INTO `admin_session` (`idSession`, `idAdmin`, `expiration`) VALUES
('dadapgnd6cumg392a0vl26v38i', 1, '2021-05-08 19:17:08'),
('g4vf5apngllr7et63jqpj9g16j', 1, '2021-05-09 08:37:47'),
('k56lnniqg0d7jltslk25d0eqbg', 1, '2021-05-08 20:56:28'),
('o2ciqeo742sdfg8f4nbo2iqa92', 1, '2021-05-09 09:34:15');

-- --------------------------------------------------------

--
-- Struttura della tabella `author`
--

CREATE TABLE `author` (
  `idAuthor` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `author`
--

INSERT INTO `author` (`idAuthor`, `name`, `surname`) VALUES
(1, 'Walter', 'Isaacson'),
(2, 'Leonardo', 'Sasso');

-- --------------------------------------------------------

--
-- Struttura della tabella `book`
--

CREATE TABLE `book` (
  `ISBN` varchar(15) NOT NULL,
  `title` varchar(45) NOT NULL,
  `subtitle` varchar(45) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `cover` varchar(20) DEFAULT NULL,
  `idPublisher` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `book`
--

INSERT INTO `book` (`ISBN`, `title`, `subtitle`, `language`, `year`, `cover`, `idPublisher`) VALUES
('9788804678397', 'Einstein', 'La sua vita, il suo universo', 'Italiano', 2019, '9788804678397.jpg', 1),
('9788804678398', 'Test😀', 'Un test, un altro test con àò $%', 'Maroccçò', 2020, NULL, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `borrow`
--

CREATE TABLE `borrow` (
  `idLoan` int(11) NOT NULL,
  `idCopy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `borrow`
--

INSERT INTO `borrow` (`idLoan`, `idCopy`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `copy`
--

CREATE TABLE `copy` (
  `idCopy` int(11) NOT NULL,
  `ISBN` varchar(15) NOT NULL,
  `shelf` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `copy`
--

INSERT INTO `copy` (`idCopy`, `ISBN`, `shelf`) VALUES
(1, '9788804678397', '12'),
(2, '9788804678397', '3');

-- --------------------------------------------------------

--
-- Struttura della tabella `loan`
--

CREATE TABLE `loan` (
  `idLoan` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `loanDate` datetime NOT NULL DEFAULT current_timestamp(),
  `duration` int(11) NOT NULL,
  `returnDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `loan`
--

INSERT INTO `loan` (`idLoan`, `idUser`, `loanDate`, `duration`, `returnDate`) VALUES
(1, 5, '2021-04-03 22:38:19', 4, '2021-05-08 10:57:49');

-- --------------------------------------------------------

--
-- Struttura della tabella `publisher`
--

CREATE TABLE `publisher` (
  `idPublisher` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `publisher`
--

INSERT INTO `publisher` (`idPublisher`, `name`) VALUES
(1, 'Mondadori'),
(2, 'DeA Scuola');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 100 day),
  `address` varchar(100) DEFAULT NULL,
  `avatar` varchar(20) DEFAULT NULL,
  `isEnabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`idUser`, `name`, `surname`, `mail`, `password`, `expiration`, `address`, `avatar`, `isEnabled`) VALUES
(1, 'Ethan Aldino', 'Cominelli', 'toninelli.alessandro02@gmail.com', '$2y$10$kkA6.uGZV.wA3fM10Eeci.01OutlfvgTjGVUomz/yNctwUb5gQYHW', '2021-07-27 16:22:26', 'Via Pisani 11, 25040 Esine (BS)', '1.PNG', 1),
(5, 'Gabriele', 'Baiguini', 'toninelli.alessandro00@gmail.com', '$2y$10$kCCidRxchyT87HD8u.XiqOeAVU0pIZWcaArgGJ7iDHRWSEpeU0Of6', '2021-07-24 23:23:24', NULL, '5.png', 1),
(23, 'Wade', 'Baisini', 'sheduxerr@gmail.com', '$2y$10$/.YcPX2op9vWPzArrbaXoeGfMcBzVgjlcIw7Vx56qnevOBkQeNXge', '2021-07-26 10:41:04', NULL, '23.PNG', 1),
(26, 'Manuel', 'Bonù', 'manuel.bonu02@gmail.com', '$2y$10$CngVNb0BVqtVqasHeKwmxOcKJoPAPUhOp/khZpbfkbiEtbeMUdXfO', '2021-08-16 09:22:20', 'Via Porte 12, Angolo Terme', '26.jpeg', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_session`
--

CREATE TABLE `user_session` (
  `idSession` varchar(100) NOT NULL,
  `idUser` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `user_session`
--

INSERT INTO `user_session` (`idSession`, `idUser`, `expiration`) VALUES
('g4vf5apngllr7et63jqpj9g16j', 26, '2021-05-09 09:23:56');

-- --------------------------------------------------------

--
-- Struttura della tabella `write_book`
--

CREATE TABLE `write_book` (
  `ISBN` varchar(15) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `write_book`
--

INSERT INTO `write_book` (`ISBN`, `idAuthor`, `position`) VALUES
('9788804678397', 1, 1),
('9788804678398', 1, 2),
('9788804678398', 2, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indici per le tabelle `admin_session`
--
ALTER TABLE `admin_session`
  ADD PRIMARY KEY (`idSession`),
  ADD KEY `fk2` (`idAdmin`);

--
-- Indici per le tabelle `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`idAuthor`);

--
-- Indici per le tabelle `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `fk3_idx` (`idPublisher`);

--
-- Indici per le tabelle `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`idLoan`,`idCopy`),
  ADD KEY `fk7_idx` (`idCopy`);

--
-- Indici per le tabelle `copy`
--
ALTER TABLE `copy`
  ADD PRIMARY KEY (`idCopy`),
  ADD KEY `fk4_idx` (`ISBN`);

--
-- Indici per le tabelle `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`idLoan`),
  ADD KEY `fk5` (`idUser`);

--
-- Indici per le tabelle `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`idPublisher`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- Indici per le tabelle `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`idSession`),
  ADD KEY `fk1` (`idUser`);

--
-- Indici per le tabelle `write_book`
--
ALTER TABLE `write_book`
  ADD PRIMARY KEY (`ISBN`,`idAuthor`),
  ADD KEY `fk9_idx` (`idAuthor`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `author`
--
ALTER TABLE `author`
  MODIFY `idAuthor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `copy`
--
ALTER TABLE `copy`
  MODIFY `idCopy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `loan`
--
ALTER TABLE `loan`
  MODIFY `idLoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `publisher`
--
ALTER TABLE `publisher`
  MODIFY `idPublisher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `admin_session`
--
ALTER TABLE `admin_session`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`idAdmin`) REFERENCES `admin` (`idAdmin`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `fk3` FOREIGN KEY (`idPublisher`) REFERENCES `publisher` (`idPublisher`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `fk6` FOREIGN KEY (`idLoan`) REFERENCES `loan` (`idLoan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk7` FOREIGN KEY (`idCopy`) REFERENCES `copy` (`idCopy`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `copy`
--
ALTER TABLE `copy`
  ADD CONSTRAINT `fk4` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk5` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `write_book`
--
ALTER TABLE `write_book`
  ADD CONSTRAINT `fk8` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk9` FOREIGN KEY (`idAuthor`) REFERENCES `author` (`idAuthor`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
