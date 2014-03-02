-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 09, 2013 at 10:58 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.6-1ubuntu1.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Reward-Yourself`
--

-- --------------------------------------------------------

--
-- Table structure for table `Deposit`
--

CREATE TABLE IF NOT EXISTS `Deposit` (
  `DepositID` int(10) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL,
  `Amount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`DepositID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Purchase`
--

CREATE TABLE IF NOT EXISTS `Purchase` (
  `PurchaseID` int(10) unsigned NOT NULL,
  `UserID` int(10) unsigned NOT NULL,
  `RewardID` int(10) unsigned NOT NULL,
  `Quantity` int(10) unsigned NOT NULL,
  `Total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`PurchaseID`),
  KEY `UserID` (`UserID`,`RewardID`),
  KEY `RewardID` (`RewardID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Reward`
--

CREATE TABLE IF NOT EXISTS `Reward` (
  `RewardID` int(11) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UnitPrice` int(11) unsigned NOT NULL,
  `UnitID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`RewardID`),
  UNIQUE KEY `Name` (`Name`),
  KEY `UnitID` (`UnitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Unit`
--

CREATE TABLE IF NOT EXISTS `Unit` (
  `UnitID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`UnitID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Hash` varchar(255) NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Deposit`
--
ALTER TABLE `Deposit`
  ADD CONSTRAINT `Deposit_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserId`);

--
-- Constraints for table `Purchase`
--
ALTER TABLE `Purchase`
  ADD CONSTRAINT `Purchase_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserId`),
  ADD CONSTRAINT `Purchase_ibfk_2` FOREIGN KEY (`RewardID`) REFERENCES `Reward` (`RewardID`);

--
-- Constraints for table `Reward`
--
ALTER TABLE `Reward`
  ADD CONSTRAINT `Reward_ibfk_1` FOREIGN KEY (`UnitID`) REFERENCES `Unit` (`UnitID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
