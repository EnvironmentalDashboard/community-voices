<?php

$dbHandler = new PDO('mysql:host='.getenv('MYSQL_HOST').';dbname='.getenv('MYSQL_DB').';charset=utf8;port=' . getenv('MYSQL_PORT'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'));
$dbHandler->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbHandler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
