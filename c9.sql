-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 21 mars 2019 à 18:42
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
  `URL_IMAGE` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`ID_ARTICLE`),
  UNIQUE KEY `NOM` (`NOM`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`ID_ARTICLE`, `NOM`, `PRIX`, `DESCRIPTION`, `URL_IMAGE`) VALUES
(1, 'Expresso', '0.45', 'Un simple expresso.\nAttention : LivrÃ© sans George Clooney.', 'https://www.telegraph.co.uk/content/dam/food-and-drink/2016/02/25/GeorgeClooney90712405-xlarge_trans%2B%2BEDjTm7JpzhSGR1_8ApEWQA1vLvhkMtVb21dMmpQBfEs.jpg'),
(2, 'Oreiller spÃ©cial dodo', '10.00', 'Pour bien dormir', 'https://img0.etsystatic.com/101/0/6291024/il_fullxfull.1011794160_dfh9.jpg'),
(3, 'PC Master race', '4242.42', 'Parce que les exclus consoles c\'est chiant #TeamPC', 'http://i.kinja-img.com/gawker-media/image/upload/s--_Y8m611J--/c_fit,fl_progressive,q_80,w_636/uib3zv6rygjx9rx8uswt.jpg'),
(4, 'Half life 3', '50.00', 'Un jour peut Ãªtre', 'https://img.tamindir.com/ti_e_ul/canerdil/h/gabe-newell-half-life-3-hakkinda-konustu-1_640x360.png'),
(6, 'Pizza 4 Fromages', '5.00', 'Savoureuse pizza Mozzarella, Emmental, Fourme d\'Ambert et ChÃ¨vre avec une PÃ¢te moelleuse.', 'https://commande.dominos.fr/ManagedAssets/FR/product/P4FR/FR_P4FR_fr_hero_2142.png?v2008054620'),
(7, 'Kebab Salade Tomate Oignon Ketchup Mayonnaise', '5.00', 'En bonus ; une petite barquette de frites.', 'http://www.pizzadiroma29.fr/produit/1335_225.png'),
(8, 'Martine ÃƒÂ©crit en UTF-8', '5.00', 'Une vrai leÃƒÂ§on de vie pour les programmeurs en herbe.', 'http://leblogdundsi.lesprost.fr/data/documents/humour/martine-ecrit-en-utf8.png'),
(9, 'Voxmakers - Je code avec le Q', '4.99', 'Tant pis si Ã§a plante, jm\'en fou, on m\'paie pas pour tester', 'http://media.lelombrik.net/t/1a7594ff247cfe0c61ecd7f10b9698a2/p/03.jpg'),
(13, 'Delete me', '666.00', 'Ce serais dommage de supprimer un article marrant.', 'https://4.bp.blogspot.com/-RmbiaxQv4sU/WA35YKKBspI/AAAAAAAAPBw/WvJDB57XP_QpSQneB9upA6iVo6vPJWkLACLcB/s1600/DELETE.jpg'),
(16, 'Le trophÃ©e Youtube de Wankil Studio', '1000.00', 'Ne le dites pas Ã  Terra', 'https://i.ytimg.com/vi/Djb7XjxD3xk/hqdefault.jpg'),
(17, 'Muffin suicidaire', '2.00', 'It\'s muffin time !', 'http://ih1.redbubble.net/image.15120430.9216/fc,550x550,white.u1.jpg'),
(18, 'L\'orange du marchand', '0.99', 'C\'Ã©tait moi le voleur depuis le dÃ©but !', 'https://www.brillen-sehhilfen.de/optik/image/orange-frucht.jpg'),
(19, 'MisterMV - Best Of', '14.99', 'Avec des morceaux acclamÃ©s par les critiques : Il se prÃ©nomme Ayepierre, IndÃ©finitivement et J\'adore le Zboub', 'https://images.ecosia.org/vVJiWg4ut4qNBuemUhG_htMQY10=/0x390/smart/https%3A%2F%2Fyt3.ggpht.com%2Fa-%2FAJLlDp3ZWS3UaFQ5BAl_biuy2VTDodK83Bd9GWYq4w%3Ds900-mo-c-c0xffffffff-rj-k-no'),
(20, 'Macarons de chez LadurÃ©e', '500.00', 'Attention : Peut Ã©nerver des chanteurs au point d\'en faire un tube', 'http://3.bp.blogspot.com/_-3wL_WHFHmw/Sj6KXpucNwI/AAAAAAAAD4Q/-McDwIvgIVA/s320/helmutfritzcamenerve.jpg'),
(21, 'Pilule bleu', '69.00', 'Permet de faire de beau rÃªve et de penser ce que l\'on veux d\'aprÃ¨s le mec louche en blouson et lunette noire qui me l\'a donnÃ©.', 'https://image.noelshack.com/fichiers/2019/12/4/1553193530-matrixbluepill.jpg');

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
(2, 'BROSSARD', 'Florian', 'ILLKIRCH-GRAFFENSTADEN', '67400', 'Route de Lyon', '71', 'florian.brossard@etu.unistra.fr', '$2y$10$xj4sZrrtIUPandVdO1eDEuPkGwEcA3jO2BFtbUyIITCp10ylcg6kG', '0123456789', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
