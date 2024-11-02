<?php

namespace App;

use App\Models\Article;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ArticleService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchAndStoreArticles()
    {
        $this->fetchNewsApiArticles();
        $this->fetchGuardianArticles();
        $this->fetchNYTArticles();
    }

    protected function fetchNewsApiArticles()
    {
        $newsApiKey = config('news.newsapi.api_key');
        $response = $this->client->get('https://newsapi.org/v2/top-headlines?country=india&apiKey=' . $newsApiKey);
        $articles = json_decode($response->getBody(), true)['articles'];

        foreach ($articles as $article) {
            $publishedAt = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $article['publishedAt'])->format('Y-m-d H:i:s');

            Article ::updateOrCreate(
                ['title' => $article['title']],
                [
                    'description' => $article['description'],
                    'url' => $article['url'],
                    'url_to_image' => $article['urlToImage'],
                    'author' => $article['author'],
                    'source_name' => $article['source']['name'],
                    'source_id' => $article['source']['id'],
                    'published_at' => $publishedAt,
                    'content' => $article['content'],
                    'category' => null,
                ]
            );
        }
    }

    protected function fetchGuardianArticles()
    {
        $guardianApiKey = config('news.guardian.api_key');
        $response = $this->client->get('https://content.guardianapis.com/search?api-key=' . $guardianApiKey);
        $articles = json_decode($response->getBody(), true)['response']['results'];

        foreach ($articles as $article) {
            $publishedAt = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $article['webPublicationDate'])->format('Y-m-d H:i:s');

            Article::updateOrCreate(
                ['title' => $article['webTitle']],
                [
                    'description' => $article['fields']['trailText'] ?? null,
                    'url' => $article['webUrl'],
                    'url_to_image' => $article['fields']['thumbnail'] ?? null,
                    'author' => $article['fields']['byline'] ?? null,
                    'source_name' => 'The Guardian',
                    'source_id' => null,
                    'published_at' => $publishedAt,
                    'content' => $article['fields']['body'] ?? null,
                    'category' => $article['type'] ?? null,
                ]
            );
        }
    }

    protected function fetchNYTArticles()
    {
        $nytApiKey = config('news.nyt.api_key');
        $response = $this->client->get('https://api.nytimes.com/svc/topstories/v2/home.json?api-key=' . $nytApiKey);
        $articles = json_decode($response->getBody(), true)['results'];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title']],
                [
                    'description' => $article['abstract'] ?? null,
                    'url' => $article['url'],
                    'url_to_image' => $article['multimedia'][0]['url'] ?? null,
                    'author' => $article['byline'] ?? null,
                    'source_name' => 'The New York Times',
                    'source_id' => null,
                    'published_at' => $article['published_date'] ?? null,
                    'content' => null,
                    'category' => $article['section'] ?? null,
                ]
            );
        }
    }
}
