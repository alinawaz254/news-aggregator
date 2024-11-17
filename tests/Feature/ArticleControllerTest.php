<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_paginated_articles()
    {
        // Create some articles
        Article::factory()->count(15)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure();
    }

    /** @test */
    public function it_can_filter_articles_by_keyword()
    {
        Article::factory()->create([
            'title' => 'Laravel Testing',
            'content' => 'Learn how to write tests in Laravel.',
        ]);

        Article::factory()->create([
            'title' => 'PHP Tips',
            'content' => 'Tips for advanced PHP developers.',
        ]);

        $response = $this->getJson('/api/articles?keyword=Laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Laravel Testing'])
            ->assertJsonMissing(['title' => 'PHP Tips']);
    }

    /** @test */
    public function it_returns_a_single_article()
    {
        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_article()
    {
        $response = $this->getJson('/api/articles/999');

        $response->assertJson([
            'statusCode' => 404,
            'response' => [], // Expect an empty array
            'message' => 'Article not found',
            'status' => false // Expect a boolean
        ]);

    }
}
