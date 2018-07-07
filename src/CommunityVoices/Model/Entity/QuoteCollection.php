<?php

namespace CommunityVoices\Model\Entity;

class QuoteCollection extends MediaCollection
{
    private $count = 0;
    private $limit = 0;
    private $page = 0;

    public function __construct(){
        $this->mediaType = self::MEDIA_TYPE_QUOTE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity(){
        return new Quote;
    }

    public function toArray()
    {
        return ['quoteCollection' => array_merge(parent::toArray()['mediaCollection'], ['count' => $this->count, 'limit' => $this->limit, 'page' => $this->page])];
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
