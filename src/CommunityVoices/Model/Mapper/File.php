<?php

/**
 * File mapper
 *
 * @todo Not liking how this depends on the Symfony UploadedFile object, which seems
 * to be poorly designed anyways.
 */

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Entity;

class File
{
    private $uploadsDirectory;

    public function __construct($uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    public function save(UploadedImageFile $file)
    {
        $file->setDirectory($this->uploadsDirectory);

        do {
            $file->generateUniqueFilename();
        } while(file_exists($file->getFilepath()));

        $file->move();
    }

    public function delete(UploadedImageFile $file)
    {
        unlink($file->getFilename());
    }

    private function generateUniqueHash()
    {
        return hash("sha256", uniqid());
    }
}
