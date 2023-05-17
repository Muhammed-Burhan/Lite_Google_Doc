<?php

namespace App\Http\Controllers;

use App\Events\Models\User\UserCreated;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *  @return ResourceCollection
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 5;
        $users = User::query()->paginate($pageSize);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *  @return UserResource
     */
    public function store(Request $request, UserRepository $repository)
    {
        $createdUser = $repository->create($request->only([
            'name',
            'email',
            'password'
        ]));

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
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $updatedUser = $repository->update($user, $request->only([
            'name',
            'email',
        ]));
        return new UserResource($updatedUser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $deleteUser = $repository->destroy($user);
        return new JsonResponse([
            'data' => $deleteUser
        ]);
    }
}
