<?php

try {

$createImageMDFields = "CREATE TABLE IF NOT EXISTS `community-voices_image_metadata` (
    `id` int(21) NOT NULL AUTO_INCREMENT,
     PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
  $dbHandler->exec($createImageMDFields);



  foreach($argv as $metaDataField) {
    $addNewColumn = "ALTER TABLE `community-voices_image_metadata`
                            ADD COLUMN :metaDataField VARCHAR(250)";
    $statement = $dbHandler->prepare($addNewColumn);
    $statement->bindValue('metaDataField', $metaDataField);
    $statement->execute();
  }
  
  // Log out that this migration was run
  $logger->notice('community-voices_image_metadata table created with fields' . implode(',',$args));

} catch (\PDOException $error) {
    // something has gone seriously wrong, alert the authorities!
    $logger->error('** Creating image_metadata table from createNewImageBatchUploadFields.php',[
      'exception' => [
          'message' => $error->getMessage(),
          'trace' => $error->getTraceAsString()
      ]
    ]);
}


