-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2021 at 08:28 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MyMusicGear`
--
CREATE DATABASE IF NOT EXISTS `MyMusicGear` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `MyMusicGear`;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `UniqueID` int(11) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Brand` varchar(25) NOT NULL,
  `Year` year(4) NOT NULL,
  `Characteristics` varchar(4000) NOT NULL,
  `Status` tinyint(1) NOT NULL,
  `CostPD` int(11) NOT NULL,
  `CostOD` int(11) NOT NULL,
  `renterID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`UniqueID`, `Category`, `Brand`, `Year`, `Characteristics`, `Status`, `CostPD`, `CostOD`, `renterID`) VALUES
(29, 'Guitar', 'yamaha', 1999, 'Never Used\nBrown', 0, 10, 25, 2),
(30, 'Drums', 'gretsch', 2021, 'Never Used\nBlue', 1, 100, 200, 0),
(31, 'KeyBoard', 'casio', 2000, 'Used\nGreen', 1, 5, 10, 0),
(32, 'Accessory', 'other', 2001, 'Damaged\nRed', 1, 5, 10, 0),
(33, 'Guitar', 'fender', 2021, 'Never Used\nBlack', 1, 125, 275, 0),
(34, 'Guitar', 'fender', 1969, 'Restored\nWhite', 1, 125, 250, 0),
(35, 'Guitar', 'takamine', 2005, 'Like New\nBrown', 1, 25, 50, 0),
(36, 'Amplifier', 'blackstar', 2020, 'Like New\nBlack', 1, 70, 150, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rentalrecords`
--

CREATE TABLE `rentalrecords` (
  `UniqueID` int(11) NOT NULL,
  `rentalID` int(11) NOT NULL,
  `renterID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rentalrecords`
--

INSERT INTO `rentalrecords` (`UniqueID`, `rentalID`, `renterID`) VALUES
(29, 44, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rented`
--

CREATE TABLE `rented` (
  `UniqueID` int(11) NOT NULL,
  `rentalID` int(11) NOT NULL,
  `renterID` int(11) DEFAULT NULL,
  `rentedDate` date NOT NULL DEFAULT current_timestamp(),
  `dueDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rented`
--

INSERT INTO `rented` (`UniqueID`, `rentalID`, `renterID`, `rentedDate`, `dueDate`) VALUES
(29, 44, 2, '2021-05-28', '2021-06-04');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Password_md5` varchar(32) NOT NULL,
  `Type` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `Name`, `Surname`, `Phone`, `Email`, `Password_md5`, `Type`) VALUES
(1, 'tim', 'tam', '0421418621', 'tim@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 'administrator'),
(2, 'john', 'james', '0421418621', 'jj@g.com', 'fcea920f7412b5da7be0cf42b8c93759', 'client'),
(32, 'Admin', 'Admin', '0412345678', 'admin@admin.com', 'fcea920f7412b5da7be0cf42b8c93759', 'client');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`UniqueID`);

--
-- Indexes for table `rentalrecords`
--
ALTER TABLE `rentalrecords`
  ADD KEY `UniqueID` (`UniqueID`),
  ADD KEY `renterID` (`renterID`);

--
-- Indexes for table `rented`
--
ALTER TABLE `rented`
  ADD UNIQUE KEY `rentalID` (`rentalID`),
  ADD KEY `UniqueID` (`UniqueID`),
  ADD KEY `RenterID` (`renterID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `UniqueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `rented`
--
ALTER TABLE `rented`
  MODIFY `rentalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentalrecords`
--
ALTER TABLE `rentalrecords`
  ADD CONSTRAINT `rentalrecords_ibfk_1` FOREIGN KEY (`UniqueID`) REFERENCES `product` (`UniqueID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rentalrecords_ibfk_3` FOREIGN KEY (`renterID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rented`
--
ALTER TABLE `rented`
  ADD CONSTRAINT `rented_ibfk_1` FOREIGN KEY (`UniqueID`) REFERENCES `product` (`UniqueID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rented_ibfk_2` FOREIGN KEY (`RenterID`) REFERENCES `user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
