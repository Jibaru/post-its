<?php

namespace App\Contracts\Repositories;

interface PostitRepositoryInterface
{
    public function savePostit($postitData);
    public function getPostitsByGroupId($groupId);
}