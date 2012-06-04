-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-06-05 00:22:41
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table rota.accounts: ~3 rows (approximately)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`a_id`, `a_email`, `a_password`, `a_created`, `a_lastlogin`, `a_verify`, `a_enabled`, `a_type`) VALUES
	(1, 'craig.rodway@gmail.com', 'rota#VXixaRjZ4q#e6b73a63533f9a994fdee9bd229b68230690a93a', '2011-11-27 00:01:42', '2012-06-05 01:00:23', NULL, 'Y', 'admin'),
	(2, 'craig.rodway+user@gmail.com', 'rota#kYZmXkXLMA#b405464576628a5097c9b7af65d50d5dc50851f4', '2011-11-30 18:52:00', '2012-06-05 00:08:24', NULL, 'Y', 'member'),
	(3, 'test@example.com', NULL, '2012-05-22 21:39:11', NULL, 'XD1gzlyyoW', 'N', 'member');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;


-- Dumping structure for table rota.events
CREATE TABLE IF NOT EXISTS `events` (
  `e_year` year(4) NOT NULL,
  `e_start_date` date NOT NULL,
  `e_end_date` date NOT NULL,
  `e_current` enum('Y','N') NOT NULL,
  PRIMARY KEY (`e_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table rota.events: ~4 rows (approximately)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`e_year`, `e_start_date`, `e_end_date`, `e_current`) VALUES
	('2011', '2011-09-24', '2011-09-25', 'N'),
	('2012', '2012-09-22', '2012-09-23', 'Y'),
	('2013', '2013-09-21', '2013-09-22', 'N'),
	('2014', '2014-09-27', '2014-09-28', 'N'),
	('2015', '2015-09-26', '2015-09-27', 'N');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;


-- Dumping structure for table rota.operators
CREATE TABLE IF NOT EXISTS `operators` (
  `o_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `o_a_id` int(11) unsigned NOT NULL,
  `o_type` enum('club','person') NOT NULL DEFAULT 'club',
  `o_name` varchar(100) NOT NULL,
  `o_callsign` varchar(20) NOT NULL,
  `o_url` varchar(100) DEFAULT NULL,
  `o_info` text NOT NULL,
  PRIMARY KEY (`o_id`),
  KEY `a_id` (`o_a_id`),
  CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`o_a_id`) REFERENCES `accounts` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table rota.operators: ~0 rows (approximately)
/*!40000 ALTER TABLE `operators` DISABLE KEYS */;
/*!40000 ALTER TABLE `operators` ENABLE KEYS */;


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
  `r_added_by` int(11) unsigned NOT NULL,
  `r_added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8;

-- Dumping data for table rota.railways: ~56 rows (approximately)
/*!40000 ALTER TABLE `railways` DISABLE KEYS */;
INSERT INTO `railways` (`r_id`, `r_name`, `r_slug`, `r_url`, `r_info_src`, `r_info_html`, `r_photo`, `r_postcode`, `r_wab`, `r_locator`, `r_lat`, `r_lng`, `r_added_by`, `r_added_at`) VALUES
	(2, 'Ribble Steam Railway', 'ribble-steam-railway', 'http://www.ribblesteam.org.uk/', '', '', NULL, 'PR2 2PD', 'SD52', 'IO83OS', 53.760067, -2.752216, 1, '2011-12-03 22:41:12'),
	(3, 'Kirkby Stephen East', 'kirkby-stephen-east', 'http://www.kirkbystepheneast.co.uk/', '', '', NULL, 'CA17 4LA', 'NY70', 'IO84TL', 54.462536, -2.357481, 1, '2011-12-03 22:41:12'),
	(4, 'Tomoulin FNQ Australia', 'tomoulin-fnq-australia', '', '', '', NULL, '', '', '', 0.000000, 0.000000, 1, '2011-12-03 22:41:12'),
	(6, 'Paignton and Dartmouth Steam Railway', 'paignton-and-dartmouth-steam-railway', 'http://www.paignton-steamrailway.co.uk/', '', '', NULL, 'TQ4 6AF', '', 'IO80FK', 50.434765, -3.563878, 1, '2011-12-03 22:41:12'),
	(10, 'Leadhills and Wanlockhead Railway', 'leadhills-and-wanlockhead-railway', 'http://www.leadhillsrailway.co.uk/', '', '', NULL, 'ML12 6XP', '', 'IO85CJ', 55.414841, -3.761897, 1, '2011-12-03 22:41:12'),
	(11, 'Stephenson Railway Museum', 'stephenson-railway-museum', 'http://www.twmuseums.org.uk/stephenson/', '', '', NULL, 'NE29 8DX', '', 'IO95GA', 55.016304, -1.497970, 1, '2011-12-03 22:41:12'),
	(13, 'Lincolnshire Wolds Railway', 'lincolnshire-wolds-railway', 'http://www.lincolnshirewoldsrailway.co.uk', '', '', NULL, 'DN36 5SQ', 'TF39', 'IO93XK', 53.442547, -0.044010, 1, '2011-12-03 22:41:12'),
	(14, 'Brusselton Incline Engine House, S&D Railway', 'brusselton-incline-engine-house-sd-railway', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2011-12-03 22:41:12'),
	(18, 'Hollycombe Steam Collection', 'hollycombe-steam-collection', 'http://www.hollycombe.co.uk/', '', '', NULL, 'GU30 7LP', '', 'IO91OB', 51.060764, -0.789542, 1, '2011-12-03 22:41:12'),
	(19, 'Waverley Route Heritage Association', 'waverley-route-heritage-association', 'http://www.wrha.org.uk', '', '', NULL, 'TD9 9TY', '', 'IO85PH', 55.295891, -2.745615, 1, '2011-12-03 22:41:12'),
	(20, 'Midland Railway Centre', 'midland-railway-centre', 'http://www.midlandrailwaycentre.co.uk', '', '', NULL, '', '', 'IO93HB', 53.059753, -1.404623, 1, '2011-12-03 22:41:12'),
	(24, 'Vale of Glamorgan Railway', 'vale-of-glamorgan-railway', 'http://en.wikipedia.org/wiki/Vale_of_Glamorgan_Railway', '', '', NULL, '', '', '', 0.000000, 0.000000, 1, '2011-12-03 22:41:12'),
	(27, 'Welsh Highland Heritage Railway', 'welsh-highland-heritage-railway', 'http://www.whr.co.uk', '', '', NULL, 'LL49 9DY', '', 'IO72WW', 52.930611, -4.133003, 1, '2011-12-03 22:41:12'),
	(29, 'Darlington Head of Steam', 'darlington-head-of-steam', 'http://www.darlington.gov.uk/headofsteam/', '', '', NULL, 'DL3 6ST', '', 'IO94FM', 54.535526, -1.554838, 1, '2011-12-03 22:41:12'),
	(38, 'Peak Rail', 'peak-rail', 'http://www.peakrail.co.uk/', '', '', NULL, 'DE4 2EQ', '', 'IO93ED', 53.160576, -1.592140, 1, '2011-12-03 22:41:12'),
	(46, 'Keith and Dufftown Railway', 'keith-and-dufftown-railway', 'http://www.keith-dufftown-railway.co.uk/', '', '', NULL, 'AB55 4BA', '', 'IO87KK', 57.453568, -3.132885, 1, '2011-12-03 22:41:12'),
	(47, 'Sidmouth Observatory Funicular Railway', 'sidmouth-observatory-funicular-railway', 'http://projects.exeter.ac.uk/nlo/tour/welcome.htm', '', '', NULL, '', '', '', 0.000000, 0.000000, 1, '2011-12-03 22:41:12'),
	(49, 'East Somerset Railway', 'east-somerset-railway', 'http://www.eastsomersetrailway.com/', '', '', NULL, 'BA4 4QP', 'ST64', 'IO81SE', 51.185043, -2.480434, 1, '2011-12-03 22:41:12'),
	(50, 'West Lancashire Light Railway', 'west-lancashire-light-railway', 'http://www.westlancs.org/', '', '', NULL, 'PR4 6SP', 'SD42', 'IO83NQ', 53.700443, -2.839702, 1, '2011-12-03 22:41:12'),
	(51, 'Eden Valley Railway', 'eden-valley-railway', 'http://evr-cumbria.org.uk', '', '', NULL, 'CA166PR ', 'NZ71', 'IO84TM', 54.535786, -2.381689, 1, '2011-12-03 22:41:12'),
	(53, 'Blaenavon Heritage Railway', 'blaenavon-heritage-railway', 'http://www.pontypool-and-blaenavon.co.uk/', '', '', NULL, 'NP49ND', '', 'IO81KS', 51.772026, -3.085463, 1, '2011-12-03 22:41:12'),
	(54, 'Sørlandsbanen (Railway to the South)', 'sorlandsbanen-railway-to-the-south', 'http://www.nsb.no/sorlandsbanen/', '', '', NULL, '', '', 'JO49OI', 59.354168, 9.208333, 1, '2011-12-03 22:41:12'),
	(57, 'Chasewater Railway', 'chasewater-railway', 'http://www.chasewaterrailway.co.uk/', '', '', NULL, 'WS8 7NL', 'SK00', 'IO92AP', 52.661427, -1.943050, 1, '2011-12-03 22:41:12'),
	(58, 'Railway Preservation Society of Ireland', 'railway-preservation-society-of-ireland', 'http://www.steamtrainsireland.com', NULL, NULL, NULL, NULL, 'J49', 'IO74DS', 54.770832, -5.708333, 1, '2011-12-03 22:41:12'),
	(59, 'Gloucestershire Warwickshire Railway', 'gloucestershire-warwickshire-railway', 'http://www.gwsr.com/', '', '', NULL, 'GL54 5DT', 'SO92', 'IO91AX', 51.989655, -1.930271, 1, '2011-12-03 22:41:12'),
	(60, 'Bluebell Railway', 'bluebell-railway', 'http://www.bluebell-railway.com/', '', '', NULL, 'TN22 3QL', 'TQ32', 'IO90XX', 50.995403, -0.001535, 1, '2011-12-03 22:41:12'),
	(70, 'Embsay & Bolton Abbey Steam Railway', 'embsay-bolton-abbey-steam-railway', 'http://www.embsayboltonabbeyrailway.org.uk', '', '', NULL, 'BD236AF', 'SE05', 'IO93BX', 53.976036, -1.909050, 1, '2011-12-03 22:41:12'),
	(74, 'Corris Railway', 'corris-railway', 'http://www.corris.co.uk', '', '', NULL, 'SY20 9SH', '', 'IO82BP', 52.653183, -3.842865, 1, '2011-12-03 22:41:12'),
	(77, 'Nene Valley Railway', 'nene-valley-railway', 'http://www.nvr.org.uk/', '', '', NULL, 'PE8 6LR', 'TO09', 'IO92TN', 52.568329, -0.389761, 1, '2011-12-03 22:41:12'),
	(78, 'Middleton Railway Leeds', 'middleton-railway-leeds', 'http://www.middletonrailway.org.uk/', '', '', NULL, 'LS10 2JQ', '', 'IO93FS', 53.773254, -1.538312, 1, '2011-12-03 22:41:12'),
	(79, 'Whitwell & Reepham Railway', 'whitwell-reepham-railway', 'http://www.whitwellstation.com/default.asp', '', '', NULL, 'NR10 4GA', 'TG02', 'JO02NS', 52.751434, 1.097139, 1, '2011-12-03 22:41:12'),
	(80, 'Amman Valley Railway', 'amman-valley-railway', 'http://avrail.webplus.net/', '', '', '0', '', 'SN61', 'IO81AT', 51.812500, -3.958333, 1, '2011-12-03 22:41:12'),
	(82, 'Monkwearmouth Station Musuem', 'monkwearmouth-station-musuem', 'http://www.twmuseums.org.uk/monkwearmouth/', '', '', NULL, 'SR5 1AP', '', 'IO94HV', 54.911873, -1.384092, 1, '2011-12-03 22:41:12'),
	(89, 'Weardale Railway', 'weardale-railway', 'http://www.weardale-railway.org.uk/', '', '', NULL, 'DL13 2YS', '', 'IO84XR', 54.742859, -2.002624, 1, '2011-12-03 22:41:12'),
	(90, 'Somerset & Dorset Railway Heritage Trust', 'somerset-dorset-railway-heritage-trust', 'http://www.sdjr.co.uk', '', '', NULL, 'BA3 2EY', '', 'IO81SG', 51.279633, -2.483385, 1, '2011-12-03 22:41:12'),
	(92, 'Bredgar & Wormshill Light Railway', 'bredgar-wormshill-light-railway', 'http://www.bwlr.co.uk/', '', '', NULL, 'ME9 8AT', '', 'JO01IH', 51.296890, 0.682452, 1, '2011-12-03 22:41:12'),
	(93, 'Crewe Heritage Centre', 'crewe-heritage-centre', 'http://www.creweheritagecentre.co.uk', '', '', NULL, 'CW1 2DB', 'SJ65', 'IO83SC', 53.093433, -2.436262, 1, '2011-12-03 22:41:12'),
	(97, 'Princess Royal Class Locomotive Trust', 'princess-royal-class-locomotive-trust', 'http://www.prclt.co.uk', '', '', NULL, 'DE5 4AD', 'SK43', 'IO93HB', 53.049919, -1.408429, 1, '2011-12-03 22:41:12'),
	(99, 'Battlefield Line Railway - Shackerstone Station', 'battlefield-line-railway-shackerstone-station', 'http://www.battlefield-line-railway.co.uk/', NULL, NULL, NULL, NULL, 'SK30', 'IO92GP', 52.645832, -1.458333, 1, '2011-12-03 22:41:12'),
	(100, 'Normanby Park Minature Railway', 'normanby-park-minature-railway', 'http://homepage.ntlworld.com/wilfred.baker/', '', '', NULL, 'DN15 9HU', 'SE88', 'IO93QP', 53.638786, -0.655958, 1, '2011-12-03 22:41:12'),
	(101, 'Tyttenhanger Light Railway', 'tyttenhanger-light-railway', 'http://www.nlsme.co.uk', '', '', NULL, 'AL4 0NJ', 'TL', 'IO91UR', 51.738094, -0.266543, 1, '2011-12-03 22:41:12'),
	(104, 'Worth Valley Railway', 'worth-valley-railway', 'http://www.kwvr.co.uk', NULL, NULL, NULL, NULL, 'SE03', NULL, NULL, NULL, 1, '2011-12-03 22:41:12'),
	(105, 'Helston Railway', 'helston-railway', 'http://www.helstonrailway.co.uk/', '', '', NULL, 'TR13 0RU', 'SW63', 'IO70ID', 50.126423, -5.301466, 1, '2011-12-03 22:41:12'),
	(107, 'Lappa Valley Railway', 'lappa-valley-railway', 'http://www.lappavalley.co.uk', '', '', NULL, 'TR8 5LX', 'SW86', 'IO70LI', 50.373074, -5.049064, 1, '2011-12-03 22:41:12'),
	(108, 'Battlefield Line Railway - Market Bosworth Station', 'battlefield-line-railway-market-bosworth-station', 'http://www.battlefield-line-railway.co.uk', '', '', NULL, '', 'SK30', 'IO92GO', 52.604168, -1.458333, 1, '2011-12-03 22:41:12'),
	(109, 'North Norfolk Railway', 'north-norfolk-railway', 'http://www.nnrailway.co.uk/', '', '', NULL, 'NR26 8RA', 'TG03', 'JO02OW', 52.941563, 1.208976, 1, '2011-12-03 22:41:12'),
	(111, 'Lakeside & Havethwaite Railway', 'lakeside-havethwaite-railway', 'http://www.lakesiderailway.co.uk/', '', '', NULL, 'LA12 8AL', 'SD38', 'IO84LF', 54.249443, -3.000135, 1, '2011-12-03 22:41:12'),
	(112, 'GC Railway, Nottingham', 'gc-railway-nottingham', 'http://217.158.157.207/railways/GCRN/index.htm', '', '', NULL, 'NG11 6JS', 'SK53', 'IO92KV', 52.882221, -1.143732, 1, '2011-12-03 22:41:12'),
	(115, 'GWR Preservation Group (Southall Railway Centre)', 'gwr-preservation-group-southall-railway-centre', 'http://www.gwrpg.co.uk/', '', '', NULL, 'UB2 4SE', '', 'IO91TM', 51.506210, -0.365244, 1, '2011-12-03 22:41:12'),
	(116, 'Dean Forest Railway', 'dean-forest-railway', 'http://www.deanforestrailway.co.uk/', '', '', NULL, 'GL15 4ET', 'SO60', 'IO81RR', 51.736759, -2.538022, 1, '2011-12-03 22:41:12'),
	(117, 'Drumawhey Miniature Railway', 'drumawhey-miniature-railway', 'http://bcdmrs.org.uk', '', '', NULL, 'BT21 0LZ', '', 'IO74FP', 54.638878, -5.545771, 1, '2011-12-03 22:41:12'),
	(118, 'Foxfield Railway', 'foxfield-railway', 'http://www.foxfieldrailway.co.uk/', '', '', NULL, 'ST11 9BG', 'SJ94', 'IO82XX', 52.971081, -2.065774, 1, '2011-12-03 22:41:12'),
	(120, 'Ravenglass and Eskdale Railway', 'ravenglass-and-eskdale-railway', 'http://www.ravenglass-railway.co.uk/', '', '', NULL, 'CA181SW', 'SD10', 'IO84HI', 54.356487, -3.409687, 1, '2011-12-03 22:41:12'),
	(123, 'Amberley Narrow Gauge Railway', 'amberley-narrow-gauge-railway', 'http://www.amberleynarrowgauge.co.uk', 'Amberley Narrow Gauge Railway lies within the Amberley Museum, and is home to a large collection of locomotives, rolling stock and other equipment, plus an extensive track network across the 36 acre site.', 'Amberley Narrow Gauge Railway lies within the Amberley Museum, and is home to a large collection of locomotives, rolling stock and other equipment, plus an extensive track network across the 36 acre site.', '../../storage/railwa', 'BN18 9LT', 'TQ01', 'IO90RV', 50.901596, -0.539748, 1, '2011-12-03 22:41:12'),
	(125, 'Worden Park Miniature Railway', 'worden-park-miniature-railway', 'http://en.wikipedia.org/wiki/Worden_Park', '', '', NULL, 'PR5 2DJ', '', 'IO83PQ', 53.695236, -2.681725, 1, '2011-12-03 22:41:12'),
	(126, 'Isle of Wight Steam Railway', 'isle-of-wight-steam-railway', 'http://www.iwsteamrailway.co.uk/', '', '', NULL, 'PO33 4DS', 'SZ58', 'IO90JQ', 50.704735, -1.214796, 1, '2011-12-03 22:41:12');
/*!40000 ALTER TABLE `railways` ENABLE KEYS */;


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
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=latin1;

-- Dumping data for table rota.rota_legacy: 128 rows
/*!40000 ALTER TABLE `rota_legacy` DISABLE KEYS */;
INSERT INTO `rota_legacy` (`id`, `name`, `url`, `callsign`, `club`, `railway_name`, `railway_url`, `wab`, `locator`, `lat`, `lng`, `email`, `date`, `confirm`, `year`) VALUES
	(1, '', NULL, 'GB2EVR ', -1, 'Eden Valley Railway ', 'http://www.evr.org.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(2, '', NULL, 'GB5RSR', -1, 'Ribble Steam Railway', 'http://www.ribblesteam.org.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(3, '', NULL, 'MX0RXY ', -1, 'Kirkby Stephen East ', 'http://www.kirkbystepheneast.co.uk/ ', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(4, '', NULL, 'VK4GHL ', -1, 'Tomoulin FNQ Australia ', ' http://www.tablelandradiogroup.com ', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(5, '', NULL, 'GB4WLR', -1, 'Westlancs Railway ', 'http://www.westlancs.org', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(6, '', NULL, 'GB6PDR ', -1, 'Paignton and Dartmouth Steam Railway ', ' http://www.paignton-steamrailway.co.uk ', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(7, '', NULL, 'GB4CHC ', -1, 'Crewe Heritage Centre ', ' http://www.creweheritagecentre.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(8, '', NULL, 'GB0HBL', -1, 'The Helston Restoration Project ', ' http://www.helstonrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(9, '', NULL, 'GM3TKV/P ', -1, 'The Keith and Dufftown Railway', 'www.keith-dufftown-railway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(10, '', NULL, 'GB0LWR', -1, 'Leadhills and Wanlockhead Railway', 'http://www.leadhillsrailway.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(11, '', NULL, 'GB4SRM ', -1, 'Stephenson Railway Museum ', 'http://www.twmuseums.org.uk/stephenson/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(12, '', NULL, 'GB2STI ', -1, 'Railway Preservation Society of Ireland', 'http://www.steamtrainsireland.com', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(13, '', NULL, 'GB2LWR', -1, 'Lincolnshire Wolds Railway', 'http://www.lincolnshirewoldsrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(14, '', NULL, 'G1DFN', -1, 'Brusselton Incline Engine House, S&D Railway', '', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(15, '', NULL, 'SP8YZZ', -1, 'Zagorz Railway 1872, SP FIRAC', 'http://sp9jpa.blogspot.com/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(16, '', NULL, 'GB0WHR ', -1, 'Welsh Highland Railway ', 'http://www.whr.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(17, '', NULL, 'GB0GWR ', -1, 'Gloucester & Warwickshire Railway', 'http://www.gwsr.com', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(47, '', NULL, 'GB2HSC', -1, 'Hollycombe Steam Collection', 'http://www.Hollycombe.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(46, '', NULL, 'GB0WRH', -1, 'Waverley Route Heritage Assoc.', 'http://www.wrha.org.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(48, '', NULL, 'GX2DJ', -1, 'Midland Railway Centre', 'http://www.midlandrailwaycentre.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(45, '', NULL, 'GB3BR', -1, 'Bluebell Railway', 'http://www.bluebell-railway.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(49, '', NULL, 'GB2LHR', -1, 'Lakeside & Haverthwaite Railway', 'http://www.lakesiderailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(50, '', NULL, 'GB4HSR', -1, 'Havenstreet Steam Railway ', 'http://www.iwsteamrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(51, '', NULL, 'GW4BRS', -1, 'The Vale of Glamorgan Railway ', '', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2008'),
	(53, '', NULL, 'GB2EVR', -1, 'Eden Valley Railway', 'http://www.evr.org.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(54, '', NULL, 'GB4CHC', -1, 'Crewe Heritage Centre', 'http://www.creweheritagecentre.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(55, '', NULL, 'GB0WHR', -1, 'Welsh Highland Heritage Railway', 'http://www.whr.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(56, '', NULL, 'GB0HBL', -1, 'The Helston Railway Preservation Society', 'http://www.helstonrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(57, '', NULL, 'GB4DSR', -1, 'Darlington Head of Steam', 'http://www.darlington.gov.uk/Culture/headofsteam/welcome.htm', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(58, '', NULL, 'GB2LWR', -1, 'Lincolnshire Wolds Railway, Ludborough', 'http://www.lincolnshirewoldsrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(59, '', NULL, 'GB4IWR', -1, 'Isle of Wight Steam Railway', 'http://www.iwsteamrailway.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(60, '', NULL, 'GB0WRH', -1, 'Waverley Route Heritage Assoc.', 'http://www.wrha.org.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(61, '', NULL, 'GB0GWR', -1, 'Gloucestershire Warwickshire Railway', 'http://www.gwsr.com/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(70, '', NULL, 'GB1NNR', -1, 'North Norfolk Railway - Sheringham', 'http://www.nnrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(63, '', NULL, 'GB4BLR', -1, 'Battlefield Line Railway', 'http://homepage.ntlworld.com/candj_simmons/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(64, '', NULL, 'gb2hsc', -1, 'hollycombe steam collection', 'http://www.Hollycombe.com', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(65, '', NULL, 'GB5RSR', -1, 'RIBBLE STEAM RAILWAY', 'http://GB5RSR.MULTIPLY.COM', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(66, '', NULL, 'G8OGJ', -1, 'Peak Rail', 'http://www.peakrail.co.uk/index.htm', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(67, '', NULL, 'GB2RER', -1, 'Ravenglas & Eskdale Railway', 'http://www.ravenglass-railway.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(68, '', NULL, 'GB2LHR', -1, 'Lakeside & Haverthwaite', 'http://www.lakesiderailway.co.uk/pages/home.html', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(69, '', NULL, 'GB4WLR', -1, 'west lancs railway', 'http://www.westlancs.org', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(72, '', NULL, 'GB4DFR', -1, 'Dean Forest Railway', 'http://www.deanforestrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(71, '', NULL, 'GB2NNR', -1, 'North Norfolk Railway - Holt', 'http://www.nnrailway.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(73, '', NULL, 'GB2CR', -1, 'Corris Railway', 'http://corris.co.uk', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(74, '', NULL, 'GB50BR', -1, 'Bluebell Railway', '', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(75, '', NULL, 'GM3TKV', -1, 'Dufftown Keith Railway', 'http://www.keith-dufftown-railway.co.uk/', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(76, '', NULL, 'GB400IYA', -1, 'SIDMOUTH OBSERVATORY FUNICULAR RAILWAY', 'http://projects.exeter.ac.uk/nlo/tour/welcome.htm', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(77, '', NULL, 'GB2ESR', -1, 'East Somerset Railway', 'http://www.eastsomersetrailway.com', NULL, NULL, NULL, NULL, '', '2010-03-26 18:59:21', NULL, '2009'),
	(78, 'Weston-Super-Mare Radio Society', 'http://www.radioclubs.net/wsmrs', 'GB2ESR', 1, 'East Somerset Railway', 'http://www.eastsomersetrailway.com/', 'ST64', 'IO81SE', 51.187500, -2.458333, 'stephen.cole@amserve.com', '2010-04-24 23:21:07', NULL, '2010'),
	(79, 'Preston ARS', 'http://www.prestonars.co.uk/', 'GB4WLR', 1, 'Westlancs Railway', 'http://www.westlancs.org/', 'SD42', 'IO83NQ', 53.687500, -2.875000, 'g1pie@btinternet.com', '2010-04-24 23:20:23', NULL, '2010'),
	(81, 'Bishop Auckland Radio Amateur Club', 'http://barac.m0php.net/', 'GB2EVR', 1, 'Eden Valley Railway', 'http://evr-cumbria.org.uk', 'NZ 71', 'IO84TM', 54.535786, -2.381689, 'g0nrk@teesdaleonline.co.uk', '2010-04-24 23:19:27', NULL, '2010'),
	(82, 'Crewe Heritage Centre ARS', NULL, 'GB4CHC', 1, 'Crewe Heritage Centre', 'http://www.creweheritagecebtre.co.uk', 'SJ75', 'IO83SC', 53.104168, -2.458333, 'johndalgliesh@talktalk.net', '2010-09-03 09:08:13', NULL, '2010'),
	(83, 'Hoover ARC/Jimmy Sneddon', 'http://www.radioclubs.net/hooverarc/', 'GB0BHR', 1, 'Blaenavon Heritage Railway', 'http://www.pontypool-and-blaenavon.co.uk/', '', NULL, NULL, NULL, 'mw0eql@googlemail.com', '2010-09-03 09:07:34', NULL, '2010'),
	(124, 'Norwegian Expedition Club', 'http://qsl.no', 'LA1NEC', 1, 'Sørlandsbanen (RAILWAY TO THE SOUTH)', '', '', 'JO49OI', 59.354168, 9.208333, 'frode@qsl.no', '2010-09-25 19:28:54', NULL, '2010'),
	(121, 'Brickfields ARS', 'http://www.b-a-r-s.org.uk/', 'GB4IWR', 1, 'Isle of Wight Steam Railway', 'http://www.iwsteamrailway.co.uk/', 'SZ58', 'IO90JQ', 50.687500, -1.208333, 'Doug_Fenna@hotmail.com', '2010-09-24 08:48:29', NULL, '2010'),
	(85, 'Brickfields ARS', 'http://www.b-a-r-s.org.uk/', 'GB4IWR', 1, 'Isle of Wight Steam Railway', 'http://www.iwsteamrailway.co.uk/', 'SZ58', 'IO90CQ', 50.687500, -1.791667, 'colinsymons@onwight.net', '2010-05-11 09:39:45', NULL, '2010'),
	(86, 'West Bromwich Central Radio Club', '', 'GB8CR', 1, 'Chasewater Railway', 'http://www.chasewaterrailway.co.uk/', 'SK00', 'IO92AP', 52.645832, -1.958333, 'G0KRK@HOTMAIL.CO.UK', '2010-09-19 14:49:33', NULL, '2010'),
	(87, 'Railway Preservation Society of Ireland Group', 'http://www.steamtrainsireland.com', 'GB2STI', 0, 'Railway Preservation Society of Ireland', 'http://www.steamtrainsireland.com', 'J49', 'IO74DS', 54.770832, -5.708333, 'gi0kpf@googlemail.com', '2010-09-19 14:52:57', NULL, '2010'),
	(88, 'Cheltenham Amateur Radio Association', 'http://www.caranet.co.uk', 'GB0GWR', 1, 'Gloucestershire Warwickshire Railway', 'http://www.gwsr.com/', 'SO92', 'IO81XW', 51.937500, -2.041667, 'chairman@caranet.co.uk', '2010-06-24 09:10:13', NULL, '2010'),
	(89, 'Gavin Keegan', NULL, 'GB 50 BR', 0, 'Bluebell Railway', 'http://www.bluebell-railway.co.uk', 'TQ32', 'IO91XB', 51.062500, -0.041667, 'gavin.keegan@tiscali.co.uk', '2010-09-03 09:08:23', NULL, '2010'),
	(90, 'Dave Greenhalgh', '', 'GB2KSE', 0, 'Stainmore Railway Company', 'http://www.kirkbystepheneast.co.uk', 'NY70', 'IO84TL', 54.479168, -2.375000, 'dgre24@hotmail.com', '2010-07-16 10:06:42', NULL, '2010'),
	(91, 'D.Lawrence', '', 'GB2HSC', 0, 'Hollycombe Steam Collection', 'http://Hollycombe.com', 'not Known', NULL, NULL, NULL, 'rown89@btinternet.com', '2010-07-30 18:49:49', NULL, '2010'),
	(92, 'Newquay And District Amateur Radio Society', 'http://www.binternet.com/~kevin.francks/', 'GB0LVR', 1, 'Lappa Valley Railway', 'http://www.lappavalley.co.uk/', '', 'IO70LI', 50.354168, -5.041667, 'Kneebone@kfamily.freeserve.co.uk', '2010-08-05 17:28:30', NULL, '2010'),
	(93, 'Poldhu Amateur Radio Club', 'http://www.gb2gm.org', 'GB0HBL', 1, 'The Helston Branch Line', 'http://www.helstonrailway.co.uk/', 'SW63', NULL, NULL, NULL, 'les@ltjnet.demon.co.uk', '2010-08-04 20:22:37', '4c59bde56acbf', '2010'),
	(94, 'Poldhu Amateur Radio Club', 'http://www.gb2gm.org', 'GB0HBL', 1, 'The Helston Branch Line', 'http://www.helstonrailway.co.uk', 'SW63', NULL, NULL, NULL, 'les@ltjnet.demon.co.uk', '2010-08-04 20:25:00', '4c59be74536d3', '2010'),
	(95, 'Poldhu Amateur Radio Club', 'http://www.gb2gm.org', 'GB0HBL', 1, 'The Heston branch Line', 'http://www.helstonrailway.co.uk', 'SW63', NULL, NULL, NULL, 'les@ltjnet.demon.co.uk', '2010-08-04 20:37:00', '4c59c144cf51d', '2010'),
	(96, 'Poldhu Amateur Radio Club', 'http://www.gb2gm.org', 'GB0HBL', 1, 'The Helston Branch Line', 'http://www.helstonrailway.co.uk', 'SW63', NULL, NULL, NULL, 'les@ltjnet.demon.co.uk', '2010-08-04 20:41:10', '4c59c23e920c5', '2010'),
	(97, 'Poldhu Amateur Radio Club', 'http://www.gb2gm.org', 'GB0HBL', 1, 'The Helston Branch Line', 'http://www.helstonrailway.co.uk', 'SW63', NULL, NULL, NULL, 'les@ltjnet.demon.co.uk', '2010-08-05 09:44:42', NULL, '2010'),
	(122, 'Chepstow and District Amateur Radio Society', 'http://www.gw4lwz.org.uk', 'G4LWZ', 1, 'Dean Forest Railway', 'http://www.deanforestrailway.co.uk', '', NULL, NULL, NULL, 'paul.dekkers@tiscali.co.uk', '2010-09-24 08:48:14', NULL, '2010'),
	(100, 'Craven Radio Amateur Group', 'http://www.mx0bcq.weebly.com', 'MX0BCQ/A', 1, 'Embsay & Bolton Abbey Steam Railway', 'http://www.embsayboltonabbeyrailway.org.uk', 'SE05', 'IO93BX', 53.979168, -1.875000, 'm0xltradioham@gmail.com', '2010-08-06 20:39:17', '4c5c64cde3bdd', '2010'),
	(101, 'HARES Hinckley Amateur Radio & Electronics Society', 'http://www.hares.org.uk/gb4blr.htm', 'GB4BLR', 1, 'Battlefield Line Railway - Shackerstone Station', 'http://www.battlefield-line-railway.co.uk/', 'SK30', 'IO92GP', 52.645832, -1.458333, 'm0jav@lowgables.co.uk', '2010-08-09 15:33:23', NULL, '2010'),
	(102, 'Furness Amateur Radio Society', '', 'GB2LHR', 1, 'Lakeside & Haverthwaite Steam Railway', 'http://www.lakesiderailway.co.uk/', 'sd38', 'IO84MF', 54.229168, -2.958333, 'g4usw1@sky.com', '2010-09-19 14:52:08', NULL, '2010'),
	(103, 'Furness Amateur Radio Society', '', 'GB2RER', 1, 'Ravenglass & Eskdale Railway', 'http://www.ravenglass-railway.co.uk/', 'SD09', 'IO84HI', 54.354168, -3.375000, 'g4usw1@sky.com', '2010-09-19 14:52:08', NULL, '2010'),
	(104, 'Derek Morrison Smith', '', 'GB2CR', 0, 'Corris Railway', 'http://www.corris.co.uk', '', 'IO82BP', 52.645832, -3.875000, 'morrisonsmith@btinternet.com', '2010-08-11 22:48:22', NULL, '2010'),
	(105, 'Grimsby ARS', 'http://www.gars.org.uk', 'GB2LWR', 1, 'Lincolnshire Wolds Railway', 'http://www.lincolnshirewoldsrailway.co.uk', 'TF39', NULL, NULL, NULL, 'g4yhp@gars.org.uk', '2010-08-15 18:42:37', NULL, '2010'),
	(106, 'Central Lancs. Amateur Radio Club', 'http://www.ribblesteam.org.uk', 'GB5RSR', 1, 'Ribble Steam Railway', 'http://www.ribblesteam.org.uk', 'SD 52', 'IO83QR', 53.729168, -2.625000, 'G3UCA@BLUEYONDER.CO.UK', '2010-09-19 14:48:51', NULL, '2010'),
	(107, 'Peterborough & DARC', 'http://www.radioclubs.net/padarc', 'GB0NVR', 1, 'Nene Valley Railway', 'http://www.nvr.org.uk/', 'TO09', 'IO92TN', 52.562500, -0.375000, 'padarc@tesco.net', '2010-09-09 22:11:08', 'withdrawn', '2010'),
	(108, 'Stephen Harrison', '', '2E0 NKF', 0, 'Middleton Railway Leeds', '', '', NULL, NULL, NULL, '2e0nkf@hotmail.com', '2010-09-19 14:50:20', NULL, '2010'),
	(109, 'Alan Dale (Notre Dame ARC)', '', 'GB0WRR', 0, 'Whitwell & Reepham Railway', 'http://www.whitwellstation.com/default.asp', 'TG02', 'JO02NS', 52.770832, 1.125000, 'm0lsx@yahoo.co.uk', '2010-08-24 11:34:21', NULL, '2010'),
	(110, 'St.Tybie Amateur Radio Society', 'http://www.radioclubs.net/llandybiears/', 'GB0AVR', 1, 'Amman Valley Railway', 'http:///avrail.webplus.net/', 'SN61', 'IO81AT', 51.812500, -3.958333, 'gw4jpc@yahoo.co.nz', '2010-08-27 20:20:08', NULL, '2010'),
	(111, 'Galashiels and District Amateur Radio Society', 'http://www.galaradioclub.co.uk', 'GB0WRH', 1, 'Waverley Route Heritage Association', 'http://www.wrha.org.uk', '', NULL, NULL, NULL, 'mail@gm7lun.co.uk', '2010-08-29 17:52:40', NULL, '2010'),
	(112, 'Northeast Special Events Group', 'http://kenradio.co.uk', 'GB4MSM', 1, 'Monkwearmouth Station Musuem', '', '', NULL, NULL, NULL, 'kenradio@btinternet.com', '2010-08-29 17:49:22', NULL, '2010'),
	(125, 'Bishop Auckland Radio Amateur Club', 'http://barac.m0php.net', 'GB2EVR', 1, 'Eden Valley Railway', 'http://www.evr-cumbria.org.uk', 'NY71 EDEN', NULL, NULL, NULL, 'bdingle@hotmail.co.uk', '2011-09-18 22:46:44', NULL, '2011'),
	(114, 'Brigg and District Amateur Radio Club', 'http://www.bdarc.co.uk/', 'GB2NPR', 1, 'Normaby Park Railway', 'http://www.northlincs.gov.uk/NorthLincs/leisure/museums/normanbyhall/', 'SE88', 'IO93QP', 53.645832, -0.625000, 'gb2npr@bdarc.co.uk', '2010-09-01 11:13:49', NULL, '2010'),
	(115, 'Derby & D.A.R.S.', 'http://www.dadars.org.uk', 'GX3ERD/P', 1, 'Princess Royal Class Locomotive Trust', 'http://www.prclt.co.uk', 'SK43  (SK 410 318)', 'IO93HB', 53.062500, -1.375000, 'g1vab@hotmail.co.uk', '2010-09-02 09:29:16', NULL, '2010'),
	(116, 'John Akinin & Friends', 'http://www.m0gwr.co.uk', 'GB0GCR', 0, 'The Great Central Railway (Nottingham)', 'http://www.nthc.co.uk/', 'SK53', 'IO92KV', 52.895832, -1.125000, 'jp.radioroom@ntlworld.com', '2010-09-04 22:34:06', NULL, '2010'),
	(117, 'Craven Radio Amateur Group', 'http://www.mx0bcq.weebly.com', 'MX0BCQ/A', 1, 'Embsay & Boton Abbey Steam Railway', 'http://www.embsayboltonabbeyrailway.org.uk', 'SE05', 'IO93BX', 53.979168, -1.875000, 'm0xltradioham@gmail.com', '2010-09-10 10:52:58', '4c89ffe226c8f', '2010'),
	(118, 'Bittern DX Group', 'http://www.bittern-dxers.org.uk', 'GB2NNR', 1, 'The North Norfolk Railway - Holt Station', 'http://www.nnrailway.co.uk/', 'TG03', 'JO02NV', 52.895832, 1.125000, 'keith@g0gfq.com', '2010-09-16 15:29:27', NULL, '2010'),
	(123, 'Matt Lynn', '', 'M0LYI', 0, 'Weardale Railway', 'http://www.weardale-railway.org.uk/', '', 'IO84XR', 54.729168, -2.041667, 'atravellingmatt@aol.com', '2010-09-24 08:48:21', NULL, '2010'),
	(126, 'South Bristol Amateur Radio Club', 'http://www.sbarc.co.uk', 'GB0SDR', 1, 'Somerset & Dorset Railway Heritage Trust', 'http://www.sdjr.co.uk', 'TBC', NULL, NULL, NULL, 'info@sbarc.co.uk', '2011-08-01 20:31:35', NULL, '2011'),
	(127, 'Mid Sussex ARS / Gavin Keegan', 'http://msars.org.uk', 'G6DGK', 1, 'Bluebell Railway', 'http://bluebellrailway', '', NULL, NULL, NULL, 'gavin.keegan@tiscali.co.uk', '2011-01-07 14:30:03', NULL, '2011'),
	(128, 'Bredhurst Receiving and Transmitting Society', 'http://www.brats-qth.org/', 'G0BRC', 1, 'Bredgar & Wormshill Light Railway', 'http://www.bwlr.co.uk/', 'Kent', NULL, NULL, NULL, 'charles@darleys.co.uk', '2011-01-21 08:24:50', NULL, '2011'),
	(129, 'Crewe Herritage Centre A.R.C.', 'http://GB4CHC.co.uk', 'GB4CHC', 1, 'Crewe Heritage Centre', 'http://www.creweheritagecentre.co.uk', 'SJ65', 'IO83SC', 53.104168, -2.458333, 'johndalgliesh@talktalk.net', '2011-02-05 19:28:02', NULL, '2011'),
	(130, 'Cheltenham Amateur Radio Association', 'http://www.caranet.co.uk', 'GB0GWR', 1, 'Gloucestershire Warwickshire Railway', 'http://www.gwsr.com', 'SO92', 'IO81XW', 51.937500, -2.041667, 'g3nks@blueyonder.co.uk', '2011-03-10 09:22:43', NULL, '2011'),
	(131, 'Central Lancs. Amateur Radio Club', 'http://www.ribblesteam.org.uk', 'GB5RSR', 1, 'Ribble Steam Railway', 'http://www.g0fdx.multiply.com', 'SD 52', 'IO83QR', 53.729168, -2.625000, 'g3uca@blueyonder.co.uk', '2011-09-18 22:38:22', NULL, '2011'),
	(132, 'Cliff Jobling G4YHP - Grimsby ARS', 'http://www.gars.org.uk', 'GB2LWR', 1, 'Lincolnshire Wolds Railway', 'http://www.lincolnshirewoldsrailway.co.uk', 'TF39', 'IO93XK', 53.437500, -0.041667, 'g4yhp@gars.org.uk', '2011-04-07 17:56:03', NULL, '2011'),
	(133, 'Derby & District ARS', 'http://www.dadars.org.uk', 'GX3ERD/P', 1, 'Princess Royal Class Locomotive Trust', 'http://www.prclt.co.uk', 'SK43', 'IO93HB', 53.062500, -1.375000, 'g1vab@hotmail.co.uk', '2011-05-19 08:44:13', NULL, '2011'),
	(134, 'Northeast Special Events Group', 'http://kenradio.co.uk', 'GB4MSM', 1, 'Monkwearmouth Station Musuem', '', '', NULL, NULL, NULL, 'kenradio@btinternet.com', '2011-06-05 16:55:19', NULL, '2011'),
	(135, 'Hinckley Amateur Radio and Electronics Society', 'http://www.hares.org.uk/index.htm', 'GB4BLR', 1, 'Battlefield Line Railway - Shackerstone Station', 'http://www.battlefield-line-railway.co.uk/', 'SK30', 'IO92GP', 52.645832, -1.458333, 'm0jav@lowgables.co.uk', '2011-06-05 22:05:26', NULL, '2011'),
	(161, 'Brigg and District Amateur Radio Club', 'http://bdarc.co.uk', 'GB2NPR', 1, 'Normanby Park Minature Railway', 'http://homepage.ntlworld.com/wilfred.baker/', 'SE88', 'IO93QP', 53.645832, -0.625000, 'leemcga@gmail.com', '2011-09-21 09:16:01', NULL, '2011'),
	(137, 'Southgate Amateur Radio Club', 'http://www.southgatearc.org', 'GB0TLR', 1, 'Tyttenhanger Light Railway', 'http://www.nlsme.co.uk', 'TL', NULL, NULL, NULL, 'g8rpa@arrl.net', '2011-06-13 20:37:49', NULL, '2011'),
	(138, 'Norfolk Amateur Radio Club', 'http://www.norfolkamateurradio.org', 'GB1NNR', 1, 'North Norfolk Railway (NB jointly with Bittern DX Group)', 'http://www.nnr.co.uk', '', NULL, NULL, NULL, 'radio@dcpmicro.com', '2011-08-16 23:17:40', NULL, '2011'),
	(139, 'Craven Radio Amateur Group', 'http://www.mx0bcq.weebly.com', 'MX0BCQ/A', 1, 'Embsay & Bolton Abbey Steam Railway', 'http://www.embsayboltonabbeyrailway.org.uk', 'SE05', 'IO93BX', 53.979168, -1.875000, 'mx0bcq@gmail.com', '2011-06-22 16:12:17', NULL, '2011'),
	(140, 'Jack, Mark, Pam', 'http://www.qrz.com/db/gb5wvr', 'GB5WVR', 0, 'Worth Valley Railway', 'http://www.kwvr.co.uk', 'SE03', NULL, NULL, NULL, 'g1pie@btinternet.com', '2011-09-18 22:38:22', NULL, '2011'),
	(141, 'Poldhu Amateur Radio Club', 'http://gb2gm.org', 'GB0HBL', 1, 'Helston Branch Line', 'http://www.helstonrailway.co.uk/', 'SW63', 'IO70ID', 50.145832, -5.291667, 'les@ltjnet.demon.co.uk', '2011-07-13 12:33:41', NULL, '2011'),
	(142, 'Weston-super-Mare Radio Society', 'http://www.radioclubs.net/wsmrs', 'GX4WSM', 1, 'East Somerset Railway', '', 'ST64', 'IO81SE', 51.187500, -2.458333, 's.k.cole@btinternet.com', '2011-09-18 22:26:45', NULL, '2011'),
	(143, 'Newquay And District Amateur Radio Society', 'http://www.qsl.net/g4adv/', 'GB0LVR', 1, 'Lappa Valley Railway', 'http://www.lappavalley.co.uk', 'SW86', 'IO70LI', 50.354168, -5.041667, 'kneebone@kfamily.freeserve.co.uk', '2011-07-19 18:43:33', NULL, '2011'),
	(144, 'Leicester Radio Society', 'http://www.g3lrs.co.uk', 'GB2MBS', 1, 'Battlefield Line, Market Bosworth Station', 'http://www.battlefield-line-railway.co.uk', 'SK30', 'IO92GO', 52.604168, -1.458333, 'john.g0ijm@btinternet.com', '2011-08-16 19:15:01', NULL, '2011'),
	(145, 'Bittern DX Group', 'http://www.bittern-dxers.org.uk', 'GB2NNR', 1, 'North Norfolk Railway', 'http://www.nnrailway.co.uk', 'TG03', 'JO02NV', 52.895832, 1.125000, 'web@bittern-dxers.org.uk', '2011-08-17 22:05:13', NULL, '2011'),
	(146, 'Furness ARS and Workington ARITG', '', 'GB2RER', 1, 'Ravenglass and Eskdale Railway', '', 'SD08', 'IO84HI', 54.354168, -3.375000, 'norman.m0crm@btinternet.com', '2011-09-18 22:34:16', NULL, '2011'),
	(147, 'Furness Amateur Radio Society', '', 'GB2LHR', 1, 'Lakeside & Havethwaite Railway', 'http://www.lakesiderailway.co.uk/', 'SD38', 'IO84MF', 54.229168, -2.958333, 'g4usw1@sky.com', '2011-08-25 16:27:44', NULL, '2011'),
	(148, 'John Akinin & Friends', 'http://www.om3kwt.net/gb0gcr/', 'GB0GCR', 0, 'GC Railway, Nottingham', 'http://217.158.157.207/railways/GCRN/index.htm', 'SK53', 'IO92KV', 52.895832, -1.125000, 'jp.radioroom@ntlworld.com', '2011-09-02 08:52:33', NULL, '2011'),
	(149, 'D Lawrence', '', 'GB2HSC', 0, 'Hollycombe Steam Collection', '', '', NULL, NULL, NULL, 'crown89@btinternet.com', '2011-09-18 22:38:22', NULL, '2011'),
	(150, 'd lawrence', '', 'GB2HSC', 1, 'hollycombe steam collection', '', '', NULL, NULL, NULL, 'crown89@btinternet.com', '2011-09-18 22:38:22', 'X', '2011'),
	(151, 'Radio Society of Harrow', 'http://g3efx.org.uk', 'GB4GWR', 1, 'GWR Preservation Group (Southall Railway Centre)', 'http://www.gwrpg.co.uk/', '', 'IO91TM', 51.520832, -0.375000, 'g0cag@hotmail.com', '2011-09-18 22:38:22', NULL, '2011'),
	(152, 'Chepstow and District Amateur Radio Society', 'http://www.gw4lwz.org.uk/', 'G4LWZ/P', 1, 'Dean Forest Railway', 'http://www.deanforestrailway.co.uk/', 'SO60', 'IO81RR', 51.729168, -2.541667, 'secretary@gw4lwz.com', '2011-09-18 22:29:09', NULL, '2011'),
	(153, 'Bangor & District Amateur Radio Society', 'http://www.bdars.com', 'GN3XRQ', 1, 'Drumawhey Miniature Railway', 'http://bcdmrs.org.uk', '', NULL, NULL, NULL, 'bill.langtry@btinternet.com', '2011-09-06 11:33:34', NULL, '2011'),
	(154, 'Moorlands and District ARS', 'http://www.madars.co.uk', 'G1MAD', 1, 'Foxfield Railway', 'http://www.foxfieldrailway.co.uk/', 'SJ94 England', 'IO82XX', 52.979168, -2.041667, 'g6kte@btinternet.com', '2011-09-06 20:35:00', NULL, '2011'),
	(155, 'St Tybie Amateur Radio Society', 'http://www.clubbz.com/club/6522/ammanford/st.-tybie-amateur-radio-society', 'GB0AVR', 1, 'Amman Valley Railway', 'http://www.ammanvalleyrail.netfirms.com', 'SN 61', 'IO81AT', 51.812500, -3.958333, 'gw4jpc@yahoo.co.nz', '2011-09-07 06:25:40', NULL, '2011'),
	(156, 'Furness ARS and Workington ARITG', '', 'GB5RER', 1, 'Ravenglass and Eskdale Railway', '', 'SD10', 'IO84IJ', 54.395832, -3.291667, 'norman72@btinternet.com', '2011-09-18 22:34:16', NULL, '2011'),
	(157, 'ILZ-TAL-BAHN Railways', 'http://www.qrz.com/dr11itb', 'DR11 ITB', 1, 'ILZ Valley Railways', 'http://www.qrz.com/dr11itb', '', 'JN68US', 48.770832, 13.708333, 'bkm.barth@gmx.de', '2011-09-18 22:38:22', NULL, '2011'),
	(158, 'Furness ARS and Workington ARITG', '', 'GB5RER', 1, 'Ravenglass and Eskdale Railway', '', 'SD10', NULL, NULL, NULL, 'norman72@btinternet.com', '2011-09-18 22:34:16', 'X', '2011'),
	(159, 'Amberley Museum Radio Club', 'http://www.amberleymuseum.co.uk', 'GB2CPM', 1, 'Amberley Narrow Gauge Railway', 'http://www.amberleynarrowgauge.co.uk', 'TQ01', 'IO90RV', 50.895832, -0.541667, 'm6pap@hotmail.co.uk', '2011-09-11 22:04:43', NULL, '2011'),
	(160, 'Brigg And District Amateur Radio Club', 'http://www.bdarc.co.uk', 'GB2NPR', 1, 'Normanby Park Miniture Railway', 'http://homepage.ntlworld.com/wilfred.baker/', 'SE88', 'IO93QP', 53.645832, -0.625000, 'leemcga@gmail.com', '2011-09-16 11:06:03', NULL, '2011'),
	(162, 'Preston ARS', '', 'GB0LMR', 1, 'Worden Park Miniature Railway', '', '', NULL, NULL, NULL, 'theboggarts@aol.com', '2011-09-23 20:15:01', NULL, '2011'),
	(163, 'Brickfields ARS', 'http://www.b-a-r-s.org.uk/', 'GB4IWR', 1, 'Isle of Wight Steam Railway', 'http://www.iwsteamrailway.co.uk/', 'SZ58', 'IO90CQ', 50.687500, -1.791667, 'davidabear@btinternet.com', '2011-09-27 17:27:57', NULL, '2011'),
	(164, 'Bishop Auckland Amateur Radio Club G4TTF', 'http://rota.m0php.net/stations', 'GB2EVR', 1, 'Eden Valley Railway    WARCOP', 'http://www.evr-cumbria.org.uk', 'NY 71 EDEN', 'IO84TM', 54.520832, -2.375000, 'g0nrk@teesdaleonline.co.uk', '2011-11-06 21:07:02', NULL, '2012'),
	(165, 'Mid Sussex ARS/Gavin Keegan', 'http://msars.org.uk', 'GB2BR', 1, 'Bluebell Railway', '', '', NULL, NULL, NULL, 'gavin.keegan@tiscali.co.uk', '2011-11-27 08:17:29', NULL, '2012');
/*!40000 ALTER TABLE `rota_legacy` ENABLE KEYS */;


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

-- Dumping data for table rota.sessions: 92 rows
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
	('02d6c34aad21ce21af3399f23f967b0c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1322350958, ''),
	('7d8568748228b5d1a7d3ef467b1c760a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1322500221, ''),
	('b948b43c246a2c578da1780c13e82a01', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC2; .NET', 1322693374, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"2";s:5:"email";s:27:"craig.rodway+user@gmail.com";s:4:"type";s:4:"user";}'),
	('4be3b46e492b0f8fa29ef82b56a38c18', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ', 1322693415, ''),
	('cfbd2b9c36b8113e610cd27e6b571a74', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1322953305, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}'),
	('2a4c83682ef46cc78388fec4be111857', '127.0.0.1', 'Opera/9.80 (Windows NT 6.1; U; Edition United Kingdom Local; en) Presto/2.9.168 Version/11.52', 1322870306, ''),
	('b46e5046e6db0744d2fd85367853b3e1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1323734328, ''),
	('2b6e57bd39a49489d64e4b55b95322b7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.0', 1323461490, ''),
	('857254e0223794c8be9e627f40589413', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1323028350, ''),
	('ba5dd76dc576fe08eaf0fb61f28c065b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1324167435, ''),
	('2da0a8cd580bebd90d67fcfd77e838bc', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.63 Safari/535.7', 1326061720, ''),
	('eee04bdf71bd99fcb2b35df71c56ce5a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1326836696, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}'),
	('356120da27da7033d827188b59dcdd68', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327535949, ''),
	('a240ad84f8b8c7aceb4a89444f0162f9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327704437, 'a:5:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";s:11:"redirect_to";s:42:"http://rota.local/index.php/admin/railways";}'),
	('5410a2232c67b0d2ce4886c0548befc2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327704438, ''),
	('ce12a61bbf57f79c47dd6ea51deea777', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328831322, ''),
	('ffa55abb3df0bfc2833058707fc1284c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329083507, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}'),
	('c95e338c5ac066f71c583da756953c6f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1330889819, ''),
	('42cde5348562421d1e94ef927eca8404', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911433, ''),
	('cc5d2b72692041201c9684d781c1d1d4', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911436, ''),
	('1af0c5f804200aca12b5edaf09d5f39c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911438, ''),
	('e4c00457b79cb30401b3cbc8f0d8edc3', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911442, ''),
	('19b05a5e2b5f05acfcbd88d9331bdac8', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911444, ''),
	('e48390c67127750d84162516a62efaad', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911449, ''),
	('db56083ce4484a598ddb560eb41eec6b', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911452, ''),
	('42306b4ffb2d126a16ed2546b98d0943', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911456, ''),
	('4a8b62aef510f3dad1f058f95f037488', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911457, ''),
	('b3a4399f2a983a0def5f5f12dc90d4ce', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911458, ''),
	('ec3c27ebcb03173c886c19e2b6f7f8f0', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911473, ''),
	('bb6c1f1b30c2b7fa24b4f98898881976', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911488, ''),
	('73369c26674c6146a4d0a51f23451f8a', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911489, ''),
	('5693932bdffe2f1b5e1edfda8be3187c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911490, ''),
	('3decc541dd110513e3189a8a4850b0b9', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911491, ''),
	('39c27024de427ec707f9c57b413118c7', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911492, ''),
	('2a59fc8bfc67468da9ff5495cfe9732c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911494, ''),
	('8e0b61d1d03ff5a482c8fbc48d266fd4', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911497, ''),
	('95ed6e53e479afe91ee7b070534d89b7', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911498, ''),
	('e419f0296e842cdcec198b9a4b019214', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911499, ''),
	('a5780739b3c958db4e314e01e211affa', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911500, ''),
	('cc91763bac0f9c53fb90b736aef381e7', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911501, ''),
	('bb332f875b190338b0fd7fdbf99ea49d', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911503, ''),
	('67b9733724b4791d3e53209b5aaa04e8', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911503, ''),
	('10922455294c50a0283ba8a1a6386565', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911505, ''),
	('e26c2e01ecf09408892b88908ae32777', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911506, ''),
	('879fdbf6ea9726858094b12899698f2b', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911507, ''),
	('f0e371e7477f3b99f84db3b2d3dd5fd0', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911508, ''),
	('7550aef6bdbf87c70ee88fb01c212979', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911509, ''),
	('3517fc1f611b31dd3886472051f4336c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911510, ''),
	('167f57650b39d085756815174dd4fda5', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911512, ''),
	('18bfcd4a73e71daa1a1a8551af465878', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911512, ''),
	('35fd03c9aa80464036e1dade0b9d2b89', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911513, ''),
	('c85029627071a42350028f23cbf531fe', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911517, ''),
	('326a676dc7b90076e8a546d2e1248f02', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911518, ''),
	('b3c19922668d6acf7d806be3afda9063', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911519, ''),
	('e73bd39d5d3b2e0147e838b547a528a0', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911520, ''),
	('8b7486c0cc14313a44acbc8e4fe69a01', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911521, ''),
	('70a4b1ed33871291597a3775c13446e9', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911522, ''),
	('f426af8ea91b1baaadb26b8978b94631', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911523, ''),
	('ac217c6a0722103eb3dfba5c4c52b6ca', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911523, ''),
	('44bfd20fc738ffd8c22329f59ba5dd4c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911524, ''),
	('3c819b005aba95d5f8a75526635ba2ef', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911525, ''),
	('0efa0090086b15ebff009982e852347d', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911526, ''),
	('e020768ff82dc481bd9c528097bcc669', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911528, ''),
	('7528ae5abf1e4664438ab9564c3d469c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911529, ''),
	('fc8dfe1fa89a46e011f8ed387e6601d6', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911530, ''),
	('9e6ea7077e5d60a9dff59173a018ce5b', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911531, ''),
	('a6f558672f167dd9b134e28717334beb', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911533, ''),
	('667066232c4720134a4016cd9a8a47ec', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911534, ''),
	('40ac18a22f677117c6ee34b38131c199', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911536, ''),
	('df746168ae2e75c2d16a28c5a109db25', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911537, ''),
	('4e7658d9c1c504ca9859643fb0e0d38b', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911539, ''),
	('5078ea14eec50a46a642a7f9eb23b7e2', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911540, ''),
	('29feac04b54d031e0bade822e1aec582', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911541, ''),
	('06377ca0bdf41afbac81e7706c86adf7', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911544, ''),
	('949dc1be62042fa00b4b5b3da1a16f30', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911545, ''),
	('371cdaa35caaa13ac0068c90fd24dca6', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911551, ''),
	('59c44aa276a2658b8a7cde03bfd3018a', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911551, ''),
	('e6d218b7f310035ecbb368e1f5328bf7', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911553, ''),
	('94d7967daa14568823b12c5067e9d34c', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911554, ''),
	('b8784cff4d599645110d75f9b5e0d414', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911555, ''),
	('f5046170a71f9b2920604d2278250c58', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911561, ''),
	('c8d8ec2916b357070a0a21c59f2e9230', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 1329911562, ''),
	('31293f379691859a2a47ec590b115dc2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.78 Safari/535.11', 1331422307, ''),
	('ff80ad0adac951e3622b8b3949e8a0e1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.83 Safari/535.11', 1332792377, ''),
	('b47c14a781a86183e0a00f879377253e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.142 Safari/535.19', 1333665541, ''),
	('dc5d75dcea1862a35168c09a34b03bc2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.151 Safari/535.19', 1334263046, ''),
	('c77048b38a16327bd918048e8d020462', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.152 Safari/535.19', 1334441551, 'a:5:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";s:17:"flash:old:success";s:27:"You are now been logged in!";}'),
	('72bc683af011461e55cc91f23f9ff1a1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.46 Safari/536.5', 1337545557, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}'),
	('87d1531869499681e74007b272251921', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 1338242865, ''),
	('abbe137a13b27d8f02f775c0cc9340e5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 1338501606, ''),
	('1bcca5c470b146e01ca29db8a117686d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 1338835963, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}'),
	('6200254a583380aa1a40ed253ec560d0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5', 1338851911, 'a:4:{s:9:"user_data";s:0:"";s:10:"account_id";s:1:"1";s:5:"email";s:22:"craig.rodway@gmail.com";s:4:"type";s:5:"admin";}');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;


-- Dumping structure for table rota.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table rota.settings: 1 rows
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`name`, `value`) VALUES
	('email_from', 'no-reply@barac.m0php.net');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


-- Dumping structure for table rota.stations
CREATE TABLE IF NOT EXISTS `stations` (
  `s_e_year` year(4) NOT NULL,
  `s_r_id` int(11) unsigned NOT NULL,
  `s_o_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`s_e_year`,`s_r_id`,`s_o_id`),
  KEY `r_id` (`s_r_id`),
  KEY `o_id` (`s_o_id`),
  CONSTRAINT `stations_ibfk_7` FOREIGN KEY (`s_e_year`) REFERENCES `events` (`e_year`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stations_ibfk_5` FOREIGN KEY (`s_r_id`) REFERENCES `railways` (`r_id`),
  CONSTRAINT `stations_ibfk_6` FOREIGN KEY (`s_o_id`) REFERENCES `operators` (`o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table rota.stations: ~0 rows (approximately)
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
