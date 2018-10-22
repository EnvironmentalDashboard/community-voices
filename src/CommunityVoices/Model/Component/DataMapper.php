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

    // query prepare (turn array into query)
    // $seq is the array
    // $type is the name of the field in database
    protected function query_prep($seq, $type)
    {
        if ($seq == null) {
            return "";
        } else {
            $toRet = array_map(
                function($x) use ($type) {return $type . "='" . $x ."'";},
                $seq);
            $toRet = implode(" OR ",$toRet);
            return " AND " . $toRet;
        }
    }
}
