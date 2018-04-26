-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 26 Avril 2018 à 08:45
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `reservation_ressources`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin@admin.com', 1, NULL, '$2y$13$f6q.ke7jCWeqmEuapWjSv.qGXJpRJrR2b5eC/ARpB4uwHc8Pk5kHS', '2018-04-26 04:52:56', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}');

-- --------------------------------------------------------

--
-- Structure de la table `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `dispositif_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `application`
--

INSERT INTO `application` (`id`, `dispositif_id`, `name`) VALUES
(1, 1, 'Chrome'),
(2, 1, 'Youtube'),
(3, 3, 'Chrome'),
(4, 3, 'Instagram'),
(5, 3, 'Facebook'),
(6, 4, 'IOS Tools'),
(7, 4, 'Facebook');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id`, `name`) VALUES
(1, 'SmartPhone IOS'),
(2, 'SmartPhone Android'),
(3, 'Projecteur'),
(4, 'Salle');

-- --------------------------------------------------------

--
-- Structure de la table `dispositif`
--

CREATE TABLE `dispositif` (
  `id` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `OsVersion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpu` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ram` double NOT NULL,
  `disk_space` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resolution` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `deviceName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cpu_cores` int(11) NOT NULL,
  `free_disk_space` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `used_disk_space` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_uuid` varchar(80) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `dispositif`
--

INSERT INTO `dispositif` (`id`, `model`, `os`, `OsVersion`, `cpu`, `ram`, `disk_space`, `resolution`, `deviceName`, `cpu_cores`, `free_disk_space`, `used_disk_space`, `device_uuid`) VALUES
(1, 'Samsung Note 4', 'ANDROID', 'Marshmallow 6.0', 'Snapdragon 805', 3.072, '595292529252529', '2048x1440', 'Samsung Test', 0, '', '', 'sqdf56qsd554fs5d4f4fz'),
(3, 'Google Pixel 2', 'ANDROID', 'Marshmallow 7.0', 'Snapdragon 805', 2.048, '59529247245252529', '2048x1440', 'Pixel Test', 0, '', '', 'zef54d5fdz5fdzfz'),
(4, 'Iphone 6', 'IOS', 'iOS 9.1', 'Octa-core', 2.048, '456754527252529', '1080x1920', 'Iphone Test', 0, '', '', 'zdf545q1s5d1f5zef5'),
(12, 'iPhone 7 (GSM)', 'IOS', '11.2', 'A10 Fusion , 2.34 GHz', 2.048, '31.99 GB', '1334x750', 'iPhone de Proxym (Ref 51)', 2, '24.27 GB', '7.72 GB', 'B9F27A3F-4992-4C0B-BD17-F3C18597ECCC'),
(13, 'iPhone Simulator', 'IOS', '11.2', 'A9 , 1.85 GHz', 12, '998.97 GB', '1136x640', 'support’s MacBook Pro', 2, '851.18 GB', '147.79 GB', 'D4D109BA-B94D-48B5-AE65-D9FB264B741B');

-- --------------------------------------------------------

--
-- Structure de la table `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `dn` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `fos_user`
--

INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `dn`) VALUES
(1, 'ghandri', 'ghandri', 'ghandrisemh@gmail.com', 'ghandrisemh@gmail.com', 1, NULL, '', '2018-04-26 04:39:07', NULL, NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'uid=ghandri,ou=People,dc=localhost'),
(2, 'jbrown', 'jbrown', 'jbrown@keycloak.org', 'jbrown@keycloak.org', 1, NULL, '', '2018-04-14 19:33:19', NULL, NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'uid=jbrown,ou=People,dc=localhost');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `dispositive_id` int(11) DEFAULT NULL,
  `file_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_ajout` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `etat_inventaire` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_inventaire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `inventory`
--

INSERT INTO `inventory` (`id`, `etat_inventaire`, `date_inventaire`) VALUES
(1, 'Terminé', '2018-04-15 17:20:41'),
(2, 'Ouvert', '2018-04-15 22:48:55'),
(3, 'Terminé', '2018-04-19 16:06:33'),
(4, 'Ouvert', '2018-04-26 04:36:06');

-- --------------------------------------------------------

--
-- Structure de la table `ldapconfig`
--

CREATE TABLE `ldapconfig` (
  `id` int(11) NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `base_dn` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `ldapconfig`
--

INSERT INTO `ldapconfig` (`id`, `host`, `base_dn`, `port`) VALUES
(1, '192.168.56.102', 'ou=people,dc=localhost', 389);

-- --------------------------------------------------------

--
-- Structure de la table `line_inventory`
--

CREATE TABLE `line_inventory` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `inventaire_id` int(11) DEFAULT NULL,
  `etat` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `line_inventory`
--

INSERT INTO `line_inventory` (`id`, `resource_id`, `inventaire_id`, `etat`) VALUES
(1, 1, 1, 'Fonctionnel'),
(2, 4, 1, 'Fonctionnel'),
(16, 3, 1, 'Fonctionnel');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vu` tinyint(1) NOT NULL,
  `date_envoi` datetime NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id`, `user_id`, `vu`, `date_envoi`, `message`) VALUES
(1, 1, 1, '2018-04-22 11:30:00', 'hello people');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `vu` tinyint(1) NOT NULL,
  `date_res` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `notification`
--

INSERT INTO `notification` (`id`, `reservation_id`, `vu`, `date_res`) VALUES
(50, 52, 1, '2018-04-12 10:17:45'),
(54, 56, 1, '2018-04-12 15:03:31'),
(65, 67, 1, '2018-04-13 09:43:16'),
(79, 82, 1, '2018-04-13 11:22:28'),
(82, 85, 1, '2018-04-14 17:48:39'),
(88, 92, 1, '2018-04-16 23:57:46'),
(89, 93, 1, '2018-04-16 23:57:53'),
(90, 94, 1, '2018-04-16 23:58:14'),
(125, 129, 1, '2018-04-18 23:09:11'),
(126, 130, 1, '2018-04-18 23:19:34'),
(130, 134, 1, '2018-04-18 23:26:08'),
(132, 136, 1, '2018-04-18 23:32:36'),
(135, 139, 1, '2018-04-18 23:45:10'),
(138, 142, 1, '2018-04-18 23:46:46'),
(140, 144, 1, '2018-04-18 23:47:53'),
(141, 145, 1, '2018-04-18 23:48:09'),
(142, 146, 1, '2018-04-18 23:48:12'),
(143, 147, 1, '2018-04-18 23:48:53'),
(147, 151, 1, '2018-04-19 00:57:15'),
(153, 157, 1, '2018-04-20 10:20:13'),
(155, 159, 1, '2018-04-20 16:12:42'),
(156, 162, 1, '2018-04-22 01:22:08'),
(157, 163, 1, '2018-04-22 01:27:28'),
(158, 164, 1, '2018-04-22 01:32:28'),
(159, 165, 1, '2018-04-22 01:34:54'),
(160, 166, 1, '2018-04-22 01:48:23'),
(161, 13, 1, '2018-04-22 23:54:15'),
(163, 16, 1, '2018-04-23 16:53:48'),
(171, 24, 1, '2018-04-23 23:43:22'),
(172, 25, 1, '2018-04-24 00:31:00'),
(173, 26, 1, '2018-04-24 10:36:00'),
(174, 27, 1, '2018-04-24 10:46:27'),
(177, 30, 1, '2018-04-24 23:00:04'),
(181, 34, 1, '2018-04-25 00:25:24'),
(182, 35, 1, '2018-04-25 11:28:42');

-- --------------------------------------------------------

--
-- Structure de la table `projecteur`
--

CREATE TABLE `projecteur` (
  `id` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `resolution` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `projecteur`
--

INSERT INTO `projecteur` (`id`, `model`, `resolution`) VALUES
(2, 'Projecteur Sharp', '2048x1092'),
(14, 'Projecteur Canon', '2048x1092');

-- --------------------------------------------------------

--
-- Structure de la table `regles`
--

CREATE TABLE `regles` (
  `id` int(11) NOT NULL,
  `lim_duree_reservation` int(11) NOT NULL,
  `nbr_limite_par_jour` int(11) NOT NULL,
  `nbr_limite_par_semaine` int(11) NOT NULL,
  `nbr_max_reserv_parallel_par` int(11) NOT NULL,
  `date_crea` datetime NOT NULL,
  `date_modif` datetime DEFAULT NULL,
  `duree_timeout` int(11) NOT NULL,
  `max_res_parall` int(11) NOT NULL,
  `dtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `regles`
--

INSERT INTO `regles` (`id`, `lim_duree_reservation`, `nbr_limite_par_jour`, `nbr_limite_par_semaine`, `nbr_max_reserv_parallel_par`, `date_crea`, `date_modif`, `duree_timeout`, `max_res_parall`, `dtype`) VALUES
(1, 3, 2, 5, 0, '2018-03-11 14:06:04', '2018-03-31 21:58:05', 55, 0, 'regles');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ressource_id` int(11) DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `ressource_id`, `statut`, `date_debut`, `date_fin`) VALUES
(2, 1, 1, 'En attente', '2018-04-23 10:00:00', '2018-04-23 12:00:00'),
(3, 1, 1, 'En attente', '2018-04-26 11:15:00', '2018-04-26 13:15:00'),
(4, 1, 1, 'En attente', '2018-04-25 15:00:00', '2018-04-25 17:00:00'),
(5, 1, 1, 'En attente', '2018-04-27 13:00:00', '2018-04-27 14:15:00'),
(6, 1, 3, 'En attente', '2018-04-24 10:00:00', '2018-04-24 12:00:00'),
(7, 2, 3, 'En attente', '2018-04-26 12:00:00', '2018-04-26 14:30:00'),
(8, 2, 3, 'En attente', '2018-04-23 13:30:00', '2018-04-23 16:30:00'),
(9, 1, 15, 'En attente', '2018-04-24 11:30:00', '2018-04-24 13:00:00'),
(10, 1, 15, 'En attente', '2018-04-27 12:00:00', '2018-04-27 15:00:00'),
(11, 2, 15, 'En attente', '2018-04-23 11:00:00', '2018-04-23 14:00:00'),
(13, 1, 13, 'En attente', '2018-04-25 16:55:00', '2018-04-25 19:00:00'),
(16, 1, 12, 'En attente', '2018-04-24 11:00:00', '2018-04-24 13:00:00'),
(24, 1, 4, 'En attente', '2018-04-24 10:00:00', '2018-04-24 11:30:00'),
(25, 1, 4, 'En attente', '2018-04-24 12:00:33', '2018-04-24 14:00:33'),
(26, 1, 12, 'En attente', '2018-04-24 13:45:00', '2018-04-24 15:45:00'),
(27, 1, 4, 'En attente', '2018-04-26 12:01:09', '2018-04-26 14:15:20'),
(30, 1, 4, 'En attente', '2018-04-27 10:30:00', '2018-04-27 13:00:00'),
(34, 1, 1, 'En attente', '2018-04-27 09:45:00', '2018-04-27 12:15:00'),
(35, 1, 3, 'En attente', '2018-04-26 09:15:00', '2018-04-26 10:45:00');

-- --------------------------------------------------------

--
-- Structure de la table `reservationprojecteur`
--

CREATE TABLE `reservationprojecteur` (
  `id` int(11) NOT NULL,
  `projecteur_id` int(11) NOT NULL,
  `statut` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

CREATE TABLE `ressource` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `bar_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_add` datetime NOT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_check_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `ressource`
--

INSERT INTO `ressource` (`id`, `categorie_id`, `bar_code`, `status`, `date_add`, `discr`, `last_check_date`) VALUES
(1, 2, '51146465416546', 'Fonctionnel', '2018-03-31 14:59:24', '1', '2018-04-15 19:26:17'),
(2, 3, 'ddfq 252912df5d6516', 'Fonctionnel', '2018-04-01 00:30:42', '2', NULL),
(3, 2, '651sd1sdcfsd65csdc', 'Fonctionnel', '2018-04-07 13:34:01', '1', '2018-04-15 22:48:24'),
(4, 1, 'sdcsdc25df20dc20c2d2csd6547', 'Fonctionnel', '2018-04-07 13:37:32', '1', '2018-04-15 21:37:13'),
(12, 1, NULL, 'Fonctionnel', '2018-04-20 09:42:36', '1', '2018-04-20 10:15:54'),
(13, 1, NULL, 'Fonctionnel', '2018-04-20 15:10:55', '1', '2018-04-25 14:20:41'),
(14, 3, '21612654qsdf6qzerf4sdf35461351461', 'Fonctionnel', '2018-04-22 22:13:10', '2', NULL),
(15, 4, NULL, 'Fonctionnel', '2018-04-22 22:15:36', '3', NULL),
(16, 4, NULL, 'Fonctionnel', '2018-04-26 04:34:41', '3', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `numero_salle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `salle`
--

INSERT INTO `salle` (`id`, `name`, `numero_salle`) VALUES
(15, 'Salle Dogga', 9),
(16, 'Salle conferences', 15);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_880E0D7692FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_880E0D76A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_880E0D76C05FB297` (`confirmation_token`);

--
-- Index pour la table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A45BDDC1D9BB2E9F` (`dispositif_id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `dispositif`
--
ALTER TABLE `dispositif`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F928A171F` (`dispositive_id`);

--
-- Index pour la table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ldapconfig`
--
ALTER TABLE `ldapconfig`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `line_inventory`
--
ALTER TABLE `line_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_12EB14489329D25` (`resource_id`),
  ADD KEY `IDX_12EB144CE430A85` (`inventaire_id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B6BD307FA76ED395` (`user_id`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_BF5476CAB83297E7` (`reservation_id`);

--
-- Index pour la table `projecteur`
--
ALTER TABLE `projecteur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `regles`
--
ALTER TABLE `regles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42C84955A76ED395` (`user_id`),
  ADD KEY `IDX_42C84955FC6CD52A` (`ressource_id`);

--
-- Index pour la table `reservationprojecteur`
--
ALTER TABLE `reservationprojecteur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A27C715C1992C1C2` (`projecteur_id`);

--
-- Index pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_939F4544BCF5E72D` (`categorie_id`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `ldapconfig`
--
ALTER TABLE `ldapconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `line_inventory`
--
ALTER TABLE `line_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;
--
-- AUTO_INCREMENT pour la table `regles`
--
ALTER TABLE `regles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT pour la table `reservationprojecteur`
--
ALTER TABLE `reservationprojecteur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ressource`
--
ALTER TABLE `ressource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `FK_A45BDDC1D9BB2E9F` FOREIGN KEY (`dispositif_id`) REFERENCES `dispositif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dispositif`
--
ALTER TABLE `dispositif`
  ADD CONSTRAINT `FK_4719F6CDBF396750` FOREIGN KEY (`id`) REFERENCES `ressource` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F928A171F` FOREIGN KEY (`dispositive_id`) REFERENCES `dispositif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `line_inventory`
--
ALTER TABLE `line_inventory`
  ADD CONSTRAINT `FK_12EB14489329D25` FOREIGN KEY (`resource_id`) REFERENCES `ressource` (`id`),
  ADD CONSTRAINT `FK_12EB144CE430A85` FOREIGN KEY (`inventaire_id`) REFERENCES `inventory` (`id`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CAB83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projecteur`
--
ALTER TABLE `projecteur`
  ADD CONSTRAINT `FK_2D3297D6BF396750` FOREIGN KEY (`id`) REFERENCES `ressource` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_42C84955FC6CD52A` FOREIGN KEY (`ressource_id`) REFERENCES `ressource` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservationprojecteur`
--
ALTER TABLE `reservationprojecteur`
  ADD CONSTRAINT `FK_A27C715C1992C1C2` FOREIGN KEY (`projecteur_id`) REFERENCES `projecteur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `FK_939F4544BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salle`
--
ALTER TABLE `salle`
  ADD CONSTRAINT `FK_4E977E5CBF396750` FOREIGN KEY (`id`) REFERENCES `ressource` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
