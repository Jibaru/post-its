<?php

namespace App\Repositories;

use App\Contracts\Repositories\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class FileRepository implements FileRepositoryInterface 
{
    private static $DRIVE_URL = 'https://docs.google.com/uc?export=view&id=';

    /**
     * Create a new file
     * 
     * @param string $fileName
     * @param string $fileContent
     * @return String
     */
    public function saveFile($fileName, $fileContent)
    {
        Storage::disk('google')->put($fileName, $fileContent);
        return $this->getFileUrl($fileName);
    }

    /**
     * Retrieves the file url 
     * 
     * @param string $fileName
     * @return String
     */
    public function getFileUrl($fileName)
    {
        $contents = collect(Storage::disk('google')->listContents());

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($fileName, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($fileName, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        return FileRepository::$DRIVE_URL . $file['path'];
    }
}