<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ImageManagement
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

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * Uploads a new Image to the database
     * @param  [type] $file         [description]
     * @param  [type] $title        [description]
     * @param  [type] $description  [description]
     * @param  [type] $dateTaken    [description]
     * @param  [type] $photographer [description]
     * @param  [type] $organization [description]
     * @param  [type] $identity     [description]
     * @return [type]               [description]
     */
    public function upload(
        $files,
        $title,
        $description,
        $dateTaken,
        $photographer,
        $organization,
        $addedBy,
        $approved,
        $tags
    ) {

        /*
         * Create image entity and set attributes
         */
        $counter = (count($files) > 1) ? 1 : null;
        foreach ($files as $file) {
            $image = new Entity\Image;

            $target_dir = "/var/www/uploads/CV_Media/images/";
            $fileName = $this->generateUniqueFileName() . "." . $file->guessExtension();

            $file->move($target_dir, $fileName);

            $image->setFileName($target_dir . $fileName);
            $image->setTitle($title);
            if ($counter !== null) {
                $image->setDescription($description . ' - ' . ($counter++));
            } else {
                $image->setDescription($description);
            }
            $image->setDateTaken($dateTaken);
            $image->setPhotographer($photographer);
            $image->setOrganization($organization);
            $image->setAddedBy($addedBy);

            if ($approved) {
                $image->setStatus(3);
            } else {
                $image->setStatus(1);
            }

            /*
             * Create error observer w/ appropriate subject and pass to validator
             */

            $this->stateObserver->setSubject('imageUpload');
            $isValid = $image->validateForUpload($this->stateObserver);

            $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

            /*
             * Stop the upload process and save errors to the application state. If
             * there is no attribution, there is no point in continuing the upload process.
             */

            if (!$isValid && $this->stateObserver->hasEntry('attribution', $image::ERR_ATTRIBUTION_REQUIRED)) {
                $clientState->save($this->stateObserver);
                return false;
            }

            $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);

            /*
             * If there are any errors at this point, save the error state and stop
             * the registration process
             */

            if ($this->stateObserver->hasEntries()) {
                $clientState->save($this->stateObserver);
                return false;
            }

            /*
             * save $image to database
             */

            $imageMapper->save($image);

            if (is_array($tags)) {
                $iid = $image->getId();
                $tagCollection = new Entity\GroupCollection;
                foreach ($tags as $tid) {
                    $tag = new Entity\Tag;
                    $tag->setMediaId($iid);
                    $tag->setGroupId($tid);
                    $tagCollection->addEntity($tag);
                }
                $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
                $tagMapper->saveTags($tagCollection);
            }
        }

        return true;
    }

    public function update(
        $id,
        $title,
        $description,
        $dateTaken,
        $photographer,
        $organization,
        $rect,
        $status
      ) {
        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);

        /*
         * Create image entity and set attributes
         */

        $image = new Entity\Image;
        $image->setId((int) $id);

        $imageMapper->fetch($image);

        $image->setTitle($title);
        $image->setDescription($description);
        $image->setDateTaken($dateTaken);
        $image->setPhotographer($photographer);
        $image->setOrganization($organization);
        $image->setCropRect($rect);
        $image->setStatus($status);

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('imageUpdate');
        $isValid = $image->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state. If
         * there is no attribution, there is no point in continuing the upload process.
         */

        if (!$isValid && $this->stateObserver->hasEntry('attribution', $image::ERR_ATTRIBUTION_REQUIRED)) {
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
         * save $image to database
         */
// var_dump($image);die;
        $imageMapper->save($image);

        return true;
    }
}
