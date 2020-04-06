-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Apr 2020 um 11:17
-- Server-Version: 10.1.35-MariaDB
-- PHP-Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `lyricsweb`
--
CREATE DATABASE IF NOT EXISTS `lyricsweb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `lyricsweb`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `genre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `history`
--

CREATE TABLE `history` (
  `action` varchar(50) NOT NULL,
  `lyrics_title` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `berechtigung` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lyrics`
--

CREATE TABLE `lyrics` (
  `id` int(11) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `songtext` blob NOT NULL,
  `fk_genre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Trigger `login`
--
DELIMITER $$
CREATE TRIGGER `test` BEFORE INSERT ON `login` FOR EACH ROW BEGIN
	IF NEW.username = 'jasmin.fitz@hispeed.com' THEN
    	SET NEW.username = 'jasmin.fitz@hispeed.ch';
    END IF;
END
$$
DELIMITER ;


--
-- Trigger `lyrics`
--
DELIMITER $$
CREATE TRIGGER `delete_lyrics` AFTER DELETE ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("DELETE", OLD.titel, DEFAULT);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_lyrics` AFTER INSERT ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("INSERT", NEW.titel, DEFAULT);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updated_lyrics` AFTER UPDATE ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("UPDATE", NEW.titel, DEFAULT);
END
$$
DELIMITER ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre` (`genre`);

--
-- Indizes für die Tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`);

--
-- Indizes für die Tabelle `lyrics`
--
ALTER TABLE `lyrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_genre` (`fk_genre`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `lyrics`
--
ALTER TABLE `lyrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `lyrics`
--
ALTER TABLE `lyrics`
  ADD CONSTRAINT `lyrics_ibfk_1` FOREIGN KEY (`fk_genre`) REFERENCES `genre` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
