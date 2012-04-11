ALTER TABLE `dbch`.`tblmanagerial` ADD COLUMN `userGroupId` INT(11) NULL  AFTER `provinceId` , 

  ADD CONSTRAINT `fk_tblManagerial_userGroupId`

  FOREIGN KEY (`userGroupId` )

  REFERENCES `dbch`.`tblusergroup` (`id` )

  ON DELETE NO ACTION

  ON UPDATE CASCADE

, ADD INDEX `fk_tblManagerial_userGroupId` (`userGroupId` ASC) ;