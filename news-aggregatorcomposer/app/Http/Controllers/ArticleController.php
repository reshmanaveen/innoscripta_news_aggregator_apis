<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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

            $currentPage = $request->page ?? 1;
            $perPage = $request->per_page ?? 10;
            Paginator::currentPageResolver(function () use ($currentPage){
                return $currentPage;
            });
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

            $articles = $query->paginate($perPage); 
            return response()->success($articles);
        } catch (\Exception $e) {
            \Log::error('Get Articles error > ' . $e->getMessage());
            return response()->error('Get Articles error.');
        }
    }

    /**
     * @OA\Get(
     *     path="/articles/{id}",
     *     summary="Fetch a specific article by ID",
     *     tags={"articles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="A child needed antivenom for a snakebite. It cost more than $200,000. - The Washington Post"),
     *                 @OA\Property(property="description", type="string", example="It took more than 30 vials of antivenom to save a toddler’s life after he was bitten by a rattlesnake."),
     *                 @OA\Property(property="url", type="string", format="uri", example="https://www.washingtonpost.com/wellness/2024/10/30/snakebite-antivenom-child-cost-bill/"),
     *                 @OA\Property(property="url_to_image", type="string", format="uri", example="https://www.washingtonpost.com/wp-apps/imrs.php?src=https://arc-anglerfish-washpost-prod-washpost.s3.amazonaws.com/public/GJPDFSKQ5GTXARYX5B5JAL4VBA_size-normalized.JPG&w=1440"),
     *                 @OA\Property(property="author", type="string", example="Jackie Fortiér"),
     *                 @OA\Property(property="source_name", type="string", example="The Washington Post"),
     *                 @OA\Property(property="source_id", type="string", example="the-washington-post"),
     *                 @OA\Property(property="published_at", type="string", format="date-time", example="2024-10-30 12:40:30"),
     *                 @OA\Property(property="content", type="string", example="A few days after his 2nd birthday, Brigland Pfeffer was playing with his siblings..."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-31T11:43:49.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-31T11:43:49.000000Z"),
     *                 @OA\Property(property="category", type="string", nullable=true)
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $article = Article::findOrFail($id);
            return response()->success(new ArticleResource($article));
        } catch (\Exception $e) {
            \Log::error('Show Article error > ' . $e->getMessage());
            return response()->error('Show Article error.');
        }
    }

    public function getArticlePreferences()
    {
        try {
            $preferredSources = Article::distinct()->pluck('source_name');
            $preferredCategories = Article::distinct()->pluck('category');
            $preferredAuthors = Article::distinct()->pluck('author');

            $preferences =  [
                'preferred_sources' => $preferredSources,
                'preferred_categories' => $preferredCategories,
                'preferred_authors' => $preferredAuthors,
            ];

            return response()->success($preferences);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error retrieving article preferences: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while retrieving article preferences.'], 500);
        }
    }
}
