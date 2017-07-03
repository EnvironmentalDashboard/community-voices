<?php

namespace CommunityVoices\Model\Component;

use PDO;

class DataMapper
{
    protected $conn;

    protected $table;

    /**
     * Instantiates data mapper instance
     * @param PDO $conn
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
}
