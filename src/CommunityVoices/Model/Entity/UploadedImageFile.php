<?php

namespace CommunityVoices\Model\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedImageFile
{
    private $uploadedFile;

    private $extension;
    private $filename;
    private $directory;

    public function load(UploadedFile $file)
    {
        $this->uploadedFile = $file;

        $this->extension = $file->guessExtension();
        $filename = $this->generateUniqueFileName();
    }

    public function setDirectory($dirPath)
    {
        $this->directory = $dirPath;
    }

    public function getFilepath()
    {
        return $this->directory . '/' . $this->filename;
    }

    private function generateUniqueFilename()
    {
        $unique = hash("sha256", uniqid());

        $this->filename = $unique . "." . $this->getExtension();
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function move()
    {
        return $this->uploadedFile->move($this->directory, $this->filename);
    }
}