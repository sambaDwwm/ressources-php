DROP TABLE IF EXISTS `<DB_PREFIX>calendar`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL DEFAULT '0',
  `event_date` date NULL DEFAULT NULL,
  `event_time` time NOT NULL,
  `slot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - initial, 0 - middle',
  `unique_key` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `notification_sent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `<DB_PREFIX>events`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL DEFAULT '0',
  `location_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(70) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8 NOT NULL,
  `participant_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `locations_id` (`location_id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `<DB_PREFIX>events_categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `color` varchar(7) CHARACTER SET utf8 NOT NULL DEFAULT '#000000',
  `duration` varchar(3) CHARACTER SET latin1 NOT NULL,
  `show_in_filter` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `duration` (`duration`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `<DB_PREFIX>events_locations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_locations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `show_in_filter` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `<DB_PREFIX>events_participants`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>events_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) NOT NULL DEFAULT '0',
  `participant_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `<DB_PREFIX>participants`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>participants` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
