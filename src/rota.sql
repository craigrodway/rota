-- Adminer 3.3.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'Europe/London';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `a_email` varchar(100) NOT NULL,
  `a_password` varchar(100) DEFAULT NULL,
  `a_created` datetime NOT NULL,
  `a_lastlogin` datetime DEFAULT NULL,
  `a_verify` char(10) DEFAULT NULL,
  `a_enabled` enum('Y','N') NOT NULL DEFAULT 'N',
  `a_type` varchar(10) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `email` (`a_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `accounts` (`a_id`, `a_email`, `a_password`, `a_created`, `a_lastlogin`, `a_verify`, `a_enabled`, `a_type`) VALUES
(1,	'craig.rodway@gmail.com',	'rota#VXixaRjZ4q#e6b73a63533f9a994fdee9bd229b68230690a93a',	'2011-11-27 00:01:42',	'2012-02-08 23:44:41',	NULL,	'Y',	'admin'),
(2,	'craig.rodway+user@gmail.com',	'rota#kYZmXkXLMA#b405464576628a5097c9b7af65d50d5dc50851f4',	'2011-11-30 18:52:00',	'2012-02-08 23:25:09',	NULL,	'Y',	'user');

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `e_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `e_year` year(4) NOT NULL,
  `e_start_date` date NOT NULL,
  `e_end_date` date NOT NULL,
  `e_active` enum('Y','N') NOT NULL,
  PRIMARY KEY (`e_id`),
  UNIQUE KEY `year` (`e_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `operators`;
CREATE TABLE `operators` (
  `o_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `a_id` int(11) unsigned NOT NULL,
  `o_type` enum('club','person') NOT NULL DEFAULT 'club',
  `o_name` varchar(100) NOT NULL,
  `o_callsign` varchar(20) NOT NULL,
  `o_url` varchar(100) DEFAULT NULL,
  `o_info` text NOT NULL,
  PRIMARY KEY (`o_id`),
  KEY `a_id` (`a_id`),
  CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`a_id`) REFERENCES `accounts` (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `railways`;
CREATE TABLE `railways` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `railways` (`r_id`, `r_name`, `r_slug`, `r_url`, `r_info_src`, `r_info_html`, `r_photo`, `r_postcode`, `r_wab`, `r_locator`, `r_lat`, `r_lng`, `r_added_by`, `r_added_at`) VALUES
(2,	'Ribble Steam Railway',	'ribble-steam-railway',	'http://www.ribblesteam.org.uk/',	'',	'',	NULL,	'PR2 2PD',	'SD52',	'IO83OS',	53.760067,	-2.752216,	1,	'2011-12-03 22:40:48'),
(3,	'Kirkby Stephen East',	'kirkby-stephen-east',	'http://www.kirkbystepheneast.co.uk/',	'',	'',	NULL,	'CA17 4LA',	'NY70',	'IO84TL',	54.462536,	-2.357481,	1,	'2011-12-03 22:40:48'),
(4,	'Tomoulin FNQ Australia',	'tomoulin-fnq-australia',	'',	'',	'',	NULL,	'',	'',	'',	0.000000,	0.000000,	1,	'2011-12-03 22:40:48'),
(6,	'Paignton and Dartmouth Steam Railway',	'paignton-and-dartmouth-steam-railway',	'http://www.paignton-steamrailway.co.uk/',	'',	'',	NULL,	'TQ4 6AF',	'',	'IO80FK',	50.434765,	-3.563878,	1,	'2011-12-03 22:40:48'),
(10,	'Leadhills and Wanlockhead Railway',	'leadhills-and-wanlockhead-railway',	'http://www.leadhillsrailway.co.uk/',	'',	'',	NULL,	'ML12 6XP',	'',	'IO85CJ',	55.414841,	-3.761897,	1,	'2011-12-03 22:40:48'),
(11,	'Stephenson Railway Museum',	'stephenson-railway-museum',	'http://www.twmuseums.org.uk/stephenson/',	'',	'',	NULL,	'NE29 8DX',	'',	'IO95GA',	55.016304,	-1.497970,	1,	'2011-12-03 22:40:48'),
(13,	'Lincolnshire Wolds Railway',	'lincolnshire-wolds-railway',	'http://www.lincolnshirewoldsrailway.co.uk',	'',	'',	NULL,	'DN36 5SQ',	'TF39',	'IO93XK',	53.442547,	-0.044010,	1,	'2011-12-03 22:40:48'),
(14,	'Brusselton Incline Engine House, S&D Railway',	'brusselton-incline-engine-house-sd-railway',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	'2011-12-03 22:40:48'),
(18,	'Hollycombe Steam Collection',	'hollycombe-steam-collection',	'http://www.hollycombe.co.uk/',	'',	'',	NULL,	'GU30 7LP',	'',	'IO91OB',	51.060764,	-0.789542,	1,	'2011-12-03 22:40:48'),
(19,	'Waverley Route Heritage Association',	'waverley-route-heritage-association',	'http://www.wrha.org.uk',	'',	'',	NULL,	'TD9 9TY',	'',	'IO85PH',	55.295891,	-2.745615,	1,	'2011-12-03 22:40:48'),
(20,	'Midland Railway Centre',	'midland-railway-centre',	'http://www.midlandrailwaycentre.co.uk',	'',	'',	NULL,	'0',	'',	'IO93HB',	53.059753,	-1.404623,	1,	'2011-12-03 22:40:48'),
(24,	'Vale of Glamorgan Railway',	'vale-of-glamorgan-railway',	'http://en.wikipedia.org/wiki/Vale_of_Glamorgan_Railway',	'',	'',	NULL,	'',	'',	'',	0.000000,	0.000000,	1,	'2011-12-03 22:40:48'),
(27,	'Welsh Highland Heritage Railway',	'welsh-highland-heritage-railway',	'http://www.whr.co.uk',	'',	'',	NULL,	'LL49 9DY',	'',	'IO72WW',	52.930611,	-4.133003,	1,	'2011-12-03 22:40:48'),
(29,	'Darlington Head of Steam',	'darlington-head-of-steam',	'http://www.darlington.gov.uk/headofsteam/',	'',	'',	NULL,	'DL3 6ST',	'',	'IO94FM',	54.535526,	-1.554838,	1,	'2011-12-03 22:40:48'),
(38,	'Peak Rail',	'peak-rail',	'http://www.peakrail.co.uk/',	'',	'',	NULL,	'DE4 2EQ',	'',	'IO93ED',	53.160576,	-1.592140,	1,	'2011-12-03 22:40:48'),
(46,	'Keith and Dufftown Railway',	'keith-and-dufftown-railway',	'http://www.keith-dufftown-railway.co.uk/',	'',	'',	NULL,	'AB55 4BA',	'',	'IO87KK',	57.453568,	-3.132885,	1,	'2011-12-03 22:40:48'),
(47,	'Sidmouth Observatory Funicular Railway',	'sidmouth-observatory-funicular-railway',	'http://projects.exeter.ac.uk/nlo/tour/welcome.htm',	'',	'',	NULL,	'',	'',	'',	0.000000,	0.000000,	1,	'2011-12-03 22:40:48'),
(49,	'East Somerset Railway',	'east-somerset-railway',	'http://www.eastsomersetrailway.com/',	'',	'',	NULL,	'BA4 4QP',	'ST64',	'IO81SE',	51.185043,	-2.480434,	1,	'2011-12-03 22:40:48'),
(50,	'West Lancashire Light Railway',	'west-lancashire-light-railway',	'http://www.westlancs.org/',	'',	'',	NULL,	'PR4 6SP',	'SD42',	'IO83NQ',	53.700443,	-2.839702,	1,	'2011-12-03 22:40:48'),
(51,	'Eden Valley Railway',	'eden-valley-railway',	'http://evr-cumbria.org.uk',	'',	'',	NULL,	'CA166PR ',	'NZ71',	'IO84TM',	54.535786,	-2.381689,	1,	'2011-12-03 22:40:48'),
(53,	'Blaenavon Heritage Railway',	'blaenavon-heritage-railway',	'http://www.pontypool-and-blaenavon.co.uk/',	'',	'',	NULL,	'NP49ND',	'',	'IO81KS',	51.772026,	-3.085463,	1,	'2011-12-03 22:40:48'),
(54,	'Sørlandsbanen (Railway to the South)',	'sorlandsbanen-railway-to-the-south',	'http://www.nsb.no/sorlandsbanen/',	'',	'',	NULL,	'',	'',	'JO49OI',	59.354168,	9.208333,	1,	'2011-12-03 22:40:48'),
(57,	'Chasewater Railway',	'chasewater-railway',	'http://www.chasewaterrailway.co.uk/',	'',	'',	NULL,	'WS8 7NL',	'SK00',	'IO92AP',	52.661427,	-1.943050,	1,	'2011-12-03 22:40:48'),
(58,	'Railway Preservation Society of Ireland',	'railway-preservation-society-of-ireland',	'http://www.steamtrainsireland.com',	NULL,	NULL,	NULL,	NULL,	'J49',	'IO74DS',	54.770832,	-5.708333,	1,	'2011-12-03 22:40:48'),
(59,	'Gloucestershire Warwickshire Railway',	'gloucestershire-warwickshire-railway',	'http://www.gwsr.com/',	'',	'',	NULL,	'GL54 5DT',	'SO92',	'IO91AX',	51.989655,	-1.930271,	1,	'2011-12-03 22:40:48'),
(60,	'Bluebell Railway',	'bluebell-railway',	'http://www.bluebell-railway.com/',	'',	'',	NULL,	'TN22 3QL',	'TQ32',	'IO90XX',	50.995403,	-0.001535,	1,	'2011-12-03 22:40:48'),
(70,	'Embsay & Bolton Abbey Steam Railway',	'embsay-bolton-abbey-steam-railway',	'http://www.embsayboltonabbeyrailway.org.uk',	'',	'',	NULL,	'BD236AF',	'SE05',	'IO93BX',	53.976036,	-1.909050,	1,	'2011-12-03 22:40:48'),
(74,	'Corris Railway',	'corris-railway',	'http://www.corris.co.uk',	'',	'',	NULL,	'SY20 9SH',	'',	'IO82BP',	52.653183,	-3.842865,	1,	'2011-12-03 22:40:48'),
(77,	'Nene Valley Railway',	'nene-valley-railway',	'http://www.nvr.org.uk/',	'',	'',	NULL,	'PE8 6LR',	'TO09',	'IO92TN',	52.568329,	-0.389761,	1,	'2011-12-03 22:40:48'),
(78,	'Middleton Railway Leeds',	'middleton-railway-leeds',	'http://www.middletonrailway.org.uk/',	'',	'',	NULL,	'LS10 2JQ',	'',	'IO93FS',	53.773254,	-1.538312,	1,	'2011-12-03 22:40:48'),
(79,	'Whitwell & Reepham Railway',	'whitwell-reepham-railway',	'http://www.whitwellstation.com/default.asp',	'',	'',	NULL,	'NR10 4GA',	'TG02',	'JO02NS',	52.751434,	1.097139,	1,	'2011-12-03 22:40:48'),
(80,	'Amman Valley Railway',	'amman-valley-railway',	'http://avrail.webplus.net/',	'',	'',	'0',	'',	'SN61',	'IO81AT',	51.812500,	-3.958333,	1,	'2011-12-03 22:40:48'),
(82,	'Monkwearmouth Station Musuem',	'monkwearmouth-station-musuem',	'http://www.twmuseums.org.uk/monkwearmouth/',	'',	'',	NULL,	'SR5 1AP',	'',	'IO94HV',	54.911873,	-1.384092,	1,	'2011-12-03 22:40:48'),
(89,	'Weardale Railway',	'weardale-railway',	'http://www.weardale-railway.org.uk/',	'',	'',	NULL,	'DL13 2YS',	'',	'IO84XR',	54.742859,	-2.002624,	1,	'2011-12-03 22:40:48'),
(90,	'Somerset & Dorset Railway Heritage Trust',	'somerset-dorset-railway-heritage-trust',	'http://www.sdjr.co.uk',	'',	'',	NULL,	'BA3 2EY',	'',	'IO81SG',	51.279633,	-2.483385,	1,	'2011-12-03 22:40:48'),
(92,	'Bredgar & Wormshill Light Railway',	'bredgar-wormshill-light-railway',	'http://www.bwlr.co.uk/',	'',	'',	NULL,	'ME9 8AT',	'',	'JO01IH',	51.296890,	0.682452,	1,	'2011-12-03 22:40:48'),
(93,	'Crewe Heritage Centre',	'crewe-heritage-centre',	'http://www.creweheritagecentre.co.uk',	'',	'',	NULL,	'CW1 2DB',	'SJ65',	'IO83SC',	53.093433,	-2.436262,	1,	'2011-12-03 22:40:48'),
(97,	'Princess Royal Class Locomotive Trust',	'princess-royal-class-locomotive-trust',	'http://www.prclt.co.uk',	'',	'',	NULL,	'DE5 4AD',	'SK43',	'IO93HB',	53.049919,	-1.408429,	1,	'2011-12-03 22:40:48'),
(99,	'Battlefield Line Railway - Shackerstone Station',	'battlefield-line-railway-shackerstone-station',	'http://www.battlefield-line-railway.co.uk/',	NULL,	NULL,	NULL,	NULL,	'SK30',	'IO92GP',	52.645832,	-1.458333,	1,	'2011-12-03 22:40:48'),
(100,	'Normanby Park Minature Railway',	'normanby-park-minature-railway',	'http://homepage.ntlworld.com/wilfred.baker/',	'',	'',	NULL,	'DN15 9HU',	'SE88',	'IO93QP',	53.638786,	-0.655958,	1,	'2011-12-03 22:40:48'),
(101,	'Tyttenhanger Light Railway',	'tyttenhanger-light-railway',	'http://www.nlsme.co.uk',	'',	'',	NULL,	'AL4 0NJ',	'TL',	'IO91UR',	51.738094,	-0.266543,	1,	'2011-12-03 22:40:48'),
(104,	'Worth Valley Railway',	'worth-valley-railway',	'http://www.kwvr.co.uk',	NULL,	NULL,	NULL,	NULL,	'SE03',	NULL,	NULL,	NULL,	1,	'2011-12-03 22:40:48'),
(105,	'Helston Railway',	'helston-railway',	'http://www.helstonrailway.co.uk/',	'',	'',	NULL,	'TR13 0RU',	'SW63',	'IO70ID',	50.126423,	-5.301466,	1,	'2011-12-03 22:40:48'),
(107,	'Lappa Valley Railway',	'lappa-valley-railway',	'http://www.lappavalley.co.uk',	'',	'',	NULL,	'TR8 5LX',	'SW86',	'IO70LI',	50.373074,	-5.049064,	1,	'2011-12-03 22:40:48'),
(108,	'Battlefield Line Railway - Market Bosworth Station',	'battlefield-line-railway-market-bosworth-station',	'http://www.battlefield-line-railway.co.uk',	'',	'',	NULL,	'',	'SK30',	'IO92GO',	52.604168,	-1.458333,	1,	'2011-12-03 22:40:48'),
(109,	'North Norfolk Railway',	'north-norfolk-railway',	'http://www.nnrailway.co.uk/',	'',	'',	NULL,	'NR26 8RA',	'TG03',	'JO02OW',	52.941563,	1.208976,	1,	'2011-12-03 22:40:48'),
(111,	'Lakeside & Havethwaite Railway',	'lakeside-havethwaite-railway',	'http://www.lakesiderailway.co.uk/',	'',	'',	NULL,	'LA12 8AL',	'SD38',	'IO84LF',	54.249443,	-3.000135,	1,	'2011-12-03 22:40:48'),
(112,	'GC Railway, Nottingham',	'gc-railway-nottingham',	'http://217.158.157.207/railways/GCRN/index.htm',	'',	'',	NULL,	'NG11 6JS',	'SK53',	'IO92KV',	52.882221,	-1.143732,	1,	'2011-12-03 22:40:48'),
(115,	'GWR Preservation Group (Southall Railway Centre)',	'gwr-preservation-group-southall-railway-centre',	'http://www.gwrpg.co.uk/',	'',	'',	NULL,	'UB2 4SE',	'',	'IO91TM',	51.506210,	-0.365244,	1,	'2011-12-03 22:40:48'),
(116,	'Dean Forest Railway',	'dean-forest-railway',	'http://www.deanforestrailway.co.uk/',	'',	'',	NULL,	'GL15 4ET',	'SO60',	'IO81RR',	51.736759,	-2.538022,	1,	'2011-12-03 22:40:48'),
(117,	'Drumawhey Miniature Railway',	'drumawhey-miniature-railway',	'http://bcdmrs.org.uk',	'',	'',	NULL,	'BT21 0LZ',	'',	'IO74FP',	54.638878,	-5.545771,	1,	'2011-12-03 22:40:48'),
(118,	'Foxfield Railway',	'foxfield-railway',	'http://www.foxfieldrailway.co.uk/',	'',	'',	NULL,	'ST11 9BG',	'SJ94',	'IO82XX',	52.971081,	-2.065774,	1,	'2011-12-03 22:40:48'),
(120,	'Ravenglass and Eskdale Railway',	'ravenglass-and-eskdale-railway',	'http://www.ravenglass-railway.co.uk/',	'',	'',	NULL,	'CA181SW',	'SD10',	'IO84HI',	54.356487,	-3.409687,	1,	'2011-12-03 22:40:48'),
(123,	'Amberley Narrow Gauge Railway',	'amberley-narrow-gauge-railway',	'http://www.amberleynarrowgauge.co.uk',	'Amberley Narrow Gauge Railway lies within the Amberley Museum, and is home to a large collection of locomotives, rolling stock and other equipment, plus an extensive track network across the 36 acre site.',	'Amberley Narrow Gauge Railway lies within the Amberley Museum, and is home to a large collection of locomotives, rolling stock and other equipment, plus an extensive track network across the 36 acre site.',	'../../storage/railwa',	'BN18 9LT',	'TQ01',	'IO90RV',	50.901596,	-0.539748,	1,	'2011-12-03 22:40:48'),
(125,	'Worden Park Miniature Railway',	'worden-park-miniature-railway',	'http://en.wikipedia.org/wiki/Worden_Park',	'',	'',	NULL,	'PR5 2DJ',	'',	'IO83PQ',	53.695236,	-2.681725,	1,	'2011-12-03 22:40:48'),
(126,	'Isle of Wight Steam Railway',	'isle-of-wight-steam-railway',	'http://www.iwsteamrailway.co.uk/',	'',	'',	NULL,	'PO33 4DS',	'SZ58',	'IO90JQ',	50.704735,	-1.214796,	1,	'2011-12-03 22:40:48');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('02d6c34aad21ce21af3399f23f967b0c',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1322350958,	''),
('7d8568748228b5d1a7d3ef467b1c760a',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1322500221,	''),
('b948b43c246a2c578da1780c13e82a01',	'127.0.0.1',	'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC2; .NET',	1322693374,	'a:4:{s:9:\"user_data\";s:0:\"\";s:10:\"account_id\";s:1:\"2\";s:5:\"email\";s:27:\"craig.rodway+user@gmail.com\";s:4:\"type\";s:4:\"user\";}'),
('4be3b46e492b0f8fa29ef82b56a38c18',	'127.0.0.1',	'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ',	1322693415,	''),
('cfbd2b9c36b8113e610cd27e6b571a74',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1322953305,	'a:4:{s:9:\"user_data\";s:0:\"\";s:10:\"account_id\";s:1:\"1\";s:5:\"email\";s:22:\"craig.rodway@gmail.com\";s:4:\"type\";s:5:\"admin\";}'),
('2a4c83682ef46cc78388fec4be111857',	'127.0.0.1',	'Opera/9.80 (Windows NT 6.1; U; Edition United Kingdom Local; en) Presto/2.9.168 Version/11.52',	1322870306,	''),
('b46e5046e6db0744d2fd85367853b3e1',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1323734328,	''),
('2b6e57bd39a49489d64e4b55b95322b7',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.0',	1323461490,	''),
('857254e0223794c8be9e627f40589413',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1323028350,	''),
('ba5dd76dc576fe08eaf0fb61f28c065b',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2',	1324167435,	''),
('2da0a8cd580bebd90d67fcfd77e838bc',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.63 Safari/535.7',	1326061720,	''),
('eee04bdf71bd99fcb2b35df71c56ce5a',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7',	1326836696,	'a:4:{s:9:\"user_data\";s:0:\"\";s:10:\"account_id\";s:1:\"1\";s:5:\"email\";s:22:\"craig.rodway@gmail.com\";s:4:\"type\";s:5:\"admin\";}'),
('356120da27da7033d827188b59dcdd68',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7',	1327535949,	''),
('a240ad84f8b8c7aceb4a89444f0162f9',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7',	1327704437,	'a:5:{s:9:\"user_data\";s:0:\"\";s:10:\"account_id\";s:1:\"1\";s:5:\"email\";s:22:\"craig.rodway@gmail.com\";s:4:\"type\";s:5:\"admin\";s:11:\"redirect_to\";s:42:\"http://rota.local/index.php/admin/railways\";}'),
('5410a2232c67b0d2ce4886c0548befc2',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7',	1327704438,	''),
('9299ccbbb1ee775864952a0fea5a5a23',	'127.0.0.1',	'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7',	1328745686,	'');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`name`, `value`) VALUES
('email_from',	'no-reply@barac.m0php.net');

DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
  `e_id` int(11) unsigned NOT NULL,
  `r_id` int(11) unsigned NOT NULL,
  `o_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`e_id`,`r_id`,`o_id`),
  KEY `r_id` (`r_id`),
  KEY `o_id` (`o_id`),
  CONSTRAINT `stations_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `events` (`e_id`),
  CONSTRAINT `stations_ibfk_2` FOREIGN KEY (`r_id`) REFERENCES `railways` (`r_id`),
  CONSTRAINT `stations_ibfk_3` FOREIGN KEY (`o_id`) REFERENCES `operators` (`o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2012-02-09 00:05:28
