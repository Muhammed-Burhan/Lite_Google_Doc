<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
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
    public function index()
    {
        $posts = Post::query()->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *  @return PostResource
     */
    public function store(Request $request)
    {
        $createPost = DB::transaction(function () use ($request) {

            $createPost = Post::create([
                'title' => $request->title,
                'body' => $request->body,
            ]);
            $createPost->users()->sync($request->user_ids);
            return $createPost;
        });
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
    public function update(Post $post, Request $request)
    {
        $updatedPost = $post->update([
            'title' => $request->title ?? $post->title,
            'body' => $request->body ?? $post->body
        ]);

        if (!$updatedPost) {
            return new JsonResponse(
                [
                    'error' => [
                        'Failed To Update'
                    ]
                ],
                400
            );
        } else {
            return new PostResource($post);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $deletedPost = $post->forceDelete();

        if (!$deletedPost) {
            return new JsonResponse([
                'error' => [
                    'failed to delete the resource'
                ]
            ], 400);
        } else {
            return new JsonResponse([
                'data' => 'success'
            ]);
        }
    }
}
