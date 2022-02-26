<?php

namespace App\Contracts\Repositories;

interface UserRepositoryInterface
{
    public function register(array $userData);
    public function getUserByEmail($email);
    public function getUsersInGroup($groupId);
}