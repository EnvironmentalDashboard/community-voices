<?php

namespace CommunityVoices\Model\Entity;

class ImageCollection extends MediaCollection
{
    private $count = 0;
    private $limit = 0;
    private $page = 0;

    public function __construct(){
        $this->mediaType = self::MEDIA_TYPE_IMAGE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity(){
        return new Image;
    }

    public function toArray()
    {
        return ['imageCollection' => array_merge(parent::toArray()['mediaCollection'], ['count' => $this->count, 'limit' => $this->limit, 'page' => $this->page])];
    }

    public function setCount(int $count) {
        $this->count = $count;
    }

    public function getCount() {
        return $this->count;
    }

    public function setLimit(int $limit) {
        $this->limit = $limit;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setPage(int $page) {
        $this->page = $page;
    }

    public function getPage() {
        return $this->page;
    }

}
