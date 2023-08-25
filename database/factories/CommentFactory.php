<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => function () {
                // Generate a random article ID to associate with the comment
                return \App\Models\Article::inRandomOrder()->first()->id;
            },
            'author_id' => function () {
                // Generate a random user ID as the comment author
                return \App\Models\User::inRandomOrder()->first()->id;
            },
            'content' => $this->faker->paragraph,
            'withfile' => $this->faker->imageUrl,
        ];
    }
}
