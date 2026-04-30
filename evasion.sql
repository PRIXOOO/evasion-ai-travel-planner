-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 15 avr. 2026 à 14:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `evasion`
--

-- --------------------------------------------------------

--
-- Structure de la table `destinations_ia`
--

CREATE TABLE `destinations_ia` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `prix` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `mots_cles` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `destinations_ia`
--

INSERT INTO `destinations_ia` (`id`, `titre`, `pays`, `description`, `prix`, `image`, `mots_cles`) VALUES
(1, 'Maldives en Bungalow', 'Maldives', 'Un bungalow sur l\'eau turquoise, parfait pour se détendre.', 2500, 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=500', 'plage, soleil, palmiers, mer, turquoise, sable, detente, couple, lune de miel, luxe, chaud, baignade, calme, romantique'),
(2, 'Bora Bora Premium', 'Polynésie', 'Le luxe ultime au bout du monde. Lagons et raies manta.', 4500, 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=500', 'plage, luxe, cher, pacifique, lagon, plongée, lune de miel, couple, paradis, chaud, exclusif'),
(3, 'Plages de Phuket', 'Thaïlande', 'Sable blanc, pad thaï et ambiance festive le soir.', 900, 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=500', 'plage, asie, pas cher, fete, amis, detente, exotique, chaud, nourriture, routard'),
(4, 'Cancun All-Inclusive', 'Mexique', 'Hôtel club avec cocktails à volonté et mer des Caraïbes.', 1300, 'https://images.unsplash.com/photo-1512813159011-5b912f8409e5?w=500', 'plage, fete, amis, caraibes, chaud, club, alcool, detente, piscine, fun'),
(5, 'Îles Grecques', 'Grèce', 'Maisons blanches, dômes bleus et mer Égée à Santorin.', 850, 'https://images.unsplash.com/photo-1533105079780-92b9be482077?w=500', 'europe, plage, culturel, romantique, couple, pas tres cher, soleil, mediterranee, calme, beaute'),
(6, 'Chalets de Chamonix', 'France', 'Ski, fondue et poudreuse avec vue sur le Mont Blanc.', 1200, 'https://images.unsplash.com/photo-1463130456064-92265efbfdb6?w=500', 'montagne, neige, ski, froid, hiver, chalet, sport, nature, famille, alpes, france'),
(7, 'Aurores en Islande', 'Islande', 'Un voyage glacé à la recherche des aurores boréales.', 2100, 'https://images.unsplash.com/photo-1476610182048-b716b8518aae?w=500', 'froid, neige, glace, aurores boreales, nature, sauvage, paysages, roadtrip, hiver, aventure, photographe'),
(8, 'Magie de la Laponie', 'Finlande', 'Chiens de traîneau, père Noël et nuits dans un igloo.', 1800, 'https://images.unsplash.com/photo-1517823382935-51bfcb0ec6bc?w=500', 'froid, neige, hiver, noel, famille, enfants, magie, animaux, foret, glace, insolite'),
(9, 'Rando dans les Dolomites', 'Italie', 'Des montagnes grandioses pour des randonnées inoubliables.', 700, 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?w=500', 'montagne, ete, nature, randonnee, sport, pas cher, europe, air pur, paysages, calme'),
(10, 'Safari au Kenya', 'Kenya', 'À la rencontre des lions et des éléphants dans la savane.', 3200, 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=500', 'animaux, safari, savane, afrique, aventure, jeep, nature, chaud, decouverte, famille, photo'),
(11, 'Jungle de Bali', 'Indonésie', 'Retraite spirituelle, yoga et cascades perdues dans la forêt.', 1400, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500', 'nature, zen, yoga, foret, jungle, rizieres, cascades, asie, spirituel, calme, solo, pas cher'),
(12, 'Trek au Machu Picchu', 'Pérou', 'L\'ascension vers la cité perdue des Incas.', 2200, 'https://images.unsplash.com/photo-1526392060635-9d60198d3de3?w=500', 'montagne, histoire, ruines, amerique du sud, aventure, sport, marche, trek, culture, mystere'),
(13, 'Roadtrip Grand Canyon', 'États-Unis', 'Louez une Mustang et parcourez les routes mythiques de l\'Ouest.', 2400, 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500', 'usa, amerique, roadtrip, desert, voiture, canyon, amis, aventure, paysages, nature, ouest'),
(14, 'Amazonie Sauvage', 'Brésil', 'Exploration du poumon de la terre en pirogue.', 1900, 'https://images.unsplash.com/photo-1518182170546-076616fdfaaf?w=500', 'jungle, nature, animaux, aventure, fleuve, vert, amerique du sud, faune, flore, extreme, sauvage'),
(15, 'Week-end à Rome', 'Italie', 'Pâtes, pizzas et monuments antiques en amoureux.', 450, 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=500', 'histoire, architecture, culture, ville, gastronomie, europe, romantique, amour, monuments, week-end, pas cher'),
(16, 'Effervescence de Tokyo', 'Japon', 'Sushis, néons, temples et technologie.', 1800, 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=500', 'ville, urbain, technologie, japon, asie, culture, temples, gastronomie, lumiere, anime, insolite, solo'),
(17, 'Magie de Paris', 'France', 'La Tour Eiffel, les musées et le romantisme à la française.', 600, 'https://images.unsplash.com/photo-1502602881462-834e83c6c06a?w=500', 'ville, europe, romantique, amour, culture, musee, gastronomie, luxe, shopping, week-end'),
(18, 'New York City', 'États-Unis', 'Shopping, gratte-ciels et comédies musicales sur Broadway.', 1600, 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=500', 'ville, urbain, shopping, amerique, usa, fetes, buildings, culture, dynamique, amis'),
(19, 'Londres Underground', 'Royaume-Uni', 'Pubs, musées gratuits et ambiance royale.', 350, 'https://images.unsplash.com/photo-1513635269975-5969336ac1cb?w=500', 'ville, europe, week-end, pas cher, pub, culture, pluie, amis, etudiant'),
(20, 'Oasis de Marrakech', 'Maroc', 'Les souks, les épices et le calme d\'un riad.', 400, 'https://images.unsplash.com/photo-1539020140153-e479b8c22e70?w=500', 'chaleur, desert, culture, epices, afrique, souk, chameau, soleil, depaysement, pas cher, week-end'),
(21, 'Folie à Ibiza', 'Espagne', 'Les plus grands DJs du monde et des plages de rêve.', 800, 'https://images.unsplash.com/photo-1532655767597-40faad23b610?w=500', 'fete, amis, boite de nuit, musique, dj, plage, europe, chaud, jeune, alcool, danse'),
(22, 'Carnaval de Rio', 'Brésil', 'Couleurs, samba et caipirinha sur la plage de Copacabana.', 2200, 'https://images.unsplash.com/photo-1483401757487-2ced74628115?w=500', 'fete, bresil, amerique du sud, danse, musique, chaud, soleil, amis, evenement, spectacle'),
(23, 'Las Vegas Casino', 'États-Unis', 'Poker, machines à sous et spectacles grandioses.', 1700, 'https://images.unsplash.com/photo-1605810230434-7631ac76ec81?w=500', 'fete, amis, jeu, casino, argent, usa, desert, nuit, folie, luxe, excessif'),
(24, 'Mystères d\'Égypte', 'Égypte', 'Croisière sur le Nil et visite des Pyramides.', 1100, 'https://images.unsplash.com/photo-1539650116574-8efeb43e2b08?w=500', 'histoire, ruines, desert, chaud, culture, afrique, pyramides, mystere, fleuve, decouverte'),
(25, 'Traditions au Vietnam', 'Vietnam', 'Baie d\'Along, street-food et balade à scooter.', 1200, 'https://images.unsplash.com/photo-1528127269322-539801943592?w=500', 'asie, nature, culture, nourriture, pas cher, routard, baie, mer, scooter, aventure'),
(26, 'Beauté de Sydney', 'Australie', 'Opéra, surf et kangourous au bout du monde.', 2600, 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=500', 'ville, plage, surf, oceanie, loin, cher, amis, soleil, animaux, roadtrip'),
(27, 'Dubaï Démesure', 'Émirats', 'Ski en intérieur, gratte-ciels géants et shopping de luxe.', 1500, 'https://images.unsplash.com/photo-1512453979798-5ea904ac6605?w=500', 'luxe, ville, chaud, shopping, desert, building, moderne, cher, excessif, technologie'),
(28, 'Cité de Pétra', 'Jordanie', 'Explorez la cité antique taillée dans la roche rose.', 1300, 'https://images.unsplash.com/photo-1549888834-3ec93abae044?w=500', 'histoire, desert, ruines, moyen orient, chaud, decouverte, culture, aventure, marche'),
(29, 'Trésors de l\'Inde', 'Inde', 'Taj Mahal, couleurs vibrantes et spiritualité.', 1150, 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=500', 'culture, asie, histoire, spirituel, temples, couleurs, epices, depaysement, monde, routard'),
(30, 'Fjords de Norvège', 'Norvège', 'Croisière majestueuse entre les falaises et l\'eau pure.', 1600, 'https://images.unsplash.com/photo-1500366601445-512061eb0d58?w=500', 'nature, eau, montagne, froid, europe, bateau, calme, sauvage, air pur, paysages');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo_profil` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `date_inscription`, `photo_profil`) VALUES
(1, 'Sarah Yahou', 'sarahyahoucoucouhiboutoutoubaptou@gmail.com', '$2y$10$vrP0/sRhEdrGRRF2Q83u7uIOQdGHlUprnhS5QJ/Tpitj0WtV2ad26', '2026-03-09 13:42:32', 'default.png'),
(3, 'Chat', 'chat@chat.com', '$2y$10$AecOMuA9IRVeQfV8D0JWS.w8Q2HPQLIAKIB/dzbIDPFHHLkBw7.kO', '2026-03-23 13:24:05', '3_avatar.jpg'),
(4, 'imane', 'imanehdg@gmail.com', '$2y$10$GfyKTF9eiSDUSSa60KiUp..HrezPMiEbQ6Ujld9SjwU/iWoqsydl.', '2026-03-30 11:59:35', 'default.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `destinations_ia`
--
ALTER TABLE `destinations_ia`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `destinations_ia`
--
ALTER TABLE `destinations_ia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
