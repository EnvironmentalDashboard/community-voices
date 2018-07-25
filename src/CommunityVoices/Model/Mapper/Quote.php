<?php

/**
 * Quote mapper.
 *
 * Quotes are a type of Media, thus this class extends the Media mapper and
 * mapping logic in this file occasionally references the Media (parent) mapper.
 *
 * Deletions and updates cascade, thus this mapper does not need to implement logic
 * for deleting entries in the table (it may invoke the parent mapper's method,
 * knowing the delete will cascade down).
 */

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class Quote extends Media
{

    public function relatedSlideId(int $quote_id) {
        $query = "SELECT media_id FROM `community-voices_slides` WHERE quote_id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':id', $quote_id);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function prevQuote(int $quote_id) {
        $query = "SELECT media_id FROM `community-voices_quotes` WHERE media_id < :id ORDER BY media_id DESC LIMIT 1";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':id', $quote_id);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function nextQuote(int $quote_id) {
        $query = "SELECT media_id FROM `community-voices_quotes` WHERE media_id > :id ORDER BY media_id ASC LIMIT 1";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':id', $quote_id);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * @uses Quote::fetchById
     */
    public function fetch(Entity\Media $quote)
    {
        return $this->fetchById($quote);
    }

    /**
     * Maps a Quote entity by the ID assigned on the instance. If no rows match
     * the quote's ID, the Quote entity's ID is overwritten as null.
     *
     * @param  Media $quote Quote entity to fetch & map
     */
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
                        child.sub_attribution           AS subAttribution,
                        child.date_recorded             AS dateRecorded,
                        child.public_document_link      AS publicDocumentLink,
                        child.source_document_link      AS sourceDocumentLink
                    FROM
                        `community-voices_media` parent
                    JOIN
                        `community-voices_quotes` child
                        ON parent.id = child.media_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query); // there's an issue here!

        $statement->bindValue(':id', $quote->getId());

        $statement->execute();

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            $convertedParams = $this->convertRelations(
                $this->relations,
                $results
            );

            $this->populateEntity($quote, array_merge($results, $convertedParams));
        } else {
            $quote->setId(null);
        }
    }

    /**
     * Save a Quote entity to database by either: updating a current record if
     * an ID exists or creating a new record.
     *
     * @param  Media $quote instance to save to database
     */
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
        /**
         * Update parent row
         */

        parent::update($quote);

        /**
         * Update child row
         */

        $query = "UPDATE
                        `community-voices_quotes`
                    SET
                        text = :text,
                        attribution = :attribution,
                        sub_attribution = :sub_attribution,
                        date_recorded = :date_recorded,
                        public_document_link = :public_document_link,
                        source_document_link = :source_document_link
                    WHERE
                        media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $quote->getId());
        $statement->bindValue(':text', $quote->getText());
        $statement->bindValue(':attribution', $quote->getAttribution());
        $statement->bindValue(':sub_attribution', $quote->getSubAttribution());
        $statement->bindValue(':date_recorded', date('Y-m-d H:i:s', $quote->getDateRecorded()));
        $statement->bindValue(':public_document_link', $quote->getPublicDocumentLink());
        $statement->bindValue(':source_document_link', $quote->getSourceDocumentLink());

        $statement->execute();
    }

    protected function create(Entity\Media $quote)
    {
        /**
         * Create parent row
         */

        parent::create($quote);

        /**
         * Credit child row
         */

        $query = "INSERT INTO
                        `community-voices_quotes`
                        (media_id, text, attribution, sub_attribution, date_recorded,
                            public_document_link, source_document_link)
                    VALUES
                        (:media_id, :text, :attribution, :sub_attribution, :date_recorded,
                            :public_document_link, :source_document_link)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $quote->getId());
        $statement->bindValue(':text', $quote->getText());
        $statement->bindValue(':attribution', $quote->getAttribution());
        $statement->bindValue(':sub_attribution', $quote->getSubAttribution());
        $statement->bindValue(':date_recorded', date('Y-m-d H:i:s', $quote->getDateRecorded()));
        $statement->bindValue(':public_document_link', $quote->getPublicDocumentLink());
        $statement->bindValue(':source_document_link', $quote->getSourceDocumentLink());

        $statement->execute();
    }
}
