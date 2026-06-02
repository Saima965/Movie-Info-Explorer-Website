-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 08:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `movie_id`, `username`, `comment`) VALUES
(6, 37, 'anika', 'It\'s really worth of watching'),
(7, 40, 'anika', 'I will recommend to watch'),
(8, 40, 'saima', 'good one'),
(9, 37, 'saima', 'not bad'),
(10, 36, 'saima', 'good'),
(11, 42, 'saima', 'not that much good');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `id` int(11) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`id`, `customer_email`, `movie_id`, `title`, `genre`, `description`, `image`) VALUES
(20, 'anika', 36, 'Pandemic', 'Action', 'While Aidan loses hope of surviving a zombie outbreak, his outlook changes when he comes across Eva, another survivor, and decides to fight back with a renewed sense of hope.', '1757790501_68c5c125b3757.jpeg'),
(21, 'anika', 39, 'Sahara', 'Adventure', 'An explorer goes in search of a lost Civil War battleship known as the Ship of Death in the deserts of West Africa while helping a WHO doctor in her search for the source of a wide-spreading plague.', '1757822655_68c63ebf8dbd4.jpg'),
(22, 'saima', 36, 'Pandemic', 'Action', 'While Aidan loses hope of surviving a zombie outbreak, his outlook changes when he comes across Eva, another survivor, and decides to fight back with a renewed sense of hope.', '1757790501_68c5c125b3757.jpeg'),
(23, 'saima', 37, 'Sin City', 'Action', 'Four individuals cross paths when they try to solve their personal problems and fight violence and corruption in the wretched town of Basin City, Washington.', '1757822359_68c63d974941a.jpg'),
(24, 'saima', 39, 'Sahara', 'Adventure', 'An explorer goes in search of a lost Civil War battleship known as the Ship of Death in the deserts of West Africa while helping a WHO doctor in her search for the source of a wide-spreading plague.', '1757822655_68c63ebf8dbd4.jpg'),
(25, 'saima', 40, 'The jungle Book', 'Adventure', 'The Jungle Book is an 1894 collection of stories by the English author Rudyard Kipling. Most of the characters are animals such as Shere Khan the tiger and Baloo the bear, though a principal character is the boy or \"man-cub\" Mowgli, who is raised in the jungle by wolves.', '1757822724_68c63f044c2af.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `added_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`id`, `title`, `genre`, `description`, `image`, `added_by`) VALUES
(36, 'Pandemic', 'Action', 'While Aidan loses hope of surviving a zombie outbreak, his outlook changes when he comes across Eva, another survivor, and decides to fight back with a renewed sense of hope.', '1757790501_68c5c125b3757.jpeg', 'admin'),
(37, 'Sin City', 'Action', 'Four individuals cross paths when they try to solve their personal problems and fight violence and corruption in the wretched town of Basin City, Washington.', '1757822359_68c63d974941a.jpg', 'admin'),
(38, 'Archer', 'Adventure', 'Lauren Pierce has just become the high-school Tri-State Archery Champion. After the competition, Lauren and her teammate Emily return to their hotel room for a night of irresponsible celebratory drinking that grows into more. When interrupted by Emily\'s abusive boyfriend, Lauren snaps and brutally beats Daniel.', '1757822540_68c63e4c6b7b6.jpeg', 'editor2'),
(39, 'Sahara', 'Adventure', 'An explorer goes in search of a lost Civil War battleship known as the Ship of Death in the deserts of West Africa while helping a WHO doctor in her search for the source of a wide-spreading plague.', '1757822655_68c63ebf8dbd4.jpg', 'editor2'),
(40, 'The jungle Book', 'Adventure', 'The Jungle Book is an 1894 collection of stories by the English author Rudyard Kipling. Most of the characters are animals such as Shere Khan the tiger and Baloo the bear, though a principal character is the boy or \"man-cub\" Mowgli, who is raised in the jungle by wolves.', '1757822724_68c63f044c2af.jpg', 'editor2'),
(41, 'Home Alone', 'Comedy', 'Eight-year-old Kevin is accidentally left behind when his family leaves for France. At first, he\'s happy to be in charge; but when thieves try to break into his home, he puts up a fight like no other.', '1757822844_68c63f7c755b1.jpg', 'editor2'),
(42, 'The Johnsons', 'Comedy', 'The Strange Thing About the Johnsons received polarized reviews from critics and audiences. Many were divided on the film\'s controversial themes, although Mayo and Bullock received widespread acclaim for their performances.', '1757822923_68c63fcb5dc33.jpg', 'editor2'),
(43, 'Scream', 'Horror', 'When Sidney receives a mysterious phone call and finds that her friends are being killed, she suspects that a serial killer may be on the prowl, who is also linked to her mother\'s murder.', '1757823052_68c6404ca9d06.jpg', 'admin'),
(44, 'Secret', 'Thriller', 'Celess and Tina play the game too well to be considered your average gold diggers. However, when the jaw-dropping secret they both share is exposed, the same shovel that got them out of the dirt could very well bury them.', '1757823157_68c640b5772da.jpg', 'admin'),
(45, 'Goonies', 'Adventure', 'A group of west coast kids facing their last days together before a development paves over their homes stumble onto evidence of pirate\'s treasure, attracting the attention of a family of criminals.', '1757823520_68c642203f0e1.jpg', 'admin'),
(46, 'Journey2', 'Adventure', 'A teenager gets a coded signal from an uncharted island, which he suspects was sent by his long-missing grandfather. Soon, with his stepfather, he sets out in search of his grandfather and the island.', '1757823585_68c642614bbd2.jpg', 'admin'),
(47, 'Jungle Cruise', 'Adventure', 'Dr Lily Houghton, a researcher, and her brother team up with Frank, a skipper, to locate a mystical tree in the Amazon. However, they are pursued by evil entities lusting after immortality.', '1757823711_68c642dfda6e5.jpg', 'admin'),
(48, 'Adventure of TinTin', 'Adventure', 'Tintin, a young reporter, buys the model of a ship and is intrigued when his dog shows him a scroll after the toy breaks. He tells Captain Haddock and they embark on an adventure to find a shipwreck.', '1757823828_68c643542bf90.jpg', 'admin'),
(49, 'Hugo', 'Adventure', 'Hugo is a young orphan who loves pottering around with the station clocks and whose most treasured possession is his late father\'s automaton. His mission is to find a key that will get it working.', '1757823918_68c643ae50911.jpeg', 'admin'),
(50, 'Judgement of paris', 'Action', 'udgment of Paris\" can refer to a famous 1976 blind wine tasting that elevated California wines, or to the mythological story from Greek antiquity where the Trojan prince Paris chose Aphrodite as the most beautiful of three goddesses, a decision that led to the Trojan War. ', '1757824024_68c6441853973.jpg', 'admin'),
(51, 'The riots', 'Action', 'The city of Los Angeles is on edge, awaiting the verdict of the Rodney King trial. Jeffrey Lee (Dante Basco) is working at his family\'s liquor store in South Central. A boyfriend and girlfriend spend the afternoon at the local shopping mall.', '1757824110_68c6446e8a603.jpg', 'admin'),
(52, 'Vice', 'Action', 'Dick Cheney overcomes several challenges in life and becomes a powerful bureaucrat in Washington. His position of vice president to George W Bush allows him to reshape the world.', '1757824231_68c644e7520a0.jpg', 'admin'),
(53, 'Shutter island', 'Thriller', 'US Marshals Teddy Daniels and Chuck Aule are called to investigate the disappearance of a patient at a mental institution on a secluded island and soon stumble upon a sinister plot by the doctors.', '1757824353_68c645619bffe.jpeg', 'admin'),
(54, 'Spiderman', 'Action', 'Spider-Man is a superhero in American comic books published by Marvel Comics. Created by writer-editor Stan Lee and artist Steve Ditko, he first appeared in the anthology comic book Amazing Fantasy #15 in the Silver', '1757824444_68c645bc8e004.jpg', 'admin'),
(55, 'Johncina The marine', 'Action', 'John Triton, a former US Marine, ruthlessly pursues a gang of diamond thieves when they kidnap his wife, Kate, after she witnesses them committing a crime.', '1757824517_68c6460543bad.jpg', 'admin'),
(56, 'MR Poppers Penguins', 'Comedy', 'When Tom Popper\'s father dies in Antarctica, he inherits six penguins. Tom initially decides to give them away, but his children think they are a birthday present and he is forced to keep them.', '1757838285_68c67bcd73349.jpg', 'editor2');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `editor` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `editor`, `message`, `created_at`) VALUES
(14, 'editor2', 'Your movie \"Archer\" has been approved by admin.', '2025-09-14 04:02:46'),
(15, 'editor2', 'Your movie \"Sahara\" has been approved by admin.', '2025-09-14 04:09:09'),
(16, 'editor2', 'Your movie \"The jungle Book\" has been approved by admin.', '2025-09-14 04:09:12'),
(17, 'editor2', 'Your movie \"Home Alone\" has been approved by admin.', '2025-09-14 04:09:15'),
(18, 'editor2', 'Your movie \"The Johnsons\" has been approved by admin.', '2025-09-14 04:09:17'),
(19, 'editor2', 'Your movie \"MR Poppers Penguins\" has been approved by admin.', '2025-09-14 08:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `pending_movie`
--

CREATE TABLE `pending_movie` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `added_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `movie_id`, `username`, `rating`) VALUES
(5, 37, 'anika', 4),
(6, 40, 'anika', 3),
(7, 40, 'saima', 5),
(8, 37, 'saima', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','customer') NOT NULL,
  `profileimage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `profileimage`) VALUES
(14, 'humayra', 'humayrar@example.com', 'humayra123', 'customer', NULL),
(34, 'saima', 'saima@gmail.com', '$2y$10$EU25cT5FzrjDdlWS4/eRbuKAcgLMjJEEZjdPsrX6M7n7Wmf0DEq5u', 'customer', NULL),
(35, 'anika', 'anika@gmail.com', '$2y$10$3PTG5IurAAwbiPzDGWLRyOI/BRcGHMW4y5ztUByIcon0DoC1T4qiC', 'customer', NULL),
(36, 'admin1', 'admin@gmail.com', '$2y$10$E575quUMWaKGWySADbgDdOFFQKHybmJxFKQTKPVDXmZUJdRSzorRy', 'admin', NULL),
(37, 'editor2', 'editor@gmail.com', '$2y$10$AaRsxzKn0cvlp7joUpPKNuqlIQB/l/E8hS8TMSJvlooE1DSs9SKvu', 'editor', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_movie`
--
ALTER TABLE `pending_movie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pending_movie`
--
ALTER TABLE `pending_movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
