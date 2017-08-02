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
        $query = "SELECT    parent.id,
                            parent.added_by,
                            parent.date_created,
                            parent.type,
                            parent.status,
                            child.text,
                            child.attribution,
                            child.date_recorded,
                            child.public_document_link,
                            child.source_document_link
                    FROM    " . parent::$table . " parent
                    JOIN    " . self::$table . " child
                    ON      parent.id = child.media_id
                    WHERE   parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $quote->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $parameters = $this->convertRelationsToEntities($quote->getRelations(), $result);

            $this->populateEntity($quote, $parameters);
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
