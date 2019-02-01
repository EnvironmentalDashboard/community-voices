<?php

namespace CommunityVoices\Model\Entity;

class SlideCollection extends MediaCollection
{
    private $count = 0;
    private $limit = 0;
    private $page = 0;

    public function __construct()
    {
        $this->mediaType = self::MEDIA_TYPE_SLIDE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Slide;
    }

    public function toArray()
    {
        return ['slideCollection' => array_merge(parent::toArray()['mediaCollection'], ['count' => $this->count, 'limit' => $this->limit, 'page' => $this->page])];
    }

    public function setCount(int $count)
    {
        $this->count = $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setPage(int $page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }
}
