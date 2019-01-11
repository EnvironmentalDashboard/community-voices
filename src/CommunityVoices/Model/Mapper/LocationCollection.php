<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class LocationCollection extends DataMapper
{
    public function fetch(Entity\LocationCollection $container)
    {
        $this->fetchAll($container);
    }

    public function fetchAll(Entity\LocationCollection $container)
    {
        $query = "SELECT id, label, end_use FROM `community-voices_locations` ORDER BY end_use DESC, label ASC";
        foreach ($this->conn->query($query) as $row) {
            $loc = new Entity\Location;
            $loc->setId((int) $row['id']);
            $loc->setLabel($row['label']);
            $loc->setEndUse($row['end_use']);
            $container->addEntity($loc);
        }
    }
}
