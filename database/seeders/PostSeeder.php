<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Factories\Helpers\FactoryHelper;
use Database\Seeders\Traits\DisablingForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use TruncateTable, DisablingForeignKey;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('posts');
        $posts = \App\Models\Post::factory(200)
        // ->state([
        //     'title' => 'untitled'
        // ])
        ->create();

        $posts->each(function (Post $post) {
            $post->users()->sync([FactoryHelper::getRandomModelId(User::class)]);
        });
        $this->enableForeignKey();
    }
}