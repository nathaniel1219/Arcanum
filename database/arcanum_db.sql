-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 08:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arcanum_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `auction_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `starting_price` decimal(10,2) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `auction_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `bid_id` int(11) NOT NULL,
  `auction_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `bid_amount` decimal(10,2) DEFAULT NULL,
  `bid_time` datetime DEFAULT current_timestamp(),
  `bid_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`) VALUES
(1, 1),
(3, 2),
(2, 5),
(4, 8),
(5, 9);

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE `cartitem` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartitem`
--

INSERT INTO `cartitem` (`cart_item_id`, `cart_id`, `product_id`, `quantity`) VALUES
(25, 1, 20, 3),
(26, 1, 1, 2),
(27, 1, 2, 1),
(28, 1, 11, 1),
(34, 3, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `username`, `password`, `email`, `phone`, `is_admin`) VALUES
(1, 'nathaniel', 'nathaniel123', 'nathaniel@gmail.com', '0771234567', 0),
(2, 'admin', 'adminPassword456', 'admin@example.com', '0777654321', 1),
(5, 'dulya26', 'duli2626', 'duli@gmail.com', '0770666544', 0),
(6, 'natethegreat', 'nate6969', 'nate69@gmail.com', '0770335236', 0),
(7, 'mavinda1', 'meow69', 'mavi@gmail.com', '060606060600', 0),
(8, 'jadsuga', '88888888', 'jadsuga@gmail.com', '8888888888', 0),
(9, 'Sandul', '123', 'sandul@email.com', '0706386772', 0),
(10, 'diddy', '123', 'd@babyoil.com', '245454545434', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `total`, `order_date`, `order_status`) VALUES
(1, 1, 3500.00, '2025-05-23 14:35:01', 'Pending'),
(2, 1, 13200.00, '2025-05-23 14:35:40', 'Pending'),
(3, 1, 7200.00, '2025-05-23 18:22:48', 'pending'),
(4, 1, 3700.00, '2025-05-24 11:35:39', 'pending'),
(5, 1, 24600.00, '2025-05-24 11:51:42', 'pending'),
(6, 1, 3500.00, '2025-05-24 12:10:22', 'pending'),
(7, 1, 16200.00, '2025-05-24 15:13:01', 'Shipped'),
(8, 8, 19200.00, '2025-05-26 08:48:22', 'Shipped'),
(9, 5, 3500.00, '2025-05-26 09:04:32', 'pending'),
(10, 9, 12000.00, '2025-05-26 11:39:07', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 3500.00),
(2, 2, 1, 1, 3600.00),
(3, 2, 24, 1, 4800.00),
(4, 2, 23, 1, 4800.00),
(5, 3, 2, 1, 3500.00),
(6, 3, 3, 1, 3700.00),
(7, 4, 3, 1, 3700.00),
(8, 5, 9, 1, 20900.00),
(9, 5, 3, 1, 3700.00),
(10, 6, 2, 1, 3500.00),
(11, 7, 8, 1, 1800.00),
(12, 7, 20, 3, 4800.00),
(13, 8, 27, 2, 4800.00),
(14, 8, 20, 2, 4800.00),
(15, 9, 2, 1, 3500.00),
(16, 10, 2, 1, 3500.00),
(17, 10, 3, 1, 3700.00),
(18, 10, 19, 1, 4800.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `category`, `sub_category`, `image_url`, `details`) VALUES
(1, 'SV Prismatic Evolutions', 'Booster Pack', 3600.00, 'TCG', 'pokemon', '1_prismatic_evo.jpg', 'This booster pack includes 10 randomly assorted cards from the Prismatic Evolutions expansion. Ideal for collectors and competitive players.'),
(2, 'Scarlet and Violet Twilight Masquerade', 'Booster Pack', 3500.00, 'TCG', 'pokemon', '2_sv_bp.jpg', 'Twilight Masquerade introduces mysterious new Pokémon and powerful Trainer cards. Includes one sealed booster pack with 10 cards.'),
(3, 'Scarlet and Violet Surging Sparks', 'Booster Pack', 3700.00, 'TCG', 'pokemon', '3_sv_surging_sparks_bp.png', 'Surging Sparks features dynamic electric-type Pokémon and updated gameplay mechanics. Each pack contains 10 cards.'),
(4, 'Shrouded Fable', 'Booster Bundle', 10400.00, 'TCG', 'pokemon', '4_sf_bb.png', 'Shrouded Fable Booster Bundle comes with 6 booster packs and exclusive promotional inserts.'),
(5, 'Scarlet and Violet Temporal Forces', 'Premium Collection', 62500.00, 'TCG', 'pokemon', '5_sv_temporal_forced_bb.jpeg', 'Temporal Forces Premium Collection includes 8 booster packs, a promo card, and a collector’s pin. A must-have for enthusiasts.'),
(6, 'SV Obsidian Flames 125/197 Charizard ex', 'Half Art', 2500.00, 'TCG', 'pokemon', '6_charizard_art.jpeg', 'A rare Charizard ex card (125/197) from Obsidian Flames. Beautiful half-art collectible for serious Pokémon fans.'),
(7, 'Pokemon Go', 'Booster Pack', 1800.00, 'TCG', 'pokemon', '7_pokemon_go_bp.jpeg', 'This Pokémon Go-themed booster pack captures the fun of the mobile game in trading card format. Contains 10 cards.'),
(8, 'Scarlet and Violet Stellar Crown', 'Booster Pack', 1800.00, 'TCG', 'pokemon', '8_sv_stellar_crown_bp.png', 'Stellar Crown brings a cosmic twist to Scarlet & Violet. Each pack includes 10 cards focused on space-themed Pokémon.'),
(9, 'Shrouded Fable Greninja ex Special ', 'Premium Collection', 20900.00, 'TCG', 'pokemon', '9_sf_greninja_box.jpeg', 'Greninja ex Special Premium Collection includes exclusive promo cards and premium packaging. Perfect for competitive players.'),
(10, 'Yu-Gi-Oh! Egyptian Gods Structure Deck: Slifer the Sky Dragon (Unlimited)', 'Structure Deck', 4800.00, 'TCG', 'Yu-Gi-Oh', '10_silfer_ygo.jpeg', 'Structure Deck featuring Slifer the Sky Dragon, designed to enhance Divine-Beast strategies.'),
(11, 'Yu-Gi-Oh! Egyptian Gods Structure Deck: Obelisk the Tormentor', 'Structure Deck', 4800.00, 'TCG', 'Yu-Gi-Oh', '11_obelisk_ygo.png', 'Structure Deck featuring Obelisk the Tormentor, focusing on powerful Tribute Summon tactics.'),
(12, 'Quarter Century Stampede Booster Box', 'Booster Box', 36000.00, 'TCG', 'Yu-Gi-Oh', '12_stampede_bb_ygo.jpeg', '24 packs per box, each with 5 cards. Final chance to obtain Quarter Century Secret Rares celebrating the 25th anniversary.'),
(13, 'Quarter Century Stampede Booster Pack', 'Booster Pack', 1600.00, 'TCG', 'Yu-Gi-Oh', '13_stampede_bp_ygo.jpeg', 'Each pack contains 5 cards with a guaranteed luxury secret rare, featuring nostalgic reprints and fan-voted cards.'),
(14, 'Structure Deck: Blue-Eyes White Destiny', 'Structure Deck', 4800.00, 'TCG', 'Yu-Gi-Oh', '14_blueeyes_ygo.jpeg', '50-card deck centered around Blue-Eyes Ultimate Spirit Dragon, including new Synchro and Link Monsters.'),
(15, 'Alliance Insight Booster Box', 'Booster Box', 36000.00, 'TCG', 'Yu-Gi-Oh', '15_alliance_bb_ygo.jpeg', '24 packs per box, each with 9 cards. Final set featuring Quarter Century Secret Rares, focusing on the VRAINS era.'),
(16, 'Alliance Insight Booster Pack', 'Booster Pack', 1600.00, 'TCG', 'Yu-Gi-Oh', '16_alliance_bp_ygo.jpeg', 'Each pack contains 9 cards from the Alliance Insight set, highlighting the final appearance of Quarter Century Secret Rares.'),
(17, 'Legendary Decks II (2024 Unlimited Reprint)', 'Deck Set', 12000.00, 'TCG', 'Yu-Gi-Oh', '17_lege_decks_ygo.jpeg', 'Includes three 43-card decks based on Yugi, Kaiba, and Joey, featuring iconic cards like Exodia and Blue-Eyes White Dragon.'),
(18, 'ALIN-EN004 Dark Magician Girl the Magician\'s Apprentice (Secret Rare)', 'Single Card', 6000.00, 'TCG', 'Yu-Gi-Oh', '18_mag_girl_ygo.jpeg', 'Secret Rare effect monster card from the Alliance Insight set, featuring Dark Magician Girl as the Magician\'s Apprentice.'),
(19, 'Lebron James Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '19_LeBRON.png', 'Celebrate the NBA superstar with this Funko Pop! figure of LeBron James in his Los Angeles Lakers uniform, capturing his iconic presence on the court.'),
(20, 'Ice Spice Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '20_icespice.png', 'Top the charts with Pop! Ice Spice! Rocking the ensemble from her Y2K! album, this exclusive artist adds a dash of nostalgia to your music collection.'),
(21, 'Nezuko Kamado (Demon Form) Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '21_nezuko.png', 'Expand your Demon Slayer collection with Pop! Nezuko Kamado in her Demon Form. This vinyl figure stands approximately 3.95 inches tall.'),
(22, 'John Wick with Dual Blades Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '22_johnwick.png', 'This Funko Pop! figure features John Wick wielding dual blades, capturing the intense action and style of the legendary assassin.'),
(23, 'Kuromi Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '23_kuromi.png', 'Add a touch of mischief to your collection with Pop! Kuromi, the charmingly cheeky character from the Hello Kitty universe.'),
(24, 'Satoru Gojo Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '24_gojo.png', 'Join the world of Jujutsu Kaisen with Pop! Satoru Gojo, the powerful sorcerer known for his exceptional skills and enigmatic personality.'),
(25, 'Suguru Geto Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '25_geto.png', 'Enroll in Tokyo Jujutsu High and learn to battle foes like this exclusive POP! Premium Suguru Geto with Cursed Spirit Dragon!'),
(26, 'Heimerdinger Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '26_heimerdinger.png', 'Bring home the brilliant inventor from League of Legends with Pop! Heimerdinger, featuring his signature gadgets and distinctive look.'),
(27, 'Pennywise with Spider Legs Funko Pop', 'Vinyl Figure', 4800.00, 'Figures', 'Funko Pop', '27_pennywise.png', 'Have a laugh with Pop! Pennywise with Spider Legs! This clown is ready to terrify your IT collection as he bares his frightening fangs.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `auction_id` (`auction_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bid`
--
ALTER TABLE `bid`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cartitem`
--
ALTER TABLE `cartitem`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auction` (`auction_id`),
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
