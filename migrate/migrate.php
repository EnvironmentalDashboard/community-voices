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

// Set up migration logger
$logger = new Monolog\Logger('Migration');
$logger->pushHandler(new Monolog\Handler\StreamHandler('/var/www/html/log/migration.log'));

if (count($argv) < 2) {
    echo "You must input a script name as an argument.\n";
} else {
    try {
        require __DIR__ . "/scripts/{$argv[1]}.php";
        $logger->notice("migration $argv[1] was run succesfully");
        echo "ran migration $argv[1] succesfully\n";
    } catch (Exception $e) {
        echo "migratoin $argv[1] had errors! Check the migration log for more information.\n";
        $logger->alert("Migration Error While Executing Script $argv[1]", [
            'exception' => [
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]
        ]);
    }
}

# Need to ask cv-mysql to generate the dump.
shell_exec('mysqldump -h cv-mysql --no-data community_voices > migrate/schema.sql');
