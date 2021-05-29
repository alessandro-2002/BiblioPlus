-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 29, 2021 alle 22:55
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
  `expiration` datetime NOT NULL,
  `ACLcatalogue` tinyint(1) NOT NULL DEFAULT 0,
  `ACLloan` tinyint(1) NOT NULL DEFAULT 0,
  `ACLuser` tinyint(1) NOT NULL DEFAULT 0,
  `ACLadmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`idAdmin`, `name`, `surname`, `mail`, `password`, `expiration`, `ACLcatalogue`, `ACLloan`, `ACLuser`, `ACLadmin`) VALUES
(1, 'Alessandro', 'Toninelli', 'test@test.it', '$2y$10$p7sXO5MY7xY/NWy.SFGFkOqW7JFs/TcCj9toKbhrWZnHXkJgsPavm', '2021-08-31 20:21:34', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_session`
--

CREATE TABLE `admin_session` (
  `idSession` varchar(100) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `author`
--

CREATE TABLE `author` (
  `idAuthor` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `author`
--

INSERT INTO `author` (`idAuthor`, `name`) VALUES
(1, 'Godfrey H. Hardy'),
(2, 'Richard Phillips Feynman'),
(3, 'Carlo Rovelli'),
(4, 'Stephen Hawking'),
(5, 'Walter Isaacson'),
(6, 'Primo Levi'),
(7, 'Italo Calvino'),
(8, 'Dante Alighieri'),
(9, 'Roberto Mercadini'),
(10, 'Joanne K. Rowling'),
(11, 'Paolo Nespoli');

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
('9781473695993', 'Brief Answers to the Big Questions', 'The Final Book from Stephen Hawking', 'en', NULL, '9781473695993.jpg', 3),
('9788804598893', 'Il barone rampante', NULL, 'it', 2010, '9788804598893.jpg', 6),
('9788804614401', 'Dall\'alto i problemi sembrano più piccoli', 'lezioni di vita imparate dallo spazio', 'it', 2012, '9788804614401.jpg', 6),
('9788804678397', 'Einstein. La sua vita, il suo universo', NULL, 'it', 2010, '9788804678397.jpg', 4),
('9788806219352', 'Se questo è un uomo', NULL, 'it', 2014, '9788806219352.jpg', 5),
('9788806219505', 'Il sistema periodico', NULL, 'it', 2014, '9788806219505.jpg', 1),
('9788811685272', 'Apologia di un matematico', NULL, 'it', 2002, '9788811685272.jpg', 1),
('9788817143509', 'Storia perfetta dell\'errore', NULL, 'it', 2019, '9788817143509.jpg', 8),
('9788817147347', 'Bomba atomica', NULL, 'it', 2020, '9788817147347.jpg', 8),
('9788831003384', 'Harry Potter e la pietra filosofale', NULL, 'it', 2020, '9788831003384.jpg', 9),
('9788831003391', 'Harry Potter e la camera dei segreti', NULL, 'it', 2020, '9788831003391.jpg', 9),
('9788831003407', 'Harry Potter e il prigioniero di Azkaban', NULL, 'it', 2020, '9788831003407.jpg', 9),
('9788831003414', 'Harry Potter e il calice di fuoco', NULL, 'it', NULL, '9788831003414.jpg', 9),
('9788831003421', 'Harry Potter e l\'ordine della fenice', NULL, 'it', 2020, '9788831003421.jpg', 9),
('9788831003438', 'Harry Potter e il principe mezzosangue', NULL, 'it', NULL, '9788831003438.jpg', 9),
('9788831003445', 'Harry Potter e i doni della morte', NULL, 'it', 2020, '9788831003445.jpg', 9),
('9788845927034', 'Il senso delle cose', NULL, 'it', 2012, '9788845927034.jpg', 2),
('9788845929250', 'Sette brevi lezioni di fisica', NULL, 'it', 2014, '9788845929250.jpg', 2),
('9788845931925', 'L\'ordine del tempo', NULL, 'it', 2017, '9788845931925.jpg', 2),
('9788845935053', 'Helgoland', NULL, 'it', 2020, '9788845935053.jpg', 2),
('9788854165069', 'Divina Commedia. Ediz. integrale', NULL, 'it', 2014, '9788854165069.jpg', 7);

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
(1, 15),
(1, 17),
(1, 34),
(1, 52),
(1, 72);

-- --------------------------------------------------------

--
-- Struttura della tabella `copy`
--

CREATE TABLE `copy` (
  `idCopy` int(11) NOT NULL,
  `ISBN` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `copy`
--

INSERT INTO `copy` (`idCopy`, `ISBN`) VALUES
(21, '9781473695993'),
(36, '9788804598893'),
(37, '9788804598893'),
(38, '9788804598893'),
(39, '9788804598893'),
(76, '9788804614401'),
(77, '9788804614401'),
(78, '9788804614401'),
(22, '9788804678397'),
(23, '9788804678397'),
(24, '9788804678397'),
(25, '9788804678397'),
(26, '9788804678397'),
(27, '9788806219352'),
(28, '9788806219352'),
(29, '9788806219352'),
(30, '9788806219352'),
(31, '9788806219352'),
(32, '9788806219352'),
(33, '9788806219352'),
(34, '9788806219505'),
(35, '9788806219505'),
(1, '9788811685272'),
(2, '9788811685272'),
(3, '9788811685272'),
(4, '9788811685272'),
(5, '9788811685272'),
(52, '9788817143509'),
(53, '9788817143509'),
(54, '9788817143509'),
(50, '9788817147347'),
(51, '9788817147347'),
(55, '9788831003384'),
(56, '9788831003384'),
(57, '9788831003384'),
(58, '9788831003391'),
(59, '9788831003391'),
(60, '9788831003391'),
(61, '9788831003391'),
(62, '9788831003407'),
(63, '9788831003407'),
(64, '9788831003407'),
(65, '9788831003414'),
(66, '9788831003421'),
(67, '9788831003421'),
(68, '9788831003421'),
(69, '9788831003421'),
(70, '9788831003421'),
(71, '9788831003438'),
(72, '9788831003445'),
(73, '9788831003445'),
(74, '9788831003445'),
(75, '9788831003445'),
(6, '9788845927034'),
(7, '9788845927034'),
(8, '9788845927034'),
(17, '9788845929250'),
(18, '9788845929250'),
(19, '9788845929250'),
(20, '9788845929250'),
(15, '9788845931925'),
(16, '9788845931925'),
(9, '9788845935053'),
(10, '9788845935053'),
(11, '9788845935053'),
(12, '9788845935053'),
(13, '9788845935053'),
(14, '9788845935053'),
(40, '9788854165069'),
(41, '9788854165069'),
(42, '9788854165069'),
(43, '9788854165069'),
(44, '9788854165069'),
(45, '9788854165069'),
(46, '9788854165069'),
(47, '9788854165069'),
(48, '9788854165069'),
(49, '9788854165069');

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
(1, 1, '2021-05-23 22:58:30', 30, NULL);

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
(1, 'Garzanti'),
(2, 'Adelphi'),
(3, 'John Murray'),
(4, 'Oscar storia'),
(5, 'Einaudi'),
(6, 'Mondadori'),
(7, 'Zanichelli'),
(8, 'Rizzoli'),
(9, 'Salani');

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
(1, 'Giuseppe', 'Conte', 'test@test.it', '$2y$10$g..izYnW9SzCXO3oFpypuOuHxRivEQN9G05uAiGYVyAM7x2Xl2yVS', '2021-08-31 22:25:41', 'Via Provinciale 33, Roma', '1.jpeg', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_session`
--

CREATE TABLE `user_session` (
  `idSession` varchar(100) NOT NULL,
  `idUser` int(11) NOT NULL,
  `expiration` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
('9781473695993', 4, 0),
('9788804598893', 7, 0),
('9788804614401', 11, 0),
('9788804678397', 5, 0),
('9788806219352', 6, 0),
('9788806219505', 6, 0),
('9788811685272', 1, 0),
('9788817143509', 9, 0),
('9788817147347', 9, 0),
('9788831003384', 10, 0),
('9788831003391', 10, 0),
('9788831003407', 10, 0),
('9788831003414', 10, 0),
('9788831003421', 10, 0),
('9788831003438', 10, 0),
('9788831003445', 10, 0),
('9788845927034', 2, 0),
('9788845929250', 3, 0),
('9788845931925', 3, 0),
('9788845935053', 3, 0),
('9788854165069', 8, 0);

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
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `author`
--
ALTER TABLE `author`
  MODIFY `idAuthor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `copy`
--
ALTER TABLE `copy`
  MODIFY `idCopy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT per la tabella `loan`
--
ALTER TABLE `loan`
  MODIFY `idLoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `publisher`
--
ALTER TABLE `publisher`
  MODIFY `idPublisher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
