<?php

namespace App\Contracts\Repositories;

interface GroupRepositoryInterface
{
    public function saveGroup($groupData);
    public function getAllGroups($pageSize = 2);
    public function deleteGroup($id);
}