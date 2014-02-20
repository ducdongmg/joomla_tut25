CREATE TABLE IF NOT EXISTS `#__jsn_poweradmin_config` (
	`name` varchar( 255 ) NOT NULL ,
	`value` text NOT NULL ,
	UNIQUE KEY `name` ( `name` )
) CHARACTER SET `utf8`;