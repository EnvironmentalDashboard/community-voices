<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ArticleManagement
{
    private $mapperFactory;
    private $stateObserver;

    /**
     * @param MapperFactory $mapperFactory
     * @param StateObserver $stateObserver
     */
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
    }

    /**
     * Maps a new article to the database
     * @param  String $text               [description]
     * @param  String $author             [description]
     * @param  String $dateRecorded       [description]
     * @return Boolean                     [description]
     */
    public function upload($text, $author, $dateRecorded, $approved, $addedBy)
    {
        /*
         * Create Article entity and set attributes
         */

        $article = new Entity\Article;

        $article->setText($text);
        $article->setAuthor($author);
        $article->setDateRecorded($dateRecorded);
        $article->setAddedBy($addedBy);
        if($approved){
            $article->setStatus(3);
        } else {
            $article->setStatus(1);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('articleUpload');
        $isValid = $article->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state. If
         * there is no author, there is no point in continuing the upload process.
         */

       if (!$isValid && $this->stateObserver->hasEntry('author', $article::ERR_AUTHOR_REQUIRED))
        {
             $clientState->save($this->stateObserver);
             return false;
         }

        $articleMapper = $this->mapperFactory->createDataMapper(Mapper\Article::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $article to database
         */

        $articleMapper->save($article);

        return true;

    }

    public function update($id, $text, $author, $dateRecorded, $status)
        {

        $articleMapper = $this->mapperFactory->createDataMapper(Mapper\Article::class);

        /*
         * Create Article entity and set attributes
         */

        $article = new Entity\Article;
        $article->setId((int) $id);

        $articleMapper->fetch($article);

        $article->setText($text);
        $article->setAuthor($author);
        $article->setDateRecorded($dateRecorded);
        $article->setStatus($status);

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('articleUpdate');
        $isValid = $article->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state. If
         * there is no author, there is no point in continuing the upload process.
         */

       if (!$isValid && $this->stateObserver->hasEntry('author', $article::ERR_AUTHOR_REQUIRED))
        {
             $clientState->save($this->stateObserver);
             return false;
         }

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $article to database
         */

        $articleMapper->save($article);

        return true;
    }
}
