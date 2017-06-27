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
-- Tabellstruktur `kmom07_Movies`
--

CREATE TABLE IF NOT EXISTS `kmom07_Movies` (
`id` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `text` text,
  `category` varchar(80) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `year` int(11) NOT NULL DEFAULT '1900',
  `subtext` varchar(50) DEFAULT NULL,
  `speech` varchar(20) DEFAULT NULL,
  `price` varchar(10) DEFAULT '19',
  `image` varchar(500) DEFAULT 'noimage.jpg',
  `image_header` varchar(500) DEFAULT 'noimage_header.jpg',
  `youtube` varchar(100) DEFAULT NULL,
  `imdb` varchar(100) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `kmom07_Movies`
--

INSERT INTO `kmom07_Movies` (`id`, `title`, `text`, `category`, `director`, `length`, `year`, `subtext`, `speech`, `price`, `image`, `image_header`, `youtube`, `imdb`, `score`, `published`, `created`, `updated`, `deleted`) VALUES
(1, 'World War Z', 'United Nations employee Gerry Lane traverses the world in a race against time to stop the Zombie pandemic that is toppling armies and governments, and threatening to destroy humanity itself.', 'action, äventyr, horror, sci-fi, thriller', 'Marc Forster', 116, 2013, 'svenska', 'engelska', '19', 'worldwarz.jpg', 'worldwarz_header.jpg', 'HcwTxRuq-uk', 'tt0816711', 7.1, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(2, 'Spider-Man 2', 'Peter Parker is beset with troubles in his failing personal life as he battles a brilliant scientist named Doctor Otto Octavius.', 'action, äventyr, fantasy', 'Sam Raimi', 127, 2004, 'svenska', 'engelska', '19', 'spider-man2.jpg', 'spider-man2_header.jpg', 'bpgrOgypc9g', 'tt0316654', 7.4, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(3, 'The Hobbit: The Desolation of Smaug', 'The dwarves, along with Bilbo Baggins and Gandalf the Grey, continue their quest to reclaim Erebor, their homeland, from Smaug. Bilbo Baggins is in possession of a mysterious and magical ring.', 'äventyr, fantasy', 'Peter Jackson', 161, 2013, 'svenska', 'engelska', '19', 'thehobbit2.jpg', 'thehobbit2_header.jpg', 'OPVWy1tFXuc', 'tt1170358', 8.4, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(4, 'Home Alone', 'An 8-year-old boy who is accidentally left behind while his family flies to France for Christmas must defend his home against idiotic burglars.', 'komedi, familj', 'Chris Columbus', 103, 1990, 'svenska', 'engelska', '19', 'homealone.jpg', 'homealone_header.jpeg', 'CK2Btk6Ybm0', 'tt0099785', 7.3, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(5, 'Anchorman: The Legend of Ron Burgundy', 'Ron Burgundy is San Diego''s top rated newsman in the male-dominated broadcasting of the 70''s, but that''s all about to change for Ron and his cronies when an ambitious woman is hired as a new anchor.', 'komedi', 'Adam McKay', 94, 2004, 'svenska', 'Engelska', '19', 'anchorman.jpg', 'anchorman_header.jpg', 'Ip6GolC7Mk0', 'tt0357413', 7.2, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(6, 'El laberinto del fauno', 'In the fascist Spain of 1944, the bookish young stepdaughter of a sadistic army officer escapes into an eerie but captivating fantasy world.', 'drama, fantasy', 'Guillermo del Toro', 118, 2006, 'svenska', 'spanska', '19', 'ellaberintodelfauno.jpg', 'ellaberintodelfauno_header.jpg', '6xnQDGVPdNU', 'tt0457430', 8.3, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(7, 'Psycho', 'A Phoenix secretary steals $40,000 from her employer''s client, goes on the run and checks into a remote motel run by a young man under the domination of his mother.', 'horror, thriller', 'Alfred Hitchcock', 109, 1960, 'svenska', 'engelska', '19', 'psycho.jpg', 'psycho_header.jpg', 'NG3-GlvKPcg', 'tt0054215', 8.6, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(8, 'The Conjuring', 'Paranormal investigators Ed and Lorraine Warren work to help a family terrorized by a dark presence in their farmhouse.', 'horror, thriller', 'James Wan', 112, 2013, 'svenska', 'engelska', '19', 'theconjuring.jpg', 'theconjuring_header.jpg', 'k10ETZ41q5o', 'tt1457767', 7.5, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(9, 'Star Wars', 'Luke Skywalker joins forces with a Jedi Knight, a cocky pilot, a wookiee and two droids to save the universe from the Empire''s world-destroying battle-station, while also attempting to rescue Princess Leia from the evil Darth Vader.', 'action, äventyr, fantasy, sci-fi', 'George Lucas', 121, 1977, 'svenska', 'engelska', '19', 'starwars.jpg', 'starwars_header.png', '9gvqpFbRKtQ', 'tt0076759', 8.7, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(10, 'Gravity', 'A medical engineer and an astronaut work together to survive after an accident leaves them adrift in space.', 'drama, sci-fi, thriller', 'Alfonso Cuarón', 91, 2013, 'svenska', 'engelska', '19', 'gravity.jpg', 'gravity_header.jpg', 'OiTiKOy59o4', 'tt1454468', 8.4, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(11, 'Lone Survivor', 'Marcus Luttrell and his team set out on a mission to capture or kill notorious al Qaeda leader Ahmad Shahd, in late June 2005. Marcus and his team are left to fight for their lives in one of the most valiant efforts of modern warfare.', 'action, drama, war', 'Peter Berg', 121, 2013, 'svenska', 'engelska', '19', 'lonesurvivor.jpg', 'lonesurvivor_header.jpg', 'igVDXo0W0w8', 'tt1091191', 7.8, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(12, 'Matrix', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 'action, adventure, sci-fi', 'The Wachowski Brothers', 136, 1999, 'svenska', 'engelska', '19', 'matrix.jpg', 'matrix_header.jpg', 'pcW78sj91Qg', 'tt0133093', 8.7, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(13, 'Frozen', 'Fearless optimist Anna teams up with Kristoff in an epic journey, encountering Everest-like conditions, and a hilarious snowman named Olaf in a race to find Anna''s sister Elsa, whose icy powers have trapped the kingdom in eternal winter.', 'animation, äventyr, komedi, familj, fantasy, musical', 'Chris Buck, Jennifer Lee', 102, 2013, 'svenska', 'engelska', '19', 'frozen.jpg', 'frozen_header.jpg', 'x1ieZ4f-DqM', 'tt2294629', 8.1, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(14, 'Despicable Me 2', 'Gru is recruited by the Anti-Villain League to help deal with a powerful new super criminal.', 'animation, äventyr, komedi, familj, sci-fi', 'Pierre Coffin, Chris Renaud', 98, 2013, 'svenska', 'engelska', '19', 'despicableme2.jpg', 'despicableme2_header.jpg', 'TlbnGSMJQbQ', 'tt1690953', 7.6, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(15, 'Harry Potter and the Sorcerers Stone', 'Rescued from the outrageous neglect of his aunt and uncle, a young boy with a great destiny proves his worth while attending Hogwarts School of Witchcraft and Wizardry.', 'äventyr, familj, fantasy', 'Chris Columbus', 152, 2001, 'svenska', 'engelska', '19', 'harrypotterandthesorcerersstone.jpg', 'harrypotterandthesorcerersstone_header.jpg', 'o8zKrA5kbNE', 'tt0241527', 7.4, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL),
(16, 'Monsters, Inc.', 'Monsters generate their city''s power by scaring children, but they are terribly afraid themselves of being contaminated by children, so when one enters Monstropolis, top scarer Sulley finds his world disrupted.', 'animation, äventyr, komedi, familj, fantasy', 'Pete Docter, David Silverman, Lee Unkrich', 92, 2001, 'svenska', 'engelska', '19', 'monstersinc.jpg', 'monstersinc_header.jpg', 'UplAPb2sfbs', 'tt0198781', 8.1, '2014-01-15 12:01:37', '2014-01-15 12:01:37', NULL, NULL);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `kmom07_Movies`
--
ALTER TABLE `kmom07_Movies`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `kmom07_Movies`
--
ALTER TABLE `kmom07_Movies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
