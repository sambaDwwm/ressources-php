
RENAME TABLE `<DB_PREFIX>doctors_addresses` TO `<DB_PREFIX>doctor_addresses` ;
RENAME TABLE `<DB_PREFIX>doctors_specialities` TO `<DB_PREFIX>doctor_specialities` ;
ALTER TABLE  `<DB_PREFIX>doctor_specialities` ADD  `priority_order` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `is_default`;
ALTER TABLE  `<DB_PREFIX>doctor_specialities` ADD  `visit_price` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `speciality_id`;


ALTER TABLE  `<DB_PREFIX>specialities` ADD  `is_active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `id`;

ALTER TABLE `<DB_PREFIX>doctors` ADD  `email_notifications` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `last_logged_ip`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `notification_status_changed` DATETIME NULL DEFAULT NULL AFTER  `email_notifications`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `membership_plan_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `default_visit_duration`,
                            ADD  `membership_expires` DATE NULL DEFAULT NULL AFTER  `membership_plan_id`,
                            ADD INDEX (`membership_plan_id`);
ALTER TABLE `<DB_PREFIX>doctors` ADD  `membership_images_count` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `membership_plan_id`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `membership_show_in_search` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `membership_images_count`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `membership_addresses_count` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `membership_images_count`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `license_number` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER  `medical_degree`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `additional_degree` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER  `medical_degree`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `experience_years` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `education`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_address` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `title`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_address_2` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `b_address`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_city` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `b_address_2`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_state` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `b_city`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_country` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `b_state`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `b_zipcode` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `b_country`;
ALTER TABLE `<DB_PREFIX>doctors` ADD  `phone` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `work_mobile_phone`;


ALTER TABLE `<DB_PREFIX>patients` CHANGE  `first_name`  `first_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `<DB_PREFIX>patients` CHANGE  `last_name`  `last_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `<DB_PREFIX>appointments` ADD  `created_by` ENUM('admin', 'doctor', 'patient') NOT NULL DEFAULT  'patient' AFTER  `status_changed`;
ALTER TABLE `<DB_PREFIX>appointments` ADD  `insurance_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `first_visit` ;
ALTER TABLE `<DB_PREFIX>appointments` ADD  `visit_reason_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `insurance_id`;


ALTER TABLE `<DB_PREFIX>menus` CHANGE  `menu_placement`  `menu_placement` ENUM('', 'left', 'top', 'right', 'bottom', 'hidden') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

DROP TABLE IF EXISTS `<DB_PREFIX>membership_plans`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>membership_plans` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `images_count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addresses_count` tinyint(1) unsigned NOT NULL DEFAULT '5',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `duration` smallint(6) NOT NULL DEFAULT '1' COMMENT 'in days',
  `show_in_search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

INSERT INTO `<DB_PREFIX>membership_plans` (`id`, `images_count`, `addresses_count`, `price`, `duration`, `show_in_search`, `is_default`, `is_active`) VALUES
(1, 0, 0, '0.00', 365, 0, 1, 1),
(2, 1, 1, '50.00', 720, 1, 0, 1),
(3, 2, 3, '100.00', 1825, 1, 0, 1),
(4, 3, 5, '200.00', -1, 1, 0, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>membership_plans_description`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>membership_plans_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_plan_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `membership_plan_id` (`membership_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

INSERT INTO `<DB_PREFIX>membership_plans_description` (`id`, `membership_plan_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 'de', 'Frei', 'Kostenlos werben Plan. Dieser Plan garantiert eine minimale Funktionen, aber frei.'),
(2, 1, 'es', 'Libre', 'Gratis plan de publicidad. Este plan ofrece una serie de características mínimas, pero libres.'),
(3, 1, 'en', 'Free', 'Free membership plan. This plan offers a minimal features, but free.'),
(4, 2, 'en', 'Bronze', 'A step up from free membership plan. More features available.'),
(5, 2, 'es', 'Bronce', 'Un paso adelante sin plan de publicidad. Otras características disponibles.'),
(6, 2, 'de', 'Bronze', 'Ein Schritt aus freien Werben Plan. Weitere Features zur Verfügung.'),
(7, 3, 'en', 'Silver', 'More features and details are allowed and are listed higher in results.'),
(8, 3, 'es', 'Plata', 'Más características y los detalles se les permite y se enumeran más alto en los resultados.'),
(9, 3, 'de', 'Silber', 'Weitere Merkmale und Einzelheiten sind zulässig und werden in höheren Ergebnissen aufgelistet.'),
(10, 4, 'en', 'Gold', 'This membership plan provides maximum features and benefits.'),
(11, 4, 'es', 'Oro', 'Este plan de publicidad ofrece máximas prestaciones y beneficios.'),
(12, 4, 'de', 'Gold', 'Dieses Werben Plan bietet ein Maximum an Funktionen und Vorteile.');


DROP TABLE IF EXISTS `<DB_PREFIX>visit_reasons`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>visit_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

INSERT INTO `<DB_PREFIX>visit_reasons` (`id`, `priority_order`, `is_active`) VALUES
(1, 0, 1),
(2, 1, 1),
(3, 2, 1),
(4, 3, 1),
(5, 4, 1),
(6, 5, 1),
(7, 6, 1),
(8, 7, 1),
(9, 8, 1),
(10, 9, 1),
(11, 10, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>visit_reasons_description`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>visit_reasons_description` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_reason_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

INSERT INTO `<DB_PREFIX>visit_reasons_description` (`id`, `visit_reason_id`, `language_id`, `name`) VALUES
(1, 1, 'en', 'Illness'),
(2, 1, 'es', 'Enfermedad'),
(3, 1, 'de', 'Krankheit'),
(4, 2, 'en', 'General Consultation'),
(5, 2, 'es', 'Consulta general'),
(6, 2, 'de', 'Allgemeine Beratung'),
(7, 3, 'en', 'General Follow Up'),
(8, 3, 'es', 'General de Seguimiento'),
(9, 3, 'de', 'Allgemeine Follow Up'),
(10, 4, 'en', 'Annual Physical'),
(11, 4, 'es', 'Físico Anual'),
(12, 4, 'de', 'Jahres Physikalische'),
(13, 5, 'en', 'Cardiovascular Screening Visit'),
(14, 5, 'es', 'Evaluación Cardiovascular Visita'),
(15, 5, 'de', 'Herz-Kreislauf-Screening Besuch'),
(16, 6, 'en', 'Flu Shot'),
(17, 6, 'es', 'Vacuna contra la gripe'),
(18, 6, 'de', 'Grippeimpfung'),
(19, 7, 'en', 'Pre-Surgery Checkup'),
(20, 7, 'es', 'Chequeo Pre-Op'),
(21, 7, 'de', 'Pre-Checkup Chirurgie'),
(22, 8, 'en', 'Pre-Travel Checkup'),
(23, 8, 'es', 'Pre-Travel Chequeo'),
(24, 8, 'de', 'Pre-Checkup Reise'),
(25, 9, 'en', 'Prescription / Refill'),
(26, 9, 'es', 'Prescripción / Refill'),
(27, 9, 'de', 'Rezept / Refill'),
(28, 10, 'en', 'STD (Sexually Transmitted Disease)'),
(29, 10, 'es', 'ETS (Enfermedad de Transmisión Sexual)'),
(30, 10, 'de', 'STD (Sexually Transmitted Disease)'),
(31, 11, 'en', 'Other'),
(32, 11, 'es', 'Ptro'),
(33, 11, 'de', 'Andere');


DROP TABLE IF EXISTS `<DB_PREFIX>doctor_images`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>doctor_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `item_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_file_thumb` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `image_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority_order` smallint(6) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `priority_order` (`priority_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `<DB_PREFIX>insurances`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>insurances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>insurances` (`id`, `priority_order`, `is_active`) VALUES
(1, 0, 1),
(2, 1, 1),
(3, 2, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>insurances_description`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>insurances_description` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `insurance_id` int(10) unsigned NOT NULL DEFAULT '0',
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `<DB_PREFIX>insurances_description` (`id`, `insurance_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 'en', 'General', 'General insurance plan'),
(2, 1, 'es', 'General', 'Plan de seguros generales'),
(3, 1, 'de', 'Allgemein', 'Allgemeine Versicherung'),
(4, 2, 'en', 'Medicaid', ''),
(5, 2, 'es', 'Medicaid', ''),
(6, 2, 'de', 'Medicaid', ''),
(7, 3, 'en', 'Helthcare', ''),
(8, 3, 'es', 'Helthcare', ''),
(9, 3, 'de', 'Helthcare', '');


DROP TABLE IF EXISTS `<DB_PREFIX>social_networks`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>social_networks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

INSERT INTO `<DB_PREFIX>social_networks` (`id`, `code`, `name`, `url`) VALUES
(1, 'digg', 'Digg', ''),
(2, 'dribbble', 'Dribbble', ''),
(3, 'facebook', 'Facebook', ''),
(4, 'flickr', 'Flickr', ''),
(5, 'googleplus', 'Google+', ''),
(6, 'linkedin', 'LinkedIn', ''),
(7, 'myspace', 'MySpace', ''),
(8, 'reddit', 'Reddit', ''),
(9, 'tumblr', 'Tumblr', ''),
(10, 'twitter', 'Twitter', ''),
(11, 'vimeo', 'Vimeo', ''),
(12, 'youtube', 'YouTube', '');



ALTER TABLE `<DB_PREFIX>currencies` DROP `symbol_placement`;
ALTER TABLE `<DB_PREFIX>currencies` ADD  `symbol_placement` ENUM(  'before',  'after' ) NOT NULL DEFAULT  'before' AFTER  `rate`;
ALTER TABLE `<DB_PREFIX>currencies` ADD  `decimals` TINYINT( 1 ) NOT NULL DEFAULT  '2' AFTER  `rate`;


ALTER TABLE `<DB_PREFIX>settings` CHANGE  `smtp_secure` `smtp_secure` ENUM('ssl','tls','no') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'ssl';
ALTER TABLE `<DB_PREFIX>settings` ADD  `ssl_mode` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '0 - no, 1 - entire site, 2 - admin, 3 - patient,doctor & payment modules' AFTER  `template`;
ALTER TABLE `<DB_PREFIX>settings` ADD  `smtp_auth` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `mailer_type`;
ALTER TABLE `<DB_PREFIX>settings` ADD  `mailer_wysiwyg_type` ENUM(  'none',  'tinymce' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  'none' AFTER  `mailer_type` ;
ALTER TABLE `<DB_PREFIX>settings` ADD  `week_start_day` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `time_zone`;
UPDATE `<DB_PREFIX>settings` SET `wysiwyg_type` = 'tinymce';
ALTER TABLE `<DB_PREFIX>settings` CHANGE  `wysiwyg_type`  `wysiwyg_type` ENUM(  'none',  'tinymce' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'tinymce' ;
ALTER TABLE `<DB_PREFIX>settings` CHANGE  `smtp_password`  `smtp_password` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

INSERT INTO `<DB_PREFIX>modules` (`id`, `name`, `name_const`, `description_const`, `icon_file`, `module_tables`, `dependent_modules`, `settings_page`, `settings_const`, `settings_access_by`, `management_page`, `management_const`, `management_access_by`, `is_installed`, `is_system`, `priority_order`) VALUES (NULL, 'payments', '_PAYMENTS', '_MD_PAYMENTS', 'payments.png', 'membershsip_plans,membershsip_plans_description', '', 'mod_payments_settings', '_PAYMENTS_SETTINGS', 'owner,mainadmin', '', '', '', 1, 0, 4);
ALTER TABLE `<DB_PREFIX>modules` ADD  `show_on_dashboard` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `is_system`;
UPDATE `<DB_PREFIX>modules` SET  `show_on_dashboard` =  '1' WHERE `name` = 'appointments' OR `name` = 'doctors' OR `name` = 'pages';
INSERT INTO `<DB_PREFIX>modules` (`id`, `name`, `name_const`, `description_const`, `icon_file`, `module_tables`, `dependent_modules`, `settings_page`, `settings_const`, `settings_access_by`, `management_page`, `management_const`, `management_access_by`, `is_installed`, `is_system`, `priority_order`) VALUES (NULL, 'ratings', '_RATINGS', '_MD_RATINGS', 'ratings.png', 'ratings_items,ratings_users', '', 'mod_ratings_settings', '_RATINGS_SETTINGS', 'owner,mainadmin', '', '', '', '1', '0', '13');

ALTER TABLE `<DB_PREFIX>news` ADD  `is_active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';


DROP TABLE IF EXISTS `<DB_PREFIX>specialities_description`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>specialities_description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `speciality_id` int(10) NOT NULL DEFAULT '0',
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103 ;

INSERT INTO `<DB_PREFIX>specialities_description` (`id`, `speciality_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 'en', 'Allergist (Immunologist)', 'An allergist/immunologist is a medical doctor with specialty training in the diagnosis and treatment of allergic diseases, asthma and diseases of the immune system.'),
(2, 2, 'en', 'Cardiologist (Heart Doctor)', ''),
(3, 3, 'en', 'Dentist', ''),
(4, 4, 'en', 'Dermatologist', ''),
(5, 5, 'en', 'Dietitian', ''),
(6, 6, 'en', 'Ear, Nose & Throat Doctor (ENT)', ''),
(7, 7, 'en', 'Endocrinologist', ''),
(8, 8, 'en', 'Eye Doctor', ''),
(9, 9, 'en', 'Gastroenterologist', ''),
(10, 10, 'en', 'Hematologist (Blood Specialist)', ''),
(11, 11, 'en', 'Infectious Disease Specialist', ''),
(12, 12, 'en', 'Nephrologist (Kidney Specialist)', ''),
(13, 13, 'en', 'Neurologist (incl Headache Specialists)', ''),
(14, 14, 'en', 'OB-GYN (Obstetrician-Gynecologist)', ''),
(15, 15, 'en', 'Ophthalmologist', ''),
(16, 16, 'en', 'Optometrist', ''),
(17, 17, 'en', 'Orthodontist', ''),
(18, 18, 'en', 'Orthopedic Surgeon (Orthopedist)', ''),
(19, 19, 'en', 'Pain Management Specialist', ''),
(20, 20, 'en', 'Pediatric Dentist', ''),
(21, 21, 'en', 'Pediatrician', ''),
(22, 22, 'en', 'Physical Therapist (Physical Medicine)', ''),
(23, 23, 'en', 'Plastic Surgeon', ''),
(24, 24, 'en', 'Podiatrist (Foot Specialist)', ''),
(25, 25, 'en', 'Primary Care Doctor (General Practitioner)', ''),
(26, 26, 'en', 'Prosthodontist', ''),
(27, 27, 'en', 'Psychiatrist', ''),
(28, 28, 'en', 'Psychologist', ''),
(29, 29, 'en', 'Pulmonologist (Lung Doctor)', ''),
(30, 30, 'en', 'Radiologist', ''),
(31, 31, 'en', 'Rheumatologist', ''),
(32, 32, 'en', 'Sleep Medicine Specialist', ''),
(33, 33, 'en', 'Sports Medicine Specialist', ''),
(34, 34, 'en', 'Urologist', ''),
(35, 1, 'es', 'Allergist (Immunologist)', 'An allergist/immunologist is a medical doctor with specialty training in the diagnosis and treatment of allergic diseases, asthma and diseases of the immune system.'),
(36, 2, 'es', 'Cardiologist (Heart Doctor)', ''),
(37, 3, 'es', 'Dentist', ''),
(38, 4, 'es', 'Dermatologist', ''),
(39, 5, 'es', 'Dietitian', ''),
(40, 6, 'es', 'Ear, Nose & Throat Doctor (ENT)', ''),
(41, 7, 'es', 'Endocrinologist', ''),
(42, 8, 'es', 'Eye Doctor', ''),
(43, 9, 'es', 'Gastroenterologist', ''),
(44, 10, 'es', 'Hematologist (Blood Specialist)', ''),
(45, 11, 'es', 'Infectious Disease Specialist', ''),
(46, 12, 'es', 'Nephrologist (Kidney Specialist)', ''),
(47, 13, 'es', 'Neurologist (incl Headache Specialists)', ''),
(48, 14, 'es', 'OB-GYN (Obstetrician-Gynecologist)', ''),
(49, 15, 'es', 'Ophthalmologist', ''),
(50, 16, 'es', 'Optometrist', ''),
(51, 17, 'es', 'Orthodontist', ''),
(52, 18, 'es', 'Orthopedic Surgeon (Orthopedist)', ''),
(53, 19, 'es', 'Pain Management Specialist', ''),
(54, 20, 'es', 'Pediatric Dentist', ''),
(55, 21, 'es', 'Pediatrician', ''),
(56, 22, 'es', 'Physical Therapist (Physical Medicine)', ''),
(57, 23, 'es', 'Plastic Surgeon', ''),
(58, 24, 'es', 'Podiatrist (Foot Specialist)', ''),
(59, 25, 'es', 'Primary Care Doctor (General Practitioner)', ''),
(60, 26, 'es', 'Prosthodontist', ''),
(61, 27, 'es', 'Psychiatrist', ''),
(62, 28, 'es', 'Psychologist', ''),
(63, 29, 'es', 'Pulmonologist (Lung Doctor)', ''),
(64, 30, 'es', 'Radiologist', ''),
(65, 31, 'es', 'Rheumatologist', ''),
(66, 32, 'es', 'Sleep Medicine Specialist', ''),
(67, 33, 'es', 'Sports Medicine Specialist', ''),
(68, 34, 'es', 'Urologist', ''),
(69, 1, 'de', 'Allergist (Immunologist)', 'An allergist/immunologist is a medical doctor with specialty training in the diagnosis and treatment of allergic diseases, asthma and diseases of the immune system.'),
(70, 2, 'de', 'Cardiologist (Heart Doctor)', ''),
(71, 3, 'de', 'Dentist', ''),
(72, 4, 'de', 'Dermatologist', ''),
(73, 5, 'de', 'Dietitian', ''),
(74, 6, 'de', 'Ear, Nose & Throat Doctor (ENT)', ''),
(75, 7, 'de', 'Endocrinologist', ''),
(76, 8, 'de', 'Eye Doctor', ''),
(77, 9, 'de', 'Gastroenterologist', ''),
(78, 10, 'de', 'Hematologist (Blood Specialist)', ''),
(79, 11, 'de', 'Infectious Disease Specialist', ''),
(80, 12, 'de', 'Nephrologist (Kidney Specialist)', ''),
(81, 13, 'de', 'Neurologist (incl Headache Specialists)', ''),
(82, 14, 'de', 'OB-GYN (Obstetrician-Gynecologist)', ''),
(83, 15, 'de', 'Ophthalmologist', ''),
(84, 16, 'de', 'Optometrist', ''),
(85, 17, 'de', 'Orthodontist', ''),
(86, 18, 'de', 'Orthopedic Surgeon (Orthopedist)', ''),
(87, 19, 'de', 'Pain Management Specialist', ''),
(88, 20, 'de', 'Pediatric Dentist', ''),
(89, 21, 'de', 'Pediatrician', ''),
(90, 22, 'de', 'Physical Therapist (Physical Medicine)', ''),
(91, 23, 'de', 'Plastic Surgeon', ''),
(92, 24, 'de', 'Podiatrist (Foot Specialist)', ''),
(93, 25, 'de', 'Primary Care Doctor (General Practitioner)', ''),
(94, 26, 'de', 'Prosthodontist', ''),
(95, 27, 'de', 'Psychiatrist', ''),
(96, 28, 'de', 'Psychologist', ''),
(97, 29, 'de', 'Pulmonologist (Lung Doctor)', ''),
(98, 30, 'de', 'Radiologist', ''),
(99, 31, 'de', 'Rheumatologist', ''),
(100, 32, 'de', 'Sleep Medicine Specialist', ''),
(101, 33, 'de', 'Sports Medicine Specialist', ''),
(102, 34, 'de', 'Urologist', '');


DROP TABLE IF EXISTS `<DB_PREFIX>states`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>states` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `country_id` smallint(6) unsigned NOT NULL DEFAULT '0',
  `abbrv` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `priority_order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;

INSERT INTO `<DB_PREFIX>states` (`id`, `country_id`, `abbrv`, `name`, `is_active`, `priority_order`) VALUES
(1, 224, 'AL', 'Alabama', 1, 1),
(2, 224, 'AK', 'Alaska', 1, 2),
(3, 224, 'AZ', 'Arizona', 1, 3),
(4, 224, 'AR', 'Arkansas', 1, 4),
(5, 224, 'CA', 'California', 1, 5),
(6, 224, 'CO', 'Colorado', 1, 6),
(7, 224, 'CT', 'Connecticut', 1, 7),
(8, 224, 'DE', 'Delaware', 1, 8),
(9, 224, 'DC', 'District of Columbia', 1, 9),
(10, 224, 'FL', 'Florida', 1, 10),
(11, 224, 'GA', 'Georgia', 1, 11),
(12, 224, 'HI', 'Hawaii', 1, 12),
(13, 224, 'ID', 'Idaho', 1, 13),
(14, 224, 'IL', 'Illinois', 1, 14),
(15, 224, 'IN', 'Indiana', 1, 15),
(16, 224, 'IA', 'Iowa', 1, 16),
(17, 224, 'KS', 'Kansas', 1, 17),
(18, 224, 'KY', 'Kentucky', 1, 18),
(19, 224, 'LA', 'Louisiana', 1, 19),
(20, 224, 'ME', 'Maine', 1, 20),
(21, 224, 'MD', 'Maryland', 1, 21),
(22, 224, 'MA', 'Massachusetts', 1, 22),
(23, 224, 'MI', 'Michigan', 1, 22),
(24, 224, 'MN', 'Minnesota', 1, 23),
(25, 224, 'MS', 'Mississippi', 1, 25),
(26, 224, 'MT', 'Montana', 1, 27),
(27, 224, 'MO', 'Missouri', 1, 26),
(28, 224, 'NE', 'Nebraska', 1, 28),
(29, 224, 'NV', 'Nevada', 1, 29),
(30, 224, 'NH', 'New Hampshire', 1, 30),
(31, 224, 'NJ', 'New Jersey', 1, 31),
(32, 224, 'NM', 'New Mexico', 1, 32),
(33, 224, 'NY', 'New York', 1, 33),
(34, 224, 'NC', 'North Carolina', 1, 34),
(35, 224, 'ND', 'North Dakota', 1, 35),
(36, 224, 'OH', 'Ohio', 1, 36),
(37, 224, 'OK', 'Oklahoma', 1, 37),
(38, 224, 'OR', 'Oregon', 1, 38),
(39, 224, 'PA', 'Pennsylvania', 1, 39),
(40, 224, 'RI', 'Rhode Island', 1, 40),
(41, 224, 'SC', 'South Carolina', 1, 41),
(42, 224, 'SD', 'South Dakota', 1, 42),
(43, 224, 'TN', 'Tennessee', 1, 43),
(44, 224, 'TX', 'Texas', 1, 44),
(45, 224, 'UT', 'Utah', 1, 45),
(46, 224, 'VT', 'Vermont', 1, 46),
(47, 224, 'VA', 'Virginia', 1, 47),
(48, 224, 'WA', 'Washington', 1, 48),
(49, 224, 'WV', 'West Virginia', 1, 49),
(50, 224, 'WI', 'Wisconsin', 1, 50),
(51, 224, 'WY', 'Wyoming', 1, 51),
(52, 13, 'NSW', 'New South Wales', 1, 1),
(53, 13, 'QLD', 'Queensland', 1, 2),
(54, 13, 'SA', 'South Australia', 1, 3),
(55, 13, 'TAS', 'Tasmania', 1, 4),
(56, 13, 'VIC', 'Victoria', 1, 5),
(57, 13, 'WA', 'Western Australia', 1, 6),
(58, 39, 'AB', 'Alberta', 1, 1),
(59, 39, 'BC', 'British Columbia', 1, 2),
(60, 39, 'ON', 'Ontario', 1, 6),
(61, 39, 'QC', 'Quebec', 1, 8),
(62, 39, 'NS', 'Nova Scotia', 1, 5),
(63, 39, 'NB', 'New Brunswick', 1, 4),
(64, 39, 'MB', 'Manitoba', 1, 3),
(65, 39, 'PE', 'Prince Edward Island', 1, 7),
(66, 39, 'SK', 'Saskatchewan', 1, 9),
(67, 39, 'NL', 'Newfoundland and Labrador', 1, 10);


INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'gallery', 'show_items_numeration_in_album', 'yes', 'Show Items Numeration in Album', '_MS_ALBUM_ITEMS_NUMERATION', 'yes/no', 1, '');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES			
(NULL, 'payments', 'is_active', 'yes', 'Activate Payments', '_MS_PAYMENTS_ACTIVE', 'yes/no', 1, ''),
(NULL, 'payments', 'payment_method_online', 'yes', '&#8226; ''On-line Order'' Payment Method', '_MS_PAYMENT_METHOD_ONLINE', 'yes/no', 1, ''),
(NULL, 'payments', 'online_collect_credit_card', 'yes', '&nbsp; Credit Cards for ''On-line Orders''', '_MS_ONLINE_COLLECT_CREDIT_CARD', 'yes/no', 0, ''),
(NULL, 'payments', 'payment_method_paypal', 'yes', '&#8226; PayPal Payment Method', '_MS_PAYMENT_METHOD_PAYPAL', 'yes/no', 1, ''),
(NULL, 'payments', 'paypal_email', 'paypal@example.com', '&nbsp; PayPal Email', '_MS_PAYPAL_EMAIL', 'email', 1, ''),
(NULL, 'payments', 'payment_method_2co', 'yes', '&#8226; 2CO Payment Method', '_MS_PAYMENT_METHOD_2CO', 'yes/no', 0, ''),
(NULL, 'payments', 'two_checkout_vendor', 'Your Vendor ID', '&nbsp; 2CO Vendor ID', '_MS_TWO_CHECKOUT_VENDOR', 'string', 1, ''),
(NULL, 'payments', 'payment_method_authorize', 'yes', '&#8226; Authorize.Net Payment Method', '_MS_PAYMENT_METHOD_AUTHORIZE', 'yes/no', 1, ''),
(NULL, 'payments', 'authorize_login_id', 'Your Login ID', '&nbsp; Authorize.Net Login ID', '_MS_AUTHORIZE_LOGIN_ID', 'string', 1, ''),
(NULL, 'payments', 'authorize_transaction_key', 'Your Transaction Key', '&nbsp; Authorize.Net Transaction Key', '_MS_AUTHORIZE_TRANSACTION_KEY', 'string', 1, ''),
(NULL, 'payments', 'default_payment_system', 'paypal', 'Default Payment System', '_MS_DEFAULT_PAYMENT_SYSTEM', 'enum', 1, 'online,paypal,2co,authorize.net'),
(NULL, 'payments', 'vat_value', '0', 'VAT Value', '_MS_VAT_VALUE', 'unsigned float', 0, ''),
(NULL, 'payments', 'send_order_copy_to_admin', 'yes', 'Send Order Copy To Admin', '_MS_SEND_ORDER_COPY_TO_ADMIN', 'yes/no', 0, ''),
(NULL, 'payments', 'mode', 'REAL MODE', 'Payment Mode', '_MS_PAYMENTS_MODE', 'enum', 1, 'TEST MODE,REAL MODE');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'doctors', 'watermark', 'yes', 'Add Watermark to Images', '_MS_ADD_WATERMARK', 'yes/no', '1', '');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'doctors', 'watermark_text', '', 'Watermark Text', '_MS_WATERMARK_TEXT', 'string', '0', '');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'doctors', 'key', '{module:doctors}', 'Doctors Key', '_MS_DOCTORS_KEY', 'enum', '1', '{module:doctors}');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'appointments', 'schedules_access_level', 'public', 'Schedules Access Level', '_MS_SCHEDULES_ACCESS_LEVEL', 'enum', '1', 'public,registered');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'appointments', 'page_size', '20', 'Doctors Per Page', '_MS_APPOINTMENTS_PAGE_SIZE', 'positive integer', '1', '');
ALTER TABLE `<DB_PREFIX>modules_settings` ADD  `settings_name_const` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `settings_name`;
ALTER TABLE `<DB_PREFIX>modules_settings` CHANGE  `settings_name_const`  `settings_name_const` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '';
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_name_const`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'appointments', 'allow_multiple_appointments', 'yes', 'Allow Multiple Appointments', '_MSN_ALLOW_MULTIPLE_APPOINTMENTS', '_MS_ALLOW_MULTIPLE_APPOINTMENTS', 'yes/no', '1', '');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'ratings', 'user_type', 'all', 'User Type', '_MS_RATINGS_USER_TYPE', 'enum', '1', 'all,registered');
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'ratings', 'multiple_items_per_day', 'yes', 'Multiple Items per Day', '_MS_MULTIPLE_ITEMS_PER_DAY', 'yes/no', '1', '');
ALTER TABLE `<DB_PREFIX>modules_settings` DROP  `settings_name`;
INSERT INTO `<DB_PREFIX>modules_settings` (`id`, `module_name`, `settings_key`, `settings_value`, `settings_name_const`, `settings_description_const`, `key_display_type`, `key_is_required`, `key_display_source`) VALUES (NULL, 'payments', 'maximum_allowed_orders', '10', '_MSN_MAXIMUM_ALLOWED_ORDERS', '_MS_MAXIMUM_ALLOWED_ORDERS', 'positive integer', '1', '');


DROP TABLE IF EXISTS `<DB_PREFIX>specialities`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>specialities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

INSERT INTO `<DB_PREFIX>specialities` (`id`, `is_active`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>specialities_description`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>specialities_description` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `speciality_id` int(10) NOT NULL DEFAULT '0',
  `language_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103 ;

INSERT INTO `<DB_PREFIX>specialities_description` (`id`, `speciality_id`, `language_id`, `name`, `description`) VALUES
(1, 1, 'en', 'Allergist (Immunologist)', 'An allergist/immunologist is a medical doctor with specialty training in the diagnosis and treatment of allergic diseases, asthma and diseases of the immune system.'),
(2, 2, 'en', 'Cardiologist (Heart Doctor)', 'Cardiologists are doctors who specialize in diagnosing and treating diseases or conditions of the heart and blood vessels—the cardiovascular system.'),
(3, 3, 'en', 'Dentist', ''),
(4, 4, 'en', 'Dermatologist', ''),
(5, 5, 'en', 'Dietitian', ''),
(6, 6, 'en', 'Ear, Nose & Throat Doctor (ENT)', ''),
(7, 7, 'en', 'Endocrinologist', ''),
(8, 8, 'en', 'Eye Doctor', ''),
(9, 9, 'en', 'Gastroenterologist', ''),
(10, 10, 'en', 'Hematologist (Blood Specialist)', ''),
(11, 11, 'en', 'Infectious Disease Specialist', ''),
(12, 12, 'en', 'Nephrologist (Kidney Specialist)', ''),
(13, 13, 'en', 'Neurologist (incl Headache Specialists)', ''),
(14, 14, 'en', 'OB-GYN (Obstetrician-Gynecologist)', ''),
(15, 15, 'en', 'Ophthalmologist', ''),
(16, 16, 'en', 'Optometrist', ''),
(17, 17, 'en', 'Orthodontist', ''),
(18, 18, 'en', 'Orthopedic Surgeon (Orthopedist)', ''),
(19, 19, 'en', 'Pain Management Specialist', ''),
(20, 20, 'en', 'Pediatric Dentist', ''),
(21, 21, 'en', 'Pediatrician', ''),
(22, 22, 'en', 'Physical Therapist (Physical Medicine)', ''),
(23, 23, 'en', 'Plastic Surgeon', ''),
(24, 24, 'en', 'Podiatrist (Foot Specialist)', ''),
(25, 25, 'en', 'Primary Care Doctor (General Practitioner)', ''),
(26, 26, 'en', 'Prosthodontist', ''),
(27, 27, 'en', 'Psychiatrist', ''),
(28, 28, 'en', 'Psychologist', ''),
(29, 29, 'en', 'Pulmonologist (Lung Doctor)', ''),
(30, 30, 'en', 'Radiologist', ''),
(31, 31, 'en', 'Rheumatologist', ''),
(32, 32, 'en', 'Sleep Medicine Specialist', ''),
(33, 33, 'en', 'Sports Medicine Specialist', ''),
(34, 34, 'en', 'Urologist', ''),
(35, 1, 'es', 'Alergólogo (inmunólogo)', 'Un alergólogo/inmunólogo es un médico con entrenamiento especializado en el diagnóstico y tratamiento de las enfermedades alérgicas, asma y enfermedades del sistema inmunológico.'),
(36, 2, 'es', 'Cardiólogo (corazón Doctor)', 'Los cardiólogos son médicos que se especializan en el diagnóstico y tratamiento de enfermedades o condiciones del corazón y los vasos sanguíneos-el sistema cardiovascular.'),
(37, 3, 'es', 'Dentista', ''),
(38, 4, 'es', 'Dermatólogo', ''),
(39, 5, 'es', 'Dietético', ''),
(40, 6, 'es', 'Oído, nariz y garganta doctor', ''),
(41, 7, 'es', 'Endocrinólogo', ''),
(42, 8, 'es', 'Oculista', ''),
(43, 9, 'es', 'Gastroenterólogo', ''),
(44, 10, 'es', 'Hematólogo (especialista en la sangre)', ''),
(45, 11, 'es', 'Especialista en enfermedades infecciosas', ''),
(46, 12, 'es', 'Nefrólogo (especialista en los riñones)', ''),
(47, 13, 'es', 'Neurólogo (especialistas del dolor de cabeza incluido)', ''),
(48, 14, 'es', 'OB-GYN (obstétrico-ginecólogo)', ''),
(49, 15, 'es', 'Oftalmólogo', ''),
(50, 16, 'es', 'Optometrista', ''),
(51, 17, 'es', 'Orthodontist', ''),
(52, 18, 'es', 'Cirujano Ortopédico (ortopedista)', ''),
(53, 19, 'es', 'Especialista en el tratamiento del dolor', ''),
(54, 20, 'es', 'Dentista pediátrico', ''),
(55, 21, 'es', 'Pediatra', ''),
(56, 22, 'es', 'Fisioterapeuta (Medicina Física)', ''),
(57, 23, 'es', 'Cirujano Plástico', ''),
(58, 24, 'es', 'Podólogo (especialista en pies)', ''),
(59, 25, 'es', 'Atención Primaria Doctor (Médico General)', ''),
(60, 26, 'es', 'Prosthodontist', ''),
(61, 27, 'es', 'Psiquiatra', ''),
(62, 28, 'es', 'Psicólogo', ''),
(63, 29, 'es', 'Neumólogo (pulmón Doctor)', ''),
(64, 30, 'es', 'Radiólogo', ''),
(65, 31, 'es', 'Reumatólogo', ''),
(66, 32, 'es', 'Especialista en Medicina del sueño', ''),
(67, 33, 'es', 'Especialista en Medicina del Deporte', ''),
(68, 34, 'es', 'Urólogo', ''),
(69, 1, 'de', 'Allergologe (Immunologe)', 'Ein Allergologe/Immunologe ist ein Arzt mit Facharztausbildung in der Diagnose und Behandlung von allergischen Erkrankungen, Asthma und Erkrankungen des Immunsystems.'),
(70, 2, 'de', 'Kardiologen (Herz-Doktor)', 'Kardiologen sind Ärzte, die in der Diagnose und Behandlung von Krankheiten oder Leiden des Herzens und der Blutgefäße, das Herz-Kreislauf-System zu spezialisieren.'),
(71, 3, 'de', 'Zahnarzt', ''),
(72, 4, 'de', 'Hautarzt', ''),
(73, 5, 'de', 'Ernährungsberater', ''),
(74, 6, 'de', 'Ear, Nose & Throat Arzt (HNO)', ''),
(75, 7, 'de', 'Endokrinologe', ''),
(76, 8, 'de', 'Augenarzt', ''),
(77, 9, 'de', 'Gastroenterologe', ''),
(78, 10, 'de', 'Hämatologe (Blutspezialist)', ''),
(79, 11, 'de', 'Spezialist für Infektionskrankheiten', ''),
(80, 12, 'de', 'Nephrologe (Nierenspezialist)', ''),
(81, 13, 'de', 'Neurologe (inkl. Kopfschmerz-Spezialisten)', ''),
(82, 14, 'de', 'OB-GYN (Gynäkologie und Geburtshilfe)', ''),
(83, 15, 'de', 'Augenarzt', ''),
(84, 16, 'de', 'Optiker', ''),
(85, 17, 'de', 'Orthodontist', ''),
(86, 18, 'de', 'Orthopäde (Orthopäde)', ''),
(87, 19, 'de', 'Schmerz-Management-Spezialist', ''),
(88, 20, 'de', 'Kinderzahnarzt', ''),
(89, 21, 'de', 'Kinderarzt', ''),
(90, 22, 'de', 'Krankengymnast (Physikalische Medizin)', ''),
(91, 23, 'de', 'Plastischer Chirurg', ''),
(92, 24, 'de', 'Fußpflegerin (Fuß-Spezialist)', ''),
(93, 25, 'de', 'Primary Care Arzt (Allgemeinmedizin)', ''),
(94, 26, 'de', 'Prothetiker', ''),
(95, 27, 'de', 'Psychiater', ''),
(96, 28, 'de', 'Psychologe', ''),
(97, 29, 'de', 'Pneumologe (Lungenarzt)', ''),
(98, 30, 'de', 'Röntgenologe', ''),
(99, 31, 'de', 'Rheumatologe', ''),
(100, 32, 'de', 'Schlafmedizin Fach', ''),
(101, 33, 'de', 'Sportmediziner', ''),
(102, 34, 'de', 'Urologe', '');


DROP TABLE IF EXISTS `<DB_PREFIX>ratings_items`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>ratings_items` (
    `item` VARCHAR( 200 ) NOT NULL DEFAULT  '',
    `totalrate` INT( 10 ) NOT NULL DEFAULT  '0',
    `nrrates` INT( 9 ) NOT NULL DEFAULT  '1',
    PRIMARY KEY (  `item` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `<DB_PREFIX>ratings_users`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>ratings_users` (
    `day` INT( 2 ) DEFAULT NULL ,
    `rater` VARCHAR( 15 ) DEFAULT NULL ,
    `item` VARCHAR( 200 ) NOT NULL DEFAULT  ''
) ENGINE = MYISAM DEFAULT CHARSET = utf8;


DROP TABLE IF EXISTS `<DB_PREFIX>orders`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `order_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `membership_plan_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `transaction_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` datetime NULL DEFAULT NULL,
  `payment_date` datetime NULL DEFAULT NULL,
  `payment_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 - Online Order, 1 - PayPal, 2 - 2CO, 3 - Authorize.Net',
  `payment_method` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 - Payment Company Account, 1 - Credit Card, 2 - E-Check',
  `coupon_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_campaign_id` int(10) DEFAULT '0',
  `additional_info` text COLLATE utf8_unicode_ci NOT NULL,
  `cc_type` varchar(20) CHARACTER SET latin1 NOT NULL,
  `cc_holder_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cc_number` varchar(50) CHARACTER SET latin1 NOT NULL,
  `cc_expires_month` varchar(2) CHARACTER SET latin1 NOT NULL,
  `cc_expires_year` varchar(4) CHARACTER SET latin1 NOT NULL,
  `cc_cvv_code` varchar(4) CHARACTER SET latin1 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - preparing, 1 - pending, 2 - paid, 3 - completed, 4 - refunded',
  `status_changed` datetime NULL DEFAULT NULL,
  `email_sent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `payment_type` (`payment_method`),
  KEY `status` (`status`),
  KEY `membership_plan_id` (`membership_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


INSERT INTO `<DB_PREFIX>email_templates` (`id`, `language_id`, `template_code`, `template_name`, `template_subject`, `template_content`, `is_system_template`) VALUES
(NULL, 'en', 'order_accepted_online', 'Email for online placed orders (not paid yet)', 'Your order has been accepted by the system!', 'Dear <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nYour order #{ORDER NUMBER} has been accepted and will be processed shortly.\r\n\r\n{ORDER DETAILS}\r\n\r\nP.S. Please keep this email for your records, as it contains an important information that you may\r\nneed.\r\n\r\n-\r\nSincerely,\r\nCustomer Support', 1),
(NULL, 'es', 'order_accepted_online', 'Email para pedidos en línea (aún sin pagar)', 'Su pedido ha sido aceptado por el sistema!', 'Estimado <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nSu orden {ORDER NUMBER} ha sido aceptada y se procesará en breve.\r\n\r\n{ORDER DETAILS}\r\n\r\nPD Por favor, mantenga este e-mail para sus registros, ya que contiene una información importante que puede\r\nnecesitan.\r\n\r\n-\r\nAtentamente,\r\nAtención al cliente', 1),
(NULL, 'de', 'order_accepted_online', 'Email für Online getätigte Bestellungen (noch nicht bezahlt)', 'Ihre Bestellung wurde vom System akzeptiert wurde!', 'Sehr geehrte <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nIhre Bestellung {ORDER NUMBER} angenommen worden und wird in Kürze bearbeitet.\r\n\r\n{ORDER DETAILS}\r\n\r\nP.S. Bitte bewahren Sie diese E-Mail für Ihre Unterlagen, da sie eine wichtige Information enthält, dass Sie möglicherweise\r\nbenötigen.\r\n\r\n-\r\nMit freundlichen Grüßen,\r\nCustomer Support', 1),
(NULL, 'de', 'order_paid', 'E-Mail für Bestellungen via Zahlungsabwicklung Systeme bezahlt', 'Ihre Bestellung wurde bezahlt und erhalten durch das System!', 'Sehr geehrte <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nDanke für den Kauf von unserer Seite!\r\n\r\nIhre Bestellung {ORDER NUMBER} ist abgeschlossen!\r\n\r\n{ORDER DETAILS}\r\n\r\nP.S. Bitte bewahren Sie diese E-Mail für Ihre Unterlagen, da es eine wichtige Information, die Sie benötigen enthält.\r\n\r\n-\r\nMit freundlichen Grüßen,\r\nCustomer Support', 1),
(NULL, 'en', 'order_paid', 'Email for orders paid via payment processing systems', 'Your order has been paid and received by the system!', 'Dear <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nThank you for purchasing from our site!\r\n\r\nYour order #{ORDER NUMBER} has been completed!\r\n\r\n{ORDER DETAILS}\r\n\r\nP.S. Please keep this email for your records, as it contains an important information that you may need.\r\n\r\n-\r\nSincerely,\r\nCustomer Support\r\n', 1),
(NULL, 'es', 'order_paid', 'Email para los pedidos pagados a través de sistemas de procesamiento de pagos', 'Su pedido ha sido pagado y recibido por el sistema!', 'Estimado <b>{FIRST NAME} {LAST NAME}</b>!\r\n\r\nGracias por la compra de nuestro sitio!\r\n\r\nSu {ORDER NUMBER} fin se ha terminado!\r\n\r\n{ORDER DETAILS}\r\n\r\nPD Por favor, mantenga este e-mail para sus registros, ya que contiene una información importante que usted pueda necesitar.\r\n\r\n-----------------------------\r\nAtentamente,\r\nAtención al cliente', 1);


UPDATE `<DB_PREFIX>vocabulary` SET  `key_text` =  'Datetime & Preis' WHERE `language_id` = 'de' AND `key_value` = '_DATETIME_PRICE_FORMAT';
UPDATE `<DB_PREFIX>vocabulary` SET  `key_text` =  'Datetime & Price' WHERE `language_id` = 'en' AND  `key_value` = '_DATETIME_PRICE_FORMAT';
UPDATE `<DB_PREFIX>vocabulary` SET  `key_text` =  'De fecha y hora y Precio' WHERE `language_id` = 'es' AND  `key_value` = '_DATETIME_PRICE_FORMAT';
UPDATE `<DB_PREFIX>vocabulary` SET  `key_value` =  '_SHOW_ALERT_WINDOW' WHERE `key_value` = '_OPEN_ALERT_WINDOW';        

INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_BEFORE', 'Before'), (NULL, 'es', '_BEFORE', 'Antes'), (NULL, 'de', '_BEFORE', 'Vorher');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_AFTER', 'After'), (NULL, 'es', '_AFTER', 'Después'), (NULL, 'de', '_AFTER', 'Nach');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DECIMALS', 'Decimals'), (NULL, 'es', '_DECIMALS', 'Decimales'), (NULL, 'de', '_DECIMALS', 'Dezimalstellen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYMENTS_SETTINGS', 'Payment Settings'), (NULL, 'es', '_PAYMENTS_SETTINGS', 'Configuración de pagos'), (NULL, 'de', '_PAYMENTS_SETTINGS', 'Zahlungseinstellungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CREATE_APPOINTMENT', 'Make an Appointment'), (NULL, 'es', '_CREATE_APPOINTMENT', 'Haga una cita'), (NULL, 'de', '_CREATE_APPOINTMENT', 'Termin vereinbaren');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_ALBUM_ITEMS_NUMERATION', 'Specifies whether to show items numeration in albums'), (NULL, 'es', '_MS_ALBUM_ITEMS_NUMERATION', 'Especifica si se deben mostrar los elementos de numeración en álbumes'), (NULL, 'de', '_MS_ALBUM_ITEMS_NUMERATION', 'Gibt an, ob Elemente Nummerierung in Alben zeigen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SHOW_ON_DASHBOARD', 'Show on Dashboard'), (NULL, 'es', '_SHOW_ON_DASHBOARD', 'Mostrar en el Dashboard'), (NULL, 'de', '_SHOW_ON_DASHBOARD', 'Anzeigen auf Armaturenbrett');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'es', '_MD_PAYMENTS', 'Los pagos módulo le permite aceptar y gestionar los pagos para las citas de los pacientes con facilidad.'), (NULL, 'en', '_MD_PAYMENTS', 'The module Payments allows you to easily accepts and manage payments for appointments from patients.'), (NULL, 'de', '_MD_PAYMENTS', 'Die Modul-Zahlungen können Sie problemlos akzeptiert und Zahlungen für Termine von Patienten verwalten.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_FORCE_SSL', 'Force SSL'), (NULL, 'es', '_FORCE_SSL', 'Fuerza SSL'), (NULL, 'de', '_FORCE_SSL', 'Force-SSL'), (NULL, 'en', '_FORCE_SSL_ALERT', 'Force site access to always occur under SSL (https) for selected areas. You or site visitors will not be able to access selected areas under non-ssl. Note, you must have SSL enabled on your server to make this option works.'), (NULL, 'es', '_FORCE_SSL_ALERT', 'Acceso Fuerza del sitio que se produzca siempre bajo SSL (https) para las áreas seleccionadas. Usted o visitantes del sitio no será capaz de acceder a las áreas seleccionadas en virtud de que no sea SSL. Tenga en cuenta, debe tener habilitado SSL en el servidor para que esta opción funcione.'), (NULL, 'de', '_FORCE_SSL_ALERT', 'Force-Website Zugriff auf immer unter SSL (https) treten für ausgewählte Bereiche. Sie oder Website-Besucher nicht in der Lage zu ausgewählten Bereichen unter Nicht-SSL-Zugang. Beachten Sie, müssen Sie SSL-fähigen auf Ihrem Server haben, damit diese Option funktioniert.'), (NULL, 'en', '_ENTIRE_SITE', 'Entire Site'), (NULL, 'es', '_ENTIRE_SITE', 'Todo el sitio web'), (NULL, 'de', '_ENTIRE_SITE', 'Die ganze Site'); 	
INSERT INTO `<DB_PREFIX>vocabulary` (`id` ,`language_id` ,`key_value` ,`key_text`) VALUES (NULL, 'en', '_ADMINISTRATOR_ONLY',  'Administrator Only'), (NULL ,  'es',  '_ADMINISTRATOR_ONLY',  'Administrador con permiso de'), (NULL ,  'de',  '_ADMINISTRATOR_ONLY',  'Nur Administrator');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PD_PAYMENT_MODULES', 'Patients, Doctors & Payments'), (NULL, 'es', '_PD_PAYMENT_MODULES', 'Patienten, Ärzte und Zahlungen'), (NULL, 'de', '_PD_PAYMENT_MODULES', 'Pacientes, Médicos y Pagos');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INVALID_FILE_SIZE', 'Invalid file size: _FILE_SIZE_ (max. allowed: _MAX_ALLOWED_)'), (NULL, 'es', '_INVALID_FILE_SIZE', 'Tamaño de archivo no válido: _FILE_SIZE_ (máximo permitido: _MAX_ALLOWED_)'), (NULL, 'de', '_INVALID_FILE_SIZE', 'Ungültige Dateigröße: _FILE_SIZE_ (max. erlaubt: _MAX_ALLOWED_)');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_MEMBERSHIP_PLANS', 'Doctor Membership Plans'), (NULL, 'de', '_DOCTOR_MEMBERSHIP_PLANS', 'Doktor Mitgliedschaft Pläne'), (NULL, 'es', '_DOCTOR_MEMBERSHIP_PLANS', 'Planes de Afiliación Médico');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ADMINS_DOCTORS_AND_PATIENTS', 'Admins, Doctors & Patients'), (NULL, 'es', '_ADMINS_DOCTORS_AND_PATIENTS', 'Admins, Médicos y Pacientes'), (NULL, 'de', '_ADMINS_DOCTORS_AND_PATIENTS', 'Admins, Ärzte & Patienten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INVALID_IMAGE_FILE_TYPE', 'Uploaded file is not a valid image! Please re-enter.'), (NULL, 'es', '_INVALID_IMAGE_FILE_TYPE', 'El archivo cargado no es una imagen válida! Por favor, vuelva a entrar.'), (NULL, 'de', '_INVALID_IMAGE_FILE_TYPE', 'Hochgeladene Datei ist kein gültiges Bild! Bitte geben Sie erneut.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DEFAULT_LANG_DELETE_ALERT', 'You cannot delete default language!'), (NULL, 'es', '_DEFAULT_LANG_DELETE_ALERT', 'No se puede eliminar el idioma por defecto!'), (NULL, 'de', '_DEFAULT_LANG_DELETE_ALERT', 'Sie können die Standardsprache nicht löschen!');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_OWNER', 'Owner'), (NULL, 'es', '_OWNER', 'Propietario'), (NULL, 'de', '_OWNER', 'Eigentümer');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_ADD_WATERMARK', 'Specifies whether to add watermark to doctors images or not'), (NULL, 'es', '_MS_ADD_WATERMARK', 'Especifica si se debe añadir agua a los médicos imágenes o no'), (NULL, 'de', '_MS_ADD_WATERMARK', 'Gibt an, ob Wasserzeichen zu Bildern hinzufügen oder Ärzte nicht');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_WATERMARK_TEXT', 'Watermark text that will be added to images'), (NULL, 'es', '_MS_WATERMARK_TEXT', 'Texto de la marca que se añadirá a las imágenes'), (NULL, 'de', '_MS_WATERMARK_TEXT', 'Wasserzeichen Text, der die Bilder hinzugefügt werden');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MONTHS', 'Months'), (NULL, 'es', '_MONTHS', 'Meses'), (NULL, 'de', '_MONTHS', 'Monate');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_YEARS', 'Years'), (NULL, 'es', '_YEARS', 'Años'), (NULL, 'de', '_YEARS', 'Jahre');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_UNLIMITED', 'Unlimited'), (NULL, 'es', '_UNLIMITED', 'Ilimitado'), (NULL, 'de', '_UNLIMITED', 'Unbegrenzt');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DURATION', 'Duration'), (NULL, 'es', '_DURATION', 'Duración'), (NULL, 'de', '_DURATION', 'Dauer');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_IMAGES_COUNT', 'Images Count'), (NULL, 'es', '_IMAGES_COUNT', 'Imágenes cuentan'), (NULL, 'de', '_IMAGES_COUNT', 'Bilder zählen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_LAST_VISIT', 'Last Visit'), (NULL, 'es', '_LAST_VISIT', 'Ultima visita'), (NULL, 'de', '_LAST_VISIT', 'Letzter Besuch');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_DOCTORS_KEY', 'The keyword that will be replaced with the list of doctors (copy and paste it into the page)'), (NULL, 'de', '_MS_DOCTORS_KEY', 'Das Schlüsselwort, das mit der Liste der Ärzte ersetzt werden (kopieren und ihn in die Seite)'), (NULL, 'es', '_MS_DOCTORS_KEY', 'La palabra clave que se reemplazará con la lista de los médicos (copiar y pegar en la página)');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_THUMBNAIL_CREATE_TOOLTIP', 'creates thumbnail _SIZE_'), (NULL, 'es', '_THUMBNAIL_CREATE_TOOLTIP', 'crea _SIZE_ miniaturas'), (NULL, 'de', '_THUMBNAIL_CREATE_TOOLTIP', 'erstellt Thumbnail _SIZE_');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_IMAGE_SIZE', 'image size'), (NULL, 'es', '_IMAGE_SIZE', 'tamaño de la imagen'), (NULL, 'de', '_IMAGE_SIZE', 'Bildgröße');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SOCIAL_NETWORKS', 'Social Networks'), (NULL, 'es', '_SOCIAL_NETWORKS', 'Redes Sociales'), (NULL, 'de', '_SOCIAL_NETWORKS', 'soziale Netzwerke');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_SCHEDULES_ACCESS_LEVEL', 'Specifies patient access level to view doctor schedules'), (NULL, 'es', '_MS_SCHEDULES_ACCESS_LEVEL', 'Especifica el nivel de acceso de los pacientes para ver los horarios del médico'), (NULL, 'de', '_MS_SCHEDULES_ACCESS_LEVEL', 'Gibt Patienten Zugriffsebene für den Arzt Termine angezeigt');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INSURANCES', 'Insurances'), (NULL, 'es', '_INSURANCES', 'Seguros'), (NULL, 'de', '_INSURANCES', 'Versicherungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INSURANCES_MANAGEMENT', 'Insurances Management'), (NULL, 'es', '_INSURANCES_MANAGEMENT', 'Gestión de los Seguros'), (NULL, 'de', '_INSURANCES_MANAGEMENT', 'Versicherungen-Management');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_APPOINTMENTS_PAGE_SIZE', 'Defines how many doctor profiles will be shown on the search result page'), (NULL, 'es', '_MS_APPOINTMENTS_PAGE_SIZE', 'Define cuántos perfiles médico se mostrará en la página de resultados de búsqueda'), (NULL, 'de', '_MS_APPOINTMENTS_PAGE_SIZE', 'Legt fest, wie viele Arzt-Profile werden auf der Suchergebnisseite angezeigt werden');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MEMBERSHIP_PLANS', 'Membership Plan'), (NULL, 'es', '_MEMBERSHIP_PLANS', 'Plan de membresía'), (NULL, 'de', '_MEMBERSHIP_PLANS', 'Mitgliedschaft Plan');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MEMBERSHIP_EXPIRES', 'Membership Expires'), (NULL, 'es', '_MEMBERSHIP_EXPIRES', 'La suscripción vence'), (NULL, 'de', '_MEMBERSHIP_EXPIRES', 'Mitgliedschaft läuft ab');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MEMBERSHIP_INFO', 'Membership Info'), (NULL, 'es', '_MEMBERSHIP_INFO', 'Afiliación Información'), (NULL, 'de', '_MEMBERSHIP_INFO', 'Informationen zur Mitgliedschaft');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_MAX_IMAGES_ALERT', 'You have reached the maximum number of allowed images for this doctor (according to selected membership plan).'), (NULL, 'es', '_DOCTOR_MAX_IMAGES_ALERT', 'Has alcanzado el número máximo permitido de las imágenes de este médico (según el plan de membresía seleccionada).'), (NULL, 'de', '_DOCTOR_MAX_IMAGES_ALERT', 'Sie haben die maximale Anzahl der erlaubten Bilder für diesen Arzt (nach ausgewählten Mitgliedschaft Plan) erreicht.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_MAX_ADDRESSES_ALERT', 'You have reached the maximum number of allowed addresses for this doctor (according to selected membership plan).'), (NULL, 'es', '_DOCTOR_MAX_ADDRESSES_ALERT', 'Has alcanzado el número máximo de direcciones permitidas para este médico (según el plan de membresía seleccionada).'), (NULL, 'de', '_DOCTOR_MAX_ADDRESSES_ALERT', 'Sie haben die maximale Anzahl der erlaubten Adressen für diesen Arzt (nach ausgewählten Mitgliedschaft Plan) erreicht.');        
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CREATED_BY', 'Created By'), (NULL, 'es', '_CREATED_BY', 'Creado por'), (NULL, 'de', '_CREATED_BY', 'Erstellt von');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_TODAY_APPOINTMENTS', 'Today''s Appointments'), (NULL, 'es', '_TODAY_APPOINTMENTS', 'Las citas de hoy'), (NULL, 'de', '_TODAY_APPOINTMENTS', 'Heutige Termine');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ALREADY_HAVE_APPOINTMENT', 'You already have an appointment to doctor: _DOCTOR_ at _DATE_! <br>Please choose another day or <a href=index.php?patient=my_appointments>cancel</a> a previous appointment to book another one.'), (NULL, 'es', '_ALREADY_HAVE_APPOINTMENT', 'Usted ya tiene una cita con el médico: _DOCTOR_ en _DATE_! <br>Por favor, elija otro día o <a href=index.php?patient=my_appointments>Cancelar</a> una cita previa para reservar otro.'), (NULL, 'de', '_ALREADY_HAVE_APPOINTMENT', 'Sie haben bereits einen Termin für den Arzt: _DOCTOR_ bei _DATE_! <br>Bitte wählen Sie einen anderen Tag oder <a href=index.php?patient=my_appointments>abbrechen</a> einen früheren Termin vereinbaren, um ein anderes zu buchen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_HIDDEN', 'Versteckt'), (NULL, 'en', '_HIDDEN', 'Hidden'), (NULL, 'es', '_HIDDEN', 'Oculto'), (NULL, 'de', '_DISABLED', 'behindert'), (NULL, 'en', '_DISABLED', 'disabled'), (NULL, 'es', '_DISABLED', 'discapacitado');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_WILL_YOU_USE_INSURANCE', 'Will you use insurance?'), (NULL, 'es', '_WILL_YOU_USE_INSURANCE', '¿Va a utilizar el seguro?'), (NULL, 'de', '_WILL_YOU_USE_INSURANCE', 'Werden Sie Versicherungs benutzen?');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INSURANCE', 'Insurance'), (NULL, 'es', '_INSURANCE', 'Seguro'), (NULL, 'de', '_INSURANCE', 'Versicherung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_EMAIL_TEMPLATES_EDITOR', 'Email Templates Editor'), (NULL, 'es', '_EMAIL_TEMPLATES_EDITOR', 'Plantillas de correo electrónico del editor'), (NULL, 'de', '_EMAIL_TEMPLATES_EDITOR', 'E-Mail-Editor');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_STATES', 'States'), (NULL, 'es', '_STATES', 'Unidos'), (NULL, 'de', '_STATES', 'Staaten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_LICENSE_NUMBER', 'License Number'), (NULL, 'es', '_LICENSE_NUMBER', 'Número de licencia'), (NULL, 'de', '_LICENSE_NUMBER', 'Lizenznummer');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VIEW_ALL', 'View All'), (NULL, 'es', '_VIEW_ALL', 'Ver todos'), (NULL, 'de', '_VIEW_ALL', 'Alle anzeigen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MENUS_DISABLED_ALERT', 'Take in account that some menus may be disabled for this template.'), (NULL, 'es', '_MENUS_DISABLED_ALERT', 'Tome en cuenta que algunos menús pueden ser deshabilitados para esta plantilla.'), (NULL, 'de', '_MENUS_DISABLED_ALERT', 'Nehmen Sie in Rechnung, dass einige Menüs für diese Vorlage kann deaktiviert werden.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_WEEK_START_DAY', 'Woche Starttag'),(NULL, 'en', '_WEEK_START_DAY', 'Week Start Day'),(NULL, 'es', '_WEEK_START_DAY', 'Semana Día de inicio');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ADMIN_FOLDER_CREATION_ERROR', 'Failed to create folder for this author in <b>images/upload/</b> directory. For stable work of the script please create this folder manually.'), (NULL, 'es', '_ADMIN_FOLDER_CREATION_ERROR', 'No se pudo crear la carpeta para este autor en <b>images/upload/</b> directorio. Para el trabajo estable de la secuencia de comandos por favor crear esta carpeta de forma manual.'), (NULL, 'de', '_ADMIN_FOLDER_CREATION_ERROR', 'Fehler beim Ordner zu diesem Autor in <b>images/upload/</b> Verzeichnis. Für eine stabile Arbeit des Skripts erstellen Sie bitte diesen Ordner manuell.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NEW_APPOINTMENT', 'New Appointment'), (NULL, 'es', '_NEW_APPOINTMENT', 'Nueva cita'), (NULL, 'de', '_NEW_APPOINTMENT', 'Neueinstellung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NEXT_APPOINTMENT_AT', 'Next appointment at'), (NULL, 'es', '_NEXT_APPOINTMENT_AT', 'Próxima cita en'), (NULL, 'de', '_NEXT_APPOINTMENT_AT', 'Nächster Termin bei');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NEXT_APPT_IN_2_MONTHS', 'Next appointment more than in 2 months'), (NULL, 'es', '_NEXT_APPT_IN_2_MONTHS', 'Próxima cita más que en 2 meses'), (NULL, 'de', '_NEXT_APPT_IN_2_MONTHS', 'Nächster Termin mehr als 2 Monaten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NO_APPOINTMENTS_AVAILABLE', 'No appointments available at this time'), (NULL, 'es', '_NO_APPOINTMENTS_AVAILABLE', 'No hay citas disponibles en este momento'), (NULL, 'de', '_NO_APPOINTMENTS_AVAILABLE', 'Keine Termine zu diesem Zeitpunkt');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_WEEK_DAY', 'Week Day'), (NULL, 'es', '_WEEK_DAY', 'Día de la semana'), (NULL, 'de', '_WEEK_DAY', 'Tag der Woche');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_REMOVE_ACCOUNT_PASSWORD_CONFIRM', 'Enter your password to confirm removing account'), (NULL, 'es', '_REMOVE_ACCOUNT_PASSWORD_CONFIRM', 'Ingrese su contraseña para confirmar la eliminación de la cuenta'), (NULL, 'de', '_REMOVE_ACCOUNT_PASSWORD_CONFIRM', 'Geben Sie Ihr Passwort zur Bestätigung Entfernen Konto');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_ALLOW_MULTIPLE_APPOINTMENTS', 'Specifies whether to allow multiple appointments to the doctor at the same day'), (NULL, 'es', '_MS_ALLOW_MULTIPLE_APPOINTMENTS', 'Especifica si se permite a varias citas al médico en el mismo día'), (NULL, 'de', '_MS_ALLOW_MULTIPLE_APPOINTMENTS', 'Gibt an, ob mehrere Termine mit dem Arzt am gleichen Tag ermöglichen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_APPOINTMENTS_IS_ACTIVE', 'Activate Appointments'), (NULL, 'es', '_MSN_APPOINTMENTS_IS_ACTIVE', 'Activar Nombramientos'), (NULL, 'de', '_MSN_APPOINTMENTS_IS_ACTIVE', 'Aktivieren Termine');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_MAXIMUM_ALLOWED_APPOINTMENTS', 'Maximum Allowed Appointments'), (NULL, 'es', '_MSN_MAXIMUM_ALLOWED_APPOINTMENTS', 'Máximo Citas mascotas'), (NULL, 'de', '_MSN_MAXIMUM_ALLOWED_APPOINTMENTS', 'Maximal erlaubt Termine');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_APPROVAL_REQUIRED', 'Approval Required'), (NULL, 'es', '_MSN_APPROVAL_REQUIRED', 'Aprobación requerida'), (NULL, 'de', '_MSN_APPROVAL_REQUIRED', 'Genehmigung erforderlich');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DELAY_SLOTS', 'Delay Slots'), (NULL, 'es', '_MSN_DELAY_SLOTS', 'Ranuras de retardo'), (NULL, 'de', '_MSN_DELAY_SLOTS', 'Verzögerungsschlitze');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SEND_APPT_NOTIF_TO_ADMIN', 'Send Notification To Admin'), (NULL, 'es', '_MSN_SEND_APPT_NOTIF_TO_ADMIN', 'Enviar notificación al administrador'), (NULL, 'de', '_MSN_SEND_APPT_NOTIF_TO_ADMIN', 'Benachrichtigung an Admin');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SEND_APPT_NOTIF_TO_DOCTOR', 'Send Notification To Doctor'), (NULL, 'es', '_MSN_SEND_APPT_NOTIF_TO_DOCTOR', 'Enviar notificación al doctor'), (NULL, 'de', '_MSN_SEND_APPT_NOTIF_TO_DOCTOR', 'Mitteilung senden zum Doktor');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_APPT_CANCELLATION_PERIOD', 'Cancellation Period'), (NULL, 'es', '_MSN_APPT_CANCELLATION_PERIOD', 'Período de cancelación'), (NULL, 'de', '_MSN_APPT_CANCELLATION_PERIOD', 'Kündigungsfrist');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_BANNERS_IS_ACTIVE', 'Activate Banners'), (NULL, 'es', '_MSN_BANNERS_IS_ACTIVE', 'Activar Banners'), (NULL, 'de', '_MSN_BANNERS_IS_ACTIVE', 'Banner zu aktivieren');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ROTATION_TYPE', 'Rotation Type'), (NULL, 'es', '_MSN_ROTATION_TYPE', 'Tipo de rotación'), (NULL, 'de', '_MSN_ROTATION_TYPE', 'Rotationstyp');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ROTATE_DELAY', 'Rotation Delay'), (NULL, 'es', '_MSN_ROTATE_DELAY', 'Rotación de retardo'), (NULL, 'de', '_MSN_ROTATE_DELAY', 'Rotation Verzögerung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_BANNERS_CAPTION_HTML', 'HTML in Slideshow Caption'), (NULL, 'es', '_MSN_BANNERS_CAPTION_HTML', 'HTML en Presentación Leyenda'), (NULL, 'de', '_MSN_BANNERS_CAPTION_HTML', 'HTML-Diashow in Caption');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_COMMENTS_ALLOW', 'Allow Comments'), (NULL, 'es', '_MSN_COMMENTS_ALLOW', 'Permitir comentarios'), (NULL, 'de', '_MSN_COMMENTS_ALLOW', 'Kommentare zulassen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_USER_TYPE', 'User Type'), (NULL, 'es', '_MSN_USER_TYPE', 'Tipo de usuario'), (NULL, 'de', '_MSN_USER_TYPE', 'Benutzertyp');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_COMMENTS_LENGTH', 'Comment Length'), (NULL, 'es', '_MSN_COMMENTS_LENGTH', 'Comentario Largo'), (NULL, 'de', '_MSN_COMMENTS_LENGTH', 'Kommentar Länge');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_MULTIPLE_APPOINTMENTS', 'Allow Multiple Appointments'), (NULL, 'es', '_MSN_ALLOW_MULTIPLE_APPOINTMENTS', 'Permitir múltiples citas'), (NULL, 'de', '_MSN_ALLOW_MULTIPLE_APPOINTMENTS', 'Lassen Sie mehrere Termine');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_IMAGE_VERIFICATION_ALLOW', 'Image Verification'), (NULL, 'es', '_MSN_IMAGE_VERIFICATION_ALLOW', 'Verificación de la imagen'), (NULL, 'de', '_MSN_IMAGE_VERIFICATION_ALLOW', 'Bild Verifikation');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_COMMENTS_PAGE_SIZE', 'Comments Per Page'), (NULL, 'es', '_MSN_COMMENTS_PAGE_SIZE', 'Comentarios Por Pagina'), (NULL, 'de', '_MSN_COMMENTS_PAGE_SIZE', 'Kommentare pro Seite');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PRE_MODERATION_ALLOW', 'Comments Pre-Moderation'), (NULL, 'es', '_MSN_PRE_MODERATION_ALLOW', 'Comentarios Moderación Pre'), (NULL, 'de', '_MSN_PRE_MODERATION_ALLOW', 'Kommentare Pre-Moderation');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DELETE_PENDING_TIME', 'Pending Time'), (NULL, 'es', '_MSN_DELETE_PENDING_TIME', 'Tiempo de espera'), (NULL, 'de', '_MSN_DELETE_PENDING_TIME', 'Bis Zeit');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_EMAIL', 'Contact Email'), (NULL, 'es', '_MSN_EMAIL', 'Correo electrónico de contacto'), (NULL, 'de', '_MSN_EMAIL', 'Kontakt E-Mail');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_CONTACT_US_KEY', 'Contact Key'), (NULL, 'es', '_MSN_CONTACT_US_KEY', 'Contacto Llave'), (NULL, 'de', '_MSN_CONTACT_US_KEY', 'Kontakt Schlüssel');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_IS_SEND_DELAY', 'Sending Delay'), (NULL, 'es', '_MSN_IS_SEND_DELAY', 'El envío de Delay'), (NULL, 'de', '_MSN_IS_SEND_DELAY', 'Senden Verzögerung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DELAY_LENGTH', 'Delay Length'), (NULL, 'es', '_MSN_DELAY_LENGTH', 'Retardo Largo'), (NULL, 'de', '_MSN_DELAY_LENGTH', 'Delay-Länge');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DOCTORS_KEY', 'Doctors Key'), (NULL, 'es', '_MSN_DOCTORS_KEY', 'Los médicos clave'), (NULL, 'de', '_MSN_DOCTORS_KEY', 'Ärzte Key');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_DOCTORS_LOGIN', 'Allow Doctors to Login'), (NULL, 'es', '_MSN_ALLOW_DOCTORS_LOGIN', 'Permiten a los médicos Login'), (NULL, 'de', '_MSN_ALLOW_DOCTORS_LOGIN', 'Lassen Ärzte zum Login');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ADD_WATERMARK', 'Add Watermark to Images'), (NULL, 'es', '_MSN_ADD_WATERMARK', 'Añadir marcas de agua a las imágenes'), (NULL, 'de', '_MSN_ADD_WATERMARK', 'Wasserzeichen hinzufügen, um Bilder');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_WATERMARK_TEXT', 'Watermark Text'), (NULL, 'es', '_MSN_WATERMARK_TEXT', 'Estampilla de texto'), (NULL, 'de', '_MSN_WATERMARK_TEXT', 'Wasserzeichentext');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_FAQ_IS_ACTIVE', 'Activate FAQ'), (NULL, 'es', '_MSN_FAQ_IS_ACTIVE', 'Activar FAQ'), (NULL, 'de', '_MSN_FAQ_IS_ACTIVE', 'Aktivieren FAQ');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_IMAGE_GALLERY_TYPE', 'Image Gallery Type'), (NULL, 'es', '_MSN_IMAGE_GALLERY_TYPE', 'Galería de imágenes Tipo'), (NULL, 'de', '_MSN_IMAGE_GALLERY_TYPE', 'Bildergalerie Art');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_VIDEO_GALLERY_TYPE', 'Video Gallery Type'), (NULL, 'es', '_MSN_VIDEO_GALLERY_TYPE', 'La galería de video Tipo'), (NULL, 'de', '_MSN_VIDEO_GALLERY_TYPE', 'Videogalerie Art');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_GALLERY_KEY', 'Gallery Key'), (NULL, 'es', '_MSN_GALLERY_KEY', 'Galería clave'), (NULL, 'de', '_MSN_GALLERY_KEY', 'Galerie Key');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUM_ICON_WIDTH', 'Album Icon Width'), (NULL, 'es', '_MSN_ALBUM_ICON_WIDTH', 'Álbum Icono Ancho'), (NULL, 'de', '_MSN_ALBUM_ICON_WIDTH', 'Album Ikone Breite');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUM_ICON_HEIGHT', 'Album Icon Height'), (NULL, 'es', '_MSN_ALBUM_ICON_HEIGHT', 'Icono Álbum Altura'), (NULL, 'de', '_MSN_ALBUM_ICON_HEIGHT', 'Album Icon Höhe');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUMS_PER_LINE', 'Albums Per Line'), (NULL, 'es', '_MSN_ALBUMS_PER_LINE', 'Álbumes por línea'), (NULL, 'de', '_MSN_ALBUMS_PER_LINE', 'Alben pro Zeile');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUM_KEY', 'Album Key'), (NULL, 'es', '_MSN_ALBUM_KEY', 'Clave álbum'), (NULL, 'de', '_MSN_ALBUM_KEY', 'Album Schlüssel');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_GALLERY_WRAPPER', 'HTML Wrapping Tag'), (NULL, 'es', '_MSN_GALLERY_WRAPPER', 'Envolver HTML Tag'), (NULL, 'de', '_MSN_GALLERY_WRAPPER', 'HTML-Wrapping-Tag');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUM_ITEMS_COUNT', 'Show Items Count in Album'), (NULL, 'es', '_MSN_ALBUM_ITEMS_COUNT', 'Mostrar Items Count en álbum'), (NULL, 'de', '_MSN_ALBUM_ITEMS_COUNT', 'Artikel anzeigen in Album Graf');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_GOOGLE_MAPS_API_KEY', 'Google Maps API Key'), (NULL, 'es', '_MSN_GOOGLE_MAPS_API_KEY', 'Google Maps API Key'), (NULL, 'de', '_MSN_GOOGLE_MAPS_API_KEY', 'Google Maps API Key');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_GOOGLE_MAP_WIDTH', 'Map Image Width'), (NULL, 'es', '_MSN_GOOGLE_MAP_WIDTH', 'Mapa ancho de imagen'), (NULL, 'de', '_MSN_GOOGLE_MAP_WIDTH', 'Karte Bildbreite');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_GOOGLE_MAP_HEIGHT', 'Map Image Height'), (NULL, 'es', '_MSN_GOOGLE_MAP_HEIGHT', 'Mapa alto de imagen'), (NULL, 'de', '_MSN_GOOGLE_MAP_HEIGHT', 'Karte Bildhöhe');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_NEWS_COUNT', 'News Count'), (NULL, 'es', '_MSN_NEWS_COUNT', 'Conde Noticias'), (NULL, 'de', '_MSN_NEWS_COUNT', 'Nachrichten Graf');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_NEWS_HEADER_LENGTH', 'News Header Length'), (NULL, 'es', '_MSN_NEWS_HEADER_LENGTH', 'Longitud de la cabecera Noticias'), (NULL, 'de', '_MSN_NEWS_HEADER_LENGTH', 'Nachrichten-Header-Länge');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_NEWS_RSS', 'News RSS'), (NULL, 'es', '_MSN_NEWS_RSS', 'RSS de las Noticias'), (NULL, 'de', '_MSN_NEWS_RSS', 'RSS-News');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SHOW_NEWS_BLOCK', 'News Block'), (NULL, 'es', '_MSN_SHOW_NEWS_BLOCK', 'Bloque de noticias'), (NULL, 'de', '_MSN_SHOW_NEWS_BLOCK', 'Nachrichten blockieren');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SHOW_NEWSLETTER_SUBSCRIBE_BLOCK', 'Newsletter Subscription'), (NULL, 'es', '_MSN_SHOW_NEWSLETTER_SUBSCRIBE_BLOCK', 'Suscripción boletín'), (NULL, 'de', '_MSN_SHOW_NEWSLETTER_SUBSCRIBE_BLOCK', 'Newsletter Abonnement');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_ADDING_BY_ADMIN', 'Admin Creates Patients'), (NULL, 'es', '_MSN_ALLOW_ADDING_BY_ADMIN', 'Administración Crea pacientes'), (NULL, 'de', '_MSN_ALLOW_ADDING_BY_ADMIN', 'Admin Erstellt Patienten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_PATIENTS_LOGIN', 'Allow Patients to Login'), (NULL, 'es', '_MSN_ALLOW_PATIENTS_LOGIN', 'Permitir que los pacientes Login'), (NULL, 'de', '_MSN_ALLOW_PATIENTS_LOGIN', 'Patienten ermöglichen, sich in');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_PATIENTS_REGISTRATION', 'Allow Patients to Register'), (NULL, 'es', '_MSN_ALLOW_PATIENTS_REGISTRATION', 'Permitir que los pacientes registren'), (NULL, 'de', '_MSN_ALLOW_PATIENTS_REGISTRATION', 'Lassen Patienten registrieren');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PATIENTS_IMAGE_VERIFICATION', 'Image Verification'), (NULL, 'es', '_MSN_PATIENTS_IMAGE_VERIFICATION', 'Verificación de la imagen'), (NULL, 'de', '_MSN_PATIENTS_IMAGE_VERIFICATION', 'Bild Verifikation');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_REMEMBER_ME', 'Remember Me'), (NULL, 'es', '_MSN_REMEMBER_ME', 'Recordarme'), (NULL, 'de', '_MSN_REMEMBER_ME', 'Angemeldet bleiben');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_DOCTORS_REGISTRATION', 'Allow Doctors to Register'), (NULL, 'es', '_MSN_ALLOW_DOCTORS_REGISTRATION', 'Permiten a los médicos Registro'), (NULL, 'de', '_MSN_ALLOW_DOCTORS_REGISTRATION', 'Lassen Sie sich registrieren Ärzte');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_REG_CONFIRMATION', 'Confirmation Type'), (NULL, 'es', '_MSN_REG_CONFIRMATION', 'Tipo de confirmación'), (NULL, 'de', '_MSN_REG_CONFIRMATION', 'Bestätigung Typ');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DOCTORS_IMAGE_VERIFICATION', 'Image Verification'), (NULL, 'es', '_MSN_DOCTORS_IMAGE_VERIFICATION', 'Verificación de la imagen'), (NULL, 'de', '_MSN_DOCTORS_IMAGE_VERIFICATION', 'Bild Verifikation');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALERT_ADMIN_NEW_REGISTRATION', 'Alert Admin On New Registration'), (NULL, 'es', '_MSN_ALERT_ADMIN_NEW_REGISTRATION', 'Administrador Alerta en Nuevo Registro'), (NULL, 'de', '_MSN_ALERT_ADMIN_NEW_REGISTRATION', 'Alert-Admin über Neu Registrierung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_DOCTORS_RESET_PASSWORDS', 'Allow Reset Passwords'), (NULL, 'es', '_MSN_ALLOW_DOCTORS_RESET_PASSWORDS', 'Permitir restablecer las contraseñas'), (NULL, 'de', '_MSN_ALLOW_DOCTORS_RESET_PASSWORDS', 'Lassen Passwörter zurücksetzen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ADMIN_CHANGE_DOCTOR_PASSWORD', 'Admin Changes Doctor Password'), (NULL, 'es', '_MSN_ADMIN_CHANGE_DOCTOR_PASSWORD', 'Administración Cambia doctor Contraseña'), (NULL, 'de', '_MSN_ADMIN_CHANGE_DOCTOR_PASSWORD', 'Ändert Doktor Admin Passwort');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_DOCTORS_SEARCH_NAME', 'Allow Search By Name'), (NULL, 'es', '_MSN_ALLOW_DOCTORS_SEARCH_NAME', 'Permitir Buscar por Nombre'), (NULL, 'de', '_MSN_ALLOW_DOCTORS_SEARCH_NAME', 'Lassen Suche nach Name');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_DOCTORS_SEARCH_LOCATION', 'Allow Search By Location'), (NULL, 'es', '_MSN_ALLOW_DOCTORS_SEARCH_LOCATION', 'Permitir Buscar por Ubicación'), (NULL, 'de', '_MSN_ALLOW_DOCTORS_SEARCH_LOCATION', 'Nach Ort suchen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALLOW_PATIENTS_RESET_PASSWORDS', 'Allow Reset Passwords'), (NULL, 'es', '_MSN_ALLOW_PATIENTS_RESET_PASSWORDS', 'Permitir restablecer las contraseñas'), (NULL, 'de', '_MSN_ALLOW_PATIENTS_RESET_PASSWORDS', 'Lassen Passwörter zurücksetzen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ADMIN_CHANGE_PATIENT_PASSWORD', 'Admin Changes Patient Password'), (NULL, 'es', '_MSN_ADMIN_CHANGE_PATIENT_PASSWORD', 'Administrador cambia la contraseña del Paciente'), (NULL, 'de', '_MSN_ADMIN_CHANGE_PATIENT_PASSWORD', 'Admin-Passwort ändert Patienten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_REMINDER_TYPE', 'Reminder Type'), (NULL, 'es', '_MSN_REMINDER_TYPE', 'Tipo Recordatorio'), (NULL, 'de', '_MSN_REMINDER_TYPE', 'Reminder Typ');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PATIENT_ARRIVAL_REMINDER', 'Patient Arrival Reminder'), (NULL, 'es', '_MSN_PATIENT_ARRIVAL_REMINDER', 'Recordatorio de la llegada del paciente'), (NULL, 'de', '_MSN_PATIENT_ARRIVAL_REMINDER', 'Patient Ankunft Erinnerung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DOCTOR_CONFIRM_REMINDER', 'Doctor Confirm Reminder'), (NULL, 'es', '_MSN_DOCTOR_CONFIRM_REMINDER', 'Médico Confirmar Recordatorio'), (NULL, 'de', '_MSN_DOCTOR_CONFIRM_REMINDER', 'Erinnerung Arzt bestätigen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PATIENT_CONFIRM_REMINDER', 'Patient Confirm Reminder'), (NULL, 'es', '_MSN_PATIENT_CONFIRM_REMINDER', 'Confirmar Recordatorio Paciente'), (NULL, 'de', '_MSN_PATIENT_CONFIRM_REMINDER', 'Patienten bestätigen Erinnerung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ALBUM_ITEMS_NUMERATION', 'Show Items Numeration in Album'), (NULL, 'es', '_MSN_ALBUM_ITEMS_NUMERATION', 'Mostrar elementos Numeración en álbum'), (NULL, 'de', '_MSN_ALBUM_ITEMS_NUMERATION', 'Artikel anzeigen Nummerierung in Album');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENTS_ACTIVE', 'Activate Payments'), (NULL, 'es', '_MSN_PAYMENTS_ACTIVE', 'Activar Pagos'), (NULL, 'de', '_MSN_PAYMENTS_ACTIVE', 'Aktivieren Zahlungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENT_METHOD_ONLINE', '&#8226; ''On-line Order'' Payment Method'), (NULL, 'es', '_MSN_PAYMENT_METHOD_ONLINE', '&#8226; ''Orden en línea'' Método de pago'), (NULL, 'de', '_MSN_PAYMENT_METHOD_ONLINE', '&#8226; ''Online-Bestellung'' Payment Method');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_ONLINE_COLLECT_CREDIT_CARD', '&#8226; Credit Cards for ''On-line Orders'''), (NULL, 'es', '_MSN_ONLINE_COLLECT_CREDIT_CARD', '&#8226; Tarjetas de crédito para ''Los pedidos en línea'''), (NULL, 'de', '_MSN_ONLINE_COLLECT_CREDIT_CARD', '&#8226; Kreditkarten für ''Online-Bestellungen''');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENT_METHOD_PAYPAL', '&#8226; PayPal Payment Method'), (NULL, 'es', '_MSN_PAYMENT_METHOD_PAYPAL', '&#8226; Método de pago PayPal'), (NULL, 'de', '_MSN_PAYMENT_METHOD_PAYPAL', '&#8226; PayPal Zahlungsmethode');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYPAL_EMAIL', '&nbsp; PayPal Email'), (NULL, 'es', '_MSN_PAYPAL_EMAIL', '&nbsp; PayPal Email'), (NULL, 'de', '_MSN_PAYPAL_EMAIL', '&nbsp; PayPal E-Mail');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENT_METHOD_2CO', '&#8226; 2CO Payment Method'), (NULL, 'es', '_MSN_PAYMENT_METHOD_2CO', '&#8226; 2CO Método de pago'), (NULL, 'de', '_MSN_PAYMENT_METHOD_2CO', '&#8226; 2CO Zahlungsmethode');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_TWO_CHECKOUT_VENDOR', '&nbsp; 2CO Vendor ID'), (NULL, 'es', '_MSN_TWO_CHECKOUT_VENDOR', '&nbsp; 2CO identificación del vendedor'), (NULL, 'de', '_MSN_TWO_CHECKOUT_VENDOR', '&nbsp; 2CO Hersteller-ID');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENT_METHOD_AUTHORIZE', '&#8226; Authorize.Net Payment Method'), (NULL, 'es', '_MSN_PAYMENT_METHOD_AUTHORIZE', '&#8226; Authorize.Net Método de pago'), (NULL, 'de', '_MSN_PAYMENT_METHOD_AUTHORIZE', '&#8226; Authorize.Net Zahlungsmethode');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_AUTHORIZE_LOGIN_ID', '&nbsp; Authorize.Net Login ID'), (NULL, 'es', '_MSN_AUTHORIZE_LOGIN_ID', '&nbsp; Authorize.Net usuario ID'), (NULL, 'de', '_MSN_AUTHORIZE_LOGIN_ID', '&nbsp; Authorize.Net Login-ID');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_AUTHORIZE_TRANSACTION_KEY', '&nbsp; Authorize.Net Transaction Key'), (NULL, 'es', '_MSN_AUTHORIZE_TRANSACTION_KEY', '&nbsp; Authorize.Net Transacción Clave'), (NULL, 'de', '_MSN_AUTHORIZE_TRANSACTION_KEY', '&nbsp; Authorize.Net Transaktionsschlüssel');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_DEFAULT_PAYMENT_SYSTEM', '&nbsp; Default Payment System'), (NULL, 'es', '_MSN_DEFAULT_PAYMENT_SYSTEM', '&nbsp; Por defecto del sistema de pago'), (NULL, 'de', '_MSN_DEFAULT_PAYMENT_SYSTEM', '&nbsp; Standard-Zahlungssystem');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_VAT_VALUE', 'VAT Value'), (NULL, 'es', '_MSN_VAT_VALUE', 'IVA Valor'), (NULL, 'de', '_MSN_VAT_VALUE', 'MwSt. Preis');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SEND_ORDER_COPY_TO_ADMIN', 'Send Order Copy To Admin'), (NULL, 'es', '_MSN_SEND_ORDER_COPY_TO_ADMIN', 'Enviar copia a fin de administrador'), (NULL, 'de', '_MSN_SEND_ORDER_COPY_TO_ADMIN', 'Bestellung abschicken Kopie an Admin');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_PAYMENTS_MODE', 'Payment Mode'), (NULL, 'es', '_MSN_PAYMENTS_MODE', 'Modo de Pago'), (NULL, 'de', '_MSN_PAYMENTS_MODE', 'Zahlungsmodus');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_SCHEDULES_ACCESS_LEVEL', 'Schedules Access Level'), (NULL, 'es', '_MSN_SCHEDULES_ACCESS_LEVEL', 'Nivel Horarios de Acceso'), (NULL, 'de', '_MSN_SCHEDULES_ACCESS_LEVEL', 'Termine Zugriffsebene');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_APPOINTMENTS_PAGE_SIZE', 'Doctors Per Page'), (NULL, 'es', '_MSN_APPOINTMENTS_PAGE_SIZE', 'Los médicos por página'), (NULL, 'de', '_MSN_APPOINTMENTS_PAGE_SIZE', 'Ärzte pro Seite');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_RATINGS_USER_TYPE', 'User Type'), (NULL, 'es', '_MSN_RATINGS_USER_TYPE', 'Tipo de usuario'), (NULL, 'de', '_MSN_RATINGS_USER_TYPE', 'Benutzertyp');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_MULTIPLE_ITEMS_PER_DAY', 'Multiple Items per Day'), (NULL, 'es', '_MSN_MULTIPLE_ITEMS_PER_DAY', 'Varios elementos por Día'), (NULL, 'de', '_MSN_MULTIPLE_ITEMS_PER_DAY', 'Mehrere Artikel pro Tag');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MSN_MAXIMUM_ALLOWED_ORDERS', 'Maximum Allowed Orders'), (NULL, 'es', '_MSN_MAXIMUM_ALLOWED_ORDERS', 'Máximo Órdenes animales'), (NULL, 'de', '_MSN_MAXIMUM_ALLOWED_ORDERS', 'Maximal erlaubt Bestellungen'), (NULL, 'en', '_MS_MAXIMUM_ALLOWED_ORDERS', 'Specifies the maximum number of allowed orders (not completed) per customer'), (NULL, 'es', '_MS_MAXIMUM_ALLOWED_ORDERS', 'Especifica el número máximo permitido de las órdenes (no terminado) por cliente'), (NULL, 'de', '_MS_MAXIMUM_ALLOWED_ORDERS', 'Gibt die maximale Anzahl von erlaubten Befehle (nicht vollendet) pro Kunde');

INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ABOUT', 'About'), (NULL, 'es', '_ABOUT', 'Sobre'), (NULL, 'de', '_ABOUT', 'Über');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CONTACT', 'Contact'), (NULL, 'es', '_CONTACT', 'Contacto'), (NULL, 'de', '_CONTACT', 'Kontakt');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VISIT_PRICE', 'Visit Price'), (NULL, 'es', '_VISIT_PRICE', 'Visita Precio'), (NULL, 'de', '_VISIT_PRICE', 'Besuchen Preis');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ADDITIONAL_DEGREE', 'Additional Degree'), (NULL, 'es', '_ADDITIONAL_DEGREE', 'Grado adicional'), (NULL, 'de', '_ADDITIONAL_DEGREE', 'Zusätzliche Studien');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CANCELED_BY_PATIENT', 'Canceled by patient'), (NULL, 'es', '_CANCELED_BY_PATIENT', 'Cancelado por el paciente'), (NULL, 'de', '_CANCELED_BY_PATIENT', 'Von Patienten abgebrochen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_INTEGRATION', 'Integration'), (NULL, 'en', '_INTEGRATION', 'Integration'), (NULL, 'es', '_INTEGRATION', 'Integración');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_INTEGRATION_MESSAGE', 'Kopieren Sie den Code unten ein und legte es an der entsprechenden Stelle Ihrer Website, um eine <b>Ausstattung</b>-Block zu bekommen.'), (NULL, 'en', '_INTEGRATION_MESSAGE', 'Copy the code below and put it in the appropriate place of your web site to get a <b>Appointments</b> block.'), (NULL, 'es', '_INTEGRATION_MESSAGE', 'Copie el código abajo y lo puso en el lugar correspondiente de su sitio web para obtener una disponibilidad <b>Nombramientos</b> bloque.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_WIDGET_INTEGRATION_MESSAGE', 'Sie können Medical Termin Suchmaschine mit einem anderen bestehenden Website zu integrieren.'), (NULL, 'en', '_WIDGET_INTEGRATION_MESSAGE', 'You may integrate Medical Appointment search engine with another existing web site.'), (NULL, 'es', '_WIDGET_INTEGRATION_MESSAGE', 'Usted puede integrar el motor de búsqueda de Cita Médica con otro sitio web existente.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SHOW_DASHBOARD', 'Show Dashboard'), (NULL, 'es', '_SHOW_DASHBOARD', 'Mostrar Dashboard'), (NULL, 'de', '_SHOW_DASHBOARD', 'Übersicht anzeigen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_EXPERIENCE', 'Experience'), (NULL, 'es', '_EXPERIENCE', 'Experiencia'), (NULL, 'de', '_EXPERIENCE', 'Erfahrung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'de', '_SIDE_PANEL', 'Seitenteil'), (NULL, 'en', '_SIDE_PANEL', 'Side Panel'), (NULL, 'es', '_SIDE_PANEL', 'El panel lateral');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VISIT_REASONS', 'Visit Reasons'), (NULL, 'es', '_VISIT_REASONS', 'Visita Razones'), (NULL, 'de', '_VISIT_REASONS', 'Besuchen Gründe');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VISIT_REASONS_MANAGEMENT', 'Visit Reasons Management'), (NULL, 'es', '_VISIT_REASONS_MANAGEMENT', 'Visita Gestión Razones'), (NULL, 'de', '_VISIT_REASONS_MANAGEMENT', 'Besuchen Gründe Verwaltung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_UPLOAD_IMAGES_TO_GALLERY', 'Click to upload images to gallery.'), (NULL, 'es', '_UPLOAD_IMAGES_TO_GALLERY', 'Haga clic para subir imágenes a la galería.'), (NULL, 'de', '_UPLOAD_IMAGES_TO_GALLERY', 'Klicken Sie auf die Bilder auf Galerie hochladen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SMTP_AUTH', 'SMTP Authentication'), (NULL, 'es', '_SMTP_AUTH', 'Autenticación SMTP'), (NULL, 'de', '_SMTP_AUTH', 'SMTP-Authentifizierung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_RATINGS', 'Ratings'), (NULL, 'es', '_RATINGS', 'Valoraciones'), (NULL, 'de', '_RATINGS', 'Bewertungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_RATINGS_SETTINGS', 'Ratings Settings'), (NULL, 'es', '_RATINGS_SETTINGS', 'Valoraciones Configuración'), (NULL, 'de', '_RATINGS_SETTINGS', 'Bewertungen Einstellungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VISIT_REASON', 'Visit Reason'), (NULL, 'es', '_VISIT_REASON', 'Visita Razón'), (NULL, 'de', '_VISIT_REASON', 'Besuchen Grund');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MD_RATINGS', 'The Ratings module allows your users to rate doctors. The number of votes and average rating will be shown at the doctor''s profile.'), (NULL, 'es', '_MD_RATINGS', 'El módulo de calificación permite a sus usuarios valorar los médicos. El número de votos y la nota media se mostrará en el perfil del médico.'), (NULL, 'de', '_MD_RATINGS', 'Die Bewertungen Modul können Benutzer Ärzte bewerten. Die Stimmenzahl und durchschnittliche Bewertung wird am Profil des Arztes angezeigt.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_RATINGS_USER_TYPE', 'Type of users, who can rate doctors'), (NULL, 'es', '_MS_RATINGS_USER_TYPE', 'Tipo de usuarios, que pueden calificar los médicos'), (NULL, 'de', '_MS_RATINGS_USER_TYPE', 'Typ von Usern, die Ärzte bewerten können');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_MULTIPLE_ITEMS_PER_DAY', 'Specifies whether to allow users to rate multiple items per day or not'), (NULL, 'es', '_MS_MULTIPLE_ITEMS_PER_DAY', 'Especifica si se permite a los usuarios artículos tasas múltiples por día o no'), (NULL, 'de', '_MS_MULTIPLE_ITEMS_PER_DAY', 'Gibt an, ob Benutzer sich mehrere Artikel pro Tag oder nicht zulassen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_UPCOMING_APPOINTMENTS', 'Upcoming Appointments'), (NULL, 'es', '_UPCOMING_APPOINTMENTS', 'Próximas citas'), (NULL, 'de', '_UPCOMING_APPOINTMENTS', 'Upcoming Appointments');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PATIENTS_RATING', 'Patients Rating'), (NULL, 'es', '_PATIENTS_RATING', 'Pacientes Puntuación'), (NULL, 'de', '_PATIENTS_RATING', 'Patienten-Bewertung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ASSIGN_PATIENT', 'Assign Patient'), (NULL, 'es', '_ASSIGN_PATIENT', 'Asigne Paciente'), (NULL, 'de', '_ASSIGN_PATIENT', 'Weisen Patienten');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_VOTE_NOT_REGISTERED', 'Your vote has not been registered! You must be logged in as a patient before you can vote.'), (NULL, 'es', '_VOTE_NOT_REGISTERED', 'Su voto no se ha registrado? Debe estar registrado como paciente antes de poder votar.'), (NULL, 'de', '_VOTE_NOT_REGISTERED', 'Ihre Stimme wurde nicht registriert! Sie müssen als Patienten eingeloggt sein, bevor Sie wählen können.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DAYS_UC', 'Days'), (NULL, 'es', '_DAYS_UC', 'Días'), (NULL, 'de', '_DAYS_UC', 'Tage');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_MISSING_SPECIALITIES_ALERT', 'You still have not added specialties to your profile. We recommend to add at least one speciality to allow patients to see your profile in the search results. Click <a href=''_HREF_''>here</a> to add.'), (NULL, 'es', '_DOCTOR_MISSING_SPECIALITIES_ALERT', '¿Todavía no has añadido especialidades a tu perfil. Se recomienda agregar al menos una especialidad para que los pacientes puedan ver su perfil en los resultados de búsqueda. Haga clic en <a href=''_HREF_''> aquí </ a> añadir.'), (NULL, 'de', '_DOCTOR_MISSING_SPECIALITIES_ALERT', 'Sie haben noch nicht zu Ihrem Profil hinzugefügt Spezialitäten. Wir empfehlen, mindestens eine Spezialität in den Patienten ermöglichen, Ihr Profil in den Suchergebnissen angezeigt. Klicken Sie hier, <a href=''_HREF_''> </ a> hinzufügen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CHANGE_PATIENT', 'Change Patient'), (NULL, 'es', '_CHANGE_PATIENT', 'Cambie Paciente'), (NULL, 'de', '_CHANGE_PATIENT', 'Patient ändern');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NO_PATIENTS_FOUND', 'No Patients Found'), (NULL, 'es', '_NO_PATIENTS_FOUND', 'Ninguno de los pacientes encontrados'), (NULL, 'de', '_NO_PATIENTS_FOUND', 'Keine Patienten gefunden');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_PAYMENTS_ACTIVE', 'Defines whether payments module is active or not'), (NULL, 'es', '_MS_PAYMENTS_ACTIVE', 'Define si el módulo de pagos está activa o no'), (NULL, 'de', '_MS_PAYMENTS_ACTIVE', 'Legt fest, ob Zahlungen Modul aktiv ist oder nicht'), (NULL, 'en', '_MS_PAYMENT_METHOD_ONLINE', 'Specifies whether to allow ''On-line Order'' payment method'), (NULL, 'es', '_MS_PAYMENT_METHOD_ONLINE', 'Especifica si se permite el método ''on-line Orden de pago'), (NULL, 'de', '_MS_PAYMENT_METHOD_ONLINE', 'Gibt an, ob ''On-line Bestellung'' Zahlungsart'), (NULL, 'en', '_MS_ONLINE_COLLECT_CREDIT_CARD', 'Specifies whether to allow collecting of credit card info for ''On-line Orders'''), (NULL, 'es', '_MS_ONLINE_COLLECT_CREDIT_CARD', 'Especifica si se permite la recogida de información de tarjetas de crédito de ''Pedidos on-line'''), (NULL, 'de', '_MS_ONLINE_COLLECT_CREDIT_CARD', 'Gibt an, ob das Sammeln von Kreditkartendaten für ''On-line Bestellungen'''), (NULL, 'en', '_MS_PAYMENT_METHOD_PAYPAL', 'Specifies whether to allow ''PayPal'' payment method'), (NULL, 'es', '_MS_PAYMENT_METHOD_PAYPAL', 'Especifica si se permite ''PayPal'' método de pago'), (NULL, 'de', '_MS_PAYMENT_METHOD_PAYPAL', 'Gibt an, ob ''PayPal'' Zahlungsmethode'), (NULL, 'en', '_MS_PAYPAL_EMAIL', 'Specifies PayPal (business) email '), (NULL, 'es', '_MS_PAYPAL_EMAIL', 'Especifica PayPal (de negocios) de correo electrónico'), (NULL, 'de', '_MS_PAYPAL_EMAIL', 'Gibt PayPal (Geschäft) E-Mail');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_PAYMENT_METHOD_2CO', 'Specifies whether to allow ''2CO'' payment method'), (NULL, 'es', '_MS_PAYMENT_METHOD_2CO', 'Especifica si se permite el método de CO ''2 ''de pago'), (NULL, 'de', '_MS_PAYMENT_METHOD_2CO', 'Gibt an, ob ''2CO'' Zahlungsmethode ermöglichen'), (NULL, 'en', '_MS_TWO_CHECKOUT_VENDOR', 'Specifies 2CO Vendor ID'), (NULL, 'es', '_MS_TWO_CHECKOUT_VENDOR', 'Especifica 2CO Vendor ID'), (NULL, 'de', '_MS_TWO_CHECKOUT_VENDOR', 'Gibt 2CO Vendor ID'), (NULL, 'en', '_MS_PAYMENT_METHOD_AUTHORIZE', 'Specifies whether to allow ''Authorize.Net'' payment method'), (NULL, 'es', '_MS_PAYMENT_METHOD_AUTHORIZE', 'Especifica si se permite el método ''Authorize.Net de pago'), (NULL, 'de', '_MS_PAYMENT_METHOD_AUTHORIZE', 'Gibt an, ob "Authorize.Net" Zahlungsmethode');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_AUTHORIZE_LOGIN_ID', 'Specifies Authorize.Net API Login ID'), (NULL, 'es', '_MS_AUTHORIZE_LOGIN_ID', 'Especifica Authorize.Net API Login ID'), (NULL, 'de', '_MS_AUTHORIZE_LOGIN_ID', 'Gibt Authorize.Net API Login ID'), (NULL, 'en', '_MS_AUTHORIZE_TRANSACTION_KEY', 'Specifies Authorize.Net Transaction Key'), (NULL, 'es', '_MS_AUTHORIZE_TRANSACTION_KEY', 'Especificador Authorize.Net Transaction Key'), (NULL, 'de', '_MS_AUTHORIZE_TRANSACTION_KEY', 'Authorize.Net Transaction Key gibt'), (NULL, 'en', '_MS_DEFAULT_PAYMENT_SYSTEM', 'Specifies default payment processing system'), (NULL, 'es', '_MS_DEFAULT_PAYMENT_SYSTEM', 'Especifica el pago por defecto del sistema de procesamiento'), (NULL, 'de', '_MS_DEFAULT_PAYMENT_SYSTEM', 'Gibt Zahlungsverzug Verarbeitungssystem');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MS_VAT_VALUE', 'Specifies default VAT value for order (in %)'), (NULL, 'es', '_MS_VAT_VALUE', 'Especifica el valor predeterminado de IVA por el orden (en%)'), (NULL, 'de', '_MS_VAT_VALUE', 'Gibt standardmäßige MwSt. Preis-Leistungs-Ordnung (in%)'), (NULL, 'en', '_MS_SEND_ORDER_COPY_TO_ADMIN', 'Specifies whether to allow sending a copy of order to admin'), (NULL, 'es', '_MS_SEND_ORDER_COPY_TO_ADMIN', 'Especifica si se permite el envío de una copia de la orden de admin'), (NULL, 'de', '_MS_SEND_ORDER_COPY_TO_ADMIN', 'Gibt an, ob Sie eine Kopie, um admin'), (NULL, 'en', '_MS_PAYMENTS_MODE', 'Specifies which mode is turned ON for payments'), (NULL, 'es', '_MS_PAYMENTS_MODE', 'Especifica en qué modo está activado para los pagos'), (NULL, 'de', '_MS_PAYMENTS_MODE', 'Gibt an, welcher Modus wird für Zahlungen gedreht');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ADDRESSES_COUNT', 'Addresses Count'), (NULL, 'es', '_ADDRESSES_COUNT', 'Cuentan Direcciones'), (NULL, 'de', '_ADDRESSES_COUNT', 'Adressen zählen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MY_IMAGES', 'My Images'), (NULL, 'es', '_MY_IMAGES', 'Mis Imágenes'), (NULL, 'de', '_MY_IMAGES', 'Meine Bilder');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SET_ADMIN', 'Set Admin'), (NULL, 'es', '_SET_ADMIN', 'Set Admin'), (NULL, 'de', '_SET_ADMIN', 'Stellen Admin');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPLY', 'Apply'), (NULL, 'es', '_APPLY', 'Aplicar'), (NULL, 'de', '_APPLY', 'Anwenden');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_OR', 'or'), (NULL, 'es', '_OR', 'o'), (NULL, 'de', '_OR', 'oder');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_UPGRADE_MEMBERSHIP_ALERT', 'Currently your profile is not available in search. To make it visible for your patients you have to upgrade your membership plan. Click here <a href=_HREF_>here</a> to proceed.'), (NULL, 'es', '_DOCTOR_UPGRADE_MEMBERSHIP_ALERT', 'Actualmente su perfil no está disponible en esta categoría. Para hacerla visible para sus pacientes que tienen que actualizar su plan de membresía. Haga clic aquí <a href=_HREF_>aquí</a> para continuar.'), (NULL, 'de', '_DOCTOR_UPGRADE_MEMBERSHIP_ALERT', 'Aktuell Ihr Profil ist auf der Suche nach nicht zur Verfügung. Um es für Ihre Patienten Sie Ihre Mitgliedschaft Plan Upgrade haben sichtbar zu machen. Klicken Sie hier <a href=_HREF_>hier</a>, um fortzufahren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SELECT_PLAN', 'Select Plan'), (NULL, 'es', '_SELECT_PLAN', 'Seleccionar plan'), (NULL, 'de', '_SELECT_PLAN', 'Plan auswählen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYMENT_TYPE', 'Payment Type'), (NULL, 'es', '_PAYMENT_TYPE', 'Tipo de Pago'), (NULL, 'de', '_PAYMENT_TYPE', 'Zahlungsart');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CLICK_TO_SELECT', 'Click To Select'), (NULL, 'es', '_CLICK_TO_SELECT', 'Haga clic para seleccionar'), (NULL, 'de', '_CLICK_TO_SELECT', 'Klicken Sie auf das');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_ADD_IMAGES_ALERT', 'Currently you have no images in profile gallery. To add more images click here <a href=_HREF_>here</a>.'), (NULL, 'es', '_DOCTOR_ADD_IMAGES_ALERT', 'Actualmente no tiene imágenes en la galería perfil. Para añadir más imágenes haz clic aquí <a href=_HREF_>aquí</a>.'), (NULL, 'de', '_DOCTOR_ADD_IMAGES_ALERT', 'Derzeit haben Sie noch keine Bilder im Profil Galerie. Um weitere Bilder hinzufügen, klicken Sie bitte hier <a href=_HREF_>hier</a>.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_ADD_ADDRESSES_ALERT', 'Currently you have no addresses added in your profile. To add addresses click here <a href=_HREF_>here</a>.'), (NULL, 'es', '_DOCTOR_ADD_ADDRESSES_ALERT', 'Actualmente no tiene direcciones añadidas en su perfil. Para agregar direcciones clic aquí <a href=_HREF_>aquí</a>.'), (NULL, 'de', '_DOCTOR_ADD_ADDRESSES_ALERT', 'Derzeit haben Sie keine Adressen in Ihrem Profil hinzugefügt. Um Adressen hinzufügen, klicken Sie bitte hier <a href=_HREF_>hier</a>.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_EMPTY_PATIENT_NAME_ALERT', 'The patient name is empty! Please enter a patient name for searching or click Set Admin button to assign administrator to this appointment.'), (NULL, 'es', '_EMPTY_PATIENT_NAME_ALERT', 'El nombre del paciente está vacía! Por favor ingrese el nombre del paciente para la búsqueda o haga clic en el botón Set Admin para asignar el administrador de este nombramiento.'), (NULL, 'de', '_EMPTY_PATIENT_NAME_ALERT', 'Der Patientenname ist leer! Bitte geben Sie eine Patientennamen für die Suche oder klicken Sie auf Admin-Taste, um diesen Termin Administrator zuweisen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NO_PATIENTS_FOUND_ALERT', 'No patients found! Try another search or click Set Admin button to assign administrator to this appointment.'), (NULL, 'es', '_NO_PATIENTS_FOUND_ALERT', 'Ninguno de los pacientes encontraron! Realiza una nueva búsqueda o haga clic en el botón Set Admin para asignar el administrador de este nombramiento.'), (NULL, 'de', '_NO_PATIENTS_FOUND_ALERT', 'Keiner der Patienten gefunden! Probieren Sie eine andere Suche oder klicken Sie auf Admin-Taste, um Administrator zu diesem Termin zuweisen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_PROFILE_INACTIVE', 'This doctor''s profile is inactive.'), (NULL, 'es', '_DOCTOR_PROFILE_INACTIVE', 'El perfil de este médico está inactivo.'), (NULL, 'de', '_DOCTOR_PROFILE_INACTIVE', 'Das Profil dieses Arztes ist inaktiv.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SELECT_PATIENT_ALERT', 'Select patient from drop down box and click Apply button to proceed.'), (NULL, 'es', '_SELECT_PATIENT_ALERT', 'Seleccione paciente de lista desplegable y haga clic en el botón de Aceptar para continuar.'), (NULL, 'de', '_SELECT_PATIENT_ALERT', 'Wählen Patienten von Dropdown-Feld und klicken Sie auf die Schaltfläche Übernehmen, um fortzufahren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPOINTMENT_ASSIGNED_TO_PATIENT_ALERT', 'Appointment has been assigned to a selected patient! Click <b>Book Now</b> button to proceed.'), (NULL, 'es', '_APPOINTMENT_ASSIGNED_TO_PATIENT_ALERT', 'Nombramiento haya sido asignado a un paciente seleccionado! Haga clic <b>Reserve Ahora</b> para continuar.'), (NULL, 'de', '_APPOINTMENT_ASSIGNED_TO_PATIENT_ALERT', 'Termin hat zu einem ausgewählten Patienten zugeordnet! Klicken Sie auf <b>Jetzt buchen</b>, um fortzufahren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPOINTMENT_ASSIGNED_TO_ADMIN_ALERT', 'Appointment has been assigned to admin! Click <b>Book Now</b> button to proceed.'), (NULL, 'es', '_APPOINTMENT_ASSIGNED_TO_ADMIN_ALERT', 'Nombramiento ha asignado a Admin! Haga clic <b>Reserve ahora</b> para continuar.'), (NULL, 'de', '_APPOINTMENT_ASSIGNED_TO_ADMIN_ALERT', 'Termin zugewiesen wurde an den Admin! Klicken Sie auf <b>Jetzt buchen</b>, um fortzufahren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPOINTMENT_ASSIGNED_TO_PATIENT_MSG', 'This appointment is assigned to patient: _PATIENT_.'), (NULL, 'es', '_APPOINTMENT_ASSIGNED_TO_PATIENT_MSG', 'Este nombramiento se asigna al paciente: _PATIENT_.'), (NULL, 'de', '_APPOINTMENT_ASSIGNED_TO_PATIENT_MSG', 'Diese Ernennung ist mit Patienten zugeordnet: _PATIENT_.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPOINTMENT_ASSIGNED_TO_ADMIN_MSG', 'This appointment is assigned to administrator.'), (NULL, 'es', '_APPOINTMENT_ASSIGNED_TO_ADMIN_MSG', 'Este nombramiento se asigna al administrador.'), (NULL, 'de', '_APPOINTMENT_ASSIGNED_TO_ADMIN_MSG', 'Dieser Termin wird dem Administrator zugeordnet.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYPAL', 'PayPal'), (NULL, 'es', '_PAYPAL', 'PayPal'), (NULL, 'de', '_PAYPAL', 'PayPal');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ONLINE_ORDER', 'On-line Order'), (NULL, 'es', '_ONLINE_ORDER', 'Orden en línea'), (NULL, 'de', '_ONLINE_ORDER', 'On-line Bestellen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MEMBERSHIP_PLAN', 'Membership Plan'), (NULL, 'es', '_MEMBERSHIP_PLAN', 'Plan de Membresía'), (NULL, 'de', '_MEMBERSHIP_PLAN', 'Mitgliedschaft Plan');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_DOCTOR_DETAILS', 'Doctor Detail'), (NULL, 'es', '_DOCTOR_DETAILS', 'Detalle doctor'), (NULL, 'de', '_DOCTOR_DETAILS', 'Doktor Detail');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_NUMBER', 'Order number'), (NULL, 'es', '_ORDER_NUMBER', 'Número de orden'), (NULL, 'de', '_ORDER_NUMBER', 'Bestell-Nummer'), (NULL, 'en', '_PAYMENT_METHOD', 'Payment Method'), (NULL, 'es', '_PAYMENT_METHOD', 'Forma de Pago'), (NULL, 'de', '_PAYMENT_METHOD', 'Zahlungsweise');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYED_BY', 'Payed by'), (NULL, 'es', '_PAYED_BY', 'Pagado por'), (NULL, 'de', '_PAYED_BY', 'Bezahlt per'), (NULL, 'en', '_ORDER_PRICE', 'Order Price'), (NULL, 'es', '_ORDER_PRICE', 'Orden de precios'), (NULL, 'de', '_ORDER_PRICE', 'Bestellen Preis');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CREDIT_CARD', 'Credit Card'), (NULL, 'es', '_CREDIT_CARD', 'Tarjeta de Crédito'), (NULL, 'de', '_CREDIT_CARD', 'Kreditkarte'), (NULL, 'en', '_CREDIT_CARD_EXPIRES', 'Expires'), (NULL, 'es', '_CREDIT_CARD_EXPIRES', 'Expira'), (NULL, 'de', '_CREDIT_CARD_EXPIRES', 'Ablauf'), (NULL, 'en', '_CREDIT_CARD_NUMBER', 'Credit Card Number'), (NULL, 'es', '_CREDIT_CARD_NUMBER', 'Número de tarjeta'), (NULL, 'de', '_CREDIT_CARD_NUMBER', 'Nummer der Kreditkarte'), (NULL, 'en', '_CREDIT_CARD_TYPE', 'Credit Card Type'), (NULL, 'es', '_CREDIT_CARD_TYPE', 'Tarjeta de Crédito'), (NULL, 'de', '_CREDIT_CARD_TYPE', 'Art der Kreditkarte');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INVOICE', 'Invoice'), (NULL, 'es', '_INVOICE', 'Factura'), (NULL, 'de', '_INVOICE', 'Rechnung'), (NULL, 'en', '_INVOICE_SENT_SUCCESS', 'The invoice has been successfully sent!'), (NULL, 'es', '_INVOICE_SENT_SUCCESS', 'La factura ha sido enviado con éxito!'), (NULL, 'de', '_INVOICE_SENT_SUCCESS', 'Die Rechnung wurde erfolgreich gesendet!');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_STATUS_CHANGED', 'Status Changed'), (NULL, 'es', '_STATUS_CHANGED', 'Cambio en el estado'), (NULL, 'de', '_STATUS_CHANGED', 'Status geändert'), (NULL, 'en', '_CVV_CODE', 'CVV Code'), (NULL, 'es', '_CVV_CODE', 'CVV Código'), (NULL, 'de', '_CVV_CODE', 'CVV-Code'), (NULL, 'en', '_VAT', 'VAT'), (NULL, 'es', '_VAT', 'IVA'), (NULL, 'de', '_VAT', 'Mehrwertsteuer');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CREDIT_CARD_HOLDER_NAME', 'Card Holder''s Name'), (NULL, 'es', '_CREDIT_CARD_HOLDER_NAME', 'Nombre del titular'), (NULL, 'de', '_CREDIT_CARD_HOLDER_NAME', 'Name des Karteninhabers'), (NULL, 'en', '_CC_CARD_HOLDER_NAME_EMPTY', 'No card holder''s name provided! Please re-enter.'), (NULL, 'es', '_CC_CARD_HOLDER_NAME_EMPTY', 'No Nombre del titular de la tarjeta siempre! Por favor, vuelva a introducir.'), (NULL, 'de', '_CC_CARD_HOLDER_NAME_EMPTY', 'No Name des Karteninhabers versehen! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_EXPIRES', 'Expires'), (NULL, 'es', '_EXPIRES', 'Expira'), (NULL, 'de', '_EXPIRES', 'Ablauf'), (NULL, 'en', '_CLEANED', 'Cleaned'), (NULL, 'es', '_CLEANED', 'Limpiar'), (NULL, 'de', '_CLEANED', 'Gereinigt'), (NULL, 'en', '_METHOD', 'Method'), (NULL, 'es', '_METHOD', 'Método'), (NULL, 'de', '_METHOD', 'Methode');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PLAN', 'Plan'), (NULL, 'es', '_PLAN', 'Plan'), (NULL, 'de', '_PLAN', 'Plan'), (NULL, 'en', '_REFUNDED', 'Refunded'), (NULL, 'es', '_REFUNDED', 'Devuelto'), (NULL, 'de', '_REFUNDED', 'Erstattet'), (NULL, 'en', '_PAID', 'Paid'), (NULL, 'es', '_PAID', 'Pagado'), (NULL, 'de', '_PAID', 'Bezahlt');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PENDING', 'Pending'), (NULL, 'es', '_PENDING', 'Pendiente'), (NULL, 'de', '_PENDING', 'Anstehend'), (NULL, 'en', '_PREPARING', 'Preparing'), (NULL, 'es', '_PREPARING', 'Preparación'), (NULL, 'de', '_PREPARING', 'Vorbereitung'), (NULL, 'en', '_PAYMENT_COMPANY_ACCOUNT', 'Payment Company Account'), (NULL, 'es', '_PAYMENT_COMPANY_ACCOUNT', 'Pago de cuenta de la compañía'), (NULL, 'de', '_PAYMENT_COMPANY_ACCOUNT', 'Zahlung Firmenkonto');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYPAL_ORDER', 'PayPal Order'), (NULL, 'es', '_PAYPAL_ORDER', 'PayPal Orden'), (NULL, 'de', '_PAYPAL_ORDER', 'PayPal Auftrag'), (NULL, 'en', '_ORDER_PEPARING_ERROR', 'An error occurred while preparing the order! Please try again later.'), (NULL, 'es', '_ORDER_PEPARING_ERROR', 'Se produjo un error mientras se prepara la orden! Por favor, inténtelo de nuevo más tarde.'), (NULL, 'de', '_ORDER_PEPARING_ERROR', 'Ein Fehler trat bei der Vorbereitung der Bestellung! Bitte versuchen Sie es später erneut.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_CARD_INVALID_FORMAT', 'Credit card number has invalid format! Please re-enter.'), (NULL, 'es', '_CC_CARD_INVALID_FORMAT', 'Número de tarjeta de crédito tiene un formato válido! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_CARD_INVALID_FORMAT', 'Kreditkarten-Nummer hat ungültiges Format! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_CARD_INVALID_NUMBER', 'Credit card number is invalid! Please re-enter.'), (NULL, 'es', '_CC_CARD_INVALID_NUMBER', 'Número de tarjeta de crédito no es válido! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_CARD_INVALID_NUMBER', 'Kreditkarten-Nummer ist ungültig! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_CARD_NO_CVV_NUMBER', 'No CVV Code provided! Please re-enter.'), (NULL, 'es', '_CC_CARD_NO_CVV_NUMBER', 'No Código CVV siempre! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_CARD_NO_CVV_NUMBER', 'Keine CVV-Code zur Verfügung gestellt! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_CARD_WRONG_EXPIRE_DATE', 'Credit card expiry date is wrong! Please re-enter.'), (NULL, 'es', '_CC_CARD_WRONG_EXPIRE_DATE', 'Tarjeta de crédito fecha de caducidad está mal! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_CARD_WRONG_EXPIRE_DATE', 'Kreditkarte Verfallsdatum ist falsch! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_CARD_WRONG_LENGTH', 'Credit card number has a wrong length! Please re-enter.'), (NULL, 'es', '_CC_CARD_WRONG_LENGTH', 'Número de tarjeta de crédito es la longitud del mal! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_CARD_WRONG_LENGTH', 'Kreditkarten-Nummer falsch ist lang! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_NO_CARD_NUMBER', 'No card number provided! Please re-enter.'), (NULL, 'es', '_CC_NO_CARD_NUMBER', 'No hay número de tarjeta de siempre! Por favor, vuelva a introducir.'), (NULL, 'de', '_CC_NO_CARD_NUMBER', 'Keine Kartennummer zur Verfügung gestellt! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_NO_CARD_NUMBER_PROVIDED', 'No card number provided! Please re-enter.'), (NULL, 'es', '_CC_NO_CARD_NUMBER_PROVIDED', 'No hay número de tarjeta de siempre! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_NO_CARD_NUMBER_PROVIDED', 'Keine Kartennummer zur Verfügung gestellt! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CC_UNKNOWN_CARD_TYPE', 'Unknown card type! Please re-enter.'), (NULL, 'es', '_CC_UNKNOWN_CARD_TYPE', 'Tipo de tarjeta desconocido! Por favor, vuelva a entrar.'), (NULL, 'de', '_CC_UNKNOWN_CARD_TYPE', 'Unknown Karte Typ! Bitte erneut eingeben.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_DATE', 'Order Date'), (NULL, 'es', '_ORDER_DATE', 'Fecha del pedido'), (NULL, 'de', '_ORDER_DATE', 'Sortierung: Datum'), (NULL, 'en', '_PAYMENT_SUM', 'Payment Sum'), (NULL, 'es', '_PAYMENT_SUM', 'Pago de Suma'), (NULL, 'de', '_PAYMENT_SUM', 'Die Zahlung Sum'), (NULL, 'en', '_PAYPAL_NOTICE', 'Save time. Pay securely using your stored payment information.<br />Pay with <b>credit card</b>, <b>bank account</b> or <b>PayPal</b> account balance.'), (NULL, 'es', '_PAYPAL_NOTICE', 'Ahorre tiempo. Pague con seguridad con su información almacenada pago.<br /> Pago con <b>tarjeta de crédito</b>, <b> cuenta bancaria</b> o <b>PayPal</b> saldo de la cuenta.'), (NULL, 'de', '_PAYPAL_NOTICE', 'Sparen Sie Zeit. Sicher bezahlen mit Ihrer gespeicherten Zahlungsinformationen. <br />Bezahlen mit <b>Kreditkarte, Bankkonto</b> oder <b>PayPal-Guthaben.</b>');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SUBTOTAL', 'Subtotal'), (NULL, 'es', '_SUBTOTAL', 'Total parcial'), (NULL, 'de', '_SUBTOTAL', 'Zwischensumme');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_2CO_NOTICE', '2CheckOut.com Inc. (Ohio, USA) is an authorized retailer for goods and services.'), (NULL, 'es', '_2CO_NOTICE', '2CheckOut.com Inc. (Ohio, EE.UU.) es un vendedor autorizado de bienes y servicios.'), (NULL, 'de', '_2CO_NOTICE', '2CheckOut.com Inc. (Ohio, USA) is an authorized retailer for goods and services.'), (NULL, 'en', '_2CO_ORDER', '2CO Order'), (NULL, 'es', '_2CO_ORDER', '2CO Orden'), (NULL, 'de', '_2CO_ORDER', '2CO Order'), (NULL, 'en', '_BUY_NOW', 'Buy Now'), (NULL, 'es', '_BUY_NOW', '¡Cómpralo ya!'), (NULL, 'de', '_BUY_NOW', 'Jetzt kaufen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_AUTHORIZE_NET_NOTICE', 'The Authorize.Net payment gateway service provider.'), (NULL, 'es', '_AUTHORIZE_NET_NOTICE', 'El pago Authorize.Net portal de proveedores de servicios.'), (NULL, 'de', '_AUTHORIZE_NET_NOTICE', 'Die Authorize.Net Payment Gateway Service Provider.'), (NULL, 'en', '_AUTHORIZE_NET_ORDER', 'Authorize.Net Order'), (NULL, 'es', '_AUTHORIZE_NET_ORDER', 'Authorize.Net Orden'), (NULL, 'de', '_AUTHORIZE_NET_ORDER', 'Authorize.Net Auftrag');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'es', '_WHAT_IS_CVV', 'Qué es el CVV'), (NULL, 'en', '_WHAT_IS_CVV', 'What is CVV'), (NULL, 'de', '_WHAT_IS_CVV', 'Was ist CVV'), (NULL, 'es', '_PLACE_ORDER', 'Lugar Orden'), (NULL, 'en', '_PLACE_ORDER', 'Place Order'), (NULL, 'de', '_PLACE_ORDER', 'Bestellung aufgeben'), (NULL, 'es', '_SUBMIT_PAYMENT', 'Presentar el pago'), (NULL, 'en', '_SUBMIT_PAYMENT', 'Submit Payment'), (NULL, 'de', '_SUBMIT_PAYMENT', 'Submit Zahlung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_CANCELED', 'Order Canceled'), (NULL, 'es', '_ORDER_CANCELED', 'Pedido cancelado'), (NULL, 'de', '_ORDER_CANCELED', 'Bestellung storniert');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_COMPLETED', 'Order Completed'), (NULL, 'es', '_ORDER_COMPLETED', 'Order Completo'), (NULL, 'de', '_ORDER_COMPLETED', 'Auftrag abgeschlossen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_DETAILS', 'Order Details'), (NULL, 'es', '_ORDER_DETAILS', 'Detalles de pedidos'), (NULL, 'de', '_ORDER_DETAILS', 'Um Details');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_WAS_COMPLETED_MSG', 'Thank you for purchasing from our site! Your order has been completed.'), (NULL, 'es', '_ORDER_WAS_COMPLETED_MSG', 'Muchas gracias por comprar en nuestro sitio! Su pedido ha sido completado.'), (NULL, 'de', '_ORDER_WAS_COMPLETED_MSG', 'Danke für den Kauf von unserer Seite! Ihre Bestellung ist abgeschlossen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_WAS_CANCELED_MSG', 'Your order has been canceled.'), (NULL, 'es', '_ORDER_WAS_CANCELED_MSG', 'Su pedido ha sido cancelado.'), (NULL, 'de', '_ORDER_WAS_CANCELED_MSG', 'Ihre Bestellung wurde storniert.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_ERROR', 'Cannot complete your order! Please try again later.'), (NULL, 'es', '_ORDER_ERROR', 'No se puede completar su solicitud! Por favor, inténtelo de nuevo más tarde.'), (NULL, 'de', '_ORDER_ERROR', 'Kann nicht abgeschlossen werden Ihre Bestellung! Bitte versuchen Sie es später erneut.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_PLACED_MSG', 'Thank you! The order has been accepted and will be processed shortly.'), (NULL, 'es', '_ORDER_PLACED_MSG', '¡Gracias! La orden fue aceptada y se procesan en breve.'), (NULL, 'de', '_ORDER_PLACED_MSG', 'Vielen Dank! Die Bestellung wurde angenommen und wird in Kürze bearbeitet werden.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_OPERATION_COMMON_COMPLETED', 'The operation has been successfully completed!'), (NULL, 'es', '_OPERATION_COMMON_COMPLETED', 'La operación se completó con éxito!'), (NULL, 'de', '_OPERATION_COMMON_COMPLETED', 'Die Operation wurde erfolgreich abgeschlossen!');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_PAYMENT_DATE', 'Payment Date'), (NULL, 'es', '_PAYMENT_DATE', 'Fecha de Pago'), (NULL, 'de', '_PAYMENT_DATE', 'Zahltag');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CREATED_DATE', 'Date Created'), (NULL, 'es', '_CREATED_DATE', 'Fecha de creación'), (NULL, 'de', '_CREATED_DATE', 'Erstellungsdatum');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_BILLING_INFORMATION', 'Billing Information'), (NULL, 'es', '_BILLING_INFORMATION', 'Información de facturación'), (NULL, 'de', '_BILLING_INFORMATION', 'Informationen zur Abrechnung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_TEST_MODE_ALERT_SHORT', 'Payment Module is running in Test Mode!'), (NULL, 'es', '_TEST_MODE_ALERT_SHORT', 'Módulo de pago se está ejecutando en modo de prueba!'), (NULL, 'de', '_TEST_MODE_ALERT_SHORT', 'Zahlung Modul wird im Test-Modus läuft!');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_TEST_MODE_ALERT', 'Test Mode in Payment Module is turned ON! To change current mode click <a href=index.php?admin=mod_payments_settings>here</a>.'), (NULL, 'es', '_TEST_MODE_ALERT', 'Modo de prueba en el módulo de pago se pone en ON! Para cambiar <a actual, haga clic en el modo de href=index.php?admin=mod_payments_settings>aquí </a>.'), (NULL, 'de', '_TEST_MODE_ALERT', 'Testmodus in Zahlung Modul ist eingeschaltet! Um aktuelle Modus ändern, klicken Sie <a href=index.php?admin=mod_payments_settings>hier</a>.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_WORK_PHONE', 'Work Phone'), (NULL, 'es', '_WORK_PHONE', 'Teléfono del trabajo'), (NULL, 'de', '_WORK_PHONE', 'Arbeit Telefon');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MOBILE_WORK_PHONE', 'Mobile Work Phone'), (NULL, 'es', '_MOBILE_WORK_PHONE', 'Teléfono móvil de trabajo'), (NULL, 'de', '_MOBILE_WORK_PHONE', 'Mobile Arbeits Telefon');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_SEND_INVOICE', 'Send Invoice'), (NULL, 'es', '_SEND_INVOICE', 'Enviar factura'), (NULL, 'de', '_SEND_INVOICE', 'Senden Rechnung');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_MY_ORDERS', 'My Orders'), (NULL, 'es', '_MY_ORDERS', 'Mis pedidos'), (NULL, 'de', '_MY_ORDERS', 'Meine Bestellungen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_INCOME', 'Income'), (NULL, 'es', '_INCOME', 'Ingresos'), (NULL, 'de', '_INCOME', 'Einkommen');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'es', '_MAX_ORDERS_ERROR', 'Usted ha alcanzado el número máximo permitido de las órdenes, que aún no ha terminado! Por favor, complete al menos uno de ellos para proceder.'), (NULL, 'en', '_MAX_ORDERS_ERROR', 'You have reached the maximum number of permitted orders, that you have not yet finished! Please complete at least one of them to proceed.'), (NULL, 'de', '_MAX_ORDERS_ERROR', 'Sie haben die maximale Anzahl von erlaubten Auftragseingang erreichte, dass Sie noch nicht fertig! Bitte vervollständigen mindestens eine von ihnen, um fortzufahren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_PAID_ADMIN_COPY', 'Order has been paid (admin copy)'), (NULL, 'es', '_ORDER_PAID_ADMIN_COPY', 'Orden ha sido pagado (admin copia)'), (NULL, 'de', '_ORDER_PAID_ADMIN_COPY', 'Bestellung bezahlt wurde (admin kopieren)');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ORDER_PLACED_ONLINE_ADMIN_COPY', 'Online order has been placed (admin copy)'), (NULL, 'es', '_ORDER_PLACED_ONLINE_ADMIN_COPY', 'Pedido en línea se ha colocado (admin copia)'), (NULL, 'de', '_ORDER_PLACED_ONLINE_ADMIN_COPY', 'Online Bestellung aufgegeben wurde (admin kopieren)');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_EVENTS_NEW_USER_REGISTERED', 'Events - new user has been registered'), (NULL, 'es', '_EVENTS_NEW_USER_REGISTERED', 'Eventos - nuevo usuario se ha registrado'), (NULL, 'de', '_EVENTS_NEW_USER_REGISTERED', 'Events - neuen Benutzer registriert wurde');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CHOOSE_AVAILABLE_PLANS_NOTICE', 'Your current plan is _PLAN_NAME_. You may choose available plans to upgrade it.'), (NULL, 'es', '_CHOOSE_AVAILABLE_PLANS_NOTICE', 'Su plan actual es _PLAN_NAME_. Usted puede elegir los planes disponibles para actualizarlo.'), (NULL, 'de', '_CHOOSE_AVAILABLE_PLANS_NOTICE', 'Ihre aktuelle Plan ist _PLAN_NAME_. Sie können Pläne zur Verfügung, es zu aktualisieren wählen.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NO_AVAILABLE_PLANS_NOTICE', 'Your current plan is _PLAN_NAME_. You have no available plans to upgrade.'), (NULL, 'es', '_NO_AVAILABLE_PLANS_NOTICE', 'Su plan actual es _PLAN_NAME_. No tiene planes disponibles para actualizar.'), (NULL, 'de', '_NO_AVAILABLE_PLANS_NOTICE', 'Ihre aktuelle Plan ist _PLAN_NAME_. Sie haben keine Pläne zur Verfügung, zu aktualisieren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NOT_YET_PAID', 'Not yet paid'), (NULL, 'es', '_NOT_YET_PAID', 'Todavía no se ha pagado'), (NULL, 'de', '_NOT_YET_PAID', 'Noch nicht entrichtet');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_OPEN_ORDER_NOTICE', 'You have an order that has not yet paid! Before you proceed with a booking please cancel any open orders.'), (NULL, 'es', '_OPEN_ORDER_NOTICE', 'Usted tiene una orden que aún no ha pagado! Antes de continuar con una reserva por favor cancelar cualquier orden abierta.'), (NULL, 'de', '_OPEN_ORDER_NOTICE', 'Sie haben eine Bestellung, die noch nicht bezahlt hat! Bevor Sie mit einer Buchung gehen Sie bitte alle offenen Aufträge stornieren.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_NO_PAYMENT_METHODS_ALERT', 'No payment methods available! Please contact our technical support.'), (NULL, 'es', '_NO_PAYMENT_METHODS_ALERT', 'No hay métodos de pago disponibles! Por favor, póngase en contacto con nuestro soporte técnico.'), (NULL, 'de', '_NO_PAYMENT_METHODS_ALERT', 'Keine Zahlungsmethoden zur Verfügung! Bitte kontaktieren Sie unseren technischen Support.');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_ALL_SPECIALITIES', 'all specialities'), (NULL, 'es', '_ALL_SPECIALITIES', 'todas las especialidades'), (NULL, 'de', '_ALL_SPECIALITIES', 'Alle Fach');



-----------------------------

INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CANCEL_OPERATION_COMPLETED', 'The cancellation operation completed successfully!'), (NULL, 'es', '_CANCEL_OPERATION_COMPLETED', 'La operación de cancelación completada con éxito!'), (NULL, 'de', '_CANCEL_OPERATION_COMPLETED', 'Die Stornovorgang erfolgreich abgeschlossen!');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_CANCEL_APPOINTMENT', 'Cancel Appointment'), (NULL, 'es', '_CANCEL_APPOINTMENT', 'Cancelar cita'), (NULL, 'de', '_CANCEL_APPOINTMENT', 'Abbrechen Termin');
INSERT INTO `<DB_PREFIX>vocabulary` (`id`, `language_id`, `key_value`, `key_text`) VALUES (NULL, 'en', '_APPOINTMENT_APPROVED', 'Appointment has been successfully approved!'), (NULL, 'es', '_APPOINTMENT_APPROVED', 'Nombramiento ha sido aprobado con éxito!'), (NULL, 'de', '_APPOINTMENT_APPROVED', 'Termin wurde erfolgreich zugelassen!');
