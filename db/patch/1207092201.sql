CREATE  TABLE IF NOT EXISTS `tblPictureType` (
  `id` 			INT				NOT NULL AUTO_INCREMENT,
  `name` 		VARCHAR(50) 	NOT NULL,
  `description`	TEXT			NULL,
  `created` 	DATETIME 		NOT NULL,
  `changed` 	DATETIME 		DEFAULT NULL,
  `createdBy` 	INT(11) 		DEFAULT NULL,
  `changedBy` 	INT(11) 		DEFAULT NULL,
  `state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
  CONSTRAINT `pk_tblChurch`
	PRIMARY KEY (`id`) ,

  KEY `i_tblPictureType_id` (`id`) ,
  INDEX `i_tblPictureType_state_id` (`state`, `id`) )
ENGINE = INNODB

CREATE  TABLE IF NOT EXISTS `tblPictureCategory` (
  `id` 			INT				NOT NULL AUTO_INCREMENT,
  `name` 		VARCHAR(50) 	NOT NULL,
  `description`	TEXT			NULL,
  `created` 	DATETIME 		NOT NULL,
  `changed` 	DATETIME 		DEFAULT NULL,
  `createdBy` 	INT(11) 		DEFAULT NULL,
  `changedBy` 	INT(11) 		DEFAULT NULL,
  `state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
  CONSTRAINT `pk_tblChurch`
	PRIMARY KEY (`id`) ,

  KEY `i_tblPictureCategory_id` (`id`) ,
  INDEX `i_tblPictureCategory_state_id` (`state`, `id`) )
ENGINE = INNODB

CREATE  TABLE IF NOT EXISTS `tblPicture` (
  `id` 					INT				NOT NULL AUTO_INCREMENT,
  `title` 				VARCHAR(50) 	NOT NULL,
  `description`			TEXT			NULL,
  `filename` 			VARCHAR(150)	NOT NULL,
  `mimeType` 			VARCHAR(45) 	NOT NULL,
  `src`		 			VARCHAR(150)	NOT NULL,
  `filenameCrop` 		VARCHAR(150)	DEFAULT NULL,
  `mimeTypeCrop` 		VARCHAR(45) 	DEFAULT NULL,
  `srcCrop`		 		VARCHAR(150)	DEFAULT NULL,
  `created` 			DATETIME 		NOT NULL,
  `changed` 			DATETIME 		DEFAULT NULL,
  `createdBy` 			INT(11) 		DEFAULT NULL,
  `changedBy` 			INT(11) 		DEFAULT NULL,
  `state` 				TINYINT(1) 		NOT NULL DEFAULT '1',
  `clubId`				INT 			DEFAULT NULL,
  `pictureTypeId`		INT 			DEFAULT NULL,
  `pictureCategoryId`	INT 			DEFAULT NULL,
  CONSTRAINT `pk_tblPicture`
	PRIMARY KEY (`id`) ,

  KEY `i_tblPicture_id` (`id`),
  INDEX `i_tblPicture_state_id` (`state`, `id`),
  INDEX `fk_tblPicture_clubId` (`clubId`),

  CONSTRAINT `fk_tblPicture_clubId`
	FOREIGN KEY (`clubId`)
	REFERENCES `tblClubPathfinder` (`id`)
	ON UPDATE CASCADE,
	CONSTRAINT `fk_tblPicture_pictureTypeId`
	FOREIGN KEY (`pictureTypeId`)
	REFERENCES `tblPictureType` (`id`)ON UPDATE CASCADE,
  CONSTRAINT `fk_tblPicture_pictureCategoryId`
	FOREIGN KEY (`pictureCategoryId`)
	REFERENCES `tblPictureCategory` (`id`)
	ON UPDATE CASCADE)
ENGINE = INNODB