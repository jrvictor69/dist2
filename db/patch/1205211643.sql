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
  `categoryId` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tblPicture_categoryId` (`categoryId`),
  CONSTRAINT `fk_tblPicture_categoryId` FOREIGN KEY (`categoryId`) REFERENCES `tblCategory` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=INNODB;

ALTER TABLE `dbch`.`tblPicture` ADD COLUMN `srcCrops` VARCHAR(125) NULL DEFAULT NULL  AFTER `description` , ADD COLUMN `src` VARCHAR(125) NOT NULL  AFTER `description` ;