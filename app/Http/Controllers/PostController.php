<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\PostCondition;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *  @return ResourceCollection
     */
    public function index(Request $request)
    {
        $paginateSize = $request->paginate_size ?? 10;
        $posts = Post::query()->paginate($paginateSize);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *  @return PostResource
     */
    public function store(Request $request, PostRepository $repository)
    {
        $createPost = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));
        return new PostResource($createPost);
    }

    /**
     * Display the specified resource.
     *  @return PostResource
     */
    public function show(string $id)
    {
        $post = Post::query()->find($id);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *  @return PostResource |  JsonResponse
     */
    public function update(Post $post, Request $request, PostRepository $repository)
    {
        $updatedPost = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids'
        ]));
        return new PostResource($updatedPost);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $deletedPost = $repository->destroy($post);

        return new JsonResponse([
            'data' => $deletedPost
        ]);
    }
}
