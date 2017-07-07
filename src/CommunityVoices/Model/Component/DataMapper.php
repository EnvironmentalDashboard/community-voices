<?php

namespace CommunityVoices\Model\Component;

/**
 * Data mapper component to be extended by entity mappers.
 *
 * Code taken from teresko/palladium
 * @link https://github.com/teresko/palladium/blob/master/src/Palladium/Component/DataMapper.php
 */

use PDO;

class DataMapper extends Mapper
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
}
