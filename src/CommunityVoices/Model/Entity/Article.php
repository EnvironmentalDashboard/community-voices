<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class Article extends Media
{
    const ERR_AUTHOR_REQUIRED = 'Articles must have an author.';

    private $text;

    private $author;
    private $dateRecorded;

    public function __construct()
    {
        $this->type = self::TYPE_ARTICLE;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getDateRecorded()
    {
        return $this->dateRecorded;
    }

    public function setDateRecorded($dateRecorded)
    {
        $this->dateRecorded = strtotime($dateRecorded);

    }


    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        if (!$this->author || empty($this->author)) {
            $isValid = false;
            $stateObserver->addEntry('author', self::ERR_AUTHOR_REQUIRED);
        }

        return $isValid;
    }

    public function toArray()
    {
        return ['article' => array_merge(parent::toArray()['media'], [
            'text' => $this->text,
            'author' => $this->author,
            'dateRecorded' => date("M j\, Y", $this->dateRecorded)
        ])];
    }
}
