<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Exception\ImageFileException;

use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class ImageFile
{
    private $symfonyUploadedFile;

    private $extension;
    private $filename;
    private $directory;

    public function load(SymfonyUploadedFile $file)
    {
        $this->symfonyUploadedFile = $file;

        $this->extension = $file->guessExtension();
        $this->filename = $this->generateUniqueFileName();
    }

    public function setDirectory($dirPath)
    {
        $this->directory = $dirPath;
    }

    public function getFilepath()
    {
        return $this->directory . '/' . $this->filename;
    }

    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
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
        if(!($this->symfonyUploadedFile instanceof SymfonyUploadedFile)) {
            // Since this entity is coupled to Symfony and leaks a bit of logic
            throw new ImageFileException('Could not move or upload file without proxy Symyfony instance.');
        }

        return $this->uploadedFile->move($this->directory, $this->filename);
    }
}