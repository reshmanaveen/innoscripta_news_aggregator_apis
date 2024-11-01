<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPreferenceRequest;
use App\Models\UserPreference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserPreferenceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $preferences = $request->user()->preferences;

            // Check if preferences exist
            if ($preferences) {
                return response()->success($preferences);
            }

            return response()->success(null, 'No preferences set yet.');
        } catch (Exception $e) {
            Log::error('Get User preference error > ' . $e->getMessage());
            return response()->error('An error occurred while retrieving preferences.');
        }
    }


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
