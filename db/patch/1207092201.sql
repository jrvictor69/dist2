CREATE  TABLE IF NOT EXISTS `tblGalleryView` (
  `id` 				INT				NOT NULL AUTO_INCREMENT,
  `title` 			VARCHAR(50) 	NOT NULL,
  `description`		TEXT			NULL,
  `filename` 		VARCHAR(150)	NOT NULL,
  `mimeType` 		VARCHAR(45) 	NOT NULL,
  `src`		 		VARCHAR(150)	NOT NULL,
  `filenameCrop` 	VARCHAR(150)	DEFAULT NULL,
  `mimeTypeCrop` 	VARCHAR(45) 	DEFAULT NULL,
  `srcCrop`		 	VARCHAR(150)	DEFAULT NULL,
  `type` 			INT(11) 		NOT NULL DEFAULT '1',
  `created` 		DATETIME 		NOT NULL,
  `changed` 		DATETIME 		DEFAULT NULL,
  `createdBy` 		INT(11) 		DEFAULT NULL,
  `changedBy` 		INT(11) 		DEFAULT NULL,
  `state` 			TINYINT(1) 		NOT NULL DEFAULT '1',
  `clubId`			INT 			DEFAULT NULL,
  CONSTRAINT `pk_tblGalleryView`
	PRIMARY KEY (`id`) ,

  KEY `i_tblGalleryView_id` (`id`),
  INDEX `i_tblGalleryView_state_id` (`state`, `id`),
  INDEX `fk_tblGalleryView_clubId` (`clubId`),

  CONSTRAINT `fk_tblGalleryView_clubId`
  	FOREIGN KEY (`clubId`) 
  	REFERENCES `tblClubPathfinder` (`id`)
  	ON UPDATE CASCADE)
ENGINE = INNODB