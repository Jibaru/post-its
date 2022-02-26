<?php

namespace App\Contracts\Repositories;

interface PostitRepositoryInterface
{
    public function savePostit($postitData);
    public function getPostitsByGroupId($groupId);
    public function deletePostitById($id);
    public function getPostitById($id);
}