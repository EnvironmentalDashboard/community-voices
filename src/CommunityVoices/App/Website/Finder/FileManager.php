<?php

namespace CommunityVoices\App\Website\Finder;

class FileManager
{
    /**
     * Moves uploaded file
     * @param  Array $file uploaded file reference
     * @return String new file name
     */
    public function upload($file)
    {
        move_uploaded_file($file['tmp_name'], __DIR__ . '/tmp/' . $file['name']);

        return __DIR__ . '/tmp/' . $file['name'];
    }

    /**
     * Unlinks filepath
     * @param  String $fn Filepath to unlink
     */
    public function delete($fn)
    {
        return unlink($fn);
    }
}
