<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require dirname(__DIR__) . '/App/Website/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Screen URLs</title>
</head>
<body>
	<ul>
		<?php foreach ($dbHandler->query('SELECT id, label FROM `community-voices_locations` ORDER BY label ASC') as $loc) {
			echo "\t\t\t<li>{$loc['label']}: https://environmentaldashboard.org/cv/public/digital-signage.php?loc={$loc['id']}</li>\n";
		} ?>
	</ul>
</body>
</html>