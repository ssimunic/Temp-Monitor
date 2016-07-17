CREATE DATABASE  IF NOT EXISTS `tempmonitor` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tempmonitor`;
-- MySQL dump 10.13  Distrib 5.7.12, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: tempmonitor
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `datalog`
--

DROP TABLE IF EXISTS `datalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sensors_unique_id` varchar(10) NOT NULL,
  `temperature` float DEFAULT '0',
  `humidity` float DEFAULT '0',
  `date` varchar(10) NOT NULL,
  `time` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_datalog_sensors1_idx` (`sensors_unique_id`),
  CONSTRAINT `fk_datalog_sensors1` FOREIGN KEY (`sensors_unique_id`) REFERENCES `sensors` (`unique_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43155 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maps`
--

DROP TABLE IF EXISTS `maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `name` varchar(90) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_maps_users1_idx` (`users_id`),
  CONSTRAINT `fk_maps_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maps_entry`
--

DROP TABLE IF EXISTS `maps_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maps_id` int(11) NOT NULL,
  `sensors_unique_id` varchar(10) NOT NULL,
  `map_location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_maps_entry_sensors1_idx` (`sensors_unique_id`),
  KEY `fk_maps_entry_maps1_idx` (`maps_id`),
  CONSTRAINT `fk_maps_entry_maps1` FOREIGN KEY (`maps_id`) REFERENCES `maps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_maps_entry_sensors1` FOREIGN KEY (`sensors_unique_id`) REFERENCES `sensors` (`unique_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensors`
--

DROP TABLE IF EXISTS `sensors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensors` (
  `unique_id` varchar(10) NOT NULL,
  `mac_address` varchar(90) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `notes` text,
  `version` varchar(45) DEFAULT NULL,
  `last_temp` float DEFAULT NULL,
  `last_hum` float DEFAULT NULL,
  `last_check` varchar(90) DEFAULT NULL,
  `contact` varchar(90) DEFAULT NULL,
  `contact_type` varchar(90) DEFAULT '0',
  `location` varchar(255) DEFAULT NULL,
  `api_key` varchar(32) DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`unique_id`),
  KEY `fk_sensors_users_idx` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensors_config`
--

DROP TABLE IF EXISTS `sensors_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensors_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sensors_unique_id` varchar(10) NOT NULL,
  `server_address` varchar(90) DEFAULT NULL,
  `min_temp` float DEFAULT '-20',
  `min_hum` float DEFAULT '0',
  `max_temp` float DEFAULT '45',
  `max_hum` float DEFAULT '100',
  `read_sensor_time` bigint(20) DEFAULT NULL,
  `send_sensor_time` bigint(20) DEFAULT NULL,
  `deepsleep_time` bigint(20) DEFAULT '15',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sensors_config_sensors1_idx` (`sensors_unique_id`),
  CONSTRAINT `fk_sensors_config_sensors1` FOREIGN KEY (`sensors_unique_id`) REFERENCES `sensors` (`unique_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(90) DEFAULT NULL,
  `name` varchar(90) DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-17 20:08:51
