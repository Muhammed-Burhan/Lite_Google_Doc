<?php

namespace App\Repositories;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function create($attribute)
    {
        return DB::transaction(function () use ($attribute) {

            $createdUser = User::query()->create([
                'name' => data_get($attribute, 'name', 'untitled'),
                'email' => data_get($attribute, 'email'),
                'password' => data_get($attribute, 'password')
            ]);
            throw_if(!$createdUser, GeneralJsonException::class, 'failed to create user');
            event(new UserCreated($createdUser));
            return $createdUser;
        });
    }




    public function update($user, $attribute)
    {
        return DB::transaction(function () use ($user, $attribute) {
            $updatedUser = $user->update([
                'name' => data_get($attribute, 'name', $user->name),
                'email' => data_get($attribute, 'email', $user->email),
            ]);
            throw_if(!$updatedUser, GeneralJsonException::class, 'failed to update');
            event(new UserUpdated($user));
            return $user;
        });
    }



    public function destroy($user)
    {
        return DB::transaction(function () use ($user) {
            $deletedUser = $user->forceDelete();
            throw_if(!$deletedUser, GeneralJsonException::class, 'failed to delete');
            event(new UserDeleted($user));
            return "Success";
        });
    }
}
