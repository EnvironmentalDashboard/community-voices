<?php
// this should probably be put somewhere else, but it's so simple like this...
echo json_encode(exif_read_data($_POST['image']));
?>