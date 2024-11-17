<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_preferences_returns_user_preferences()
    {
        $user = User::factory()->create();
        $preferences = UserPreference::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/user/preferences');

        $response->assertStatus(200);

    }

    public function test_set_preferences_validates_input()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/user/preferences', [
            'news_sources' => 'Invalid data',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['news_sources']);
    }

    public function test_personalized_feed_returns_404_when_preferences_not_set()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/user/personalized-feed');

        $response->assertStatus(404);
    }

    public function test_personalized_feed_returns_articles_based_on_preferences()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        UserPreference::factory()->create([
            'user_id' => $user->id,
            'news_sources' => ['BBC News'],
            'categories' => ['Technology'],
            'authors' => ['John Doe'],
        ]);

        Article::factory()->create([
            'source' => 'BBC News',
            'category' => 'Technology',
            'author' => 'John Doe',
        ]);

        $response = $this->getJson('/api/user/personalized-feed');


        $response->assertStatus(200);
    }
}
