-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 03 Avril 2016 à 12:29
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
(0, 'Henry Cavill'),
(0, 'Amy Adams'),
(0, 'Michael Shannon'),
(0, 'Diane Lane'),
(0, 'Russell Crowe'),
(0, 'Laurence Fishburne'),
(0, 'Kevin Costner'),
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
(6, 'Ben Affleck'),
(6, 'Henry Cavill'),
(6, 'Gal Gadot'),
(6, 'Jesse Eisenberg'),
(6, 'Amy Adams'),
(7, 'Chris Evans'),
(7, 'Robert Downey Jr.'),
(7, 'Sebastian Stan'),
(7, 'Scarlett Johansson'),
(7, 'Elizabeth Olsen');

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
  `background` varchar(150) DEFAULT NULL,
  `date_creation` varchar(150) DEFAULT NULL,
  `date_last_modif` varchar(150) DEFAULT NULL,
  `genre1` int(11) DEFAULT NULL,
  `genre2` int(11) DEFAULT NULL,
  `genre3` int(11) DEFAULT NULL,
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `films`
--

INSERT INTO `films` (`id`, `nom`, `poster`, `synopsis`, `date_sortie`, `duree`, `univers`, `realisateur`, `background`, `date_creation`, `date_last_modif`, `genre1`, `genre2`, `genre3`, `idUser`) VALUES
(0, 'Man of Steel', 'https://image.tmdb.org/t/p/original/xWlaTLnD8NJMTT9PGOD9z5re1SL.jpg', 'A young boy learns that he has extraordinary powers and is not of this earth. As a young man, he journeys to discover where he came from and what he was sent here to do. But the hero in him must emerge if he is to save the world from annihilation and become the symbol of hope for all mankind.', '2013-06-14', 143, 1, 0, 'https://image.tmdb.org/t/p/original/sToKfiVROsdMeqJ6OqbBAo6XMC2.jpg', '1970-01-01', '1970-01-01', 1, 2, 5, 1),
(1, 'Deadpool', 'https://image.tmdb.org/t/p/original/inVq3FRqcYIRl2la8iZikYYxFNR.jpg', 'Based upon Marvel Comics’ most unconventional anti-hero, DEADPOOL tells the origin story of former Special Forces operative turned mercenary Wade Wilson, who after being subjected to a rogue experiment that leaves him with accelerated healing powers, adopts the alter ego Deadpool. Armed with his new abilities and a dark, twisted sense of humor, Deadpool hunts down the man who nearly destroyed his life.', '2016-02-12', 108, 2, 2, 'https://image.tmdb.org/t/p/original/n1y094tVDFATSzkTnFxoGZ1qNsG.jpg', '1970-01-01', '1970-01-01', 1, 3, 2, 0),
(2, 'Batman Begins', 'https://image.tmdb.org/t/p/original/xiosOeLfzPbfLfqui41kSWnO0sZ.jpg', 'Driven by tragedy, billionaire Bruce Wayne dedicates his life to uncovering and defeating the corruption that plagues his home, Gotham City. Unable to work within the system, he instead creates a new identity, a symbol of fear for the criminal underworld - The Batman.', '2005-06-15', 140, 1, 1, 'https://image.tmdb.org/t/p/original/oorKqdz4pVc4IU09zOwqqVmar09.jpg', '1970-01-01', '1970-01-01', 1, 2, 5, 0),
(3, 'Ant-Man', 'https://image.tmdb.org/t/p/original/D6e8RJf2qUstnfkTslTXNTUAlT.jpg', 'Armed with the astonishing ability to shrink in scale but increase in strength, con-man Scott Lang must embrace his inner-hero and help his mentor, Dr. Hank Pym, protect the secret behind his spectacular Ant-Man suit from a new generation of towering threats. Against seemingly insurmountable obstacles, Pym and Lang must plan and pull off a heist that will save the world.', '2015-07-17', 117, 2, 3, 'https://image.tmdb.org/t/p/original/8VThUUpF2t0VuenJJrK17KUG9v.jpg', '1970-01-01', '1970-01-01', 1, 4, 3, 0),
(4, 'Iron Man', 'https://image.tmdb.org/t/p/original/s2IG9qXfhJYxIttKyroYFBsHwzQ.jpg', 'Tony Stark. Genius, billionaire, playboy, philanthropist. Son of legendary inventor and weapons contractor Howard Stark. When Tony Stark is assigned to give a weapons presentation to an Iraqi, he''s given a ride on enemy lines. That ride ends badly he survives with a chest full of shrapnel and a car battery attached to his heart. In order to survive he comes up with a way to miniaturize the battery and figures out that the battery can power something else. Thus Iron Man is born.', '2008-05-02', 126, 2, 4, 'https://image.tmdb.org/t/p/original/hqLNDL7ugQzAbK79uvzjIkmfDN2.jpg', '1970-01-01', '1970-01-01', 1, 2, 5, 0),
(5, 'Guardians of the Galaxy', 'https://image.tmdb.org/t/p/original/y31QB9kn3XSudA15tV7UWQ9XLuW.jpg', 'Light years from Earth, 26 years after being abducted, Peter Quill finds himself the prime target of a manhunt after discovering an orb wanted by Ronan the Accuser.', '2014-07-21', 121, 2, 5, 'https://image.tmdb.org/t/p/original/sDaFgf32B9FBfZM1hjAxaj8USdI.jpg', '1970-01-01', '1970-01-01', 1, 3, 4, 0),
(6, 'Batman v Superman', 'https://image.tmdb.org/t/p/original/6bCplVkhowCjTHXWv49UjRPn0eK.jpg', 'Fearing the actions of a god-like Super Hero left unchecked, Gotham City’s own formidable, forceful vigilante takes on Metropolis’s most revered, modern-day savior, while the world wrestles with what sort of hero it really needs. And with Batman and Superman at war with one another, a new threat quickly arises, putting mankind in greater danger than it’s ever known before.', '2016-03-25', 151, 1, 0, 'https://image.tmdb.org/t/p/original/5WQ4T1FXRkWQB4xA6LCsHmpcPfy.jpg', '2016-03-20', '2016-04-03', 1, 2, 5, 0),
(7, 'Captain America: Civil War', 'https://image.tmdb.org/t/p/original/ekn1f67hThCrsZOqt9cKzBYTeiw.jpg', 'Following the events of Age of Ultron, the collective governments of the world pass an act designed to regulate all superhuman activity. This polarizes opinion amongst the Avengers, causing two factions to side with Iron Man or Captain America, which causes an epic battle between former allies.', '2016-05-06', 146, 2, 6, 'https://image.tmdb.org/t/p/original/imSjsW6QRkH7fvhnqhQgjnbBBtd.jpg', '2016-04-03 12:17:08', '2016-04-03 12:21:01', 1, 4, 1, 0);

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
(0, 'Zack Snyder'),
(1, 'Tim Miller'),
(2, 'Christopher Nolan'),
(3, 'Peyton Reed'),
(4, 'Jon Favreau'),
(5, 'James Gunn'),
(6, 'Anthony Russo and Joe Russo');

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
(0, 'Darkham', 'darkham@heroesfilms.com', '$2y$10$akErPOiRzBWTM7VFfUppzOUQ.PCXyom58nIrDwHlboxsUUxaNxcs2'),
(1, 'Senayan', 'senayan@heroesfilms.com', '$2y$10$5tur0JlgRJsU1DfUUSAMOublTIl8/8Vfzt9905dkbTg4cvekSHwLC');

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
  ADD PRIMARY KEY (`id`), ADD KEY `univers` (`univers`), ADD KEY `realisateur` (`realisateur`), ADD KEY `genre1` (`genre1`), ADD KEY `genre2` (`genre2`), ADD KEY `genre3` (`genre3`), ADD KEY `idUser` (`idUser`);

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
ADD CONSTRAINT `films_ibfk_5` FOREIGN KEY (`genre3`) REFERENCES `genres` (`id`),
ADD CONSTRAINT `films_ibfk_6` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
