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

    /**
     * Soft-delete the specified group
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteGroup($id)
    {
        $group = Group::find($id);
        return $group->delete();
    }

    /**
     * Retrieve a group by its id
     * 
     * @param int $id
     * @return App\Models\Group
     */
    public function getGroupById($id)
    {
        return Group::with([
                'postits', 
                'users', 
                'createdBy',
            ])
            ->get()
            ->find($id);
    }

    /**
     * Attach a user to specified group
     * 
     * @param int $groupId
     * @param int $userId
     * @return void
     */
    public function attachUserToGroup($groupId, $userId)
    {
        $group = Group::find($groupId);
        $group->users()->attach($userId);
    }
}