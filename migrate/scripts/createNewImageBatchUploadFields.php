<?php

$createImageMDFields = "CREATE TABLE IF NOT EXISTS `community-voices_image_metadata` (
    `id` int(21) NOT NULL AUTO_INCREMENT,
    `script_name` VARCHAR(250) NOT NULL,
    `succesful` BOOLEAN NOT NULL,
    `datetime_executed` DATETIME NOT NULL,
    `exception_message` TEXT,
    `exception_trace` TEXT,
     PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $dbHandler->exec($createImageMDFields);


  $columnNameArguments = array_slice($argv,2);

  foreach($columnNameArguments as $metaDataField) { // can't use SQL binding on column names
    $addNewColumn = "ALTER TABLE `community-voices_image_metadata`
                            ADD COLUMN ${metaDataField} VARCHAR(250)";
    $dbHandler->exec($addNewColumn);
  }


