CREATE  TABLE IF NOT EXISTS `tblUnityClub` (
  `id`			INT			NOT NULL AUTO_INCREMENT,
  `name`		VARCHAR(45) NULL,
  `motto`		TEXT		NULL,
  `description`	TEXT		NULL,
  `created` 	DATETIME 	NOT NULL,
  `changed` 	DATETIME 	DEFAULT NULL,
  `createdBy` 	INT(11) 	DEFAULT NULL,
  `changedBy` 	INT(11) 	DEFAULT NULL,
  `state` 		TINYINT(1) 	NOT NULL DEFAULT '1',
  `clubId`		INT 		NOT NULL,
  CONSTRAINT `pk_tblUnityClub`
	PRIMARY KEY (`id`) ,

  KEY `i_tblUnityClub_id` (`id`),
  INDEX `i_tblUnityClub_state_id` (`state`, `id`),
  INDEX `fk_tblUnityClub_clubId` (`clubId`),
  
  CONSTRAINT `fk_tblUnityClub_clubId`
	FOREIGN KEY (`clubId`)
	REFERENCES `tblClubPathfinder` (`id`)
	ON UPDATE CASCADE)
ENGINE = INNODB