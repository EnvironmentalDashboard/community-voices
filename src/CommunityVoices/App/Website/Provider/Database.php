<?php

namespace CommunityVoices\App\Website\Provider;

use CommunityVoices\App\Website\Component\Provider;
use \PDO;

/**
 * Database connection provider
 */

class Database extends Provider {
    public function init()
    {
        $host   = getenv('MYSQL_HOST');
        $dbname = getenv('MYSQL_DB');
        $user   = getenv('MYSQL_USER');
        $pass   = getenv('MYSQL_PASS');

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8;port=3306', $host, $dbname);
        $handler = new PDO($dsn, $user, $pass);

        $handler->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $handler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->injector->share($handler);
    }
}