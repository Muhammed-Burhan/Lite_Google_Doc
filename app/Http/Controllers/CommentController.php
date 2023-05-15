<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *  @return ResourceCollection
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        $comments = Comment::query()->paginate($pageSize);
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *  @return CommentResource
     */
    public function store(Request $request, CommentRepository $repository)
    {
        $createdComment = $repository->create($request->only([
            'body',
            'user_id',
            'post_id'
        ]));

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
    public function update(Request $request, Comment $comment, CommentRepository $repository)
    {
        $repository->update(
            $comment,
            $request->only([
                'body',
                'user_id',
                'post_id'
            ])
        );
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, CommentRepository $repository)
    {
        $deletedComment = $repository->destroy($comment);
        return new JsonResponse([
            'data' => $deletedComment
        ]);
    }
}
