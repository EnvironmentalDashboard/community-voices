<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Media extends DataMapper
{
    protected $table = '`community-voices_media`';

    public function fetch(Entity\Media $media)
    {
        $this->fetchById($media);
    }

    private function fetchById(Entity\Media $media)
    {
        $query = "SELECT    id,
                            added_by,
                            date_created,
                            type,
                            status
                    FROM    {$this->table}
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $entities = [];

            $entities['added_by'] = new Entity\User;
            $entities['added_by']->setId($result['added_by']);

            $this->applyValues($media, array_merge($result, $entities));
        }
    }

    public function save(Entity\Media $media)
    {
        if ($media->getId()) {
            $this->update($media);
            return ;
        }

        $this->create($media);
    }

    private function update(Entity\Media $media)
    {
        $query = "UPDATE    {$this->table}
                    SET     added_by = :added_by,
                            date_created = :date_created,
                            type = :type,
                            status = :status
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());
        $statement->bindValue(':added_by', $media->getAddedBy()->getId());
        $statement->bindValue(':date_created', $media->getDateCreated());
        $statement->bindValue(':type', $media->getType());
        $statement->bindValue(':status', $media->getStatus());

        $statement->execute();
    }

    private function create(Entity\Media $media)
    {
        $query = "INSERT INTO   {$this->table}
                                (added_by, date_created, type, status)
                    VALUES      (:added_by, :date_created, :type, :status)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':added_by', $media->getAddedBy());
        $statement->bindValue(':date_created', $media->getDateCreated()->getId());
        $statement->bindValue(':type', $media->getType());
        $statement->bindValue(':status', $media->getStatus());

        $statement->execute();

        $media->setId($this->conn->lastInsertId());
    }

    public function delete(Entity\Media $media)
    {
        $query = "DELETE FROM   {$this->table}
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();
    }
}
