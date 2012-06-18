CREATE  TABLE IF NOT EXISTS `tblPosition` (
  `id` 			INT				NOT NULL AUTO_INCREMENT,
  `name` 		VARCHAR(50) 	NOT NULL,
  `description`	TEXT 			NULL,
  `created` 	DATETIME 		NOT NULL,
  `changed` 	DATETIME 		DEFAULT NULL,
  `createdBy` 	INT(11) 		DEFAULT NULL,
  `changedBy` 	INT(11) 		DEFAULT NULL,
  `state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
  CONSTRAINT `pk_tblPosition`
	PRIMARY KEY (`id`) ,

  KEY `i_tblPosition_id` (`id`) ,
  INDEX `i_tblPosition_state_id` (`state`, `id`) )
ENGINE = INNODB