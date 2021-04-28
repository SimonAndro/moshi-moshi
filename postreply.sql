-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 28, 2021 at 02:45 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moshi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `postreply`
--

CREATE TABLE `postreply` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `message_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `postreply`
--

INSERT INTO `postreply` (`id`, `post_id`, `creator_id`, `created_at`, `message_data`) VALUES
(1, 53, 39, 1619606633, 'first reply'),
(2, 53, 39, 1619606939, 'Another reply'),
(3, 53, 39, 1619606947, 'Another reply'),
(4, 53, 39, 1619606948, 'Another reply'),
(5, 53, 39, 1619617488, 'Wagwam'),
(6, 53, 39, 1619617637, 'first reply 3'),
(7, 53, 39, 1619617669, 'ggg'),
(8, 53, 39, 1619619760, 'first reply'),
(9, 53, 39, 1619619765, 'Another reply'),
(10, 53, 39, 1619619770, 'first reply 3'),
(11, 53, 39, 1619619778, 'haha'),
(12, 50, 39, 1619620023, 'hey'),
(13, 53, 39, 1619620469, 'first reply'),
(14, 53, 39, 1619620499, 'okay'),
(15, 52, 39, 1619620505, 'why'),
(16, 52, 39, 1619620508, 'no no'),
(17, 52, 39, 1619620510, 'haha'),
(18, 52, 39, 1619620519, 'why man'),
(19, 53, 5, 1619620752, 'haha'),
(20, 53, 39, 1619620768, 'hehe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `postreply`
--
ALTER TABLE `postreply`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `postreply`
--
ALTER TABLE `postreply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
