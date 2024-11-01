<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index(Request $request)
    {
        try {
            $preferences = $request->user()->preferences;

            $query = Article::query();

            if ($preferences) {
                if ($preferences->preferred_source) {
                    $query->where('source_name', $preferences->preferred_source);
                }

                if ($preferences->preferred_category) {
                    $query->where('category', $preferences->preferred_category);
                }

                if ($preferences->preferred_author) {
                    $query->where('author', $preferences->preferred_author);
                }
            }

            $articles = $query->get();

            return response()->json($articles);
        } catch (\Exception $e) {
            \Log::error('Get User NewsFeed error > ' . $e->getMessage());
            return response()->error('Get User NewsFeed error.');
        }
    }
}
