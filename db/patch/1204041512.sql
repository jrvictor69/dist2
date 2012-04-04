DROP TABLE IF EXISTS `tblUserGroup`;

CREATE TABLE `tblUserGroup` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `description` TEXT,
  `created` DATETIME NOT NULL,
  `changed` DATETIME DEFAULT NULL,
  `createdBy` INT(11) DEFAULT NULL,
  `changedBy` INT(11) DEFAULT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=INNODB;

DROP TABLE IF EXISTS `tblPrivilege`;

CREATE TABLE `tblPrivilege` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `description` TEXT,
  `module` VARCHAR(45) NOT NULL,
  `controller` VARCHAR(45) NOT NULL,
  `action` VARCHAR(45) NOT NULL,
  `created` DATETIME NOT NULL,
  `changed` DATETIME DEFAULT NULL,
  `createdBy` INT(11) DEFAULT NULL,
  `changedBy` INT(11) DEFAULT NULL,
  `state` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=INNODB;

CREATE  TABLE IF NOT EXISTS `dbch`.`tblUserGroup_Privilege` (
  `userGroupId` INT NOT NULL ,
  `privilegeId` INT NOT NULL ,
  PRIMARY KEY (`userGroupId`, `privilegeId`) ,
  INDEX `fk_tblUserGroup_Privilege_userGroupId` (`userGroupId`) ,
  INDEX `fk_tblUserGroup_Privilege_privilegeId` (`privilegeId`) ,
  CONSTRAINT `fk_tblUserGroup_Privilege_userGroupId`
    FOREIGN KEY (`userGroupId` )
    REFERENCES `dbch`.`tblUserGroup` (`id` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tblUserGroup_Privilege_privilegeId`
    FOREIGN KEY (`privilegeId` )
    REFERENCES `dbch`.`tblPrivilege` (`id` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB