<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *  @return ResourceCollection
     */
    public function index()
    {
        $comments = Comment::query()->get();
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *  @return CommentResource
     */
    public function store(Request $request)
    {
        $createdComment = Comment::create([
            'body' => $request->body,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id
        ]);

        return new CommentResource($createdComment);
    }

    /**
     * Display the specified resource.
     *  @return CommentResource
     */
    public function show(string $comment)
    {
        $getComment = Comment::find($comment);

        return new CommentResource($getComment);
    }

    /**
     * Update the specified resource in storage.
     *  @return CommentResource | JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $updatedComment = $comment->update([
            'body' => $request->body ?? $comment->body,
            'user_id' => $request->user_id ?? $comment->user_id,
            'post_id' => $request->post_id ?? $comment->post_id
        ]);
        if (!$updatedComment) {
            return new JsonResponse([
                'error' => [
                    'failed to update resource'
                ]
            ], 400);
        } else {
            return new CommentResource($comment);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $deletedComment = $comment->forceDelete();
        if (!$deletedComment) {
            return new JsonResponse([
                'error' => [
                    'failed to delete resource'
                ]
            ], 400);
        } else {
            return new JsonResponse(['data' => 'Success']);
        }
    }
}
