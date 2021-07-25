
ALTER TABLE  `<DB_PREFIX>events` ADD  `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '' AFTER  `description`;
ALTER TABLE  `<DB_PREFIX>events` DROP  `notification_sent`;
ALTER TABLE  `<DB_PREFIX>events` CHANGE  `user_id`  `participant_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `<DB_PREFIX>events` ADD INDEX  `participant` (  `participant_id` );

ALTER TABLE  `<DB_PREFIX>calendar` ADD  `notification_sent` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';

RENAME TABLE  `<DB_PREFIX>users` TO  `<DB_PREFIX>participants`;
RENAME TABLE  `<DB_PREFIX>events_users` TO  `<DB_PREFIX>events_participants`;

ALTER TABLE  `<DB_PREFIX>events_participants` CHANGE  `user_id`  `participant_id` INT( 10 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `<DB_PREFIX>events_participants` DROP INDEX  `user_id`, ADD INDEX  `participants_id` (  `participant_id` );
