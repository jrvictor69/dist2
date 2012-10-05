CREATE TABLE IF NOT EXISTS `tblMemberClub` (
  `id`			INT			NOT NULL AUTO_INCREMENT,
  `email`		VARCHAR(45) NULL,
  `note`		TEXT		NULL,
  `history`		TEXT		NULL,
  `clubId`		INT 		NOT NULL,
  CONSTRAINT `pk_tblMemberClub`
	PRIMARY KEY (`id`) ,

  KEY `i_tblMemberClub_id` (`id`),
  INDEX `fk_tblMemberClub_clubId` (`clubId`),

  CONSTRAINT `fk_tblMemberClub_clubId`
	FOREIGN KEY (`clubId`)
	REFERENCES `tblClubPathfinder` (`id`)
	ON UPDATE CASCADE)
ENGINE = INNODB