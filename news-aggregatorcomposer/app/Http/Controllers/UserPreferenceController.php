<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPreferenceRequest;
use App\Models\UserPreference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserPreferenceController extends Controller
{
    
    
    /**
     * @OA\Get(
     *     path="/preferences",
     *     summary="Get user preferences",
     *     tags={"preference"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="preferred_source", type="string", nullable=true, example=null),
     *                 @OA\Property(property="preferred_category", type="string", nullable=true, example=null),
     *                 @OA\Property(property="preferred_author", type="string", example="By Michael Gold"),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-01T13:39:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-01T14:04:19.000000Z")
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */

    public function index(Request $request)
    {
        try {
            $preferences = $request->user()->preferences;

            if ($preferences) {
                return response()->success($preferences);
            }

            return response()->success(null, 'No preferences set yet.');
        } catch (Exception $e) {
            Log::error('Get User preference error > ' . $e->getMessage());
            return response()->error('An error occurred while retrieving preferences.');
        }
    }

    /**
     * @OA\Post(
     *     path="/preferences",
     *     summary="Set user preferences",
     *     tags={"preference"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="preferred_source", type="string", nullable=true, example=null),
     *             @OA\Property(property="preferred_category", type="string", nullable=true, example=null),
     *             @OA\Property(property="preferred_author", type="string", example="By Michael Gold")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User preferences updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="preferred_source", type="string", nullable=true, example=null),
     *                 @OA\Property(property="preferred_category", type="string", nullable=true, example=null),
     *                 @OA\Property(property="preferred_author", type="string", example="By Michael Gold"),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-01T13:39:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-01T14:04:19.000000Z")
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */

    public function store(StoreUserPreferenceRequest $request)
    {
     try {
            $request->validate([
                'preferred_source' => 'nullable|string',
                'preferred_category' => 'nullable|string',
                'preferred_author' => 'nullable|string',
            ]);

            

            $preference = UserPreference::updateOrCreate(
                ['user_id' => $request->user()->id],
                $request->only('preferred_source', 'preferred_category', 'preferred_author')
            );

            return response()->success($preference);
        } catch (Exception $e) {
            Log::error('User prefernce store error > ' . $e->getMessage());
            return response()->error('An error occurred while storing preferences.');
        }
    }
}
