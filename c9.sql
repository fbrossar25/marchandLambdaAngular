-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 19 mars 2019 à 22:16
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `c9`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `ID_ARTICLE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(255) NOT NULL,
  `PRIX` decimal(17,2) NOT NULL,
  `DESCRIPTION` varchar(1024) NOT NULL DEFAULT 'Pas de description définie',
  PRIMARY KEY (`ID_ARTICLE`),
  UNIQUE KEY `NOM` (`NOM`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`ID_ARTICLE`, `NOM`, `PRIX`, `DESCRIPTION`) VALUES
(1, 'Expresso', '0.45', 'Un simple expresso.\r\nAttention : LivrÃ© sans George Clooney.'),
(2, 'Oreiller spÃ©cial dodo', '10.00', 'Pour bien dormir'),
(3, 'PC Master race', '4242.42', 'Parce que les exclus consoles c\'est chiant #TeamPC'),
(4, 'Half life 3', '50.00', 'Un jour peut Ãªtre'),
(5, 'Coca Cola 1,5L', '1.50', 'Parce qu\'il fallait bien un sponsor'),
(6, 'Pizza 4 Fromages', '5.00', 'Savoureuse pizza Mozzarella, Emmental, Fourme d\'Ambert et ChÃ¨vre avec une PÃ¢te moelleuse.'),
(7, 'Kebab Salade Tomate Oignon Ketchup Mayonnaise', '5.00', 'En bonus ; une petite barquette de frites.'),
(8, 'Martine ÃƒÂ©crit en UTF-8', '5.00', 'Une vrai leÃƒÂ§on de vie pour les programmeurs en herbe.'),
(9, 'Voxmakers - Je code avec le Q', '4.99', 'Tant pis si Ã§a plante, jm\'en fou, on m\'paie pas pour tester');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `ID_CLIENT` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(255) NOT NULL,
  `PRENOM` varchar(255) NOT NULL,
  `COMMUNE` varchar(255) NOT NULL,
  `CODE_POSTAL` varchar(255) NOT NULL,
  `VOIE` varchar(255) NOT NULL,
  `NUMERO_VOIE` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `PASS` varchar(255) NOT NULL,
  `TELEPHONE` varchar(255) DEFAULT NULL,
  `ADMIN` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_CLIENT`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`ID_CLIENT`, `NOM`, `PRENOM`, `COMMUNE`, `CODE_POSTAL`, `VOIE`, `NUMERO_VOIE`, `EMAIL`, `PASS`, `TELEPHONE`, `ADMIN`) VALUES
(1, 'admin', 'admin', 'test', '00000', 'test', '0', 'admin@test.fr', '$2y$10$gbwzsPMBeG5kehUTwKu6audjRwcpJ4i6I6JIE//k9LiW79LFnWAAe', '987654321', 1),
(2, 'BROSSARD', 'Florian', 'ILLKIRCH-GRAFFENSTADEN', '67400', 'Route de Lyon', '71', 'florian.brossard@etu.unistra.fr', '$2y$10$xj4sZrrtIUPandVdO1eDEuPkGwEcA3jO2BFtbUyIITCp10ylcg6kG', '0123456789', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
