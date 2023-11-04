<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Step;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Step>
 */
class StepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'article_id' =>  $this->faker->numberBetween(1, 10),
            'description' => $this->faker->text,
            'order' => $this->faker->numberBetween(1, 10),
            'attached_file' => $this->faker->imageUrl,
        ];
    }
}
