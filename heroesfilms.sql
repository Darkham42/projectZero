-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 26 Mars 2016 à 17:50
-- Version du serveur :  5.6.24
-- Version de PHP :  5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `heroesfilms`
--

-- --------------------------------------------------------

--
-- Structure de la table `casting`
--

CREATE TABLE IF NOT EXISTS `casting` (
  `idFilm` int(11) DEFAULT NULL,
  `cast` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `casting`
--

INSERT INTO `casting` (`idFilm`, `cast`) VALUES
(1, 'Ryan Reynolds'),
(1, 'Karan Soni'),
(1, 'Ed Skrein'),
(1, 'Morena Baccarin'),
(1, 'Brianna Hildebrand'),
(2, 'Christian Bale'),
(2, 'Michael Caine'),
(2, 'Liam Neeson'),
(2, 'Katie Holmes'),
(2, 'Gary Oldman'),
(3, 'Paul Rudd'),
(3, 'Michael Douglas'),
(3, 'Evangeline Lilly'),
(3, 'Corey Stoll'),
(4, 'Robert Downey Jr.'),
(4, 'Terrence Howard'),
(4, 'Jeff Bridges'),
(4, 'Gwyneth Paltrow'),
(5, 'Chris Pratt'),
(5, 'Zoe Saldana'),
(5, 'Dave Bautista'),
(5, 'Vin Diesel'),
(5, 'Bradley Cooper'),
(6, 'Henry Cavill'),
(6, 'Amy Adams'),
(6, 'Michael Shannon'),
(6, 'Diane Lane'),
(6, 'Russell Crowe'),
(6, 'Laurence Fishburne'),
(6, 'Kevin Costner');

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

CREATE TABLE IF NOT EXISTS `films` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) DEFAULT NULL,
  `poster` varchar(150) DEFAULT NULL,
  `synopsis` varchar(500) DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `univers` int(11) DEFAULT NULL,
  `realisateur` int(11) DEFAULT NULL,
  `background` varchar(150) NOT NULL,
  `date_creation` date NOT NULL,
  `date_last_modif` date NOT NULL,
  `genre1` int(11) DEFAULT NULL,
  `genre2` int(11) DEFAULT NULL,
  `genre3` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `films`
--

INSERT INTO `films` (`id`, `nom`, `poster`, `synopsis`, `date_sortie`, `duree`, `univers`, `realisateur`, `background`, `date_creation`, `date_last_modif`, `genre1`, `genre2`, `genre3`) VALUES
(1, 'Deadpool', 'https://image.tmdb.org/t/p/original/eJyRzC5uFjQryu8Hm61yqtrzj4S.jpg', 'I want a poney !', '2016-02-12', 108, 2, 2, 'https://image.tmdb.org/t/p/original/n1y094tVDFATSzkTnFxoGZ1qNsG.jpg', '0000-00-00', '0000-00-00', 1, 3, 2),
(2, 'Batman Begins', 'https://image.tmdb.org/t/p/original/zfVFOo2XCHbeA0mXbst42TAGhfC.jpg', 'Fearing the actions of Superman are left unchecked, Batman takes on the man of steel, while the world wrestles with what kind of a hero it really needs. With Batman and Superman fighting each other, a new threat, Doomsday, is created by Lex Luthor. It''s up to Superman and Batman to set aside their differences along with Wonder Woman to stop Lex Luthor and Doomsday from destroying Metropolis.', '2005-06-15', 140, 1, 1, 'https://image.tmdb.org/t/p/original/9myrRcegWGGp24mpVfkD4zhUfhi.jpg', '0000-00-00', '0000-00-00', 1, 2, 5),
(3, 'Ant-Man', 'https://image.tmdb.org/t/p/original/n2guSYqwSQfWJnh301xIfV8OjUm.jpg', 'BLABLA', '2015-07-17', 117, 2, 3, '', '0000-00-00', '0000-00-00', 1, 4, 3),
(4, 'Iron Man', 'https://image.tmdb.org/t/p/original/mDTFL6zpd2y0UsqfEY4cG1NgBHI.jpg', 'TADA', '2008-05-02', 126, 2, 4, '', '0000-00-00', '0000-00-00', 1, 2, 5),
(5, 'Guardians of the Galaxy', 'https://image.tmdb.org/t/p/original/9gm3lL8JMTTmc3W4BmNMCuRLdL8.jpg', 'I''m Groot !', '2014-07-21', 121, 2, 5, '', '0000-00-00', '0000-00-00', 1, 3, 4),
(6, 'Man of Steel', 'https://image.tmdb.org/t/p/original/4tS3qHfYYB6C9I831pYQyLQivp8.jpg', 'I believe I can fly !', '2013-06-14', 143, 1, 6, '', '0000-00-00', '0000-00-00', 1, 2, 5);

-- --------------------------------------------------------

--
-- Structure de la table `genres`
--

CREATE TABLE IF NOT EXISTS `genres` (
  `id` int(11) NOT NULL,
  `genre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `genres`
--

INSERT INTO `genres` (`id`, `genre`) VALUES
(1, 'Action'),
(2, 'Adventure'),
(3, 'Comedy'),
(4, 'Sci-Fi'),
(5, 'Fantasy');

-- --------------------------------------------------------

--
-- Structure de la table `realisateur`
--

CREATE TABLE IF NOT EXISTS `realisateur` (
  `id` int(11) NOT NULL,
  `direc` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `realisateur`
--

INSERT INTO `realisateur` (`id`, `direc`) VALUES
(1, 'Christopher Nolan'),
(2, 'Tim Miller'),
(3, 'Peyton Reed'),
(4, 'Jon Favreau'),
(5, 'James Gunn'),
(6, 'Zack Snyder');

-- --------------------------------------------------------

--
-- Structure de la table `univers`
--

CREATE TABLE IF NOT EXISTS `univers` (
  `id` int(11) NOT NULL,
  `univers` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `univers`
--

INSERT INTO `univers` (`id`, `univers`) VALUES
(1, 'DC'),
(2, 'MARVEL');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`) VALUES
(0, 'Ast''IirDarkham', 'thomas.picard666@gmail.com', '$2y$10$akErPOiRzBWTM7VFfUppzOUQ.PCXyom58nIrDwHlboxsUUxaNxcs2');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `casting`
--
ALTER TABLE `casting`
  ADD KEY `idFilm` (`idFilm`);

--
-- Index pour la table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`), ADD KEY `univers` (`univers`), ADD KEY `realisateur` (`realisateur`), ADD KEY `genre1` (`genre1`), ADD KEY `genre2` (`genre2`), ADD KEY `genre3` (`genre3`);

--
-- Index pour la table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `realisateur`
--
ALTER TABLE `realisateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `univers`
--
ALTER TABLE `univers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `casting`
--
ALTER TABLE `casting`
ADD CONSTRAINT `casting_ibfk_1` FOREIGN KEY (`idFilm`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `films`
--
ALTER TABLE `films`
ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`univers`) REFERENCES `univers` (`id`),
ADD CONSTRAINT `films_ibfk_2` FOREIGN KEY (`realisateur`) REFERENCES `realisateur` (`id`),
ADD CONSTRAINT `films_ibfk_3` FOREIGN KEY (`genre1`) REFERENCES `genres` (`id`),
ADD CONSTRAINT `films_ibfk_4` FOREIGN KEY (`genre2`) REFERENCES `genres` (`id`),
ADD CONSTRAINT `films_ibfk_5` FOREIGN KEY (`genre3`) REFERENCES `genres` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
