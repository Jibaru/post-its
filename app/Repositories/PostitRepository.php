<?php

namespace App\Repositories;

use App\Contracts\Repositories\PostitRepositoryInterface;
use App\Models\Postit;

class PostitRepository implements PostitRepositoryInterface 
{
    /**
     * Create a new postit
     * 
     * @param array<string, string> $postitData
     * @return App\Models\Postit
     */
    public function savePostit($postitData)
    {
        return Postit::create($postitData);
    }
}