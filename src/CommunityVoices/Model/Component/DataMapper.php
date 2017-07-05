<?php

namespace CommunityVoices\Model\Component;

/**
 * Data mapper component to be extended by entity mappers.
 *
 * Code taken from teresko/palladium
 * @link https://github.com/teresko/palladium/blob/master/src/Palladium/Component/DataMapper.php
 */

use PDO;

class DataMapper
{
    protected $conn;

    protected $table;

    /**
     * Instantiates data mapper instance
     *
     * @param PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Method for populating the given instance with values from the array via setters
     *
     * @param object $instance The object to be populated with values
     * @param array $parameters A key-value array, that will be matched to setters
     */
    public function applyValues($instance, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }
    }
}