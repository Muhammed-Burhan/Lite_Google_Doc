<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *  @return ResourceCollection
     */
    public function index()
    {
        $users = User::query()->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *  @return UserResource
     */
    public function store(Request $request)
    {
        $createdUser = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        return new UserResource($createdUser);
    }

    /**
     * Display the specified resource.
     *  @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *  @return JsonResponse | UserResource
     */
    public function update(Request $request, User $user)
    {
        // return response([
        //     'data'=>$user
        // ]);
        $updatedUser = $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ]);

        if (!$updatedUser) {
            return new JsonResponse([
                'error' => [
                    'failed to update resource'
                ]
            ], 400);
        } else {
            return new UserResource($user);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $deletedUser = $user->forceDelete();
        if (!$deletedUser) {
            return new JsonResponse([
                'error' => [
                    'failed to delete resource'
                ]
            ], 400);
        } else {
            return new JsonResponse([
                'data' => 'Success'
            ]);
        }
    }
}
