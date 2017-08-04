<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Quote extends Media
{
    protected static $table = '`community-voices_quote`';

    public function fetch(Entity\Media $quote)
    {
        $this->fetchById($quote);
    }

    private function fetchById(Entity\Media $quote)
    {
        $query = "SELECT
                        parent.id                       AS id,
                        parent.added_by                 AS addedBy,
                        parent.date_created             AS dateCreated,
                        CAST(parent.type AS UNSIGNED)   AS type,
                        CAST(parent.status AS UNSIGNED) AS status,
                        child.text                      AS text,
                        child.attribution               AS attribution,
                        child.date_recorded             AS dateRecorded,
                        child.public_document_link      AS publicDocumentLink,
                        child.source_document_link      AS sourceDocumentLink,
                        tag.id                          AS tagId
                    FROM
                        " . parent::$table . " parent
                    JOIN
                        " . self::$table . " child
                        ON parent.id = child.media_id

                    LEFT JOIN
                        `community-voices_media-group-map` junction
                        ON junction.media_id = parent.id

                    LEFT JOIN
                        `community-voices_groups` tag
                        ON junction.group_id = tag.id AND CAST(tag.type AS UNSIGNED) = 1

                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $quote->getId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            $relations = array_merge_recursive($this->relations, $media->getRelations());

            $entities = $this->convertSingleRelationsToEntities(
                $relations['single'],
                $results[0]
            );

            $collections = $this->convertManyRelationsToEntityCollections(
                $relations['many'],
                $results
            );

            $this->populateEntity($media, array_merge(
                $results[0],
                $entities,
                $collections
            ));
        }
    }

    public function save(Entity\Media $quote)
    {
        if ($quote->getId()) {
            $this->update($quote);
            return ;
        }

        $this->create($quote);
    }

    protected function update(Entity\Media $quote)
    {
        parent::update($quote);

        $query = "UPDATE    " . self::$table . "
                    SET     text = :text,
                            attribution = :attribution,
                            date_recorded = :date_recorded,
                            public_document_link = :public_document_link,
                            source_document_link = :source_document_link
                    WHERE   media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $quote->getId());
        $statement->bindValue(':text', $quote->getText());
        $statement->bindValue(':attribution', $quote->getAttribution());
        $statement->bindValue(':date_recorded', $quote->getDateRecorded());
        $statement->bindValue(':public_document_link', $quote->getPublicDocumentLink());
        $statement->bindValue(':source_document_link', $quote->getSourceDocumentLink());

        $statement->execute();
    }

    protected function create(Entity\Media $quote)
    {
        parent::create($quote);

        $query = "INSERT INTO   " . self::$table . "
                                (media_id, text, attribution, date_recorded, public_document_link,
                                    source_document_link)

                    VALUES      (:media_id, :text, :attribution, :date_recorded, :public_document_link,
                                    :source_document_link)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $quote->getId());
        $statement->bindValue(':text', $quote->getText());
        $statement->bindValue(':attribution', $quote->getAttribution());
        $statement->bindValue(':date_recorded', $quote->getDateRecorded());
        $statement->bindValue(':public_document_link', $quote->getPublicDocumentLink());
        $statement->bindValue(':source_document_link', $quote->getSourceDocumentLink());

        $statement->execute();
    }

    public function delete(Entity\Media $quote)
    {
        parent::delete($quote); //deletion cascades
    }
}
