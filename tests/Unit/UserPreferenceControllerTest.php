<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_preferences_creates_or_updates_preferences()
    {
        $data = [
            'news_sources' => ['BBC News', 'CNN'],
            'categories' => ['Technology', 'Health'],
            'authors' => ['John Doe', 'Jane Smith'],
        ];

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/user/preferences', $data);

        $response->assertStatus(200);

        $this->assertEquals(1, UserPreference::where('user_id', $user->id)->count());
    }



    public function test_get_personalized_feed_returns_articles_based_on_preferences()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $preferences = UserPreference::factory()->create([
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
