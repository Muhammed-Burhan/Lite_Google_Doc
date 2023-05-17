<?php

namespace App\Repositories;

use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;
use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Throw_;

class CommentRepository extends BaseRepository
{

    public function create($attribute)
    {
        return DB::transaction(function () use ($attribute) {

            $commentCreated = Comment::create([
                'body' => data_get($attribute, 'body'),
                'user_id' => data_get($attribute, 'user_id', 1),
                'post_id' => data_get($attribute, 'post_id', 1)
            ]);
            throw_if(!$commentCreated, GeneralJsonException::class, 'Failed to post the comment');
            event(new CommentCreated($commentCreated));
            return $commentCreated;
        });
    }

    public function update($comment, $attribute)
    {
        return DB::transaction(function () use ($comment, $attribute) {
            $updatedComment =  $comment->update([
                'body' => data_get($attribute, 'body', $comment->body),
                'user_id' => data_get($attribute, 'user_id', $comment->user_id),
                'post_id' => data_get($attribute, 'post_id', $comment->post_id)
            ]);

            throw_if(!$updatedComment, GeneralJsonException::class, 'failed to create comment', 400);
            event(new CommentUpdated($comment));
            return $comment;
        });
    }

    public function destroy($comment)
    {
        return DB::transaction(function () use ($comment) {
            $deletedComment = $comment->forceDelete();
            throw_if(!$deletedComment, GeneralJsonException::class, 'failed to delete');
            event(new CommentDeleted($comment));
            return "Success";
        });
    }
}
