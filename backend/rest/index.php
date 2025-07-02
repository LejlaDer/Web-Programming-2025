<?php

require "../vendor/autoload.php";
require "./services/ExamService.php";

Flight::register('examService', 'ExamService');

require 'routes/ExamRoutes.php';

// Login Route

Flight::route('POST /auth/login', function () {
    $data = Flight::request()->data->getData();
    $email = $data['email'];
    $password = $data['password'];

    $user = Flight::examService()->get_user_by_email($email);
    if (!$user || !password_verify($password, $user['password'])) {
        Flight::halt(401, 'Invalid credentials');
    }

    $jwt_payload = [
        'user' => ['id' => $user['id'], 'email' => $user['email']],
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24)
    ];

    $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');
    Flight::json(['token' => $token]);
});

// Protect with middleware

Flight::route('/*', function () {
    if (Flight::request()->url === '/auth/login') return true;

    $token = Flight::request()->getHeader('Authentication');
    if (!$token) Flight::halt(401, "Missing token");

    try {
        $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        Flight::set('user', $decoded->user);
    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});



Flight::start();
 ?>
