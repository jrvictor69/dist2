CREATE TABLE `tblMemberFile` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `note` TEXT NULL,
  `visibleExtWeb` TINYINT(1) NOT NULL DEFAULT '1',
  `created` DATETIME NOT NULL,
  `changed` DATETIME DEFAULT NULL,
  `changedBy` INT DEFAULT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT '1',
  `memberId` INT NOT NULL,
  `fileId` INT NOT NULL,
  `createdById` INT DEFAULT NULL,
  CONSTRAINT `pk_tblMemberFile` PRIMARY KEY (`id`),
  KEY `i_tblMemberFile_id` (`id`),
  INDEX `i_tblMemberFile_state_id` (`state`, `id`),
  INDEX `fk_tblMemberFile_memberId` (`memberId`),
  INDEX `fk_tblMemberFile_fileId` (`fileId`),
  INDEX `fk_tblMemberFile_createdById` (`createdById`),
  CONSTRAINT `fk_tblMemberFile_memberId` FOREIGN KEY (`memberId`) REFERENCES `tblMember` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_tblMemberFile_fileId` FOREIGN KEY (`fileId`) REFERENCES `tblDataVault` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_tblMemberFile_createdById` FOREIGN KEY (`createdById`) REFERENCES `tblAccount` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;