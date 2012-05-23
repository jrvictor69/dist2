ALTER TABLE `dbch`.`tbldatavault` 

ADD INDEX `i_tblDataVault_id` (`id`) 

, ADD INDEX `i_tblDataVault_state_id` (`state`, `id`) ;