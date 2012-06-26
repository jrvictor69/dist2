CREATE TABLE `tblImageDataVault` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `expires` DATETIME DEFAULT NULL,
  `filename` VARCHAR(150) NOT NULL,
  `mimeType` VARCHAR(45) DEFAULT NULL,
  `binary` LONGBLOB,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` TINYINT(1) NOT NULL DEFAULT '1',
  CONSTRAINT `pk_tblImageDataVault` PRIMARY KEY (`id`),
  INDEX `i_tblImageDataVault_id` (`id`),
  INDEX `i_tblImageDataVault_state_id` (`state`,`id`)
) ENGINE=INNODB 