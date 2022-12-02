-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 02 déc. 2022 à 16:04
-- Version du serveur : 10.4.13-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `topcosmetics`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `idAchat` int(11) NOT NULL,
  `idCom` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `prixAchat` int(11) NOT NULL,
  `qteAchat` int(11) NOT NULL,
  `SomTotAchat` int(11) NOT NULL,
  `dateAchat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `annulation_commande`
--

CREATE TABLE `annulation_commande` (
  `idAnnulCom` int(11) NOT NULL,
  `idCom` int(11) NOT NULL,
  `commentAnnulCom` text NOT NULL,
  `dateAnnulCom` datetime NOT NULL DEFAULT current_timestamp(),
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `banniere_compte`
--

CREATE TABLE `banniere_compte` (
  `idBanCpt` int(11) NOT NULL,
  `idCptVend` int(11) NOT NULL,
  `nomBanCpt` varchar(256) NOT NULL,
  `lienBanCpt` varchar(256) NOT NULL,
  `extBanCpt` varchar(5) NOT NULL,
  `dateCreateBanCpt` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditBanCpt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_compte`
--

CREATE TABLE `categorie_compte` (
  `idCatCpt` int(11) NOT NULL,
  `idCatVent` int(11) NOT NULL,
  `idCptVend` int(11) NOT NULL,
  `dateCreateCptVend` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_vente`
--

CREATE TABLE `categorie_vente` (
  `idCatVent` int(11) NOT NULL,
  `libCatVent` varchar(255) NOT NULL,
  `descCatVent` text DEFAULT NULL,
  `dateCreateCatVent` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditCatVent` datetime NOT NULL DEFAULT current_timestamp(),
  `etatCatVent` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClt` int(11) NOT NULL,
  `idCptVend` int(11) NOT NULL,
  `nomClt` varchar(100) NOT NULL,
  `prenomClt` varchar(150) NOT NULL,
  `telClt` varchar(15) NOT NULL,
  `indicClt` varchar(5) NOT NULL,
  `emailClt` varchar(150) NOT NULL,
  `pswClt` int(11) NOT NULL,
  `dateCreateClt` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditClt` datetime NOT NULL DEFAULT current_timestamp(),
  `etatClt` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idCom` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  `DateCom` datetime NOT NULL DEFAULT current_timestamp(),
  `somCom` int(11) NOT NULL,
  `remCom` int(11) NOT NULL,
  `relicatCom` int(11) NOT NULL,
  `prixLivCom` int(11) NOT NULL,
  `idTypePaieCom` int(11) NOT NULL,
  `etatLivCom` int(11) NOT NULL DEFAULT 1 COMMENT '1:En cours, 2:Traité, 3:Livré',
  `etatCom` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `compte_vendeur`
--

CREATE TABLE `compte_vendeur` (
  `idCptVend` int(11) NOT NULL,
  `nomCptVend` varchar(255) NOT NULL,
  `sloganCptVend` text DEFAULT NULL,
  `telCptVend` varchar(15) NOT NULL,
  `emailCptVend` varchar(150) NOT NULL,
  `adresCptVend` text NOT NULL,
  `descCptVend` varchar(255) DEFAULT NULL,
  `dateCreateCptVend` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditCreateCptVend` datetime NOT NULL DEFAULT current_timestamp(),
  `etatCptVend` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `compte_vendeur`
--

INSERT INTO `compte_vendeur` (`idCptVend`, `nomCptVend`, `sloganCptVend`, `telCptVend`, `emailCptVend`, `adresCptVend`, `descCptVend`, `dateCreateCptVend`, `dateEditCreateCptVend`, `etatCptVend`) VALUES
(1, 'All', 'Alls', '90000000', 'all@gmail.com', 'Lomé Amadawomé', '', '2022-11-23 16:21:30', '2022-12-02 10:33:17', 1);

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

CREATE TABLE `connexion` (
  `idConn` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `dateConn` datetime DEFAULT NULL,
  `dateDeconn` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `connexion`
--

INSERT INTO `connexion` (`idConn`, `idUser`, `dateConn`, `dateDeconn`) VALUES
(1, 1, '2022-11-24 08:45:17', '2022-11-24 08:45:17'),
(2, 1, '2022-11-24 08:57:35', '2022-11-24 08:57:35'),
(3, 1, '2022-11-24 09:03:40', '2022-11-24 09:03:40'),
(4, 1, '2022-11-24 09:07:44', '2022-11-24 09:07:44'),
(5, 1, '2022-11-24 09:13:14', '2022-11-24 09:13:14'),
(6, 1, '2022-11-24 09:18:05', '2022-11-24 09:18:05'),
(7, 1, '2022-11-24 09:23:15', '2022-11-24 09:23:15'),
(8, 1, '2022-11-24 09:30:47', '2022-11-24 09:30:47'),
(9, 1, '2022-11-24 09:38:08', '2022-11-24 09:38:08'),
(10, 1, '2022-11-24 09:43:11', '2022-11-24 09:43:11'),
(11, 1, '2022-11-24 09:52:05', '2022-11-24 09:52:05'),
(12, 1, '2022-11-24 14:45:18', '2022-11-24 14:45:18'),
(13, 1, '2022-11-25 08:05:17', '2022-11-25 08:05:17'),
(14, 1, '2022-11-25 10:03:10', '2022-11-25 10:03:10'),
(15, 1, '2022-12-02 08:29:47', '2022-12-02 08:29:47');

-- --------------------------------------------------------

--
-- Structure de la table `images_produit`
--

CREATE TABLE `images_produit` (
  `idImgProd` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `nomImgProd` varchar(150) NOT NULL,
  `lienImgProd` varchar(255) NOT NULL,
  `extImgProd` varchar(5) NOT NULL,
  `dateCreateImgProd` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditImgProd` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `lien`
--

CREATE TABLE `lien` (
  `idLien` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `lien` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `lien`
--

INSERT INTO `lien` (`idLien`, `idUser`, `lien`) VALUES
(1, 1, 'compte.php');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `idMenu` int(11) NOT NULL,
  `menu` varchar(150) NOT NULL,
  `etatMenu` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`idMenu`, `menu`, `etatMenu`) VALUES
(1, 'Ventes', 1),
(2, 'Messages', 1),
(3, 'Utilisateur', 1),
(4, 'Admin', 1);

-- --------------------------------------------------------

--
-- Structure de la table `menu_user`
--

CREATE TABLE `menu_user` (
  `idMenuUser` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `idSousMenu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `menu_user`
--

INSERT INTO `menu_user` (`idMenuUser`, `idUser`, `idMenu`, `idSousMenu`) VALUES
(1, 1, 3, 3),
(2, 2, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idProd` int(11) NOT NULL,
  `idCptVend` int(11) NOT NULL,
  `idCatVent` int(11) NOT NULL,
  `nomProd` varchar(150) NOT NULL,
  `descProd` text NOT NULL,
  `prixProd` int(11) NOT NULL,
  `prixPromoProd` int(11) NOT NULL,
  `dateCreateProd` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditProd` datetime NOT NULL DEFAULT current_timestamp(),
  `etatProd` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sousmenu`
--

CREATE TABLE `sousmenu` (
  `idSousMenu` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `sousMenu` varchar(150) NOT NULL,
  `fichierSousMenu` varchar(256) NOT NULL,
  `etatSousMenu` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sousmenu`
--

INSERT INTO `sousmenu` (`idSousMenu`, `idMenu`, `sousMenu`, `fichierSousMenu`, `etatSousMenu`) VALUES
(1, 1, 'En cours & Traitée', 'ventes.php', 1),
(2, 1, 'Historiques', 'ventesHistoriques.php', 1),
(3, 3, 'Utilisateur', 'user.php', 1),
(4, 4, 'Utilisateurs', 'users.php', 1),
(5, 4, 'Compte', 'compte.php', 1),
(6, 4, 'Catégories', 'category.php', 1);

-- --------------------------------------------------------

--
-- Structure de la table `type_paiement`
--

CREATE TABLE `type_paiement` (
  `idTypePaiem` int(11) NOT NULL,
  `libellePaiem` varchar(156) NOT NULL,
  `etatPaiem` int(11) NOT NULL DEFAULT 1,
  `dateCreateTypePaiem` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditTypePaiem` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `idCptVend` int(11) NOT NULL,
  `nomUser` varchar(100) NOT NULL,
  `prenomUser` varchar(150) NOT NULL,
  `telUser` varchar(15) NOT NULL,
  `emailUser` varchar(150) NOT NULL,
  `typeUser` varchar(20) NOT NULL,
  `indicUser` varchar(5) DEFAULT NULL,
  `pwdUser` varchar(256) NOT NULL,
  `dateCreateUser` datetime NOT NULL DEFAULT current_timestamp(),
  `dateEditUser` datetime NOT NULL DEFAULT current_timestamp(),
  `etatUser` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `idCptVend`, `nomUser`, `prenomUser`, `telUser`, `emailUser`, `typeUser`, `indicUser`, `pwdUser`, `dateCreateUser`, `dateEditUser`, `etatUser`) VALUES
(1, 1, 'All', 'All', '00000000', 'alladmin@gmail.com', 'developper', '00228', 'fa956b808c8f8e3b59be14d7d584761e041a8359d58ba7e1829f12605d76203a', '2022-11-23 16:26:49', '2022-11-23 16:26:49', 1),
(2, 1, 'AGBESSI', 'Yao Christian', '91488327', 'christiano.agbessi@gmail.com', 'utilisateur', NULL, '3f83e9ad5be63bd5bf2fd009fffe6b7dd4066243975bc962edc37459c17e65b9', '2022-11-25 15:08:50', '2022-11-25 15:08:50', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`idAchat`);

--
-- Index pour la table `annulation_commande`
--
ALTER TABLE `annulation_commande`
  ADD PRIMARY KEY (`idAnnulCom`);

--
-- Index pour la table `banniere_compte`
--
ALTER TABLE `banniere_compte`
  ADD PRIMARY KEY (`idBanCpt`);

--
-- Index pour la table `categorie_compte`
--
ALTER TABLE `categorie_compte`
  ADD PRIMARY KEY (`idCatCpt`);

--
-- Index pour la table `categorie_vente`
--
ALTER TABLE `categorie_vente`
  ADD PRIMARY KEY (`idCatVent`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClt`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idCom`);

--
-- Index pour la table `compte_vendeur`
--
ALTER TABLE `compte_vendeur`
  ADD PRIMARY KEY (`idCptVend`);

--
-- Index pour la table `connexion`
--
ALTER TABLE `connexion`
  ADD PRIMARY KEY (`idConn`);

--
-- Index pour la table `images_produit`
--
ALTER TABLE `images_produit`
  ADD PRIMARY KEY (`idImgProd`);

--
-- Index pour la table `lien`
--
ALTER TABLE `lien`
  ADD PRIMARY KEY (`idLien`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idMenu`);

--
-- Index pour la table `menu_user`
--
ALTER TABLE `menu_user`
  ADD PRIMARY KEY (`idMenuUser`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idProd`);

--
-- Index pour la table `sousmenu`
--
ALTER TABLE `sousmenu`
  ADD PRIMARY KEY (`idSousMenu`);

--
-- Index pour la table `type_paiement`
--
ALTER TABLE `type_paiement`
  ADD PRIMARY KEY (`idTypePaiem`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
  MODIFY `idAchat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `annulation_commande`
--
ALTER TABLE `annulation_commande`
  MODIFY `idAnnulCom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `banniere_compte`
--
ALTER TABLE `banniere_compte`
  MODIFY `idBanCpt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie_compte`
--
ALTER TABLE `categorie_compte`
  MODIFY `idCatCpt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie_vente`
--
ALTER TABLE `categorie_vente`
  MODIFY `idCatVent` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `idClt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idCom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `compte_vendeur`
--
ALTER TABLE `compte_vendeur`
  MODIFY `idCptVend` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `connexion`
--
ALTER TABLE `connexion`
  MODIFY `idConn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `images_produit`
--
ALTER TABLE `images_produit`
  MODIFY `idImgProd` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lien`
--
ALTER TABLE `lien`
  MODIFY `idLien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `menu_user`
--
ALTER TABLE `menu_user`
  MODIFY `idMenuUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idProd` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_paiement`
--
ALTER TABLE `type_paiement`
  MODIFY `idTypePaiem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
