<?php

namespace CommunityVoices\Model\Entity;

class ArticleCollection extends MediaCollection
{
    public function __construct()
    {
        $this->mediaType = self::MEDIA_TYPE_ARTICLE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Article;
    }

    public function toArray()
    {
        return ['articleCollection' => parent::toArray()['mediaCollection']];
    }
}
