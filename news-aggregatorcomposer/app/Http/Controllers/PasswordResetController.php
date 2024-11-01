<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * @OA\Post(
     *     path="/password/email",
     *     summary="Send password reset link",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="example@mail.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Reset link sent!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $response = Password::sendResetLink($request->only('email'));

        return $response === Password::RESET_LINK_SENT
            ? response()->success('Reset link sent!')
            : response()->error('Failed to send reset link.');
    }

    /**
     * @OA\Post(
     *     path="/password/reset",
     *     summary="Reset user password",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="reshmabelman@gmail.com"),
     *             @OA\Property(property="token", type="string", example="your-reset-token"),
     *             @OA\Property(property="password", type="string", example="password860"),
     *             @OA\Property(property="password_confirmation", type="string", example="password860")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password reset successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found or invalid token"
     *     )
     * )
     */

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8'

            ]);

            $credentials = $request->only('token', 'email', 'password');

            $response = Password::reset($credentials, function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            });

            if ($response === Password::PASSWORD_RESET) {
                return response()->success('','Password reset successfully');

            } else {
                return response()->error('Password reset failed', 400);
            }
        } catch (\Exception $e) {
            \Log::error('Reset Password error> ' . $e->getMessage());
            return response()->error('Reset Password error.');
        }
    }
}
