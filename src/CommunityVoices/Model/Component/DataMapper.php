<?php

namespace CommunityVoices\Model\Component;

/**
 * Data mapper component to be extended by entity mappers.
 */

use PDO;

class DataMapper extends Mapper
{
    protected $conn;

    protected static $table;

    /**
     * Instantiates data mapper instance
     *
     * @param PDO $conn
     *
     * @codeCoverageIgnore
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
}
