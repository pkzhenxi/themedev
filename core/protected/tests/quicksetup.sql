# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: db.4004.lightspeedwebstore.com (MySQL 5.5.29-0ubuntu0.12.04.1-log)
# Database: support4004_kris5
# Generation Time: 2013-04-16 16:43:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table xlsws_category_integration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `xlsws_category_integration`;

CREATE TABLE `xlsws_category_integration` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) DEFAULT NULL,
  `foreign_id` int(11) unsigned DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  KEY `module` (`module`),
  KEY `foreign_id` (`foreign_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `xlsws_category_integration_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `xlsws_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `xlsws_category_integration` WRITE;
/*!40000 ALTER TABLE `xlsws_category_integration` DISABLE KEYS */;

INSERT INTO `xlsws_category_integration` (`category_id`, `module`, `foreign_id`, `extra`)
VALUES
	(11,'amazon',9642,NULL),
	(28,'amazon',25203,NULL),
	(30,'amazon',592,NULL),
	(14,'amazon',10143,NULL),
	(12,'amazon',10261,NULL),
	(35,'amazon',14173,NULL),
	(20,'amazon',10270,NULL),
	(19,'amazon',10261,NULL);


/*!40000 ALTER TABLE `xlsws_modules` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
