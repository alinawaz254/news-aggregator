<?php

namespace Database\Factories;

use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'news_sources' => ['BBC News', 'CNN'],
            'categories' => ['Technology', 'Health'],
            'authors' => ['John Doe', 'Jane Smith'],
        ];
    }
}
