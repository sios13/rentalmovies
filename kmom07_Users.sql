-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Värd: blu-ray.student.bth.se
-- Tid vid skapande: 02 okt 2015 kl 19:14
-- Serverversion: 5.5.44-0+deb8u1-log
-- PHP-version: 5.6.13-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `sios13`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `kmom07_Users`
--

CREATE TABLE IF NOT EXISTS `kmom07_Users` (
`id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `type` varchar(12) DEFAULT 'user',
  `text` text,
  `password` char(32) DEFAULT NULL,
  `salt` int(11) NOT NULL,
  `movies` text
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `kmom07_Users`
--

INSERT INTO `kmom07_Users` (`id`, `name`, `type`, `text`, `password`, `salt`, `movies`) VALUES
(1, 'simon', 'user', 'Detta är en profiltext för Simon.', '4f3e0291e33a873509446a8666f99d24', 1388918997, '.El laberinto del fauno.Anchorman: The Legend of Ron Burgundy.Star Wars.Harry Potter and the Sorcerers Stone.Harry Potter and the Sorcerers Stone.El laberinto del fauno'),
(2, 'doe', 'admin', 'test test test admin', 'be4ee558acea411e4dbfb5e71a949069', 1388918997, '.Spider-Man 2.El laberinto del fauno.Despicable Me 2.The Conjuring'),
(3, 'simon2', 'user', 'SIMON2', '780a6de100fb9498d16b35073254a984', 1389799458, '.Despicable Me 2.Home Alone.Home Alone');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `kmom07_Users`
--
ALTER TABLE `kmom07_Users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `kmom07_Users`
--
ALTER TABLE `kmom07_Users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
