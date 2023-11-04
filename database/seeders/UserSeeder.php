<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use App\Models\Step;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(10)->create()->each(function ($user) {
            // Create 100 articles for each user
            $articles = Article::factory(10)->create();
            $articles->each(function ($article) {
                // Create 3 tags for each article
                $tags = Tag::factory(3)->create();
                $article->tags()->attach($tags);

                // Create 200 comments from other authors on each article
                Comment::factory(3)->create()->each(function ($comment) {
                    // Create 15 responses for each comment
                    Comment::factory(15)->create([
                        'article_id' => $comment->article_id,
                        'parent_id' => $comment->id,
                        'author_id' =>  User::inRandomOrder()->first()->id,
                    ]);
                });

                Step::factory(10)->create(['article_id' => $article->id]);
            });
        });
    }
}
