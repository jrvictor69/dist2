ALTER TABLE `dbch`.`tblusergroup_privilege` ADD COLUMN `changedBy` INT NULL DEFAULT NULL  AFTER `privilegeId` , ADD COLUMN `changed` DATETIME NULL DEFAULT NULL  AFTER `privilegeId` , ADD COLUMN `created` DATETIME NOT NULL  AFTER `privilegeId` , ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT  FIRST 

, DROP PRIMARY KEY 

, ADD PRIMARY KEY (`id`) ;