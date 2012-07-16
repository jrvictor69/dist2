CREATE  TABLE IF NOT EXISTS `tblDirective` (
  `id`		INT			NOT NULL AUTO_INCREMENT,
  `email`	VARCHAR(45) NULL,
  `note`	TEXT		NULL,
  `clubId`	INT 		NOT NULL,
  CONSTRAINT `pk_tblDirective`
	PRIMARY KEY (`id`) ,

  KEY `i_tblDirective_id` (`id`),
  INDEX `fk_tblDirective_clubId` (`clubId`),

  CONSTRAINT `fk_tblDirective_clubId`
  	FOREIGN KEY (`clubId`)
  	REFERENCES `tblClubPathfinder` (`id`)
  	ON UPDATE CASCADE)
ENGINE = INNODB