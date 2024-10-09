<?php 

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function getUserById(User $user)
    {
        return $user;
    }

    public function updateUser(array $data, User $user)
    {
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->delete();
    }
}
