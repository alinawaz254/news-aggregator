<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from news APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        DB::table('articles')->truncate();

        // Fetch and save articles from NewsAPI
        $this->fetchNewsAPIArticles();

        // Fetch and save articles from New York Times
        $this->fetchNYTimesArticles();

        // Fetch and save articles from BBC
        $this->fetchBBCNewsArticles();

        $this->info('News articles fetched successfully!');
    }

    /**
     * Fetch articles from NewsAPI.
     */
    private function fetchNewsAPIArticles()
    {
        $url = 'https://newsapi.org/v2/top-headlines?apiKey=7c1d2630515d47efabd5b1d0b57093c9&country=us';
        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json()['articles'] ?? [];
            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['title' => $article['title']],
                    [
                        'content' => $article['content'] ?? '',
                        'source' => 'NewsAPI',
                        'category' => $article['category'] ?? 'General',
                        'author' => $article['author'] ?? 'Unknown',  // Added author field
                        'published_at' => isset($article['publishedAt']) ? Carbon::parse($article['publishedAt'])->toDateTimeString() : null,
                    ]
                );
            }
        } else {
            $this->error('Failed to fetch articles from NewsAPI');
        }
    }

    /**
     * Fetch articles from New York Times.
     */
    private function fetchNYTimesArticles()
    {
        $url = 'https://api.nytimes.com/svc/topstories/v2/home.json?api-key=GbSk0N8skwfAv3Zg7jlvMZwW7KIRH7sG';
        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json()['results'] ?? [];
            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['title' => $article['title']],
                    [
                        'content' => $article['abstract'] ?? '',
                        'source' => 'New York Times',
                        'category' => $article['section'] ?? 'General',
                        'author' => $article['byline'] ?? 'Unknown',  // Added author field
                        'published_at' => isset($article['published_date']) ? Carbon::parse($article['published_date'])->toDateTimeString() : null,
                    ]
                );
            }
        } else {
            $this->error('Failed to fetch articles from New York Times');
        }
    }

    /**
     * Fetch articles from BBC News API.
     */
    private function fetchBBCNewsArticles()
    {
        $url = 'https://newsapi.org/v2/top-headlines?sources=bbc-news&apiKey=7c1d2630515d47efabd5b1d0b57093c9';
        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json()['articles'] ?? [];
            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['title' => $article['title']],
                    [
                        'content' => $article['description'] ?? '',
                        'source' => 'BBC News',
                        'category' => $article['category'] ?? 'General',
                        'author' => $article['author'] ?? 'Unknown',  // Added author field
                        'published_at' => isset($article['publishedAt']) ? Carbon::parse($article['publishedAt'])->toDateTimeString() : null,
                    ]
                );
            }
        } else {
            $this->error('Failed to fetch articles from BBC News');
        }
    }
}
