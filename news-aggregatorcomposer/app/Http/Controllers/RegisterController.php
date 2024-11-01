<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Reshma"),
     *             @OA\Property(property="email", type="string", format="email", example="reshmabelman1@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Math@678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="name", type="string", example="Reshma"),
     *                 @OA\Property(property="email", type="string", format="email", example="eaxmple@mail.com"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-31T22:56:43.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-31T22:56:43.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */

    function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->success($user,'User registered successfully');

        } catch (\Exception $e) {
            \Log::error('User Register error > ' . $e->getMessage());
            return response()->error('User Register error.');
        }
    }
}
