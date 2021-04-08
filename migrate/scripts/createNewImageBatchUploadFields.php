<?php

$createImageMDFields = "CREATE TABLE IF NOT EXISTS `community-voices_image_metadata` (
    `id` int(21) NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $dbHandler->exec($createImageMDFields);


  $columnNameArguments = array_slice($argv,2);

  foreach($columnNameArguments as $metaDataField) { // can't use SQL binding on column names
    $addNewColumn = "ALTER TABLE `community-voices_image_metadata`
                            ADD COLUMN ${metaDataField} VARCHAR(250)";
    $dbHandler->exec($addNewColumn);
  }


