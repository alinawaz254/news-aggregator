<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = \App\Models\Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'source' => $this->faker->word,
            'category' => $this->faker->word,
            'published_at' => $this->faker->dateTime,
            'author' => $this->faker->name,
        ];
    }
}
