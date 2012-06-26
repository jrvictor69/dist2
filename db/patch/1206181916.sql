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

CREATE  TABLE IF NOT EXISTS `tblChurch` (
  `id` 			INT				NOT NULL AUTO_INCREMENT,
  `name` 		VARCHAR(50) 	NOT NULL,
  `address`		TEXT			NULL,
  `content`		TEXT			NULL,
  `phone`		VARCHAR(10)		NULL,
  `created` 	DATETIME 		NOT NULL,
  `changed` 	DATETIME 		DEFAULT NULL,
  `createdBy` 	INT(11) 		DEFAULT NULL,
  `changedBy` 	INT(11) 		DEFAULT NULL,
  `state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
  CONSTRAINT `pk_tblChurch`
	PRIMARY KEY (`id`) ,

  KEY `i_tblChurch_id` (`id`) ,
  INDEX `i_tblChurch_state_id` (`state`, `id`) )
ENGINE = INNODB