<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$appPrefix = '/community-voices';

$collection = new RouteCollection();

$config = json_decode(file_get_contents(__DIR__ . '/Routes.json'), true);


foreach ($config as $name => $options) {
    $collection->add(
        $name,
        new Route(
            $appPrefix . $options['notation'],
            $options['defaults'],
            isset($options['requirements']) ? $options['requirements'] : [],
            [],
            '',
            [],
            isset($options['method']) ? $options['method'] : []
        )
    );
}

return $collection;
