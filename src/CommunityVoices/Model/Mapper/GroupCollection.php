<?php

namespace CommunityVoices\Model\Mapper;

/**
 * Maps group collection items, with parent type & parent id, into SQL junction
 * tables
 *
 * @TODO Perhaps ContentCategoryCollection should have its own mapper to get rid
 * of the conditional and increase cohesion (in fetchChildrenOfLocation)
 */

use PDO;
use InvalidArgumentException;

use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Mapper;

class GroupCollection extends DataMapper
{
    const ERR_PARENT_TYPE_MISSING = 'Parent type must be specified.';
    const ERR_ID_MISSING = 'Id must be specified.';

    public function fetch(Entity\GroupCollection $groupCollection)
    {
        if (!$groupCollection->getParentId()) {
            throw new InvalidArgumentException(self::ERR_ID_MISSING);
        }

        if ($groupCollection->getParentType() === Entity\GroupCollection::PARENT_TYPE_LOCATION) {
            return $this->fetchChildrenOfLocation($groupCollection);
        } elseif ($groupCollection->getParentType() === Entity\GroupCollection::PARENT_TYPE_MEDIA) {
            return $this->fetchChildrenOfMedia($groupCollection);
        } else {
            throw new InvalidArgumentException(self::ERR_PARENT_TYPE_MISSING);
        }
    }

    public function fetchAllContentCategories(Entity\ContentCategoryCollection $contentCategoryCollection)
    {
        foreach ($this->conn->query("SELECT
                        parent.id                           AS id,
                        parent.label                        AS label,
                        CAST(parent.type AS UNSIGNED)       AS type,
                        child.image_id                      AS imageId,
                        child.color                         AS color
                    FROM
                        `community-voices_groups` parent
                    JOIN
                        `community-voices_content-categories` child
                        ON parent.id = child.group_id
                    WHERE
                        type = 'content-category'
                    ORDER BY label ASC") as $row) {
            $imageMapper = new Mapper\Image($this->conn);
            $row['image'] = new Entity\Image;
            $row['image']->setId($row['imageId']);
            $imageMapper->fetch($row['image']);

            $contentCategoryCollection->addEntityFromParams($row);
        }
    }

    public function fetchAllTags(Entity\TagCollection $tagCollection)
    {
        foreach ($this->conn->query("SELECT id, label FROM `community-voices_groups` WHERE type = 'tag' ORDER BY label ASC") as $row) {
            $tagCollection->addEntityFromParams($row);
        }
    }

    public function deleteGroups(Entity\Media $media)
    {
        $stmt = $this->conn->prepare("DELETE FROM `community-voices_media-group-map` WHERE media_id = ?");
        $stmt->execute([$media->getId()]);
    }

    public function saveGroups(Entity\GroupCollection $groupCollection)
    {
        foreach ($groupCollection->getCollection() as $group) {
            $stmt = $this->conn->prepare("INSERT INTO `community-voices_media-group-map` (media_id, group_id) VALUES (?, ?)");
            $stmt->execute([$group->getMediaId(), $group->getGroupId()]);
        }
    }

    private function fetchChildrenOfMedia(Entity\GroupCollection $groupCollection)
    {
        // also select junction.id? would it be needed anywhere?
        $query = "SELECT
                        groups.id                                   AS id,
                        CAST(groups.type AS UNSIGNED)               AS type,
                        groups.label                                AS label
                    FROM
                        `community-voices_media-group-map` junction
                    JOIN
                        #
                        # `groups` is plural because group is a reserved word
                        #
                        `community-voices_groups` groups
                        ON junction.group_id = groups.id
                    WHERE
                        CAST(groups.type AS UNSIGNED) = :type
                        AND junction.media_id = :mediaId";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':type', $groupCollection->getGroupType());
        $statement->bindValue(':mediaId', $groupCollection->getParentId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $groupCollection->addEntityFromParams($entry);
        }
    }

    private function fetchChildrenOfLocation(Entity\GroupCollection $groupCollection)
    {
        if ($groupCollection->getGroupType() === Entity\GroupCollection::GROUP_TYPE_CONT_CAT) {
            $query = "SELECT
                            junction.id                                 AS id,
                            CAST(groups.type AS UNSIGNED)               AS type,
                            junction.probability                        AS probability,
                            groups.label                                AS label,
                            contentCategory.media_filename              AS mediaFilename
                        FROM
                            `community-voices_location-category-map` junction
                        JOIN
                            #
                            #  `groups` is plural because group is a reserved word
                            #
                            `community-voices_groups` groups
                            ON junction.group_id = groups.id
                        LEFT JOIN
                            `community-voices_content-categories` contentCategory
                            ON groups.id = contentCategory.group_id
                        WHERE
                            CAST(groups.type AS UNSIGNED) = :type
                            AND junction.location_id = :locationId";
        } else {
            $query = "SELECT
                            junction.id                                 AS id,
                            CAST(groups.type AS UNSIGNED)               AS type,
                            junction.probability                        AS probability,
                            groups.label                                AS label
                        FROM
                            `community-voices_location-category-map` junction
                        JOIN
                            #
                            # `groups` is plural because group is a reserved word
                            #
                            `community-voices_groups` groups
                            ON junction.group_id = groups.id
                        WHERE
                            CAST(groups.type AS UNSIGNED) = :type
                            AND junction.location_id = :locationId";
        }

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':type', $groupCollection->getGroupType());
        $statement->bindValue(':locationId', $groupCollection->getParentId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $groupCollection->addEntityFromParams($entry);
        }
    }
}
