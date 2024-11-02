<?php

namespace Database\Seeders;

use App\ArticleService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articleService = new ArticleService();
        $articleService->fetchAndStoreArticles();
    }
}
