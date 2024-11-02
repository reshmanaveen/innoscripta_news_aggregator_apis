<?php

namespace App\Console\Commands;

use App\ArticleService;
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
        $articleService = new ArticleService();
        $articleService->fetchAndStoreArticles();

        $this->info('Articles fetched and stored successfully.');
       
    }
}
