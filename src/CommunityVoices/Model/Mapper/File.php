<?php

/**
 * File mapper
 */

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    private $uploadsDirectory;

    public function __construct($uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    public function save(UploadedFile $file)
    {
        $filename = $this->generateUniqueFileName($file);
        $filepath = $this->uploadsDirectory . '' . $filename;

        $file->move($this->uploadsDirectory, $filename);

        $file->__construct($filepath, $file->getClientOriginalName());
    }

    public function delete(File $file)
    {
        // @TODO
    }

    private function generateUniqueFileName(UploadedFile $file)
    {
        $fileName = $this->generateUniqueHash() . "." . $file->guessExtension();

        if (file_exists($fileName)) {
            return generateUniqueFileName($file);
        }

        return $fileName;
    }

    private function generateUniqueHash()
    {
        return hash("sha256", uniqid());
    }
}
