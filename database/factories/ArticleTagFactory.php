<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => Article::inRandomOrder()->first()->id,
            'tag_id' =>  Tag::inRandomOrder()->first()->id,
        ];
    }
}
