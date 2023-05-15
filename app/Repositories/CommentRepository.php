<?php

namespace App\Repositories;

use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{

    public function create($attribute)
    {
        return DB::transaction(function () use ($attribute) {

            return Comment::create([
                'body' => data_get($attribute, 'body'),
                'user_id' => data_get($attribute, 'user_id', 1),
                'post_id' => data_get($attribute, 'post_id', 1)
            ]);
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

            if (!$updatedComment) {
                throw new Exception("failed to update");
            }

            return $comment;
        });
    }

    public function destroy($comment)
    {
        return DB::transaction(function () use ($comment) {
            $deletedComment = $comment->forceDelete();
            if (!$deletedComment) {
                throw new Exception('failed to delete');
            }
            return "Success";
        });
    }
}
