<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User login",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="reshmabelman@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Math@678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="2|VzMdbPHX0lMK1twcBkUDHxpWr67jJ9bxBauO6vIqb1fccffe")
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function login(Request $request)

    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // Create token
                $token = $user->createToken('my-app-token')->plainTextToken;
                return response()->success(['token' => $token]);
            }

            return response()->error('Invalid credentials', 401);
        } catch (\Exception $e) {
            \Log::error('Login error > ' . $e->getMessage());
            return response()->error('Login error.');
        }
    }
}
