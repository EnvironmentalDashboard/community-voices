<?php
/**
* @overview Bootstraps the tests together
*
*/

require __DIR__ . '/../vendor/autoload.php';

/**
 * set time zone 
 */
date_default_timezone_set('America/New_York');

spl_autoload_register(function ($class) {
    if (strpos($class, 'Mock') !== 0) {
        return ;
    }

    $class = str_replace('Mock', 'mock', $class);
    $class = str_replace('\\', '/', $class);

    $path = __DIR__ . '/' . $class . '.php';

    if (file_exists($path)) {
        require $path;
    }
});
