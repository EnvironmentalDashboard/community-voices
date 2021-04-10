<?php

/* 
@todo all removal should be conditional instead of happening all of the time.

*/

$removeFK = "ALTER TABLE `community-voices_images`
                DROP FOREIGN KEY `community-voices_images_fk1`";



$removeMetaDataColumn = "ALTER TABLE `community-voices_images`
                                DROP COLUMN metadata_id";
$dropTable = "DROP TABLE IF EXISTS `community-voices_image_metadata`";

$dbHandler->exec($removeFK);
$dbHandler->exec($removeMetaDataColumn);
$dbHandler->exec($dropTable);