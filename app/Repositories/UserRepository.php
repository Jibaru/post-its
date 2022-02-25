<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getUserById($id)
    {
        // TODO: Implementation
    }

    /**
     * Create a new user
     * 
     * @param array<string, string> $userData
     * @return Illuminate\Database\Eloquent\Model
     */
    public function register(array $userData)
    {
        return User::create([
            "username" => $userData["username"],
            "first_name" => $userData["first_name"],
            "last_name" => $userData["last_name"],
            "email" => $userData["email"],
            "password" => Hash::make($userData["password"])
        ]);
    }
}