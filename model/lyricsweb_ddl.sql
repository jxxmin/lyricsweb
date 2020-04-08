-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Apr 2020 um 10:14
-- Server-Version: 10.1.36-MariaDB
-- PHP-Version: 7.2.11

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

DELIMITER $$
--
-- Prozeduren
--
DROP PROCEDURE IF EXISTS `spAddGenre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddGenre` (IN `newGenre` VARCHAR(50))  BEGIN
		INSERT INTO genre (genre) VALUES (spSanitize(newGenre));
	END$$

DROP PROCEDURE IF EXISTS `spAddSong`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAddSong` (IN `newTitel` VARCHAR(100), IN `newSongtext` TEXT, IN `genreId` INT(11))  BEGIN
		INSERT INTO lyrics(titel, songtext, fk_genre) VALUES ( spSanitize(newTitel), spSanitize(newSongtext), genreId);
	END$$

DROP PROCEDURE IF EXISTS `spDeleteGenre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spDeleteGenre` (`genreId` INT(11))  BEGIN
    	declare anzahl int;
        set anzahl =(SELECT count(*) FROM lyrics WHERE fk_genre = genreId);
        IF anzahl = 0 THEN
        	DELETE from genre WHERE id = genreId;
        END IF;
END$$

DROP PROCEDURE IF EXISTS `spDeleteSong`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spDeleteSong` (IN `songId` INT(11))  BEGIN
    	DELETE from lyrics WHERE id = songId;
END$$

DROP PROCEDURE IF EXISTS `spInsertUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spInsertUser` (IN `newUsername` VARCHAR(100), IN `hashedPassword` VARCHAR(225))  BEGIN
    	INSERT INTO login (username, password) VALUES (newUsername, hashedPassword);
END$$

DROP PROCEDURE IF EXISTS `spUpdateSong`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spUpdateSong` (IN `songId` INT(11), IN `titelneu` VARCHAR(100), IN `songtextneu` TEXT)  BEGIN

    	UPDATE lyrics
        SET
        	titel = spSanitize(titelneu),
        	songtext = spSanitize(songtextneu)
        WHERE id = songId;

END$$

--
-- Funktionen
--
DROP FUNCTION IF EXISTS `spSanitize`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `spSanitize` (`text` TEXT) RETURNS TEXT CHARSET latin1 NO SQL
BEGIN
     SET @X = REPLACE(REPLACE(REPLACE(text,"\\'", CHAR(39)),'\\r', CHAR(10)),'\\n', '');
     RETURN @X;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genre` (`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `action` varchar(50) NOT NULL,
  `lyrics_title` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lyrics`
--

DROP TABLE IF EXISTS `lyrics`;
CREATE TABLE IF NOT EXISTS `lyrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(100) NOT NULL,
  `songtext` text NOT NULL,
  `fk_genre` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_genre` (`fk_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Trigger `lyrics`
--
DROP TRIGGER IF EXISTS `delete_lyrics`;
DELIMITER $$
CREATE TRIGGER `delete_lyrics` AFTER DELETE ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("DELETE", OLD.titel, DEFAULT);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `new_lyrics`;
DELIMITER $$
CREATE TRIGGER `new_lyrics` AFTER INSERT ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("INSERT", NEW.titel, DEFAULT);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updated_lyrics`;
DELIMITER $$
CREATE TRIGGER `updated_lyrics` AFTER UPDATE ON `lyrics` FOR EACH ROW BEGIN
	INSERT INTO history VALUES ("UPDATE", NEW.titel, DEFAULT);
END
$$
DELIMITER ;

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
