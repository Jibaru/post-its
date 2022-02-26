<?php

namespace App\Contracts\Repositories;

interface FileRepositoryInterface
{
    public function saveFile($fileName, $fileContent);
    public function getFileUrl($fileName);
}