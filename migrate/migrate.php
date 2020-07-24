<?php

// Set-up
use CommunityVoices\App\Website\Bootstrap;

// Timezone
// @config
date_default_timezone_set('America/New_York');

// Error settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Composer autoloading
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/CommunityVoices/App/Website/db.php';

use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Entity;

$contentCategoryMapper = new Mapper\ContentCategory($dbHandler);
$cc = new Entity\ContentCategory;
$cc->setGroupId(1);

var_dump($contentCategoryMapper->fetch($cc));
var_dump($cc);
