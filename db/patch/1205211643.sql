CREATE TABLE `tblPicture` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `description` TEXT,
  `visibleExtWeb` TINYINT(1) NOT NULL DEFAULT '1',
  `created` DATETIME NOT NULL,
  `changed` DATETIME DEFAULT NULL,
  `createdBy` INT(11) DEFAULT NULL,
  `changedBy` INT(11) DEFAULT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT '1',
  `fileId` INT(11) NOT NULL,
  `categoryId` INT(11) DEFAULT NULL,
  CONSTRAINT `pk_tblPicture` PRIMARY KEY (`id`),
  KEY `i_tblPicture_id` (`id`),
  INDEX `i_tblPicture_state_id` (`state`, `id`),
  INDEX `fk_tblPicture_fileId` (`fileId`),
  INDEX `fk_tblPicture_categoryId` (`categoryId`),
  CONSTRAINT `fk_tblPicture_fileId` FOREIGN KEY (`fileId`) REFERENCES `tblImageDataVault` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_tblPicture_categoryId` FOREIGN KEY (`categoryId`) REFERENCES `tblCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=INNODB;

ALTER TABLE `dbch`.`tblPicture` ADD COLUMN `srcCrops` VARCHAR(125) NULL DEFAULT NULL  AFTER `description` , ADD COLUMN `src` VARCHAR(125) NOT NULL  AFTER `description` ;