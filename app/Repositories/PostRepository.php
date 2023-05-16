<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Attribute;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{

    public function create(array $attribute)
    {
        return DB::transaction(function () use ($attribute) {

            $createPost = Post::create([
                'title' => data_get($attribute, 'title'),
                'body' => data_get($attribute, 'body'),
            ]);

            if ($userID = data_get($attribute, 'user_ids')) {
                $createPost->users()->sync($userID);
            }
            return $createPost;
        });
    }

    public function update($post, $attribute)
    {
        return DB::transaction(function () use ($post, $attribute) {

            $updatedPost = $post->update([
                'title' => data_get($attribute, 'title', $post->title),
                'body' => data_get($attribute, 'body', $post->body),
            ]);

            throw_if(!$updatedPost, GeneralJsonException::class, 'Failed to update resource');

            if ($userIds = data_get($attribute, 'user_ids')) {
                $post->users()->sync($userIds);
            }

            return $post;
        });
    }


    public function destroy($post)
    {
        return DB::transaction(function () use ($post) {
            $deletePost = $post->forceDelete();
            throw_if(!$deletePost, GeneralJsonException::class, 'Failed to delete resource');
            return 'deleted';
        });
    }
}
