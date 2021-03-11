-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 30 nov. 2020 à 21:43
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `spectapp-db`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin-user`
--

DROP TABLE IF EXISTS `admin-user`;
CREATE TABLE IF NOT EXISTS `admin-user` (
  `user` text NOT NULL,
  `password` text NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `admin-user`
--

INSERT INTO `admin-user` (`user`, `password`, `admin_id`) VALUES
('spectappadmin1', 'pass', 1),
('spectappadmin2', 'pass', 2);

-- --------------------------------------------------------

--
-- Structure de la table `performance`
--

DROP TABLE IF EXISTS `performance`;
CREATE TABLE IF NOT EXISTS `performance` (
  `performance_id` int(11) NOT NULL AUTO_INCREMENT,
  `performance_date` text NOT NULL,
  `performance_covid_code` text NOT NULL,
  `performance_reserved_seats` text NOT NULL,
  `show_id` int(11) NOT NULL,
  PRIMARY KEY (`performance_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `performance`
--

INSERT INTO `performance` (`performance_id`, `performance_date`, `performance_covid_code`, `performance_reserved_seats`, `show_id`) VALUES
(1, '2020-11-01T19:00:00', '0', '', 1),
(2, '2020-11-01T21:30:00', '0', '', 2),
(3, '2020-11-02T20:30:00', '0', '', 3),
(4, '2020-11-03T20:30:00', '0', '', 4),
(5, '2020-11-04T20:30:00', '0', '', 5),
(6, '2020-11-05T20:30:00', '0', '', 6),
(7, '2020-11-06T20:30:00', '0', '', 7),
(8, '2020-11-07T19:00:00', '0', '', 8),
(9, '2020-11-07T21:30:00', '0', '', 9),
(10, '2020-11-08T19:00:00', '0', '', 10),
(11, '2020-11-08T21:30:00', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', 1),
(12, '2020-11-09T20:30:00', '0', '', 2),
(13, '2020-11-10T20:30:00', '0', '', 3),
(14, '2020-11-11T19:00:00', '0', '', 4),
(15, '2020-11-11T21:30:00', '0', '', 5),
(16, '2020-11-12T20:30:00', '0', '', 6),
(17, '2020-11-13T20:30:00', '0', '', 7),
(18, '2020-11-14T19:00:00', '0', '', 8),
(19, '2020-11-14T21:30:00', '0', '', 9),
(20, '2020-11-15T19:00:00', '0', '', 10),
(21, '2020-11-15T21:30:00', '0', '4,5,6,27,28,29,75,76,77', 1),
(22, '2020-11-16T20:30:00', '0', '', 2),
(23, '2020-11-17T20:30:00', '0', '', 3),
(24, '2020-11-18T20:30:00', '0', '', 4),
(25, '2020-11-19T20:30:00', '0', '', 5),
(26, '2020-11-20T20:30:00', '0', '', 6),
(27, '2020-11-21T19:00:00', '0', '', 7),
(28, '2020-11-21T21:30:00', '0', '', 8),
(29, '2020-11-22T19:00:00', '0', '', 9),
(30, '2020-11-22T21:30:00', '0', '', 10),
(31, '2020-11-23T20:30:00', '1', '1,3,4,6,8,9,11,12,14,15,17,19,20,22,23,25,26,28,30,31,33,34,36,37,39,41,42,44,45,47,48,50,52,53,55,56,58,59,61,63,64,66,67,69,70,72,74,75,77', 1),
(32, '2020-11-24T20:30:00', '1', '14,15,30,31,39,50', 2),
(33, '2020-11-25T20:30:00', '1', '', 3),
(34, '2020-11-26T20:30:00', '1', '', 4),
(35, '2020-11-27T20:30:00', '1', '', 5),
(36, '2020-11-28T19:00:00', '1', '', 6),
(37, '2020-11-28T21:30:00', '1', '', 7),
(38, '2020-11-29T19:00:00', '1', '', 8),
(39, '2020-11-29T21:30:00', '1', '', 9),
(40, '2020-11-30T20:30:00', '1', '', 10);

-- --------------------------------------------------------

--
-- Structure de la table `promo-code`
--

DROP TABLE IF EXISTS `promo-code`;
CREATE TABLE IF NOT EXISTS `promo-code` (
  `id` int(11) NOT NULL,
  `promo_code_value` int(11) NOT NULL,
  `promo_code_name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `promo-code`
--

INSERT INTO `promo-code` (`id`, `promo_code_value`, `promo_code_name`) VALUES
(1, 20, 'BLACKFRIDAY'),
(2, 15, 'SUPERNOEL');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `reservation_code` varchar(14) NOT NULL,
  `client_email` text NOT NULL,
  `performance_id` int(11) NOT NULL,
  `reserved_seats` text NOT NULL,
  PRIMARY KEY (`reservation_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`reservation_code`, `client_email`, `performance_id`, `reserved_seats`) VALUES
('0229239o', 'j.faure@gmail.com', 11, '36,37,38,39,40'),
('0229230k', 'tom.faure@gmail.com', 11, '31,32,33,34,35'),
('0229233i', 'kiki.foix@gmail.com', 11, '26,27,28,29,30'),
('0229232c', 'pierre.foix@gmail.com', 11, '21,22,23,24,25'),
('0229231b', 'gas.foix@gmail.com', 11, '16,17,18,19,20'),
('0229230a', 'py.pamiers@gmail.com', 11, '11,12,13,14,15'),
('0229238m', 'jm.pamiers@gmail.com', 11, '1,2,3,4,5'),
('0229231d', 'xavier.pamiers@gmail.com', 11, '6,7,8,9,10'),
('0229231x', 'martine.faure@gmail.com', 11, '41,42,43,44,45'),
('0229233v', 'jp.dupont@gmail.com', 11, '46,47,48,49,50'),
('0229237q', 'mc.dupont@gmail.com', 11, '51,52,53,54,55'),
('0229236j', 'mimi.dupont@gmail.com', 11, '56,57,58,59,60'),
('0229235w', 'directeur.cadc@gmail.com', 11, '61,62,63,64,65'),
('0229238s', 'contact.cadc@gmail.com', 11, '66,67,68,69,70'),
('0229232t', 'info.cadc@gmail.com', 11, '71,72,73,74,75'),
('0229234l', 'marie.claie@gmail.com', 11, '76,77'),
('0000000z', 'resa@test.com', 21, '27,28,29'),
('0001000z', 'crea@resa.com', 21, '4,5,6'),
('0002000z', 'crea@resa.com', 21, '75,76,77'),
('0000000a', 'resa@test.com', 32, '39,50'),
('0000001a', 'crea@resa.com', 32, '14,15'),
('0000002a', 'crea@resa.com', 32, '30,31'),
('1000002c', 'crea@resa.com', 31, '1,3,4,6,8'),
('1000003c', 'crea@resa.com', 31, '9,11,12,14,15'),
('1000004c', 'crea@resa.com', 31, '17,19,20,22,23'),
('1000005c', 'crea@resa.com', 31, '25,26,28,30,31'),
('1000006c', 'crea@resa.com', 31, '33,34,36,37,39'),
('1000007c', 'crea@resa.com', 31, '41,42,44,45,47'),
('1000008c', 'crea@resa.com', 31, '48,50,52,53,55'),
('1000009c', 'crea@resa.com', 31, '56,58,59,61,63'),
('1000010c', 'crea@resa.com', 31, '64,66,67,69,70'),
('1000011c', 'crea@resa.com', 31, '72,74,75,77');

-- --------------------------------------------------------

--
-- Structure de la table `show`
--

DROP TABLE IF EXISTS `show`;
CREATE TABLE IF NOT EXISTS `show` (
  `show_name` text NOT NULL,
  `show_poster_file` text NOT NULL,
  `show_id` int(11) NOT NULL,
  `show_description` text NOT NULL,
  `show_artist` text NOT NULL,
  PRIMARY KEY (`show_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `show`
--

INSERT INTO `show` (`show_name`, `show_poster_file`, `show_id`, `show_description`, `show_artist`) VALUES
('Le monde à l\'envers', 'poster_show_1.jpg', 1, 'Après le relatif succès de son premier spectacle l\'année dernière, Corentin Chamoux tente un nouveau retour en repensant sa manière de voir le monde et sa manière de faire de l\'humour.', 'Corentin Chamoux'),
('De la plus belle des manières', 'poster_show_2.jpg', 2, 'Entre humour et émotions, Astrid nous emmène entièrement dans son univers si singulier. Elle nous émeut et nous fait rire presque sans le vouloir.', 'Astrid Landry'),
('Presque trop', 'poster_show_3.jpg', 3, 'Parti de Beauvais vers Paris pour devenir humoriste en 2010, Lucas nous raconte sa vie chamboulée par le succès qu\'il a connu ces dernières années. Après une pause de plusieurs années, il revient avec du recul mais toujours de l\'humour sur les années folles qu\'il a vécu.', 'Lucas Fluet'),
('Les écrivains parlent de nous', 'poster_show_4.jpg', 4, 'L\'écrivain est de retour avec un nouveau spectacle de vulgarisation sur de grands auteurs français. Il y décrypte a la foi avec dérision et sérieux la façon dont ces auteurs parlent des hommes.', 'Bertrand Henrichon'),
('Rendez-moi célèbre', 'poster_show_5.jpg', 5, 'Pour son premier spectacle, la jeune Céline (22 ans) se lance avec plein d\'espoirs dans l\'univers très compétitif du one-women show. Validée par plusieurs de ces paires, elle est est promise à une grande carrière dans le monde du spectacle.', 'Céline Duhamel'),
('Passe-moi le sel', 'poster_show_6.jpg', 6, 'Révélation du dernier festival d\'Avignon, le plus si jeune Antoine Angélil (40 ans) arrive enfin à l\'assaut de la capitale en nous faisant découvrir sa comédie qu\'il a écrite et mis en scène lui-même. Pleurs de rires assurés.', 'Antoine Angélil'),
('Kool with his gang', 'poster_show_7.jpg', 7, 'Kool est un ancien musicien funk du New Jersey, après plusieurs dizaines d\'années a performer avec son groupe sur les scènes outre-Atlantique, il arrive en France (toujours avec son groupe) pour raconter ses anecdotes de vies, accompagné par ses musiciens.', 'Kool Dodd'),
('S\'il vous plaît', 'poster_show_8.jpg', 8, 'Elle ne fait décidemment rien comme tout le monde. Déjà très connue dans le milieu du cinéma, elle se lance (enfin) sur scène pour raconter des aussi drôles qu\'inquiétantes. Et elle vous demande de l\'accueillir chaleureusement.', 'Cerise de Brisay'),
('Le spectacle de la maturité', 'poster_show_9.jpg', 9, 'Comme le nom de son spectacle l\'indique, Nicolas Gamelin revient sur scène fort de ses expériences passés. Il nous emmène dans une sorte de \"débriefing\" de ces deux dernières comédies, toujours en maniant autodérision et autocritique. Beaucoup d\'interaction avec le public à prévoir.', 'Nicolas Gamelin'),
('Bon chienchien', 'poster_show_10.jpg', 10, 'Près une tournée de près de deux ans dans toute la France, Mister RVB a décidé de s\'installer pour plusieurs mois au même  endroit pour faire découvrir son spectacle à son image : incroyablement singulier.', 'Mister RGB');

-- --------------------------------------------------------

--
-- Structure de la table `show-critic`
--

DROP TABLE IF EXISTS `show-critic`;
CREATE TABLE IF NOT EXISTS `show-critic` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_email` text NOT NULL,
  `comment` text NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_date` text NOT NULL,
  `show_id` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `show-critic`
--

INSERT INTO `show-critic` (`comment_id`, `comment_email`, `comment`, `comment_rate`, `comment_date`, `show_id`) VALUES
(1, 'gasparddu64@gmail.com', 'Ouais ouais j\'adore l\'humour mais pas le sien', 2, '2020-10-14T15:35', 1),
(2, 'stack.overflow@gmail.com', 'La performance de l\'artiste est plus que médiocre maglgré un spectacle très bien écrit : très frustrant', 2, '2020-11-10T15:35', 1),
(3, 'rimk.77@gmail.com', 'Nik les rageux g adore cgars est trop fort, les gens aiment pas parce qu\'il est incompris, c\'est un putain de génie !!', 5, '2020-11-12T15:35', 1),
(4, 'gen.css@gmail.com', 'Cétait remarquable, j\'ai pris une grande claque : bravo !', 5, '2020-11-11T08:52', 2),
(5, 'dom.clairement@gmail.com', 'Bon spectacle.', 3, '2020-11-11T18:22', 3),
(6, 'gilles.rickzut@gmail.com', 'Très mauvais spectacle, c\'est une perte de temps n\'y aller pas.', 1, '2020-11-02T16:43', 4),
(7, 'pierre.valles@gmail.com', 'J\'ai été outré par la vulgarité de l\'artiste, gardez vos enfants à distance.', 2, '2020-11-24T18:59', 5),
(8, 'dd.renk@gmail.com', 'Passable.', 2, '2020-10-28T15:13', 6),
(9, 'pzk.karma@gmail.com', 'haha d barres mdr jvais revenir avec mon boyfriend c t tro bi1', 5, '2020-11-02T03:01', 7),
(10, 'nwar.hadid@gmail.com', 'Trop bon sauf la blague sur les noirs, trop facile de nos jours les blagues racistes.', 4, '2020-11-08T08:39', 7),
(11, 'jeanne.baliar@gmail.com', 'Excellent ! Vraiment trop drôle !', 5, '2020-11-25T12:12', 7),
(12, 'will.hunting@gmail.com', 'Je comprends que certains peuvent trouver cela drôle, ce n\'était pas totalement mon cas.', 3, '2020-11-29T12:12', 7),
(13, 'jean.quille@gmail.com', 'Très drôle, mais pas exceptionnel...', 4, '2020-11-30T08:41', 8);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
