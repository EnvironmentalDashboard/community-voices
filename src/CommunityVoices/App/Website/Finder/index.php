<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../../../vendor/autoload.php';

use CommunityVoices\Model;
use Jenssegers\ImageHash;
use CommunityVoices\App\Website\Finder;

/**
 * Db handler configuration
 */

require '../db.php';

/**
 * Handle presentation
 */
try {
    $controller = new Finder\ImageFinderController(
        new Finder\ImageFinderResponder,
        new Finder\ImageMatcher(
            new ImageHash\ImageHash(new ImageHash\Implementations\PerceptualHash()),
            $dbHandler
        ),
        new Finder\FileManager()
    );

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $params = [
                'submit' => $_POST['submit'],
                'image' => $_FILES['image']
            ];

            die($controller->postMatchInquiry($params));

        case 'GET':
            die($controller->getInputForm());

        default:
            die('Did not recognize request method');
    }
} catch (Exception $e) {
    $params = [
        'error' => $e->getMessage()
    ];

    die($controller->getErrorNotice($params));
}
