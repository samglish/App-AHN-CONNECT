-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 04 Octobre 2025 à 01:58
-- Version du serveur :  5.7.27-0ubuntu0.18.04.1
-- Version de PHP :  7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ahnens9421_enspm`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'departement ahn', '1234'),
(2, 'admin', '1234'),
(3, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ami_id` int(11) NOT NULL,
  `statut` enum('en_attente','accepte','refuse') DEFAULT 'accepte',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP,
  `photo_profil` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `amis`
--

INSERT INTO `amis` (`id`, `user_id`, `ami_id`, `statut`, `date_ajout`, `photo_profil`) VALUES
(33, 27, 27, 'accepte', '2025-10-02 06:55:45', 'default.jpg'),
(36, 13, 32, 'accepte', '2025-10-02 12:58:42', 'default.jpg'),
(37, 32, 13, 'accepte', '2025-10-02 12:58:57', 'default.jpg'),
(38, 33, 32, 'accepte', '2025-10-02 13:11:05', 'default.jpg'),
(39, 32, 33, 'accepte', '2025-10-02 13:11:30', 'default.jpg'),
(40, 34, 32, 'en_attente', '2025-10-02 13:16:00', 'default.jpg'),
(41, 36, 13, 'accepte', '2025-10-02 13:19:59', 'default.jpg'),
(42, 13, 36, 'accepte', '2025-10-02 13:20:07', 'default.jpg'),
(43, 33, 36, 'accepte', '2025-10-02 13:23:31', 'default.jpg'),
(44, 36, 33, 'accepte', '2025-10-02 13:23:45', 'default.jpg'),
(45, 35, 34, 'accepte', '2025-10-02 13:25:58', 'default.jpg'),
(46, 34, 35, 'accepte', '2025-10-02 13:26:20', 'default.jpg'),
(47, 27, 27, 'accepte', '2025-10-02 16:48:55', 'default.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `bibliotheque`
--

CREATE TABLE `bibliotheque` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `classe` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `chat`
--

INSERT INTO `chat` (`id`, `user_id`, `nom`, `prenom`, `photo`, `message`, `created_at`) VALUES
(78, 36, 'TAKOUJOU MENOUNGA', 'CAMILA', '1759407461_1000174453.jpg', 'Bonjour', '2025-10-02 13:25:52'),
(80, 27, 'Toutouya ', 'El ', '1759384499_IMG_20250109_083613.jpg', 'Cc', '2025-10-02 16:50:32');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `department_news`
--

CREATE TABLE `department_news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `department_news`
--

INSERT INTO `department_news` (`id`, `title`, `content`, `created_at`) VALUES
(32, 'Résultats ', 'Les résultats de la session normale et rattrapage sont disponibles sur la page résultats.', '2025-10-02 01:35:14');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `filiere` varchar(100) DEFAULT NULL,
  `bio` text,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `photo_profil` varchar(255) DEFAULT 'default.jpg',
  `role` enum('admin','etudiant') DEFAULT 'etudiant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `nom`, `prenom`, `matricule`, `numero`, `filiere`, `bio`, `email`, `mot_de_passe`, `photo_profil`, `role`) VALUES
(13, 'BEIDI', 'SAMUEL', '18A1093FS', '697241071', 'INFORMATIQUE ', 'Ingénieur réseaux et systèmes ', 'beidisamuel11@gmail.com', '$2y$10$ydoOh301Ba7qkoPGQW/yYe8Q1d5IqCNRYkl5onPZez7DzQvQJYBtK', '1754436857_sammmmm.png', 'admin'),
(17, 'Matchinda', 'Anelka', '24ENSPM284', '670653411', 'IHN', NULL, 'anelkafabiola7@gmail.com', '$2y$10$b8xMOuIDe0pQttDTJXMAOe2Y6rqKONSe9sI6zjOb25d9gN1j/pAP.', 'default.jpg', 'etudiant'),
(18, 'Namekong', 'Ivanelle', '24ENSPM186', '689666604', 'IHN', NULL, 'ivanellenamakong@gmail.com', '$2y$10$6i9wnlfHpWvRqd5RRLp/DOjCVJ1lnYmHbxAWzfxaeJgOYVbHkT8AW', 'default.jpg', 'etudiant'),
(23, 'FEHEM ', 'BENJAMIN ', '24ENSPM017', '678993543', 'IAN', 'Focus 🧠', 'benjaminfehem@gmail.com', '$2y$10$/rrTEYt5czgYSTQ8GETAhulm8baxTGAAdrPvL5X2D1LJ3Fear.BAG', '1759436194_IMG_20251001_225953_191~2.jpg', 'etudiant'),
(27, 'Toutouya ', 'El ', '24ENSPM280 ', '688283514', 'IAN ', '', 'nsteveterence@gmail.com', '$2y$10$OG1RaApHHA34kiAXXLS7sOiyTgOaePC1A25X9YSxRCPKgvkjMUqj2', '1759384499_IMG_20250109_083613.jpg', 'etudiant'),
(28, 'SOUSSEE-BAA DJONMA', 'JOSEPHINE', '22E0453EP', '693261374', 'IHN', '', 'josephinesousseebaa@gmail.com', '$2y$10$2898zQ5gfUihkyfTphLQneMRXuUVpW6YjJBN23D6Wx8T7ZUS/QXv6', '1758542030_IMG_20250922_093136_793.jpg', 'admin'),
(29, 'NDONGO BARE  ', 'ROMEO ', '24ENSPM181', '694617728/673001319', 'IAN', NULL, 'ndongoromeo2@gmail.com', '$2y$10$LBQj.F4EPjvXRVJ.D.hyO.4wZaTJl4oEh.OBX/vplo7nVEnoYqdni', 'default.jpg', 'etudiant'),
(32, 'ARTHUR ', 'AARON ', '23ENSPM0396', '691112252', 'IAN', 'Président Club AHN', 'arthuraaron251@gmail.com', '$2y$10$RvPOVjlr3T1jgB0M07eW9ei8B1lr.pKDklYWaNAylyy88mUV34euC', '1759406006_IMG-20250929-WA0052.jpg', 'admin'),
(33, 'NDJEM ', 'Hervé', '23ENSPM0401', '699163826', 'IAN', '', 'hbinong123@gmail.com', '$2y$10$uHR7eVt7ZBDwr/KzhMXCHe5A1PT8MZ4N6ttIgvSjsIo8j8fE05pMa', '1759407538_20250924_155953.jpg', 'etudiant'),
(34, 'AMOUBE NDE', 'Louange- mystere ', '23ENSPM0394', '690463183', 'IAN', NULL, 'louangende@gmail.com', '$2y$10$V6a0am472FAdDUlModF88.C.vHFjtDtnPKBPoljqRM.ZqubzhojfS', 'default.jpg', 'etudiant'),
(35, 'Ketchadji ', 'Eric', '23ENSPM0400', '671135872', 'IAN', '', 'eketchadji@gmail.com', '$2y$10$TCu5ztWcu2uRBv/hUgWqlOTahgApEKJBqzsfEjr9GdKvME80VFdBa', 'default.jpg', 'admin'),
(36, 'TAKOUJOU MENOUNGA', 'CAMILA', '23ENSPM0407', '658377413', 'IHN ', NULL, 'takoujoumenoungacamila@gmail.com', '$2y$10$KKG4dACBt80sUDmgSDE4QeYaOqar.b/fRvnMSzmxjggKYAUK8dzGi', '1759407461_1000174453.jpg', 'etudiant'),
(37, 'Abdoullahi ', 'Amadou', '24ENSPM182', '699148354', 'IHN', NULL, 'amadouabdoullahi44@gmail.com', '$2y$10$auPohE4/D1QyCOij4/xMFOA34ey4xBdOTdwjqxQtm8KTjYU48Cosi', 'default.jpg', 'etudiant'),
(38, 'TSAGUE ', 'Louidivine cardie ', '22E0439EP', '686127708', 'IAN', NULL, 'cardietsague@gmail.com', '$2y$10$eP8zhZ1NDM1jztLe6rRejur1CWSe7egOMCmA8jUpCPNjR8VyTh.9O', '1759424164_1000140585.jpg', 'etudiant'),
(39, 'Youssouf ', 'Mahamat ', '24ENSPM188', '658183731', 'IHN', NULL, 'yy0497862@gmail.com', '$2y$10$NiMSY2SNtnPdfov9wtOQnuGHlv45h/7ByAtfN12DGcXQ.M5yd5GEO', '1759432727_de-verre-et-de-beton_4819096.jpg', 'etudiant');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `file_path`, `created_at`) VALUES
(21, 13, 'Test', '68de6543c8210.jpg', '2025-10-02 11:42:59'),
(23, 33, 'Hello', NULL, '2025-10-02 12:10:09');

-- --------------------------------------------------------

--
-- Structure de la table `post_files`
--

CREATE TABLE `post_files` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `filiere` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ami_id` (`ami_id`);

--
-- Index pour la table `bibliotheque`
--
ALTER TABLE `bibliotheque`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `department_news`
--
ALTER TABLE `department_news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`post_id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post_files`
--
ALTER TABLE `post_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `amis`
--
ALTER TABLE `amis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `bibliotheque`
--
ALTER TABLE `bibliotheque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `department_news`
--
ALTER TABLE `department_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `post_files`
--
ALTER TABLE `post_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `amis`
--
ALTER TABLE `amis`
  ADD CONSTRAINT `amis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `amis_ibfk_2` FOREIGN KEY (`ami_id`) REFERENCES `etudiants` (`id`);

--
-- Contraintes pour la table `post_files`
--
ALTER TABLE `post_files`
  ADD CONSTRAINT `post_files_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
