<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {

/**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"auth"},
     *     summary="Login with email and password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="student@example.com"),
     *             @OA\Property(property="password", type="string", example="mypassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="JWT token for authorization",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIs...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */

Flight::route('POST /login', function() {
    $data = Flight::request()->data;
    $token = Flight::authService()->login($data['username'], $data['password']);
    if ($token) {
        Flight::json($token);
    } else {
        Flight::halt(401, 'Invalid credentials');
    }
});

Flight::route('POST /logout', function() {
    // In reality, invalidate the token. Here, just return success.
    Flight::json(["message" => "Logged out"]);
});

});
?>