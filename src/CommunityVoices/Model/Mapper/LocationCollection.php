<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class LocationCollection extends DataMapper
{
    public function fetch(Entity\LocationCollection $locationCollection)
    {
        $this->fetchAll($locationCollection);
    }

    public function fetchAll(Entity\LocationCollection $locationCollection)
    {
        $query = "SELECT id, label, end_use FROM `community-voices_locations` ORDER BY end_use DESC, label ASC";

        foreach ($this->conn->query($query) as $row) {
            $locationCollection->addEntityFromParams($row);
        }
    }
}
