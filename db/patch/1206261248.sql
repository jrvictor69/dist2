
CREATE  TABLE IF NOT EXISTS `tblClubPathfinder` (
  `id` 			INT				NOT NULL AUTO_INCREMENT,
  `name` 		VARCHAR(50) 	NOT NULL,
  `textbible`	VARCHAR(150) 	NULL,
  `address`		TEXT			NULL,
  `content`		TEXT			NULL,
  `created` 	DATETIME 		NOT NULL,
  `changed` 	DATETIME 		DEFAULT NULL,
  `createdBy` 	INT(11) 		DEFAULT NULL,
  `changedBy` 	INT(11) 		DEFAULT NULL,
  `state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
  `churchId`	INT 			NOT NULL,
  `logoId`		INT				DEFAULT NULL,
  CONSTRAINT `pk_tblChurch`
	PRIMARY KEY (`id`) ,

  KEY `i_tblClubPathfinder_id` (`id`),
  INDEX `i_tblClubPathfinder_state_id` (`state`, `id`),
  INDEX `fk_tblClubPathfinder_churchId` (`churchId`),
  INDEX `fk_tblClubPathfinder_logoId` (`logoId`),

  CONSTRAINT `fk_tblClubPathfinder_churchId`
  	FOREIGN KEY (`churchId`) 
  	REFERENCES `tblChurch` (`id`)
  	ON UPDATE CASCADE,
  CONSTRAINT `fk_tblClubPathfinder_logoId`
  	FOREIGN KEY (`logoId`)
  	REFERENCES `tblImageDataVault` (`id`)
  	ON UPDATE CASCADE)
ENGINE = INNODB