<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Image extends Media
{
    protected static $table = '`community-voices_images`';

    public function fetch(Entity\Media $image)
    {
        $this->fetchById($image);
    }

    private function fetchById(Entity\Media $image)
    {
        $query = "SELECT    parent.id,
                            parent.added_by,
                            parent.date_created,
                            parent.type,
                            parent.status,
                            child.filename,
                            child.generated_tags,
                            child.title,
                            child.description,
                            child.date_taken,
                            child.photographer,
                            child.organization
                    FROM    " . parent::$table . " parent
                    JOIN    " . self::$table . " child
                    ON      parent.id = child.media_id
                    WHERE   parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $image->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $parameters = $this->convertRelationsToEntities($image->getRelations(), $result);

            $this->applyValues($image, $parameters);
        }
    }

    public function save(Entity\Media $image)
    {
        if ($image->getId()) {
            $this->update($image);
            return ;
        }

        $this->create($image);
    }

    protected function update(Entity\Media $image)
    {
        parent::update($image);

        $query = "UPDATE    " . self::$table . "
                    SET     filename = :filename,
                            generated_tags = :generated_tags,
                            title = :title,
                            description = :description,
                            date_taken = :date_taken,
                            photographer = :photographer,
                            organization = :organization
                    WHERE   media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':filename', $image->getFilename());
        $statement->bindValue(':generated_tags', $image->getGeneratedTags());
        $statement->bindValue(':title', $image->getTitle());
        $statement->bindValue(':description', $image->getDescription());
        $statement->bindValue(':date_taken', $image->getDateTaken());
        $statement->bindValue(':photographer', $image->getPhotographer());
        $statement->bindValue(':organization', $image->getOrganization());
        $statement->bindValue(':media_id', $image->getId());

        $statement->execute();
    }

    protected function create(Entity\Media $image)
    {
        parent::create($image);

        $query = "INSERT INTO   " . self::$table . "
                                (media_id, filename, generated_tags, title, description,
                                    date_taken, photographer, organization)

                    VALUES      (:media_id, :filename, :generated_tags, :title, :description,
                                    :date_taken, :photographer, :organization)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $image->getId());
        $statement->bindValue(':filename', $image->getFilename());
        $statement->bindValue(':generated_tags', $image->getGeneratedTags());
        $statement->bindValue(':title', $image->getTitle());
        $statement->bindValue(':description', $image->getDescription());
        $statement->bindValue(':date_taken', $image->getDateTaken());
        $statement->bindValue(':photographer', $image->getPhotographer());
        $statement->bindValue(':organization', $image->getOrganization());

        $statement->execute();
    }

    public function delete(Entity\Media $image)
    {
        parent::delete($image); //deletion cascades
    }
}
