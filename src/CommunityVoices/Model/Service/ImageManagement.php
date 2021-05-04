<?php

namespace CommunityVoices\Model\Service;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Website\Finder\ImageMatcher;
use Jenssegers\ImageHash;

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
        $tags,
        $userDefinedMetaData = []
    ) {
        /*
         * Create image entity and set attributes
         */

        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $uploaded = [];
        $counter = (count($files) > 1) ? 1 : null;

        require __DIR__ . '../../../App/Website/db.php'; // used for pdo for matcher
        $matcher = new ImageMatcher(new ImageHash\ImageHash(new ImageHash\Implementations\PerceptualHash()),$dbHandler);

        foreach ($files as $file) {
            $image = new Entity\Image;

            $target_dir = "/var/www/uploads/CV_Media/images/";

            if (! is_string($file)) { // type is UploadedFile (this will occur if individual photo upload endpoint is hit)
            //https://github.com/symfony/symfony/blob/5.x/src/Symfony/Component/HttpFoundation/File/UploadedFile.php
                $fileName = $this->generateUniqueFileName() . "." . $file->guessExtension();
                $file->move($target_dir, $fileName);
                
            } else { // batch upload endpoint (url is given instead of file itself)
                $fileExtension = pathinfo($file,PATHINFO_EXTENSION);
                $fileName = $this->generateUniqueFileName() . "." . $fileExtension;

                $imgHttpResponse = shell_exec("curl -L $file");
                file_put_contents($target_dir . $fileName, $imgHttpResponse); 

                if(! is_array(@getimagesize($target_dir . $fileName))) {  // check file type https://stackoverflow.com/questions/15408125/php-check-if-file-is-an-image
                    unlink($target_dir . $fileName); // remove file in case it is malicious
                    return false;
                }
            }

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

            $validMetaData = $imageMapper->getMetaDataFields();
            $image->setMetaData($userDefinedMetaData,$validMetaData);
            

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

            /*
             * If there are any errors at this point, save the error state and stop
             * the registration process
             */

            if ($this->stateObserver->hasSubjectEntries("imageUpload")) {
                $clientState->save($this->stateObserver);
                return false;
            }

            /*
             * save $image to database
             */

            $imageMapper->save($image);

            array_push($uploaded, $image);

            $iid = $image->getId();

            if (is_array($tags)) {
                $tagCollection = new Entity\GroupCollection;
                foreach ($tags as $tid) {
                    $tag = new Entity\Tag;
                    $tag->setMediaId($iid);
                    $tag->setGroupId($tid);
                    $tagCollection->addEntity($tag);
                }
                $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
                $tagMapper->saveGroups($tagCollection);
            }
        }

        return $uploaded;
    }

    public function update(
        $id,
        $title,
        $description,
        $dateTaken,
        $photographer,
        $organization,
        $rect,
        $tags,
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
        $imageMapper->save($image);

        if (is_array($tags)) {
            $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
            $tagMapper->deleteGroups($image);
            $iid = $image->getId();
            $tagCollection = new Entity\GroupCollection;
            foreach ($tags as $tid) {
                $tag = new Entity\Tag;
                $tag->setMediaId($iid);
                $tag->setGroupId($tid);
                $tagCollection->addEntity($tag);
            }
            $tagMapper->saveGroups($tagCollection);
        }

        return true;
    }

    public function delete($id)
    {
        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);

        $image = new Entity\Image;
        $image->setId((int) $id);

        $imageMapper->fetch($image);
        $fn = $image->getFilename();

        try {
            $tagMapper->deleteGroups($image);
            $imageMapper->delete($image);
            if (file_exists($fn)) {
                unlink($fn);
            }
        } catch (Exception\DataIntegrityViolation $e) {
            return false;
        }

        return true;
    }

    public function unpair($image_id, $slide_id)
    {
        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $image = new Entity\Image;
        $image->setId((int) $image_id);
        $slide = new Entity\Slide;
        $slide->setId((int) $slide_id);
        $imageMapper->unpair($image, $slide);
    }

    public function createNewBatchUploadFields($fields) 
    {

        $mapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        if (! empty($mapper->getMetaDataFields())) {
            throw new Exception\DataIntegrityViolation();  // user should only be able to set metadata fields once.
        }

        $fieldsToAdd = $fields[0] == 'none' ? '' : implode(" ",array_map(function($field){return "'". $field . "'";},$fields));

        $migrationCommand = "php /var/www/html/migrate/migrate.php createNewImageBatchUploadFields ". $fieldsToAdd; 
       
        exec($migrationCommand, $output, $return_var);
        $queriedMetaDataFields = $mapper->getMetaDataFields();

        if($queriedMetaDataFields !== $fields) { // make sure all fields we intended to create actually made it
            $migrationUndoCommand = "php /var/www/html/migrate/migrate.php removeNewImageBatchUploadFields"; 
            throw new Exception\DataIntegrityViolation(); 
        }
    }
}
