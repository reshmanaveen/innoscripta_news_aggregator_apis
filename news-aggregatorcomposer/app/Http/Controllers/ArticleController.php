<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

 /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Fetch articles with pagination",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
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

        return response()->json($articles);
    }

     // Retrieve a single article's details
     public function show($id)
     {
         $article = Article::findOrFail($id);
         return response()->json($article);
     }
}
