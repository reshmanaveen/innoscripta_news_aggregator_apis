<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class NewsFeedController extends Controller
{
    /**
     * @OA\Get(
     *     path="/news-feed",
     *     summary="Fetch User Prefered articles with pagination",
     *     tags={"News Feed"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="url", type="string", format="uri"),
     *                         @OA\Property(property="url_to_image", type="string", format="uri"),
     *                         @OA\Property(property="author", type="string"),
     *                         @OA\Property(property="source_name", type="string"),
     *                         @OA\Property(property="source_id", type="string"),
     *                         @OA\Property(property="published_at", type="string", format="date-time"),
     *                         @OA\Property(property="content", type="string", nullable=true),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="category", type="string", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", format="uri"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", format="uri"),
     *                 @OA\Property(property="links", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, format="uri"),
     *                         @OA\Property(property="label", type="string"),
     *                         @OA\Property(property="active", type="boolean")
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, format="uri"),
     *                 @OA\Property(property="path", type="string", format="uri"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, format="uri"),
     *                 @OA\Property(property="to", type="integer", example=2),
     *                 @OA\Property(property="total", type="integer", example=2)
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

    public function index(Request $request)
    {
        try {
            $currentPage = $request->page ?? 1;
            $perPage = $request->per_page ?? 10;
            Paginator::currentPageResolver(function () use ($currentPage){
                return $currentPage;
            });

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

            $articles = $query->paginate($perPage);

            return response()->success($articles);
        } catch (\Exception $e) {
            \Log::error('Get User NewsFeed error > ' . $e->getMessage());
            return response()->error('Get User NewsFeed error.');
        }
    }
}
