<?php

namespace App\Console\Commands;

use App\Models\Article;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

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
        $client = new Client();

        $newsApiKey = config('news.newsapi.api_key');
        $guardianApiKey = config('news.guardian.api_key');

        $newsApiResponse = $client->get('https://newsapi.org/v2/top-headlines?country=us&apiKey=' . $newsApiKey);
        $articles = json_decode($newsApiResponse->getBody(), true)['articles'];

        foreach ($articles as $article) {
            $publishedAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $article['publishedAt'])->format('Y-m-d H:i:s');

            Article::updateOrCreate(
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
                ]
            );
        }

        //Fetch articles from The Guardian
        $guardianResponse = $client->get('https://content.guardianapis.com/search?api-key=' . $guardianApiKey);
        $guardianArticles = json_decode($guardianResponse->getBody(), true)['response']['results'];

        foreach ($guardianArticles as $article) {
            $publishedAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $article['webPublicationDate'])->format('Y-m-d H:i:s');

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
                ]
            );
        }
    }
}
