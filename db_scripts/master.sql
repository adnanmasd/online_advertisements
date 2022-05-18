CREATE DATABASE  IF NOT EXISTS `advertisement_website`;

USE `advertisement_website`;

--
-- Table structure for table `admin_user`
--
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `date_registered` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `categories`
--
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL,
  `inherit` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `country`
--
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `city`
--
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_CITY_COUNTRY_idx` (`country`),
  CONSTRAINT `FK_CITY_COUNTRY` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_registered` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_COUNTRY_idx` (`country`),
  KEY `FK_CITY_idx` (`city`),
  CONSTRAINT `FK_CITY` FOREIGN KEY (`city`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_COUNTRY` FOREIGN KEY (`country`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `user_activation`
--
DROP TABLE IF EXISTS `user_activation`;
CREATE TABLE `user_activation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `verificationCode` varchar(255) NOT NULL,
  `date_generated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_ID_idx` (`userId`),
  CONSTRAINT `FK_USER_ID` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Table structure for table `ads`
--
DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `image1` text,
  `image2` text,
  `image3` text,
  `image4` text,
  `condition` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_ID_idx` (`userId`),
  KEY `FK_USER_ID_AD_idx` (`userId`),
  KEY `FK_CAT_ID_idx` (`category_id`),
  CONSTRAINT `FK_CAT_ID` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_USER_ID_AD` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Poplutae initial data
--

INSERT INTO `advertisement_website`.`admin_user`(`email`, `password`, `firstname`, `lastname`, `date_registered`) VALUES ('admin@adsite.com', 'admin', 'Admin', 'Admin', now());


INSERT INTO `advertisement_website`.`categories` (`id`, `parent_id`, `name`, `inherit`) VALUES ('1', '0', 'Electronics', '0');
INSERT INTO `advertisement_website`.`categories` (`id`, `parent_id`, `name`, `inherit`) VALUES ('2', '0', 'Furniture', '0');
INSERT INTO `advertisement_website`.`categories` (`id`, `parent_id`, `name`, `inherit`) VALUES ('3', '0', 'Vehicles', '0');
INSERT INTO `advertisement_website`.`categories` (`id`, `parent_id`, `name`, `inherit`) VALUES ('4', '0', 'Real State', '0');

INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('1', 'Moblies', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('1', 'Home Appliances', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('1', 'Laptops', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('1', 'Speakers', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('2', 'Bed', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('2', 'Cupboard', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('2', 'Tables', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('3', 'Cars', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('3', 'Motorcycles', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('4', 'Apartments', '1');
INSERT INTO `advertisement_website`.`categories` (`parent_id`, `name`, `inherit`) VALUES ('4', 'Houses', '1');

INSERT INTO `advertisement_website`.`country` (`id`,`name`) VALUES ('1', 'Pakistan');
INSERT INTO `advertisement_website`.`country` (`id`,`name`) VALUES ('2', 'Saudi Arabia');
INSERT INTO `advertisement_website`.`country` (`id`,`name`) VALUES ('3', 'United States Of America');
INSERT INTO `advertisement_website`.`country` (`id`,`name`) VALUES ('4', 'United Kingdom');

INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('1', '1', 'Islamabad');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('2', '1', 'Karachi');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('3', '1', 'Lahore');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('4', '1', 'Peshawar');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('5', '2', 'Riyadh');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('6', '2', 'Dammam');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('7', '2', 'Makkah');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('8', '3', 'New York City');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('9', '3', 'Miami');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('10', '3', 'San Jose');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('11', '4', 'London');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('12', '4', 'Manchester');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('13', '4', 'Swansea');
INSERT INTO `advertisement_website`.`city` (`id`, `country`, `name`) VALUES ('14', '4', 'Liverpool');
