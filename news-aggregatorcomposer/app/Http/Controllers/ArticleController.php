<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Get(
 *     path="/articles",
 *     summary="Fetch articles with pagination",
 *     tags={"articles"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="current_page", type="integer"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(type="string")
 *             ),
 *             @OA\Property(property="first_page_url", type="string"),
 *             @OA\Property(property="last_page", type="integer"),
 *             @OA\Property(property="last_page_url", type="string"),
 *             @OA\Property(property="links", type="array",
 *                 @OA\Items(type="string")
 *             ),
 *             @OA\Property(property="next_page_url", type="string"),
 *             @OA\Property(property="path", type="string"),
 *             @OA\Property(property="per_page", type="integer"),
 *             @OA\Property(property="prev_page_url", type="string"),
 *             @OA\Property(property="total", type="integer"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     )
 * )
 */



class ArticleController extends Controller
{


    public function index(Request $request)
    {
        try {
            $query = Article::query();

            // Search functionality
            if ($request->filled('keyword')) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            }

            if ($request->filled('date')) {
                $query->whereDate('published_at', $request->date);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('source')) {
                $query->where('source_name', $request->source);
            }

            // Pagination
            $articles = $query->paginate(10); // Adjust the number of articles per page as needed

            return response()->success($articles);
        } catch (\Exception $e) {
            \Log::error('Get Articles error > ' . $e->getMessage());
            return response()->error('Get Articles error.');
        }
    }

    // Retrieve a single article's details
    public function show($id)
    {
        try {
            $article = Article::findOrFail($id);
            return response()->success($article);
        } catch (\Exception $e) {
            \Log::error('Show Article error > ' . $e->getMessage());
            return response()->error('Show Article error.');
        }
    }

    public function getArticlePreferences()
    {
       // try {
            $cacheKey = env('CACHE_KEY');
            $cacheDuration = 60;

            $preferences = \Cache::remember($cacheKey, $cacheDuration, function () {
                $preferredSources = Article::distinct()->pluck('source_name');
                $preferredCategories = Article::distinct()->pluck('category');
                $preferredAuthors = Article::distinct()->pluck('author');

                return [
                    'preferred_sources' => $preferredSources,
                    'preferred_categories' => $preferredCategories,
                    'preferred_authors' => $preferredAuthors,
                ];
            });

            return response()->json($preferences);
        // } catch (\Exception $e) {
        //     // Log the exception
        //     \Log::error('Error retrieving article preferences: ' . $e->getMessage());

        //     return response()->json(['error' => 'An error occurred while retrieving article preferences.'], 500);
        // }
    }
}
