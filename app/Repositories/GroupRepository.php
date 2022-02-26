<?php

namespace App\Repositories;

use App\Contracts\Repositories\GroupRepositoryInterface;
use App\Models\Group;

class GroupRepository implements GroupRepositoryInterface
{
    /**
     * Create a new group
     * 
     * @param array<string, string> $groupData
     * @return Illuminate\Database\Eloquent\Model
     */
    public function saveGroup($groupData)
    {
        return Group::create([
            "name" => $groupData["name"],
            "description" => $groupData["description"],
            "created_by_id" => $groupData["created_by_id"]
        ]);
    }

    /**
     * Retrieves all groups in parts
     * 
     * @param int $pageSize
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllGroups($pageSize = 10)
    {
        return Group::paginate($pageSize);
    }
}