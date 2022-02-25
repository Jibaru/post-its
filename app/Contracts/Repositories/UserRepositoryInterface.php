<?php

namespace App\Contracts\Repositories;

interface UserRepositoryInterface
{
    public function register(array $userData);
    public function getUserById($id);
}