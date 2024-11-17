<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;

class ArticleTest extends TestCase
{
    /** @test */
    public function it_has_fillable_attributes()
    {
        $article = new Article();

        $this->assertEquals([
            'title',
            'content',
            'source',
            'category',
            'published_at',
            'author',
        ], $article->getFillable());
    }
}
