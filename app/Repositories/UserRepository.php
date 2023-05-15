<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function create($attribute)
    {
        return DB::transaction(function () use ($attribute) {

            return User::query()->create([
                'name' => data_get($attribute, 'name', 'untitled'),
                'email' => data_get($attribute, 'email'),
                'password' => data_get($attribute, 'password')
            ]);
        });
    }




    public function update($user, $attribute)
    {
        return DB::transaction(function () use ($user, $attribute) {
            $updatedUser = $user->update([
                'name' => data_get($attribute, 'name', $user->name),
                'email' => data_get($attribute, 'email', $user->email),
            ]);

            if (!$updatedUser) {
                throw new Exception("failed to update");
            }
            return $user;
        });
    }



    public function destroy($user)
    {
        return DB::transaction(function () use ($user) {
            $deletedUser = $user->forceDelete();
            if (!$deletedUser) {
                throw new Exception("failed to delete");
            }

            return "Success";
        });
    }
}
