-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-06-10 23:36:55
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table rota.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `a_email` varchar(100) NOT NULL,
  `a_password` varchar(100) DEFAULT NULL,
  `a_created` datetime NOT NULL,
  `a_lastlogin` datetime DEFAULT NULL,
  `a_verify` char(10) DEFAULT NULL,
  `a_enabled` enum('Y','N') NOT NULL DEFAULT 'N',
  `a_type` enum('member','admin') NOT NULL DEFAULT 'member',
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `email` (`a_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.events
CREATE TABLE IF NOT EXISTS `events` (
  `e_year` year(4) NOT NULL,
  `e_start_date` date NOT NULL,
  `e_end_date` date NOT NULL,
  `e_current` enum('Y','N') NOT NULL,
  PRIMARY KEY (`e_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.operators
CREATE TABLE IF NOT EXISTS `operators` (
  `o_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `o_a_id` int(11) unsigned DEFAULT NULL,
  `o_type` enum('club','person') NOT NULL DEFAULT 'club',
  `o_name` varchar(100) NOT NULL,
  `o_callsign` char(10) NOT NULL,
  `o_url` varchar(255) DEFAULT NULL,
  `o_info_src` text,
  `o_info_html` text,
  PRIMARY KEY (`o_id`),
  UNIQUE KEY `o_id_o_callsign` (`o_id`,`o_callsign`),
  KEY `a_id` (`o_a_id`),
  CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`o_a_id`) REFERENCES `accounts` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.railways
CREATE TABLE IF NOT EXISTS `railways` (
  `r_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `r_name` varchar(100) NOT NULL,
  `r_slug` varchar(100) DEFAULT NULL,
  `r_url` varchar(255) DEFAULT NULL,
  `r_info_src` text,
  `r_info_html` text,
  `r_photo` varchar(20) DEFAULT NULL,
  `r_postcode` varchar(8) DEFAULT NULL,
  `r_wab` varchar(20) DEFAULT NULL,
  `r_locator` varchar(8) DEFAULT NULL,
  `r_lat` float(10,6) DEFAULT NULL,
  `r_lng` float(10,6) DEFAULT NULL,
  `r_added_a_id` int(11) unsigned DEFAULT NULL,
  `r_added_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`r_id`),
  KEY `r_added_a_id` (`r_added_a_id`),
  CONSTRAINT `railways_ibfk_1` FOREIGN KEY (`r_added_a_id`) REFERENCES `accounts` (`a_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.rota_legacy
CREATE TABLE IF NOT EXISTS `rota_legacy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `callsign` varchar(10) NOT NULL,
  `club` tinyint(1) NOT NULL DEFAULT '1',
  `railway_name` varchar(100) NOT NULL,
  `railway_url` varchar(255) DEFAULT NULL,
  `wab` varchar(20) DEFAULT NULL,
  `locator` varchar(8) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `confirm` varchar(40) DEFAULT NULL,
  `year` year(4) DEFAULT '2008',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table rota.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table rota.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `s_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `s_e_year` year(4) NOT NULL,
  `s_r_id` int(11) unsigned NOT NULL,
  `s_o_id` int(11) unsigned NOT NULL,
  `s_date_registered` datetime DEFAULT NULL,
  `s_log` varchar(64) DEFAULT NULL,
  `s_num_contacts` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`s_e_year`,`s_r_id`,`s_o_id`),
  UNIQUE KEY `s_id` (`s_id`),
  KEY `r_id` (`s_r_id`),
  KEY `o_id` (`s_o_id`),
  CONSTRAINT `stations_ibfk_9` FOREIGN KEY (`s_r_id`) REFERENCES `railways` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stations_ibfk_7` FOREIGN KEY (`s_e_year`) REFERENCES `events` (`e_year`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stations_ibfk_8` FOREIGN KEY (`s_o_id`) REFERENCES `operators` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
