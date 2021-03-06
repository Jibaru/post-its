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
     * @return \App\Models\Postit
     */
    public function savePostit($postitData)
    {
        return Postit::create($postitData);
    }

    /**
     * Retrieves the postits of the specified group
     * 
     * @param int $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPostitsByGroupId($groupId)
    {
        return Postit::with(['user'])->where('group_id', $groupId)->get();
    }

    /**
     * Delete a specified postit by id
     * 
     * @param int $id
     * @return boolean
     */
    public function deletePostitById($id)
    {
        $postit = Postit::find($id);
        return $postit->delete();
    }

    /**
     * Retrieves a postit by id
     * 
     * @param int $id
     * @return \App\Models\Postit
     */
    public function getPostitById($id)
    {
        return Postit::with(['user'])->find($id);
    }
}